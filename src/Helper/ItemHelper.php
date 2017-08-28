<?php

namespace ElasticExportBeezUp\Helper;


use ElasticExport\Helper\ElasticExportCoreHelper;
use ElasticExport\Helper\ElasticExportPropertyHelper;
use Plenty\Modules\Helper\Models\KeyValue;

class ItemHelper
{
	const PROPERTY_TYPE_DESCRIPTION = 'description';

	/**
	 * @var ElasticExportPropertyHelper $elasticExportPropertyHelper
	 */
	private $elasticExportPropertyHelper;
	/**
	 * @var ElasticExportCoreHelper
	 */
	private $elasticExportHelper;

	/**
	 * ItemHelper constructor.
	 * @param ElasticExportPropertyHelper $elasticExportPropertyHelper
	 * @param ElasticExportCoreHelper $elasticExportHelper
	 */
	public function __construct(ElasticExportPropertyHelper $elasticExportPropertyHelper,
								ElasticExportCoreHelper $elasticExportHelper)
	{
		$this->elasticExportPropertyHelper = $elasticExportPropertyHelper;
		$this->elasticExportHelper = $elasticExportHelper;
	}

	/**
	 * Get item description.
	 * @param array $variation
	 * @param KeyValue $settings
	 * @return string
	 */
	public function getDescription($variation, KeyValue $settings):string
	{
		$description = $this->elasticExportPropertyHelper->getItemPropertyByBackendName($variation, self::PROPERTY_TYPE_DESCRIPTION);

		if (strlen($description) <= 0)
		{
			$description = $this->elasticExportHelper->getMutatedDescription($variation, $settings, 5000);
		}

		return $description;
	}
}