<?php

declare(strict_types=1);

namespace Differ\Formatters\Plain;

function stringify($value): string
{
    if (is_object($value)) {
        return '[complex value]';
    }

    if (is_null($value)) {
        return 'null';
    }

    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if (is_string($value)) {
        return "'$value'";
    }

    return "$value";
}

function format(array $tree): string
{
    return formatDiff($tree);
}

function formatDiff(array $tree, $parentName = ''): string
{
    $filtered = array_filter($tree, fn ($element) => $element['type'] !== 'unchanged');

    $iter = array_map(function ($item) use ($parentName): string {
        $propertyName = strlen($parentName) == 0 ? "{$item['key']}" : "$parentName.{$item['key']}";

        switch ($item['type']) {
            case 'deleted':
                return "Property '{$propertyName}' was removed";
            case 'added':
                $value = stringify($item['value']);
                return "Property '{$propertyName}' was added with value: $value";
            case 'nested':
                return formatDiff($item['children'], $propertyName);
            case 'changed':
                $oldValue = stringify($item['oldValue']);
                $newValue = stringify($item['newValue']);
                return "Property '{$propertyName}' was updated. From $oldValue to $newValue";
            default:
                throw new \Exception("Unknown type item: {$item['type']}!");
        }
    }, $filtered);

    return implode("\n", $iter);
}
