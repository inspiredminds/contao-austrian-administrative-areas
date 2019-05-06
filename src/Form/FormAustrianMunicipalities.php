<?php

declare(strict_types=1);

/*
 * This file is part of the ContaoAustrianAdminstrativeAreasBundle.
 *
 * (c) inspiredminds
 *
 * @license LGPL-3.0-or-later
 */

namespace InspiredMinds\ContaoAustrianAdministrativeAreasBundle\Form;

use Contao\FormSelectMenu;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class FormAustrianMunicipalities extends FormSelectMenu
{
    protected const CACHE_NAME = 'contao.austrianmunicipalitiesform';

    protected $cache;

    public function __construct($arrAttributes = null)
    {
        parent::__construct($arrAttributes);

        $this->cache = new FilesystemAdapter();

        // Include empty value
        $this->arrOptions[] = [['value' => '', 'label' => '']];

        // Get the municipalities
        $municipalities = $this->getMunicipalities();

        foreach ($municipalities as $municipality) {
            $this->arrOptions[] = [
                'value' => $municipality['id'],
                'label' => $municipality['name'],
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
     * Returns the available municipalities as an array.
     */
    protected function getMunicipalities(): array
    {
        $cacheItem = $this->cache->getItem(self::CACHE_NAME);

        if ($cacheItem->isHit()) {
            return $cacheItem->get();
        }

        $municipalities = [];

        // Load from server
        $data = file('https://www.statistik.at/verzeichnis/reglisten/gemliste_nam.csv', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        for ($i = 3; $i < \count($data) - 1; ++$i) {
            // Get the line
            $line = explode(';', utf8_encode($data[$i]));

            if (empty($line)) {
                continue;
            }

            $municipalities[] = [
                'id' => $line[0],
                'name' => $line[1],
                'zipcode' => $line[4],
            ];
        }

        $cacheItem->set($municipalities);
        $cacheItem->expiresAfter(365 * 24 * 60 * 60);

        // return the result
        return $municipalities;
    }
}
