<?php

use skewer\base\section\models\TreeSection;
use skewer\base\site\Layer;
use skewer\build\Tool\Rss;
use skewer\build\Tool\SeoGen\exporter\Api as ExporterApi;
use skewer\build\Tool\SeoGen\importer\Api;
use skewer\components\search;

$aConfig['name'] = 'Documents';
$aConfig['title'] = 'Документы (админ)';
$aConfig['version'] = '1.0';
$aConfig['description'] = 'Админ-интерфейс управления документами';
$aConfig['revision'] = '0002';
$aConfig['layer'] = Layer::ADM;

$aConfig['events'][] = [
    'event' => TreeSection::EVENT_BEFORE_DELETE,
    'eventClass' => TreeSection::className(),
    'class' => \skewer\build\Adm\Documents\models\Documents::className(),
    'method' => 'removeSection',
];

$aConfig['events'][] = [
    'event' => search\Api::EVENT_GET_ENGINE,
    'class' => \skewer\build\Adm\Documents\models\Documents::className(),
    'method' => 'getSearchEngine',
];

/*$aConfig['events'][] = [
    'event' => Rss\Api::EVENT_REBUILD_RSS,
    'class' => Rss\Api::className(),
    'method' => 'rebuildRss',
];

$aConfig['events'][] = [
    'event' => Rss\Api::EVENT_GET_DATA,
    'class' => \skewer\build\Adm\Documents\models\Documents::className(),
    'method' => 'getRssRows',
];

$aConfig['events'][] = [
    'event' => Api::EVENT_GET_LIST_IMPORTERS,
    'class' => \skewer\build\Adm\Documents\models\Documents::className(),
    'method' => 'getImporter',
];

$aConfig['events'][] = [
    'event' => ExporterApi::EVENT_GET_LIST_EXPORTERS,
    'class' => \skewer\build\Adm\Documents\models\Documents::className(),
    'method' => 'getExporter',
];*/

return $aConfig;
