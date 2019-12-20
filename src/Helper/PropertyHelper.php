<?php

namespace ElasticExportBeezUp\Helper;

use ElasticExport\Helper\ElasticExportPropertyHelper;
use Plenty\Modules\Item\Property\Contracts\PropertyMarketReferenceRepositoryContract;
use Plenty\Modules\Item\Property\Contracts\PropertyNameRepositoryContract;
use Plenty\Modules\Item\Property\Models\PropertyMarketReference;
use Plenty\Modules\Item\Property\Models\PropertyName;

/**
 * Class PropertyHelper
 * @package ElasticExportBeezUp\Helper
 */
class PropertyHelper
{
	const MARKET_REFERENCE_BEEZUP = 127.00;

	/**
	 * @var ElasticExportPropertyHelper $elasticExportPropertyHelper
	 */
	private $elasticExportPropertyHelper;

	/**
	 * @var PropertyMarketReferenceRepositoryContract $propertyMarketReferenceRepository
	 */
	private $propertyMarketReferenceRepository;

	/**
	 * @var PropertyNameRepositoryContract
	 */
	private $propertyNameRepository;

	/**
	 * @var array
	 */
	private $propertyList = [];

	/**
	 * PropertyHelper constructor.
	 * @param ElasticExportPropertyHelper $elasticExportPropertyHelper
	 * @param PropertyMarketReferenceRepositoryContract $propertyMarketReferenceRepository
	 * @param PropertyNameRepositoryContract $propertyNameRepository
	 */
	public function __construct(
		ElasticExportPropertyHelper $elasticExportPropertyHelper,
		PropertyMarketReferenceRepositoryContract $propertyMarketReferenceRepository,
		PropertyNameRepositoryContract $propertyNameRepository
	)
	{
		$this->elasticExportPropertyHelper = $elasticExportPropertyHelper;
		$this->propertyMarketReferenceRepository = $propertyMarketReferenceRepository;
		$this->propertyNameRepository = $propertyNameRepository;
	}

	/**
	 * Adds the data of the properties to the existing data array.
	 * The header of $data has to be extended before.
	 *
	 * @param array $data
	 * @param array $variation
	 * @return array
	 */
	public function addPropertyData($data, $variation):array
	{
		$properties = $this->elasticExportPropertyHelper->getItemPropertyList($variation, self::MARKET_REFERENCE_BEEZUP);

		if(count($properties))
		{
			foreach($properties as $name => $value)
			{
				$data[$name] = (string)$value;
			}
		}

		return $data;
	}

	/**
     * @param array $defaultHeader
	 * @return array
	 */
	public function getPropertyBasedHeader(array $defaultHeader): array
	{
		$list = [];

		/**
		 * @return array
		 */
		$result = $this->propertyMarketReferenceRepository->getPropertyMarketReferences(self::MARKET_REFERENCE_BEEZUP);

		if(count($result))
		{
			/**
			 * @var PropertyMarketReference $propertyMarketReference
			 */
			foreach($result as $propertyMarketReference)
			{
				/**
				 * @var PropertyName
				 */
				$propertyName = $this->propertyNameRepository->findOne($propertyMarketReference->propertyId, 'de');

				if (!$propertyName instanceof PropertyName || is_null($propertyName)) {
				    continue;
				}

				if (in_array($propertyName->name, $defaultHeader)) {
				    continue;
                }
				
                $list[] = $propertyName->name;
			}
		}

		return $list;
	}
}