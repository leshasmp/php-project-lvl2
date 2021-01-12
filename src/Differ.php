<?php

declare(strict_types=1);

namespace Differ\Differ;

use function Differ\Parsers\parse;
use function Differ\Formatters\formatting;
use function Funct\Collection\union;

function getContentFile(string $filePath): string
{
    return file_get_contents($filePath);
}

function getExtension(string $filename): string
{
    $array = explode(".", $filename);
    return end($array);
}

function genDiff(string $pathFile1, string $pathFile2, $formatName = 'stylish'): string
{
    $rawData1 = getContentFile($pathFile1);
    $rawData2 = getContentFile($pathFile2);

    $firstData = parse($rawData1, getExtension($pathFile1));
    $secondData = parse($rawData2, getExtension($pathFile1));

    $diffData = buildDiff($firstData, $secondData);

    return formatting($formatName, $diffData);
}

function buildDiff(object $firstData, object $secondData): array
{
    $firstKeys = array_keys(get_object_vars($firstData));
    $secondKeys = array_keys(get_object_vars($secondData));

    $keys = union($firstKeys, $secondKeys);
    sort($keys);

    return array_map(function ($key) use ($firstData, $secondData) {

        if (!property_exists($secondData, $key)) {
            return ['key' => $key, 'status' => 'deleted', 'value' => $firstData->$key];
        }

        if (!property_exists($firstData, $key)) {
            return ['key' => $key, 'status' => 'added', 'value' => $secondData->$key];
        }

        if (is_object($firstData->$key) && is_object($secondData->$key)) {
            return ['key' => $key, 'status' => 'nested', 'children' => buildDiff($firstData->$key, $secondData->$key)];
        }

        if ($secondData->$key !== $firstData->$key) {
            return ['key' => $key,'status' => 'changed','oldValue' => $firstData->$key,'newValue' => $secondData->$key];
        }

        return ['key' => $key, 'status' => 'unchanged', 'value' => $firstData->$key];
    }, $keys);
}
