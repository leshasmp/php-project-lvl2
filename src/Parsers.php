<?php

declare(strict_types=1);

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parseFile(string $filePath): object
{
    return Yaml::parse($filePath, Yaml::PARSE_OBJECT_FOR_MAP);
}
