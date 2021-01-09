<?php

declare(strict_types=1);

namespace Differ\Differ;

use function Differ\Parsers\parseFile;
use function Differ\Formatters\formatting;
use function Funct\Collection\union;

function getContentFile(string $filePath): string
{
    return file_get_contents($filePath);
}

function genDiff(string $pathFile1, string $pathFile2, $formatName = 'stylish'): string
{
    $rawData1 = getContentFile($pathFile1);
    $rawData2 = getContentFile($pathFile2);

    $firstData = parseFile($rawData1);
    $secondData = parseFile($rawData2);

    $diffData = buildDiff($firstData, $secondData);

    return trim(formatting($formatName, $diffData));
}

function buildDiff($firstData, $secondData): array
{
    $firstData = get_object_vars($firstData);
    $secondData = get_object_vars($secondData);

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
            return ['key' => $key, 'status' => 'nested', 'children' => buildDiff($firstData[$key], $secondData[$key])];
        }

        if ($secondData[$key] !== $firstData[$key]) {
            return ['key' => $key,'status' => 'changed','oldValue' => $firstData[$key],'newValue' => $secondData[$key]];
        }

        return ['key' => $key, 'status' => 'unchanged', 'value' => $firstData[$key]];
    }, $keys);
}
