<?php

declare(strict_types=1);

namespace Differ\Differ;

use function Differ\Parsers\parse;
use function Differ\Formatters\format;
use function Funct\Collection\union;

function getContentFile(string $filePath): string
{
    return file_get_contents($filePath);
}

function getExtension(string $filePath): string
{
    return pathinfo($filePath)['extension'];
}

function genDiff(string $filePath1, string $filePath2, $formatName = 'stylish'): string
{
    $rawData1 = getContentFile($filePath1);
    $rawData2 = getContentFile($filePath2);

    $firstData = parse($rawData1, getExtension($filePath1));
    $secondData = parse($rawData2, getExtension($filePath1));

    $diffData = buildDiff($firstData, $secondData);

    return format($formatName, $diffData);
}

function buildDiff(object $firstData, object $secondData): array
{
    $firstKeys = array_keys(get_object_vars($firstData));
    $secondKeys = array_keys(get_object_vars($secondData));

    $keys = union($firstKeys, $secondKeys);
    sort($keys);

    return array_map(function ($key) use ($firstData, $secondData) {

        if (!property_exists($secondData, $key)) {
            return ['key' => $key, 'type' => 'deleted', 'value' => $firstData->$key];
        }

        if (!property_exists($firstData, $key)) {
            return ['key' => $key, 'type' => 'added', 'value' => $secondData->$key];
        }

        if (is_object($firstData->$key) && is_object($secondData->$key)) {
            return ['key' => $key, 'type' => 'nested', 'children' => buildDiff($firstData->$key, $secondData->$key)];
        }

        if ($secondData->$key !== $firstData->$key) {
            return ['key' => $key,'type' => 'changed','oldValue' => $firstData->$key,'newValue' => $secondData->$key];
        }

        return ['key' => $key, 'type' => 'unchanged', 'value' => $firstData->$key];
    }, $keys);
}
