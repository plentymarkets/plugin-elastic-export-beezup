<?php

namespace ElasticExportBeezUp\Helper;

use Plenty\Modules\StockManagement\Stock\Contracts\StockRepositoryContract;
use Plenty\Repositories\Models\PaginatedResult;

class StockHelper
{
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
			$stockResult = $stockRepositoryContract->listStockByWarehouseType('sales',['stockNet'],1,1);
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
}