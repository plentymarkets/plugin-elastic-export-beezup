<?php

namespace ElasticExportBeezUp\IDL_ResultList;

use Plenty\Modules\Item\DataLayer\Contracts\ItemDataLayerRepositoryContract;
use Plenty\Modules\Helper\Models\KeyValue;
use Plenty\Modules\Item\DataLayer\Models\RecordList;

class BeezUp
{
    const BEEZUP = 127.00;
    /**
     * @param array $variationIds
     * @param KeyValue $settings
     * @return RecordList|string
     */
    public function getResultList($variationIds, $settings)
    {
        $referrerId = $settings->get('referrerId') ? $settings->get('referrerId') : self::BEEZUP;

        if(is_array($variationIds) && count($variationIds) > 0)
        {
            $searchFilter = array(
                'variationBase.hasId' => array(
                    'id' => $variationIds
                )
            );

            $resultFields = array(
                'itemBase' => array(
                    'id',
                ),

                'variationBase' => array(
                    'id',
                    'customNumber'
                ),

                'itemPropertyList' => array(
                    'fields' => array(
                        'propertyId',
                        'propertyValue',
                    )
                ),

                'variationStock' => array(
                    'params' => array(
                        'type' => 'virtual'
                    ),
                    'fields' => array(
                        'stockNet'
                    )
                ),

                'variationRetailPrice' => array(
                    'params' => array(
                        'referrerId' => $referrerId,
                    ),
                    'fields' => array(
                        'price',
                    ),
                ),

                'variationRecommendedRetailPrice' => array(
                    'params' => array(
                        'referrerId' => $referrerId,
                    ),
                    'fields' => array(
                        'price',    // uvp
                    ),
                ),
            );

            $itemDataLayer = pluginApp(ItemDataLayerRepositoryContract::class);
            /**
             * @var ItemDataLayerRepositoryContract $itemDataLayer
             */
            $itemDataLayer = $itemDataLayer->search($resultFields, $searchFilter);
            return $itemDataLayer;
        }
        return '';
    }
}