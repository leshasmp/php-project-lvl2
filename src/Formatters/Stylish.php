<?php

declare(strict_types=1);

namespace Differ\Formatters\Stylish;

function formatDiff(array $resKeysStatus, int $depth = 1): string
{
    $resItems = array_map(function ($item) use ($depth) {
        $tab = str_repeat(' ', 4 * $depth - 2);
        switch ($item['status']) {
            case 'deleted':
                $value = stringify($item['value'], $depth + 1);
                return "{$tab}- {$item['key']}: {$value}";
            case 'added':
                $value = stringify($item['value'], $depth + 1);
                return "{$tab}+ {$item['key']}: {$value}";
            case 'nested':
                $value = formatDiff($item['children'], $depth + 1);
                return "{$tab}  {$item['key']}: {$value}";
            case 'changed':
                $value1 = stringify($item['oldValue'], $depth + 1);
                $value2 = stringify($item['newValue'], $depth + 1);
                return "{$tab}- {$item['key']}: {$value1}\n{$tab}+ {$item['key']}: {$value2}";
            case 'unchanged':
                $value = stringify($item['value'], $depth + 1);
                return "{$tab}  {$item['key']}: {$value}";
            default:
                throw new \Exception("Unknown status item: {$item['status']}!");
        }
    }, $resKeysStatus);

    $resItems = implode("\n", $resItems);

    $tab = str_repeat(' ', 4 * $depth - 4);

    return "{\n{$resItems}\n$tab}";
}

function stringify($value, int $depth = 1)
{
    if (!is_object($value)) {
        if (is_null($value)) {
            return 'null';
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        return (string) $value;
    }

    $array = get_object_vars($value);
    $keys = array_keys($array);

    $resItems = array_map(function ($key) use ($array, $depth) {
        $tab = str_repeat(' ', 4 * $depth - 2);
        $value = $array[$key];
        if (is_object($array[$key])) {
            $value = stringify($array[$key], $depth + 1);
        }
        return "{$tab}  {$key}: {$value}";
    }, $keys);

    $tab = str_repeat(' ', 4 * $depth - 4);

    $resItems = implode("\n", $resItems);

    return "{\n{$resItems}\n$tab}";
}
