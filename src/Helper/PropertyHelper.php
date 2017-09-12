<?php

namespace ElasticExportBeezUp\Helper;

use ElasticExport\Helper\ElasticExportPropertyHelper;

class PropertyHelper
{
	const MARKET_REFERENCE_BEEZUP = 127.00;

	/**
	 * @var ElasticExportPropertyHelper $elasticExportPropertyHelper
	 */
	private $elasticExportPropertyHelper;

	/**
	 * @var array
	 */
	private $propertyList = [];

	/**
	 * @var array
	 */
	private $propertyBaseHeader = [];

	public function __construct(ElasticExportPropertyHelper $elasticExportPropertyHelper)
	{
		$this->elasticExportPropertyHelper = $elasticExportPropertyHelper;
	}

	/**
	 * Returns a list of additional header for the CSV based on
	 * the configured properties and builds also the property data for
	 * further usage. The properties have to have a configuration for BeezUp.
	 *
	 * @param array $variations
	 * @return array
	 */
	public function buildPropertyData($variations)
	{
		$header = [];

		foreach($variations as $variation)
		{
			foreach($variation['data']['properties'] as $property)
			{
				if(!is_null($property['property']['id']))
				{
					$propertyData = $this->elasticExportPropertyHelper->getItemPropertyList($variation, self::MARKET_REFERENCE_BEEZUP);

					if(count($propertyData))
					{
						foreach($propertyData as $propertyName => $value)
						{
							$header[] = $propertyName;
							$this->propertyList[$variation['id']][$propertyName] = $value;
						}
					}
				}
			}
		}

		$header = array_unique($header);
		return $this->propertyBaseHeader = $header;
	}

	/**
	 * Adds the data of the properties to the existing data array.
	 * The header of $data has to be extended before.
	 *
	 * @param array $data
	 * @param int $variationId
	 * @return array
	 */
	public function addPropertyData($data, $variationId):array
	{
		foreach($this->propertyBaseHeader as $header)
		{
			$data[$header] = "";
		}

		foreach($this->propertyList[$variationId] as $name => $value)
		{
			$data[$name] = (string)$value;
		}

		return $data;
	}
}