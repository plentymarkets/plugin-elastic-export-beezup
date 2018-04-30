<?php

namespace ElasticExportBeezUp\Generator;

use ElasticExport\Helper\ElasticExportPriceHelper;
use ElasticExport\Helper\ElasticExportStockHelper;
use ElasticExport\Services\FiltrationService;
use ElasticExportBeezUp\Helper\AttributeHelper;
use ElasticExportBeezUp\Helper\ImageHelper;
use ElasticExportBeezUp\Helper\ItemHelper;
use ElasticExportBeezUp\Helper\PropertyHelper;
use ElasticExportBeezUp\Helper\StockHelper;
use Plenty\Modules\DataExchange\Contracts\CSVPluginGenerator;
use Plenty\Modules\Helper\Services\ArrayHelper;
use ElasticExport\Helper\ElasticExportCoreHelper;
use Plenty\Modules\Helper\Models\KeyValue;
use Plenty\Modules\Item\Search\Contracts\VariationElasticSearchScrollRepositoryContract;
use Plenty\Plugin\Log\Loggable;

/**
 * Class BeezUp
 * @package ElasticExportBeezUp\Generator
 */
class BeezUp extends CSVPluginGenerator
{
    use Loggable;

	const MARKET_REFERENCE_BEEZUP = 127.00;
    const TRANSFER_RRP_YES = 1;
    const TRANSFER_OFFER_PRICE_YES = 1;
    const DELIMITER = ';';

	/**
	 * @var ElasticExportCoreHelper $elasticExportHelper
	 */
	private $elasticExportHelper;

	/**
	 * @var ElasticExportPriceHelper $elasticExportPriceHelper
	 */
	private $elasticExportPriceHelper;

	/**
	 * @var ArrayHelper
	 */
	private $arrayHelper;

    /**
     * @var ElasticExportStockHelper $elasticExportStockHelper
     */
    private $elasticExportStockHelper;

	/**
	 * @var PropertyHelper
	 */
	private $propertyHelper;

	/**
	 * @var ImageHelper
	 */
	private $imageHelper;

	/**
	 * @var ItemHelper
	 */
	private $itemHelper;

	/**
	 * @var AttributeHelper
	 */
	private $attributeHelper;

	/**
	 * @var StockHelper
	 */
	private $stockHelper;

    /**
     * @var FiltrationService
     */
    private $filtrationService;

	/**
	 * @var array
	 */
	private $additionalHeader;

	/**
	 * BeezUp constructor.
	 *
	 * @param ArrayHelper $arrayHelper
	 * @param PropertyHelper $propertyHelper
	 * @param ImageHelper $imageHelper
	 * @param ItemHelper $itemHelper
	 * @param AttributeHelper $attributeHelper
	 * @param StockHelper $stockHelper
	 */
	public function __construct(
		ArrayHelper $arrayHelper,
		PropertyHelper $propertyHelper,
		ImageHelper $imageHelper,
		ItemHelper $itemHelper,
		AttributeHelper $attributeHelper,
		StockHelper $stockHelper
	)
	{
		$this->arrayHelper = $arrayHelper;
		$this->propertyHelper = $propertyHelper;
		$this->imageHelper = $imageHelper;
		$this->itemHelper = $itemHelper;
		$this->attributeHelper = $attributeHelper;
		$this->stockHelper = $stockHelper;
	}

	/**
	 * @param VariationElasticSearchScrollRepositoryContract $elasticSearch
	 * @param array $formatSettings
	 * @param array $filter
	 */
	protected function generatePluginContent($elasticSearch, array $formatSettings = [], array $filter = [])
	{
		$this->elasticExportStockHelper = pluginApp(ElasticExportStockHelper::class);
		$this->elasticExportHelper = pluginApp(ElasticExportCoreHelper::class);
		$this->elasticExportPriceHelper = pluginApp(ElasticExportPriceHelper::class);

        $settings = $this->arrayHelper->buildMapFromObjectList($formatSettings, 'key', 'value');
		$this->filtrationService = pluginApp(FiltrationService::class, ['settings' => $settings, 'filterSettings' => $filter]);
        
        $this->setDelimiter(self::DELIMITER);

        $this->additionalHeader = $this->propertyHelper->getPropertyBasedHeader();
        $header = array_merge($this->getHeader(), $this->additionalHeader);
        $this->stockHelper->setAdditionalStockInformation($settings);
        $this->addCSVContent($header);

        if($elasticSearch instanceof VariationElasticSearchScrollRepositoryContract)
        {
        	$elasticSearch->setNumberOfDocumentsPerShard(250);
        	
            $limitReached = false;
            $lines = 0;

            do
            {
                if($limitReached === true)
                {
                    break;
                }

                $resultList = $elasticSearch->execute();

				if(count($resultList['error']) > 0)
				{
					$this->getLogger(__METHOD__)->error('ElasticExportBeezUp::log.ElasticSearchErrors', [
						'Error message' => $resultList['error'],
					]);
				}

                if(is_array($resultList['documents']) && count($resultList['documents']) > 0)
                {
                    foreach($resultList['documents'] as $variation)
                    {
                        if($lines == $filter['limit'])
                        {
                            $limitReached = true;
                            break;
                        }

						if($this->filtrationService->filter($variation))
						{
							continue;
						}

                        try
                        {
							// Build the new row for printing in the CSV file
							$this->buildRow($variation, $settings);
                            $lines++;
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
            }
            while ($elasticSearch->hasNext());
        }
	}

	/**
	 * @return array
	 */
	private function getHeader()
	{
		$header = [
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

		return $header;
	}

	/**
	 * Creates the variation row and prints it into the CSV file.
	 *
	 * @param array $variation
	 * @param KeyValue $settings
	 */
	private function buildRow($variation, KeyValue $settings)
	{
		$stockList = $this->stockHelper->getStockList($variation);
		$variationAttributes = $this->attributeHelper->getVariationAttributes($variation, $settings);
		$priceList = $this->elasticExportPriceHelper->getPriceList($variation, $settings);

		$price = $priceList['price'];
		$rrp = '';

		if((float)$price > 0 && (float)$priceList['recommendedRetailPrice'] > (float)$price)
		{
			$rrp = $priceList['recommendedRetailPrice'];
		}

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
			'Produktname'           =>  $this->elasticExportHelper->getMutatedName($variation, $settings, 256),
			'Produktbeschreibung'   =>  $this->itemHelper->getDescription($variation, $settings),
			'Preis inkl. MwSt.'     =>  $price,
			'UVP inkl. MwSt.'       =>  $rrp,
			'Produkt-URL'           =>  $this->elasticExportHelper->getMutatedUrl($variation, $settings),
			'Bild-URL'              =>  $this->imageHelper->getImageByNumber($variation, $settings, 0),
			'Bild-URL2'             =>  $this->imageHelper->getImageByNumber($variation, $settings, 1),
			'Bild-URL3'             =>  $this->imageHelper->getImageByNumber($variation, $settings, 2),
			'Bild-URL4'             =>  $this->imageHelper->getImageByNumber($variation, $settings, 3),
			'Bild-URL5'             =>  $this->imageHelper->getImageByNumber($variation, $settings, 4),
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
			'Grundpreis'            =>  $this->elasticExportPriceHelper->getBasePrice($variation, (float)$price),
			'ID'                    =>  $variation['data']['item']['id'],
		];

		foreach($this->additionalHeader as $header)
		{
			$data[$header] = "";
		}

		$data = $this->propertyHelper->addPropertyData($data, $variation);

		$this->addCSVContent(array_values($data));
	}
}