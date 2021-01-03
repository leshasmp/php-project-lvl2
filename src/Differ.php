<?php

declare(strict_types=1);

namespace Differ\Differ;

use function Differ\Parsers\parseFile;
use function Differ\Formatters\formatting;
use function Funct\Collection\union;

function genDiff(string $pathFile1, string $pathFile2, $formatName = 'stylish'): string
{
    $firstData = get_object_vars(parseFile($pathFile1));
    $secondData = get_object_vars(parseFile($pathFile2));

    $diffData = buildDiff($firstData, $secondData);

    $diffStr = formatting($formatName, $diffData);

    return "$diffStr\n";
}

function buildDiff($firstData, $secondData): array
{
    $firstKeys = array_keys($firstData);
    $secondKeys = array_keys($secondData);

    $keys = union($firstKeys, $secondKeys);
    sort($keys);

    return array_map(function ($key) use ($firstData, $secondData) {

        if (!array_key_exists($key, $secondData)) {
            return ['key' => $key, 'status' => 'deleted', 'value' => $firstData[$key]];
        }

        if (!array_key_exists($key, $firstData)) {
            return ['key' => $key, 'status' => 'added', 'value' => $secondData[$key]];
        }

        if (is_object($firstData[$key]) && is_object($secondData[$key])) {
            $firstData = get_object_vars($firstData[$key]);
            $secondData = get_object_vars($secondData[$key]);
            return ['key' => $key, 'status' => 'nested', 'children' => buildDiff($firstData, $secondData)];
        }

        if ($secondData[$key] !== $firstData[$key]) {
            return ['key' => $key, 'status' => 'changed', 'oldValue' => $firstData[$key], 'newValue' => $secondData[$key]];
        }

        return ['key' => $key, 'status' => 'unchanged', 'value' => $firstData[$key]];

    }, $keys);
}


