<?php

declare(strict_types=1);

namespace Differ\Formatters;

function formatting($formatName, $diffData): string
{
    return match ($formatName) {
        'stylish' => \Differ\Formatters\Stylish\formatDiff($diffData),
        'plain' => \Differ\Formatters\Plain\formatDiff($diffData),
        'json' => json_encode(\Differ\Formatters\Json\formatDiff($diffData), JSON_PRETTY_PRINT),
    default => throw new \Exception("Unknown format name: {$formatName}!"),
    };
}
