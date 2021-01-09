<?php

declare(strict_types=1);

namespace Differ\Formatters\Stylish;

function formatDiff(array $resKeysStatus, int $depth = 1): string
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
            default:
                throw new \Exception("Unknown status item: {$item['status']}!");
        }
    }, $resKeysStatus);

    $resItems = implode($resItems);
    $tab = str_repeat(' ', 4 * $depth - 4);

    return "{{$resItems}\n$tab}";
}

function toString($value, int $depth = 1)
{
    if (!is_object($value)) {
        return formatValue($value);
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

function formatValue($value): string
{
    if (is_object($value)) {
        return '[complex value]';
    }
    return match ($value) {
        null => 'null',
        true => 'true',
        false => 'false',
    default => "$value",
    };
}
