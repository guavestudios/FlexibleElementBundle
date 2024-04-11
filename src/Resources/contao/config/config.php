<?php

$GLOBALS['TL_CTE']['flexibleElement']['flexibleElement'] = Guave\FlexibleElementBundle\Elements\ContentFlexibleElement::class;

$GLOBALS['TL_FLEXIBLEELEMENT']['templates'] = [
    [
        'id'       => 'flexible-1col-text',
        'template' => 'ce_1col-text',
    ],
    [
        'id'       => 'flexible-2col-text',
        'template' => 'ce_2col-text',
    ],
    [
        'id'       => 'flexible-2col-txt-img',
        'template' => 'ce_2col-txt-img',
    ],
    [
        'id'       => 'flexible-2col-img-txt',
        'template' => 'ce_2col-img-txt',
    ],
];

$GLOBALS['TL_FLEXIBLEELEMENT']['iconPath'] = 'bundles/guaveflexibleelement/assets';
$GLOBALS['TL_FLEXIBLEELEMENT']['iconExt']  = '.jpg';
