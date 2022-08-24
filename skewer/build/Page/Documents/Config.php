<?php

/* main */
use skewer\base\site\Layer;

$aConfig['name'] = 'Documents';
$aConfig['title'] = 'Документы';
$aConfig['version'] = '1.000';
$aConfig['description'] = 'Документы';
$aConfig['revision'] = '0002';
$aConfig['layer'] = Layer::PAGE;
$aConfig['languageCategory'] = 'review';

$aConfig['dependency'] = [
    ['Documents', Layer::ADM],
    ['Review', Layer::TOOL],
];

return $aConfig;
