<?php

declare(strict_types=1);

namespace Differ\Formatters;

function format($formatName, $diffData): string
{
    return match ($formatName) {
        'stylish' => Stylish\formatDiff($diffData),
        'plain' => Plain\formatDiff($diffData),
        'json' => json_encode($diffData),
    default => throw new \Exception("Unknown format name: {$formatName}!"),
    };
}
