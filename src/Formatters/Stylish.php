<?php

declare(strict_types=1);

namespace Differ\Formatters\Stylish;

function format(array $tree): string
{
    return formatDiff($tree);
}

function formatDiff(array $tree, int $depth = 1): string
{
    $iter = array_map(function ($item) use ($depth): string {
        $indent = str_repeat(' ', 4 * $depth - 2);
        switch ($item['type']) {
            case 'deleted':
                $value = stringify($item['value'], $depth + 1);
                return "{$indent}- {$item['key']}: {$value}";
            case 'added':
                $value = stringify($item['value'], $depth + 1);
                return "{$indent}+ {$item['key']}: {$value}";
            case 'nested':
                $value = formatDiff($item['children'], $depth + 1);
                return "{$indent}  {$item['key']}: {$value}";
            case 'changed':
                $value1 = stringify($item['oldValue'], $depth + 1);
                $value2 = stringify($item['newValue'], $depth + 1);
                return "{$indent}- {$item['key']}: {$value1}\n{$indent}+ {$item['key']}: {$value2}";
            case 'unchanged':
                $value = stringify($item['value'], $depth + 1);
                return "{$indent}  {$item['key']}: {$value}";
            default:
                throw new \Exception("Unknown type item: {$item['type']}!");
        }
    }, $tree);

    $result = implode("\n", $iter);

    $indent = str_repeat(' ', 4 * $depth - 4);

    return "{\n{$result}\n$indent}";
}

function stringify($value, int $depth = 1): string
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

    $resItems = array_map(function ($key) use ($array, $depth): string {
        $indent = str_repeat(' ', 4 * $depth - 2);
        $value = is_object($array[$key]) ? stringify($array[$key], $depth + 1) : $array[$key];
        return "{$indent}  {$key}: {$value}";
    }, $keys);

    $indent = str_repeat(' ', 4 * $depth - 4);

    $result = implode("\n", $resItems);

    return "{\n{$result}\n$indent}";
}
