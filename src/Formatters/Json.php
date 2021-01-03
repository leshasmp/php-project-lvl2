<?php

declare(strict_types=1);

namespace Differ\Formatters\Json;

function formatDiff(array $resKeysStatus): array
{
    return array_map(function ($item) {
        switch ($item['status']) {
            case 'deleted':
                $value = toArray($item['value']);
                return [" - {$item['key']}" => $value];
            case 'added':
                $value = toArray($item['value']);
                return [" + {$item['key']}" => $value];
            case 'nested':
                $value = formatDiff($item['children']);
                return [" + {$item['key']}" => $value];
            case 'changed':
                $value1 = toArray($item['oldValue']);
                $value2 = toArray($item['newValue']);
                return [" - {$item['key']}" => $value1, " + {$item['key']}" => $value2];
            case 'unchanged':
                $value = toArray($item['value']);
                return [$item['key'] => $value];
            default:
                throw new \Exception("Unknown status item: {$item['status']}!");
        }
    }, $resKeysStatus);
}

function toArray($value)
{
    if (!is_object($value)) {
        return $value;
    }

    $array = get_object_vars($value);
    $keys = array_keys($array);

    return array_map(function ($key) use ($array) {
        $value = $array[$key];
        if (is_object($array[$key])) {
            $value = toArray($array[$key]);
        }
        return [$key => $value];
    }, $keys);
}
