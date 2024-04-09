<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoAustrianAdministrativeAreas\Form;

use Contao\FormSelectMenu;
use Contao\System;
use InspiredMinds\ContaoAustrianAdministrativeAreas\ContaoAustrianAdministrativeAreas;

class FormAustrianDistricts extends FormSelectMenu
{
    public function __construct($arrAttributes = null)
    {
        parent::__construct($arrAttributes);

        // Include empty value
        $this->arrOptions[] = [['value' => '', 'label' => '']];

        // Get the districts
        $districts = System::getContainer()->get(ContaoAustrianAdministrativeAreas::class)->getDistricts();

        // Group by "Bundesland"
        $countries = [];

        foreach ($districts as $district) {
            $countries[$district['Bundesland']][] = $district;
        }

        // Go through the districts
        foreach ($countries as $country => $districts) {
            $this->arrOptions[] = [
                'group' => true,
                'label' => $country,
                'value' => '',
            ];

            foreach ($districts as $district) {
                $this->arrOptions[] = [
                    'value' => $district['Politischer Bez. Code'],
                    'label' => $district['Politischer Bezirk'],
                ];
            }
        }
    }

    public function __set($strKey, $varValue): void
    {
        switch ($strKey) {
            case 'options':
                // Ignore
                break;

            default:
                parent::__set($strKey, $varValue);
                break;
        }
    }
}
