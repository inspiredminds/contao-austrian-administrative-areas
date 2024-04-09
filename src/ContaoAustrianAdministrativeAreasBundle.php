<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoAustrianAdministrativeAreas;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ContaoAustrianAdministrativeAreasBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
