<?php

namespace ElasticExportBeezUp\ResultField;

use ElasticExport\DataProvider\ResultFieldDataProvider;
use Plenty\Modules\DataExchange\Contracts\ResultFields;
use Plenty\Modules\DataExchange\Models\FormatSetting;
use Plenty\Modules\Helper\Services\ArrayHelper;
use Plenty\Modules\Item\Search\Mutators\BarcodeMutator;
use Plenty\Modules\Item\Search\Mutators\ImageMutator;
use Plenty\Modules\Cloud\ElasticSearch\Lib\Source\Mutator\BuiltIn\LanguageMutator;
use Plenty\Modules\Item\Search\Mutators\DefaultCategoryMutator;
use Plenty\Modules\Item\Search\Mutators\KeyMutator;
use Plenty\Plugin\Log\Loggable;

class BeezUp extends ResultFields
{
    use Loggable;
    
    const BEEZUP = 127.00;
    /*
     * @var ArrayHelper
     */
    private $arrayHelper;

    /**
     * @param ArrayHelper $arrayHelper
     */
    public function __construct(ArrayHelper $arrayHelper)
    {
        $this->arrayHelper = $arrayHelper;
    }

    public function generateResultFields(array $formatSettings = []):array
    {
        $settings = $this->arrayHelper->buildMapFromObjectList($formatSettings, 'key', 'value');
        $reference = $settings->get('referrerId') ? $settings->get('referrerId') : self::BEEZUP;

        //Mutator
        /**
         * @var KeyMutator $keyMutator
         */
        $keyMutator = pluginApp(KeyMutator::class);
        if($keyMutator instanceof KeyMutator)
        {
            $keyMutator->setKeyList($this->getKeyList());
            $keyMutator->setNestedKeyList($this->getNestedKeyList());
        }
        /**
         * @var ImageMutator $imageMutator
         */
        $imageMutator = pluginApp(ImageMutator::class);
        if($imageMutator instanceof ImageMutator)
        {
            $imageMutator->addMarket($reference);
        }
        /**
         * @var LanguageMutator $languageMutator
         */
        $languageMutator = pluginApp(LanguageMutator::class, [[$settings->get('lang')]]);
        /**
         * @var DefaultCategoryMutator $defaultCategoryMutator
         */
        $defaultCategoryMutator = pluginApp(DefaultCategoryMutator::class);
        if($defaultCategoryMutator instanceof DefaultCategoryMutator)
        {
            $defaultCategoryMutator->setPlentyId($settings->get('plentyId'));
        }

		/**
		 * @var BarcodeMutator $barcodeMutator
		 */
		$barcodeMutator = pluginApp(BarcodeMutator::class);
		if($barcodeMutator instanceof BarcodeMutator)
		{
			$barcodeMutator->addMarket($reference);
		}

        /**
         * @var ResultFieldDataProvider $resultFieldHelper
         */
        $resultFieldHelper = pluginApp(ResultFieldDataProvider::class);
        if($resultFieldHelper instanceof ResultFieldDataProvider)
        {
            $resultFields = $resultFieldHelper->getResultFields($settings);
        }

        if(isset($resultFields) && is_array($resultFields) && count($resultFields))
        {
            $fields[0] = $resultFields;
            $fields[1] = [
                $languageMutator,
                $defaultCategoryMutator,
                $barcodeMutator,
                $keyMutator
            ];

            if($reference != -1)
            {
                $fields[1][] = $imageMutator;
            }
        }
        else
        {
            $this->getLogger(__METHOD__)->critical('ElasticExportBeezUp::log.resultFieldError');
            exit();
        }

        if($reference != -1)
        {
            $fields[1][] = $imageMutator;
        }

        return $fields;
    }

    private function getKeyList()
    {
        $keyList = [
            //item
            'item.id',
            'item.manufacturer.id',

            //variation
            'variation.availability.id',
            'variation.stockLimitation',
            'variation.weightG',
            'variation.model',
            'variation.number',

            //unit
            'unit.content',
            'unit.id',
        ];

        return $keyList;
    }

    private function getNestedKeyList()
    {
        $nestedKeyList['keys'] = [
            //images
            'images.item',
            'images.variation',

            //texts
            'texts',

            //defaultCategories
            'defaultCategories',

            //barcodes
            'barcodes',

            //attributes
            'attributes',

            //properties
            'properties',
            'properties.selection',
            'properties.texts',
        ];
        $nestedKeyList['nestedKeys'] = [
            'images.item' => [
                'urlMiddle',
                'urlPreview',
                'urlSecondPreview',
                'url',
                'path',
                'position',
            ],

            'images.variation' => [
                'urlMiddle',
                'urlPreview',
                'urlSecondPreview',
                'url',
                'path',
                'position',
            ],

            'texts'  => [
                'urlPath',
                'name1',
                'name2',
                'name3',
                'shortDescription',
                'description',
                'technicalData',
            ],

            'defaultCategories' => [
                'id'
            ],

            'barcodes'  => [
                'code',
                'type',
            ],

            'attributes'   => [
                'valueId',
            ],

            'properties'    => [
                'property.id',
                'property.valueType',
            ],
        ];

        return $nestedKeyList;
    }
}