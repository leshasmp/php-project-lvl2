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

    return "'$value'";
}

function formatDiff(array $tree): string
{
    return formatDiffData($tree);
}

function formatDiffData(array $tree, string $parentName = ''): string
{
    $resItems = array_map(function ($item) use ($parentName) {

        $propertyName = empty($parentName) ? "{$item['key']}" : "$parentName.{$item['key']}";

        switch ($item['type']) {
            case 'deleted':
                return "Property '{$propertyName}' was removed";
            case 'added':
                $value = stringify($item['value']);
                return "Property '{$propertyName}' was added with value: $value";
            case 'nested':
                return formatDiffData($item['children'], $propertyName);
            case 'changed':
                $oldValue = stringify($item['oldValue']);
                $newValue = stringify($item['newValue']);
                return "Property '{$propertyName}' was updated. From $oldValue to $newValue";
            case 'unchanged':
                return '';
            default:
                throw new \Exception("Unknown type item: {$item['type']}!");
        }
    }, $tree);

    $filtered = array_filter($resItems, fn ($element) => !empty($element));

    return implode("\n", $filtered);
}
