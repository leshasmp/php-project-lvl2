<?php

declare(strict_types=1);

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parseFile(string $filePath): object
{
    try {
        $value = Yaml::parseFile($filePath, Yaml::PARSE_OBJECT_FOR_MAP);
        return $value;
    } catch (ParseException $exception) {
        printf('Unable to parse the YAML string: %s', $exception->getMessage());
    }

}
