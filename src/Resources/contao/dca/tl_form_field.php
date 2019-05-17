<?php

declare(strict_types=1);

/*
 * This file is part of the ContaoAustrianAdministrativeAreasBundle.
 *
 * (c) inspiredminds
 *
 * @license LGPL-3.0-or-later
 */

$GLOBALS['TL_DCA']['tl_form_field']['fields']['austrianMunicipalitiesLabel'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_form_field']['austrianMunicipalitiesLabel'],
    'exclude' => true,
    'inputType' => 'select',
    'options' => ['name', 'name_postal', 'name_id', 'id', 'id_name', 'postal', 'postal_name'],
    'reference' => &$GLOBALS['TL_LANG']['tl_form_field']['austrianMunicipalitiesLabelReference'],
    'eval' => ['tl_class' => 'w50'],
    'sql' => "varchar(16) NOT NULL default 'name'",
];

$GLOBALS['TL_DCA']['tl_form_field']['fields']['austrianMunicipalitiesValue'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_form_field']['austrianMunicipalitiesValue'],
    'exclude' => true,
    'inputType' => 'select',
    'options' => ['name', 'id', 'postal'],
    'reference' => &$GLOBALS['TL_LANG']['tl_form_field']['austrianMunicipalitiesValueReference'],
    'eval' => ['tl_class' => 'w50'],
    'sql' => "varchar(16) NOT NULL default 'name'",
];

$GLOBALS['TL_DCA']['tl_form_field']['palettes']['austrian_districts'] = '{type_legend},type,name,label;{fconfig_legend},mandatory,multiple;{expert_legend:hide},class,accesskey,tabindex;{template_legend:hide},customTpl;{invisible_legend:hide},invisible';
$GLOBALS['TL_DCA']['tl_form_field']['palettes']['austrian_municipalities'] = '{type_legend},type,name,label;{fconfig_legend},mandatory,multiple;{options_legend},austrianMunicipalitiesLabel,austrianMunicipalitiesValue;{expert_legend:hide},class,accesskey,tabindex;{template_legend:hide},customTpl;{invisible_legend:hide},invisible';
