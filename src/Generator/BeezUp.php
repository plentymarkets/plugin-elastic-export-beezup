<?php

namespace ElasticExportBeezUp\Generator;

use Plenty\Modules\DataExchange\Contracts\CSVPluginGenerator;
use Plenty\Modules\Helper\Services\ArrayHelper;
use Plenty\Modules\Item\DataLayer\Models\Record;
use Plenty\Modules\Item\DataLayer\Models\RecordList;
use Plenty\Modules\DataExchange\Models\FormatSetting;
use ElasticExport\Helper\ElasticExportCoreHelper;
use Plenty\Modules\Helper\Models\KeyValue;
use Plenty\Modules\Item\Attribute\Contracts\AttributeValueNameRepositoryContract;
use Plenty\Modules\Item\Attribute\Models\AttributeValueName;
use Plenty\Modules\Item\Property\Contracts\PropertyMarketReferenceRepositoryContract;
use Plenty\Modules\Item\Property\Contracts\PropertyNameRepositoryContract;
use Plenty\Modules\Item\Property\Models\PropertyName;

class BeezUp extends CSVPluginGenerator
{
	const MARKET_REFERENCE_BEEZUP = 127.00;
	const PROPERTY_TYPE_DESCRIPTION = 'description';

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
	 * @var array $idlVariations
	 */
	private $idlVariations = array();

	/**
	 * @var array $propertyData
	 */
	private $propertyData = [];

	/**
	 * BeezUp constructor.
	 * @param ArrayHelper $arrayHelper
	 * @param AttributeValueNameRepositoryContract $attributeValueNameRepository
	 */
	public function __construct(
		ArrayHelper $arrayHelper,
		AttributeValueNameRepositoryContract $attributeValueNameRepository
	)
	{
		$this->arrayHelper = $arrayHelper;
		$this->attributeValueNameRepository = $attributeValueNameRepository;
	}

	/**
	 * @param array $resultData
	 * @param array $formatSettings
	 * @param array $filter
	 */
	protected function generatePluginContent($resultData, array $formatSettings = [], array $filter = [])
	{
		$this->elasticExportHelper = pluginApp(ElasticExportCoreHelper::class);
		if(is_array($resultData['documents']) && count($resultData['documents']) > 0)
		{
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

			$variationIdList = $additionalHeaders = [];
			foreach($resultData['documents'] as $variation)
			{
				$variationIdList[] = $variation['id'];
			}

			//Get the missing fields in ES from IDL
			if(is_array($variationIdList) && count($variationIdList) > 0)
			{
				/**
				 * @var \ElasticExportBeezUp\IDL_ResultList\BeezUp $idlResultList
				 */
				$idlResultList = pluginApp(\ElasticExportBeezUp\IDL_ResultList\BeezUp::class);
				$idlResultList = $idlResultList->getResultList($variationIdList, $settings);
			}

			//Creates an array with the variationId as key to surpass the sorting problem
			if(isset($idlResultList) && $idlResultList instanceof RecordList)
			{
				$this->createIdlArray($idlResultList);

				// extend header by properties
				$additionalHeaders = $this->buildPropertyData($idlResultList);
			}

			// combine hard coded headers with property based headers
			$csvHeader = array_merge($csvHeader, $additionalHeaders);
			$this->addCSVContent($csvHeader);

			foreach($resultData['documents'] as $item)
			{
				$variationAttributes = $this->getVariationAttributes($item, $settings);

				$stockList = $this->getStockList($item);

                // Get and set the price and rrp
				$price = $this->idlVariations[$item['id']]['variationRetailPrice.price'];
				$rrp = $this->elasticExportHelper->getRecommendedRetailPrice($this->idlVariations[$item['id']]['variationRecommendedRetailPrice.price'], $settings);
				$rrp = ($price >= $rrp) ? 0 : $rrp;

                // Get shipping costs
				$shippingCost = $this->elasticExportHelper->getShippingCost($item['data']['item']['id'], $settings);

				if(!is_null($shippingCost))
				{
					$shippingCost = number_format((float)$shippingCost, 2, ',', '');
				}
				else
				{
					$shippingCost = '';
				}

				$data = [
					'Produkt ID'            =>  $item['id'],
					'Artikel Nr'            =>  $this->idlVariations[$item['id']]['variationBase.customNumber'],
					'MPN'                   =>  $item['data']['variation']['model'],
					'EAN'                   =>  $this->elasticExportHelper->getBarcodeByType($item, $settings->get('barcode')),
					'Marke'                 =>  $this->elasticExportHelper->getExternalManufacturerName((int)$item['data']['item']['manufacturer']['id']),
					'Produktname'           =>  $this->elasticExportHelper->getName($item, $settings, 256),
					'Produktbeschreibung'   =>  $this->getDescription($item, $settings),
					'Preis inkl. MwSt.'     =>  number_format((float)$price, 2, '.', ''),
					'UVP inkl. MwSt.'       =>  number_format((float)$rrp, 2, '.', ''),
					'Produkt-URL'           =>  $this->elasticExportHelper->getUrl($item, $settings),
					'Bild-URL'              =>  $this->getImageByNumber($item, $settings, 0),
					'Bild-URL2'             =>  $this->getImageByNumber($item, $settings, 1),
					'Bild-URL3'             =>  $this->getImageByNumber($item, $settings, 2),
					'Bild-URL4'             =>  $this->getImageByNumber($item, $settings, 3),
					'Bild-URL5'             =>  $this->getImageByNumber($item, $settings, 4),
					'Lieferkosten'          =>  $shippingCost,
					'Auf Lager'             =>  $stockList['variationAvailable'],
					'Lagerbestand'          =>  $stockList['stock'],
					'Lieferfrist'           =>  $this->elasticExportHelper->getAvailability($item['data']['defaultCategories'][0]['id'], $settings, false),
					'Kategorie 1'           =>  $this->elasticExportHelper->getCategoryBranch($item['data']['defaultCategories'][0]['id'], $settings, 1),
					'Kategorie 2'           =>  $this->elasticExportHelper->getCategoryBranch($item['data']['defaultCategories'][0]['id'], $settings, 2),
					'Kategorie 3'           =>  $this->elasticExportHelper->getCategoryBranch($item['data']['defaultCategories'][0]['id'], $settings, 3),
					'Kategorie 4'           =>  $this->elasticExportHelper->getCategoryBranch($item['data']['defaultCategories'][0]['id'], $settings, 4),
					'Kategorie 5'           =>  $this->elasticExportHelper->getCategoryBranch($item['data']['defaultCategories'][0]['id'], $settings, 5),
					'Kategorie 6'           =>  $this->elasticExportHelper->getCategoryBranch($item['data']['defaultCategories'][0]['id'], $settings, 6),
					'Farbe'                 =>  $variationAttributes['Color'],
					'Größe'                 =>  $variationAttributes['Size'],
					'Gewicht'               =>  $item['data']['variation']['weightG'],
					'Grundpreis'            =>  $this->elasticExportHelper->getBasePrice($item, $this->idlVariations[$item['id']]),
					'ID'                    =>  $item['data']['item']['id'],
				];

				$data = $this->addPropertyData($data, $item['id']);

				$this->addCSVContent(array_values($data));
			}
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

		if($item['data']['variation']['stockLimitation'] == 2)
		{
			$variationAvailable = 'Y';
			$stock = 999;
		}
		elseif($item['data']['variation']['stockLimitation'] == 1 && $this->idlVariations[$item['id']]['variationStock.stockNet'] > 0)
		{
			$variationAvailable = 'Y';
			if($this->idlVariations[$item['id']]['variationStock.stockNet'] > 999)
			{
				$stock = 999;
			}
			else
			{
				$stock = $this->idlVariations[$item['id']]['variationStock.stockNet'];
			}
		}
		elseif($item['data']['variation']['stockLimitation'] == 0)
		{
			$variationAvailable = 'Y';
			if($this->idlVariations[$item['id']]['variationStock.stockNet'] > 999)
			{
				$stock = 999;
			}
			else
			{
				if($this->idlVariations[$item['id']]['variationStock.stockNet'] > 0)
				{
					$stock = $this->idlVariations[$item['id']]['variationStock.stockNet'];
				}
				else
				{
					$stock = 0;
				}
			}
		}

		return array (
			'stock'                     =>  $stock,
			'variationAvailable'        =>  $variationAvailable,
		);

	}

	/**
	 * @param RecordList $idlResultList
	 */
	private function createIdlArray($idlResultList)
	{
		if($idlResultList instanceof RecordList)
		{
			foreach($idlResultList as $idlVariation)
			{
				if($idlVariation instanceof Record)
				{
					$this->idlVariations[$idlVariation->variationBase->id] = [
						'itemBase.id'                           => $idlVariation->itemBase->id,
						'variationBase.id'                      => $idlVariation->variationBase->id,
						'variationBase.customNumber'            => $idlVariation->variationBase->customNumber,
						'itemPropertyList'                      => $idlVariation->itemPropertyList,
						'variationStock.stockNet'               => $idlVariation->variationStock->stockNet,
						'variationRetailPrice.price'            => $idlVariation->variationRetailPrice->price,
						'variationRecommendedRetailPrice.price' => $idlVariation->variationRecommendedRetailPrice->price,
					];
				}
			}
		}
	}

	/**
	 * Returns a list of additional header for the CSV based on
	 * the configured properties and builds also the property data for
	 * further usage. The properties have to have a configuration for BeezUp.
	 *
	 * @param RecordList $idLResultList
	 * @return array
	 */
	private function buildPropertyData($idLResultList):array
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

		/**
		 * @var RecordList $variation
		 */
		foreach($idLResultList as $variation)
		{
			foreach($variation->itemPropertyList as $property)
			{
				$propertyName = $propertyNameRepository->findOne($property->propertyId, 'de');
				$propertyMarketReference = $propertyMarketReferenceRepository->findOne($property->propertyId, self::MARKET_REFERENCE_BEEZUP);

				if(!($propertyName instanceof PropertyName) ||
					is_null($propertyName) ||
					is_null($propertyMarketReference)
				)
				{
					continue;
				}



				$header[] = $propertyName->name;
				$this->propertyData[$variation->variationBase->id][$propertyName->name] = $property->propertyValue;
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
}