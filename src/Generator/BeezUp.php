<?php

namespace ElasticExportBeezUp\Generator;

use Plenty\Legacy\Repositories\Item\SalesPrice\SalesPriceSearchRepository;
use Plenty\Modules\DataExchange\Contracts\CSVPluginGenerator;
use Plenty\Modules\Helper\Services\ArrayHelper;
use ElasticExport\Helper\ElasticExportCoreHelper;
use Plenty\Modules\Helper\Models\KeyValue;
use Plenty\Modules\Item\Attribute\Contracts\AttributeValueNameRepositoryContract;
use Plenty\Modules\Item\Attribute\Models\AttributeValueName;
use Plenty\Modules\Item\Property\Contracts\PropertyMarketReferenceRepositoryContract;
use Plenty\Modules\Item\Property\Contracts\PropertyNameRepositoryContract;
use Plenty\Modules\Item\Property\Models\PropertyName;
use Plenty\Modules\Item\SalesPrice\Models\SalesPriceSearchRequest;
use Plenty\Modules\StockManagement\Stock\Contracts\StockRepositoryContract;
use Plenty\Modules\Item\Search\Contracts\VariationElasticSearchScrollRepositoryContract;
use Plenty\Plugin\Log\Loggable;

class BeezUp extends CSVPluginGenerator
{
    use Loggable;

	const MARKET_REFERENCE_BEEZUP = 127.00;
	const PROPERTY_TYPE_DESCRIPTION = 'description';
    const TRANSFER_RRP_YES = 1;
    const TRANSFER_OFFER_PRICE_YES = 1;

	/**
	 * @var ElasticExportCoreHelper $elasticExportHelper
	 */
	private $elasticExportHelper;

	/*
	 * @var ArrayHelper
	 */
	private $arrayHelper;

	/**
	 * AttributeValueNameRepositoryContract $attributeValueNameRepository
	 */
	private $attributeValueNameRepository;

	/**
	 * @var array $propertyData
	 */
	private $propertyData = [];
    /**
     * @var salesPriceSearchRepository
     */
    private $salesPriceSearchRepository;

    /**
     * BeezUp constructor.
     * @param ArrayHelper $arrayHelper
     * @param AttributeValueNameRepositoryContract $attributeValueNameRepository
     * @param SalesPriceSearchRepository $salesPriceSearchRepository
     */
	public function __construct(
		ArrayHelper $arrayHelper,
		AttributeValueNameRepositoryContract $attributeValueNameRepository,
        salesPriceSearchRepository $salesPriceSearchRepository
	)
	{
		$this->arrayHelper = $arrayHelper;
		$this->attributeValueNameRepository = $attributeValueNameRepository;
        $this->salesPriceSearchRepository = $salesPriceSearchRepository;
    }

	/**
	 * @param VariationElasticSearchScrollRepositoryContract $resultData
	 * @param array $formatSettings
	 * @param array $filter
	 */
	protected function generatePluginContent($elasticSearch, array $formatSettings = [], array $filter = [])
	{
		$this->elasticExportHelper = pluginApp(ElasticExportCoreHelper::class);
        $settings = $this->arrayHelper->buildMapFromObjectList($formatSettings, 'key', 'value');
        $this->setDelimiter(";");
        $csvHeader = [
            'Produkt ID',
            'Artikel Nr',
            'MPN',
            'EAN',
            'Marke',
            'Produktname',
            'Produktbeschreibung',
            'Preis inkl. MwSt.',
            'UVP inkl. MwSt.',
            'Produkt-URL',
            'Bild-URL',
            'Bild-URL2',
            'Bild-URL3',
            'Bild-URL4',
            'Bild-URL5',
            'Lieferkosten',
            'Auf Lager',
            'Lagerbestand',
            'Lieferfrist',
            'Kategorie 1',
            'Kategorie 2',
            'Kategorie 3',
            'Kategorie 4',
            'Kategorie 5',
            'Kategorie 6',
            'Farbe',
            'Größe',
            'Gewicht',
            'Grundpreis',
            'ID'
        ];

        if($elasticSearch instanceof VariationElasticSearchScrollRepositoryContract)
        {
            $limitReached = false;
            $lines = 0;
            do
            {
                if($limitReached === true)
                {
                    break;
                }

                $resultList = $elasticSearch->execute();

                if(is_array($resultList['documents']) && count($resultList['documents']) > 0)
                {
                    $additionalHeaders = $this->buildPropertyData($resultList['documents']);

                    // combine hard coded headers with property based headers
                    $csvHeader = array_merge($csvHeader, $additionalHeaders);
                    $this->addCSVContent($csvHeader);

                    foreach($resultList['documents'] as $variation)
                    {
                        try
                        {
                            $variationAttributes = $this->getVariationAttributes($variation, $settings);

                            $stockList = $this->getStockList($variation);
                            $priceList = $this->getPriceList($variation, $settings);

                            // Get shipping costs
                            $shippingCost = $this->elasticExportHelper->getShippingCost($variation['data']['item']['id'], $settings);

                            if(!is_null($shippingCost))
                            {
                                $shippingCost = number_format((float)$shippingCost, 2, ',', '');
                            }
                            else
                            {
                                $shippingCost = '';
                            }

                            $data = [
                                'Produkt ID'            =>  $variation['id'],
                                'Artikel Nr'            =>  $variation['data']['variation']['number'],
                                'MPN'                   =>  $variation['data']['variation']['model'],
                                'EAN'                   =>  $this->elasticExportHelper->getBarcodeByType($variation, $settings->get('barcode')),
                                'Marke'                 =>  $this->elasticExportHelper->getExternalManufacturerName((int)$variation['data']['item']['manufacturer']['id']),
                                'Produktname'           =>  $this->elasticExportHelper->getName($variation, $settings, 256),
                                'Produktbeschreibung'   =>  $this->getDescription($variation, $settings),
                                'Preis inkl. MwSt.'     =>  number_format((float)$priceList['variationRetailPrice.price'], 2, '.', ''),
                                'UVP inkl. MwSt.'       =>  $priceList['reducedPrice'] > 0 ?
                                    number_format((float)$priceList['reducedPrice'], 2, '.', '') : '',
                                'Produkt-URL'           =>  $this->elasticExportHelper->getUrl($variation, $settings),
                                'Bild-URL'              =>  $this->getImageByNumber($variation, $settings, 0),
                                'Bild-URL2'             =>  $this->getImageByNumber($variation, $settings, 1),
                                'Bild-URL3'             =>  $this->getImageByNumber($variation, $settings, 2),
                                'Bild-URL4'             =>  $this->getImageByNumber($variation, $settings, 3),
                                'Bild-URL5'             =>  $this->getImageByNumber($variation, $settings, 4),
                                'Lieferkosten'          =>  $shippingCost,
                                'Auf Lager'             =>  $stockList['variationAvailable'],
                                'Lagerbestand'          =>  $stockList['stock'],
                                'Lieferfrist'           =>  $this->elasticExportHelper->getAvailability($variation, $settings, false),
                                'Kategorie 1'           =>  $this->elasticExportHelper->getCategoryBranch($variation['data']['defaultCategories'][0]['id'], $settings, 1),
                                'Kategorie 2'           =>  $this->elasticExportHelper->getCategoryBranch($variation['data']['defaultCategories'][0]['id'], $settings, 2),
                                'Kategorie 3'           =>  $this->elasticExportHelper->getCategoryBranch($variation['data']['defaultCategories'][0]['id'], $settings, 3),
                                'Kategorie 4'           =>  $this->elasticExportHelper->getCategoryBranch($variation['data']['defaultCategories'][0]['id'], $settings, 4),
                                'Kategorie 5'           =>  $this->elasticExportHelper->getCategoryBranch($variation['data']['defaultCategories'][0]['id'], $settings, 5),
                                'Kategorie 6'           =>  $this->elasticExportHelper->getCategoryBranch($variation['data']['defaultCategories'][0]['id'], $settings, 6),
                                'Farbe'                 =>  $variationAttributes['Color'],
                                'Größe'                 =>  $variationAttributes['Size'],
                                'Gewicht'               =>  $variation['data']['variation']['weightG'],
                                'Grundpreis'            =>  $this->elasticExportHelper->getBasePrice($variation, $priceList),
                                'ID'                    =>  $variation['data']['item']['id'],
                            ];

                            $data = $this->addPropertyData($data, $variation['id']);

                            $this->addCSVContent(array_values($data));

                            $lines = $lines +1;
                        }
                        catch(\Throwable $throwable)
                        {
                            $this->getLogger(__METHOD__)->error('ElasticExportBeezUp::logs.fillRowError', [
                                'Error message ' => $throwable->getMessage(),
                                'Error line'    => $throwable->getLine(),
                                'VariationId'   => $variation['id']
                            ]);
                        }
                    }
                }
            }while ($elasticSearch->hasNext());
        }
	}

	/**
	 * Get item description.
	 * @param array $item
	 * @param KeyValue $settings
	 * @return string
	 */
	private function getDescription($item, KeyValue $settings):string
	{
		$description = $this->elasticExportHelper->getItemCharacterByBackendName($item, $settings, self::PROPERTY_TYPE_DESCRIPTION);

		if (strlen($description) <= 0)
		{
			$description = $this->elasticExportHelper->getDescription($item, $settings, 5000);
		}

		return $description;
	}

	/**
	 * Get variation attributes.
	 * @param  array   $item
	 * @param  KeyValue $settings
	 * @return array<string,string>
	 */
	private function getVariationAttributes($item, KeyValue $settings):array
	{
		$variationAttributes = [];

		foreach($item['data']['attributes'] as $variationAttribute)
		{
		    if(is_null($variationAttribute['valueId']))
		    {
		        return $variationAttributes;
            }

			$attributeValueName = $this->attributeValueNameRepository->findOne($variationAttribute['valueId'], $settings->get('lang'));

			if($attributeValueName instanceof AttributeValueName)
			{
				if($attributeValueName->attributeValue->attribute->amazonAttribute)
				{
					$variationAttributes[$attributeValueName->attributeValue->attribute->amazonAttribute] = $attributeValueName->name;
				}
			}
		}

		return $variationAttributes;
	}

	/**
	 * @param array $item
	 * @param KeyValue $settings
	 * @param int $number
	 * @return string
	 */
	private function getImageByNumber($item, KeyValue $settings, int $number):string
	{
		$imageList = $this->elasticExportHelper->getImageList($item, $settings);

		if(count($imageList) > 0 && array_key_exists($number, $imageList))
		{
			return $imageList[$number];
		}
		else
		{
			return '';
		}
	}

	/**
	 * Get stock informations that depend on stock settings and stock volume
	 * ($variationAvailable, $stock)
	 * @param $item
	 * @return array
	 */
	private function getStockList($item):array
	{
		$variationAvailable = 'N';
		$stock = 0;
		$stockNet = 0;

        $stockRepositoryContract = pluginApp(StockRepositoryContract::class);
        if($stockRepositoryContract instanceof StockRepositoryContract)
        {
            $stockRepositoryContract->setFilters(['variationId' => $item['id']]);
            $stockResult = $stockRepositoryContract->listStockByWarehouseType('sales',['stockNet'],1,1);
            $stockNet = $stockResult->getResult()->first()->stockNet;
        }
		
		if($item['data']['variation']['stockLimitation'] == 2)
		{
			$variationAvailable = 'Y';
			$stock = 999;
		}
		elseif($item['data']['variation']['stockLimitation'] == 1 && $stockNet > 0)
		{
			$variationAvailable = 'Y';
			if($stockNet > 999)
			{
				$stock = 999;
			}
			else
			{
				$stock = $stockNet;
			}
		}
		elseif($item['data']['variation']['stockLimitation'] == 0)
		{
			$variationAvailable = 'Y';
			if($stockNet > 999)
			{
				$stock = 999;
			}
			else
			{
				if($stockNet > 0)
				{
					$stock = $stockNet;
				}
				else
				{
					$stock = 999;
				}
			}
		}

		return array (
			'stock'                     =>  $stock,
			'variationAvailable'        =>  $variationAvailable,
		);

	}

    /**
     * Returns a list of additional header for the CSV based on
     * the configured properties and builds also the property data for
     * further usage. The properties have to have a configuration for BeezUp.
     *
     * @param array $variations
     * @return array
     */
    private function buildPropertyData($variations):array
    {
        /**
         * @var PropertyNameRepositoryContract $propertyNameRepository
         */
        $propertyNameRepository = pluginApp(PropertyNameRepositoryContract::class);

        /**
         * @var PropertyMarketReferenceRepositoryContract $propertyMarketReferenceRepository
         */
        $propertyMarketReferenceRepository = pluginApp(PropertyMarketReferenceRepositoryContract::class);
        $header = [];

        if(!$propertyNameRepository instanceof PropertyNameRepositoryContract ||
            !$propertyMarketReferenceRepository instanceof PropertyMarketReferenceRepositoryContract)
        {
            return [];
        }

        foreach($variations as $variation)
        {
            foreach($variation['data']['properties'] as $property)
            {
                if(!is_null($property['property']['id']))
                {
                    $propertyName = $propertyNameRepository->findOne($property['property']['id'], 'de');
                    $propertyMarketReference = $propertyMarketReferenceRepository->findOne($property['property']['id'], self::MARKET_REFERENCE_BEEZUP);

                    if(!($propertyName instanceof PropertyName) ||
                        is_null($propertyName) ||
                        is_null($propertyMarketReference)
                    )
                    {
                        continue;
                    }



                    $header[] = $propertyName->name;
                    if($property['property']['valueType'] == 'text')
                    {
                        if(is_array($property['texts']))
                        {
                            $this->propertyData[$variation['id']][$propertyName->name] = $property['texts'][0]['value'];
                        }
                    }
                    if($property['property']['valueType'] == 'selection')
                    {
                        if(is_array($property['selection']))
                        {
                            $this->propertyData[$variation['id']][$propertyName->name] = $property['selection'][0]['name'];
                        }
                    }
                }
            }
        }

        return array_unique($header);
    }

	/**
	 * Adds the data of the properties to the existing data array.
	 * The header of $data has to be extended before.
	 *
	 * @param array $data
	 * @param int $variationId
	 * @return array
	 */
	private function addPropertyData($data, $variationId):array
	{
		foreach($this->propertyData[$variationId] as $name => $value)
		{
			$data[$name] = $value;
		}

		return $data;
	}

    /**
     * Get a List of price, reduced price and the reference for the reduced price.
     * @param array $item
     * @param KeyValue $settings
     * @return array
     */
    private function getPriceList($item, KeyValue $settings):array
    {
        //getting the retail price
        /**
         * SalesPriceSearchRequest $salesPriceSearchRequest
         */
        $salesPriceSearchRequest = pluginApp(SalesPriceSearchRequest::class);
        if($salesPriceSearchRequest instanceof SalesPriceSearchRequest)
        {
            $salesPriceSearchRequest->variationId = $item['id'];
            $salesPriceSearchRequest->referrerId = $settings->get('referrerId');
        }

        $salesPriceSearch  = $this->salesPriceSearchRepository->search($salesPriceSearchRequest);
        $variationPrice = $salesPriceSearch->price;

        //getting the recommended retail price
        if($settings->get('transferRrp') == self::TRANSFER_RRP_YES)
        {
            $salesPriceSearchRequest->type = 'rrp';
            $variationRrp = $this->salesPriceSearchRepository->search($salesPriceSearchRequest)->price;
        }
        else
        {
            $variationRrp = 0.00;
        }

        //setting retail price as selling price without a reduced price
        $price = $variationPrice;
        $reducedPrice = '';

        if ($price != '' || $price != 0.00)
        {
            //if recommended retail price is set and higher than retail price...
            if ($variationRrp > 0 && $variationRrp > $variationPrice)
            {
                //set recommended retail price as selling price
                $price = $variationRrp;
                //set retail price as reduced price
                $reducedPrice = $variationPrice;
            }
        }
        return array(
            'variationRetailPrice.price'                     =>  $price,
            'reducedPrice'                                   =>  $reducedPrice,
        );
    }
}