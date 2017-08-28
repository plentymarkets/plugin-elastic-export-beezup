<?php

namespace ElasticExportBeezUp\Helper;


use ElasticExport\Helper\ElasticExportCoreHelper;
use Plenty\Modules\Helper\Models\KeyValue;

class ImageHelper
{
	private $elasticExportHelper;

	public function __construct(ElasticExportCoreHelper $elasticExportHelper)
	{
		$this->elasticExportHelper = $elasticExportHelper;
	}

	/**
	 * @param array $variation
	 * @param KeyValue $settings
	 * @param int $number
	 * @return string
	 */
	public function getImageByNumber($variation, KeyValue $settings, int $number):string
	{
		$imageList = $this->elasticExportHelper->getImageListInOrder($variation, $settings, 5, $this->elasticExportHelper::VARIATION_IMAGES);

		if(count($imageList) > 0 && array_key_exists($number, $imageList))
		{
			return $imageList[$number];
		}
		else
		{
			return '';
		}
	}
}