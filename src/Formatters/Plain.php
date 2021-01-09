<?php

declare(strict_types=1);

namespace Differ\Formatters\Plain;

function formatValue($value): string
{
    if (is_object($value)) {
        return '[complex value]';
    }
    return match ($value) {
        null => 'null',
        true => 'true',
        false => 'false',
    default => "'$value'",
    };
}

function formatDiff(array $resKeysStatus, string $parentName = ''): string
{
    $resItems = array_map(function ($item) use ($parentName) {
        switch ($item['status']) {
            case 'deleted':
                if (strlen($parentName)) {
                    $parentName .= ".{$item['key']}";
                } else {
                    $parentName = "{$item['key']}";
                }
                return "\nProperty '{$parentName}' was removed";
            case 'added':
                $value = formatValue($item['value']);
                if (strlen($parentName)) {
                    $parentName .= ".{$item['key']}";
                } else {
                    $parentName = "{$item['key']}";
                }
                return "\nProperty '{$parentName}' was added with value: $value";
            case 'nested':
                if (strlen($parentName)) {
                    $parentName .= ".{$item['key']}";
                } else {
                    $parentName = "{$item['key']}";
                }
                return formatDiff($item['children'], $parentName);
            case 'changed':
                if (strlen($parentName)) {
                    $parentName .= ".{$item['key']}";
                } else {
                    $parentName = "{$item['key']}";
                }
                $oldValue = formatValue($item['oldValue']);
                $newValue = formatValue($item['newValue']);
                return "\nProperty '{$parentName}' was updated. From $oldValue to $newValue";
            case 'unchanged':
                break;
            default:
                throw new \Exception("Unknown status item: {$item['status']}!");
        }
    }, $resKeysStatus);

    $resItems = implode($resItems);

    return "{$resItems}";
}
