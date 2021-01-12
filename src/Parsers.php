<?php

declare(strict_types=1);

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse(string $data, string $extension): object
{
    if ($extension == 'json') {
        return json_decode($data, false);
    }

    if ($extension == 'yml') {
        return Yaml::parse($data, Yaml::PARSE_OBJECT_FOR_MAP);
    }
}
