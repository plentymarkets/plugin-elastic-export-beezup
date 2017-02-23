<?php

namespace ElasticExportBeezUp;

use Plenty\Modules\DataExchange\Services\ExportPresetContainer;
use Plenty\Plugin\DataExchangeServiceProvider;

class ElasticExportBeezUpServiceProvider extends DataExchangeServiceProvider
{
    public function register()
    {

    }

    public function exports(ExportPresetContainer $container)
    {
        $container->add(
            'BeezUp-Plugin',
            'ElasticExportBeezUp\ResultField\BeezUp',
            'ElasticExportBeezUp\Generator\BeezUp',
            true
        );
    }
}