<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoAustrianAdministrativeAreas\Form;

use Contao\FormSelectMenu;
use Contao\System;
use InspiredMinds\ContaoAustrianAdministrativeAreas\ContaoAustrianAdministrativeAreas;

class FormAustrianMunicipalities extends FormSelectMenu
{
    public function __construct($arrAttributes = null)
    {
        parent::__construct($arrAttributes);

        // Include empty value
        $this->arrOptions[] = [['value' => '', 'label' => '']];

        // Fill options
        foreach ($this->getMunicipalityOptions() as $value => $label) {
            $this->arrOptions[] = [
                'value' => $value,
                'label' => $label,
            ];
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

    /**
     * Returns an associative options array, depending on the form field configuration.
     */
    protected function getMunicipalityOptions(): array
    {
        // Get the municipalities
        $municipalities = System::getContainer()->get(ContaoAustrianAdministrativeAreas::class)->getMunicipalities();

        // Create options
        $options = [];

        foreach ($municipalities as $municipality) {
            // Default
            $value = $municipality['Gemeindename'];
            $label = $municipality['Gemeindename'];

            switch ($this->austrianMunicipalitiesValue) {
                case 'id': $value = $municipality['Gemeindekennziffer'];
                    break;
                case 'postal': $value = $municipality['PLZ des Gem.Amtes'];
                    break;
            }

            switch ($this->austrianMunicipalitiesLabel) {
                case 'name_id': $label = $municipality['Gemeindename'].' ('.$municipality['Gemeindekennziffer'].')';
                    break;
                case 'name_postal': $label = $municipality['Gemeindename'].' ('.$municipality['PLZ des Gem.Amtes'].')';
                    break;
                case 'id': $label = $municipality['Gemeindekennziffer'];
                    break;
                case 'id_name': $label = $municipality['Gemeindekennziffer'].' ('.$municipality['Gemeindename'].')';
                    break;
                case 'postal': $label = $municipality['PLZ des Gem.Amtes'];
                    break;
                case 'postal_name': $label = $municipality['PLZ des Gem.Amtes'].' ('.$municipality['Gemeindename'].')';
                    break;
            }

            $options[$value] = $label;
        }

        // Sort the array
        asort($options);

        // Return the options
        return $options;
    }
}
