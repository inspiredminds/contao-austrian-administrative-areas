<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoAustrianAdministrativeAreas;

use League\Csv\Reader;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ContaoAustrianAdministrativeAreas
{
    private readonly string $districtCsvFallback;

    private readonly string $municipalitiesCsvFallback;

    public function __construct(
        private readonly CacheInterface $cache,
        private readonly HttpClientInterface $httpClient,
        private readonly LoggerInterface $contaoErrorLogger,
        private readonly string $districtCsvUrl,
        private readonly string $municipalitiesCsvUrl,
        string|null $districtCsvFallback = null,
        string|null $municipalitiesCsvFallback = null,
    ) {
        $this->districtCsvFallback = $districtCsvFallback ?? Path::join(__DIR__, '../fallback/polbezirke.csv');
        $this->municipalitiesCsvFallback = $municipalitiesCsvFallback ?? Path::join(__DIR__, '../fallback/gemliste_nam.csv');
    }

    public function getDistricts(): array
    {
        return $this->cache->get(
            'districts',
            function (): array {
                try {
                    $csv = Reader::fromString($this->httpClient->request(Request::METHOD_GET, $this->districtCsvUrl)->getContent());
                } catch (ExceptionInterface $e) {
                    $this->contaoErrorLogger->error(\sprintf('Request to %s failed (%s). Falling back to %s.', $this->districtCsvUrl, $e->getMessage(), $this->districtCsvFallback));
                    $csv = Reader::from($this->districtCsvFallback);
                }

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
                try {
                    $csv = Reader::fromString($this->httpClient->request(Request::METHOD_GET, $this->municipalitiesCsvUrl)->getContent());
                } catch (ExceptionInterface $e) {
                    $this->contaoErrorLogger->error(\sprintf('Request to %s failed (%s). Falling back to %s.', $this->municipalitiesCsvUrl, $e->getMessage(), $this->municipalitiesCsvFallback));
                    $csv = Reader::from($this->municipalitiesCsvFallback);
                }

                $csv->setHeaderOffset(2);
                $csv->setDelimiter(';');

                return iterator_to_array($csv->slice(2, $csv->count() - 3)->getRecords());
            },
        );
    }
}
