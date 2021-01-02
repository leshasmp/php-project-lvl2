<?php

declare(strict_types=1);

namespace Differ\Differ;

use function Differ\Parsers\parseFile;
use function Funct\Collection\union;

function genDiff(string $pathFile1, string $pathFile2)
{
    $firstData = get_object_vars(parseFile($pathFile1));
    $secondData = get_object_vars(parseFile($pathFile2));

    $diffData = buildDiff($firstData, $secondData);
    $diffStr = formatDiff($diffData);

    return "$diffStr\n";
}

function buildDiff($firstData, $secondData)
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

function formatDiff($resKeysStatus, $depth = 1): string
{

    $resItems = array_map(function ($item) use ($depth) {
        $tab = str_repeat(' ', 4 * $depth - 2);
        switch ($item['status']) {
            case 'deleted':
                $value = toString($item['value'], $depth + 1);
                return "\n{$tab}- {$item['key']}: {$value}";
            case 'added':
                $value = toString($item['value'], $depth + 1);
                return "\n{$tab}+ {$item['key']}: {$value}";
            case 'nested':
                $value = formatDiff($item['children'], $depth + 1);
                return "\n{$tab}  {$item['key']}: {$value}";
            case 'changed':
                $value1 = toString($item['oldValue'], $depth + 1);
                $value2 = toString($item['newValue'], $depth + 1);
                return "\n{$tab}- {$item['key']}: {$value1}\n{$tab}+ {$item['key']}: {$value2}";
            case 'unchanged':
                $value = toString($item['value'], $depth + 1);
                return "\n{$tab}  {$item['key']}: {$value}";
        }
    }, $resKeysStatus);

    $resItems = implode($resItems);
    $tab = str_repeat(' ', 4 * $depth - 4);

    return "{{$resItems}\n$tab}";
}

function toString($value, $depth = 1)
{
    if (!is_object($value)) {
        return $value;
    }

    $array = get_object_vars($value);
    $keys = array_keys($array);

    $result = array_map(function ($key) use ($array, $depth) {
        $tab = str_repeat(' ', 4 * $depth - 2);
        $value = $array[$key];
        if (is_object($array[$key])) {
            $value = toString($array[$key], $depth + 1);
        }
        return "\n{$tab}  {$key}: {$value}";
    }, $keys);

    $tab = str_repeat(' ', 4 * $depth - 4);

    $resItems = implode($result);

    return "{{$resItems}\n$tab}";
}
