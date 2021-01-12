<?php

declare(strict_types=1);

namespace Differ\Formatters;

function formatting($formatName, $diffData): string
{
    return match ($formatName) {
        'stylish' => Stylish\formatDiff($diffData),
        'plain' => Plain\formatDiff($diffData),
        'json' => json_encode(Json\formatDiff($diffData)),
    default => throw new \Exception("Unknown format name: {$formatName}!"),
    };
}
