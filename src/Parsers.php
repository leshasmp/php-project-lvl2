<?php

declare(strict_types=1);

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parseFile(string $filePath): array
{
    try {
        $value = Yaml::parseFile($filePath, Yaml::PARSE_OBJECT_FOR_MAP);
        return (array) $value;
    } catch (ParseException $exception) {
        printf('Unable to parse the YAML string: %s', $exception->getMessage());
    }

}
