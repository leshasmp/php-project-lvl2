<?php

declare(strict_types=1);

namespace Differ\Differ;

use function Differ\Parsers\parseFile;
use function Funct\Collection\union;

function genDiff(string $pathFile1, string $pathFile2)
{
    $firstArray = parseFile($pathFile1);
    $secondArray = parseFile($pathFile2);

    $firstKeys = array_keys($firstArray);
    $secondKeys = array_keys($secondArray);

    $keys = union($firstKeys, $secondKeys);
    sort($keys);

    $resKeysStatus = array_map(function ($key) use ($firstArray, $secondArray) {
        if (!array_key_exists($key, $secondArray)) {
            return [['key' => $key, 'status' => 'delete', 'array' => $firstArray[$key]]];
        }
        if (array_key_exists($key, $secondArray)
            && !array_key_exists($key, $firstArray)) {
            return [['key' => $key, 'status' => 'add', 'array' => $secondArray[$key]]];
        }
        if (array_key_exists($key, $secondArray)
            && array_key_exists($key, $firstArray)
            && $secondArray[$key] !== $firstArray[$key]) {
            return [['key' => $key, 'status' => 'change', 'oldValue' => $firstArray[$key], 'newValue' => $secondArray[$key]]];
        }
        return [['key' => $key, 'status' => 'not-change', 'array' => $firstArray[$key]]];
    }, $keys);

    $resKeysStatus = array_merge(...$resKeysStatus);

    $resItems = array_map(function ($item) {
        switch ($item['status']) {
            case 'delete':
                return "\n  - {$item['key']}: {$item['array']}";
            case 'add':
                return "\n  + {$item['key']}: {$item['array']}";
            case 'change':
                return "\n  - {$item['key']}: {$item['oldValue']}\n  + {$item['key']}: {$item['newValue']}";
            case 'not-change':
                return "\n    {$item['key']}: {$item['array']}";
        }
    }, $resKeysStatus);

    $resItems = implode($resItems);

    return "{ $resItems \n}\n";;
}
