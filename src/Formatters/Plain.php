<?php

declare(strict_types=1);

namespace Differ\Formatters\Plain;

function formatDiff($resKeysStatus, $parentName = ''): string
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
                $value = is_object($item['value']) ? '[complex value]' : $item['value'];
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
                $oldValue = is_object($item['oldValue']) ? '[complex value]' : $item['oldValue'];
                $newValue = is_object($item['newValue']) ? '[complex value]' : $item['newValue'];
                return "\nProperty '{$parentName}' was updated. From '{$oldValue}' to '{$newValue}'";

        }
    }, $resKeysStatus);

    $resItems = implode($resItems);

    return "{$resItems}";
}
