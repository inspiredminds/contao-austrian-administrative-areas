<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoAustrianAdministrativeAreas;

use League\Csv\Reader;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Cache\CacheInterface;

class ContaoAustrianAdministrativeAreas
{
    public function __construct(
        private readonly CacheInterface $cache,
        private readonly string $districtCsvUrl,
        private readonly string $municipalitiesCsvUrl,
    ) {
    }

    public function getDistricts(): array
    {
        return $this->cache->get(
            'districts',
            function (): array {
                $csv = Reader::createFromString(HttpClient::create()->request(Request::METHOD_GET, $this->districtCsvUrl)->getContent());
                $csv->setHeaderOffset(2);
                $csv->setDelimiter(';');

                return iterator_to_array($csv->slice(2, $csv->count() - 3)->getRecords());
            },
        );
    }

    public function getMunicipalities(): array
    {
        return $this->cache->get(
            'municipalities',
            function (): array {
                $csv = Reader::createFromString(HttpClient::create()->request(Request::METHOD_GET, $this->municipalitiesCsvUrl)->getContent());
                $csv->setHeaderOffset(2);
                $csv->setDelimiter(';');

                return iterator_to_array($csv->slice(2, $csv->count() - 3)->getRecords());
            },
        );
    }
}
