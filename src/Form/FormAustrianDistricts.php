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
use Contao\StringUtil;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class FormAustrianDistricts extends FormSelectMenu
{
    protected const CACHE_NAME = 'contao.austriandistrictsform';

    protected $cache;

    public function __construct($arrAttributes = null)
    {
        parent::__construct($arrAttributes);

        $this->cache = new FilesystemAdapter();

        // Include empty value
        $this->arrOptions[] = [['value' => '', 'label' => '']];

        // Go through the districts
        foreach ($this->getDistricts() as $country => $districts) {
            $this->arrOptions[] = [
                'group' => true,
                'label' => $country,
            ];

            foreach ($districts as $district) {
                $this->arrOptions[] = [
                    'value' => StringUtil::generateAlias($country.'_'.$district),
                    'label' => $district,
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

    /**
     * Returns the available districts as an array.
     */
    protected function getDistricts(): array
    {
        $cacheItem = $this->cache->getItem(self::CACHE_NAME);

        if ($cacheItem->isHit()) {
            return $cacheItem->get();
        }

        $districts = [];

        // Load from server
        $data = file('https://www.statistik.at/verzeichnis/reglisten/polbezirke.csv', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        // process the data
        for ($i = 3; $i < \count($data) - 1; ++$i) {
            // get the line
            $line = explode(';', utf8_encode($data[$i]));

            // check the line
            if (empty($line)) {
                continue;
            }

            // get the country label
            $country = $line[1];

            // check the country label
            if (!$country) {
                continue;
            }

            // get the district label
            $district = $line[3];

            // check the district label
            if (!$district) {
                continue;
            }

            // check if we have country already
            if (!isset($districts[$country])) {
                $districts[$country] = [];
            }

            // add district
            $districts[$country][] = $district;
        }

        $cacheItem->set($districts);
        $cacheItem->expiresAfter(365 * 24 * 60 * 60);

        // return the result
        return $districts;
    }
}
