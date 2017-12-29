<?php

namespace ElasticExportBeezUp\Helper;

use Plenty\Modules\StockManagement\Stock\Contracts\StockRepositoryContract;
use Plenty\Repositories\Models\PaginatedResult;
use Plenty\Modules\Helper\Models\KeyValue;

class StockHelper
{
	private $stockBuffer = 0;

	private $stockForVariationsWithoutStockLimitation = null;

	private $stockForVariationsWithoutStockAdministration = null;
	
	/**
	 * Get stock informations that depend on stock settings and stock volume
	 * ($variationAvailable, $stock)
	 * @param $item
	 * @return array
	 */
	public function getStockList($item):array
	{
		$variationAvailable = 'N';
		$stock = 0;
		$stockNet = 0;

		$stockRepositoryContract = pluginApp(StockRepositoryContract::class);
		if($stockRepositoryContract instanceof StockRepositoryContract)
		{
			$stockRepositoryContract->setFilters(['variationId' => $item['id']]);
			$stockResult = $stockRepositoryContract->listStockByWarehouseType('sales',['*'],1,1);
			if($stockResult instanceof PaginatedResult)
			{
				$stockList = $stockResult->getResult();
				foreach($stockList as $stock)
				{
					$stock = $stock->stockNet;
					break;
				}
			}
		}

		if($item['data']['variation']['stockLimitation'] == 2)
		{
			if(!is_null($this->stockForVariationsWithoutStockAdministration))
			{
				$stock = $this->stockForVariationsWithoutStockAdministration;
			}
			else
			{
				$stock = 999;
			}
			
			$variationAvailable = 'Y';
		}
		elseif($item['data']['variation']['stockLimitation'] == 1 && $stockNet > 0)
		{
			if($stockNet > 999)
			{
				$stock = 999;
			}
			else
			{
				$stock = $stockNet - $this->stockBuffer;
			}

			if($stock < 0)
			{
				$stock = 0;
			}
			else
			{
				$variationAvailable = 'Y';
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
				elseif(!is_null($this->stockForVariationsWithoutStockLimitation))
				{
					$stock = $this->stockForVariationsWithoutStockLimitation;
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
	 * @param KeyValue $settings
	 */
	public function setAdditionalStockInformation(KeyValue $settings)
	{
		if(!is_null($settings->get('stockBuffer')) && $settings->get('stockBuffer') > 0)
		{
			$this->stockBuffer = $settings->get('stockBuffer');
		}

		if(!is_null($settings->get('stockForVariationsWithoutStockAdministration')) && $settings->get('stockForVariationsWithoutStockAdministration') > 0)
		{
			$this->stockForVariationsWithoutStockAdministration = $settings->get('stockForVariationsWithoutStockAdministration');
		}

		if(!is_null($settings->get('stockForVariationsWithoutStockLimitation')) && $settings->get('stockForVariationsWithoutStockLimitation') > 0)
		{
			$this->stockForVariationsWithoutStockLimitation = $settings->get('stockForVariationsWithoutStockLimitation');
		}
	}
}