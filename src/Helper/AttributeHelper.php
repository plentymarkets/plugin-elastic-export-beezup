<?php

namespace ElasticExportBeezUp\Helper;

use Plenty\Modules\Item\Attribute\Contracts\AttributeValueNameRepositoryContract;
use Plenty\Modules\Item\Attribute\Models\AttributeValueName;
use Plenty\Modules\Helper\Models\KeyValue;

class AttributeHelper
{
	/**
	 * @var AttributeValueNameRepositoryContract
	 */
	private $attributeValueNameRepository;

	/**
	 * AttributeHelper constructor.
	 * @param AttributeValueNameRepositoryContract $attributeValueNameRepository
	 */
	public function __construct(AttributeValueNameRepositoryContract $attributeValueNameRepository)
	{
		$this->attributeValueNameRepository = $attributeValueNameRepository;
	}

	/**
	 * Get variation attributes.
	 * @param  array   $item
	 * @param  KeyValue $settings
	 * @return array<string,string>
	 */
	public function getVariationAttributes($item, KeyValue $settings):array
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
}