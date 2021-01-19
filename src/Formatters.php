<?php

declare(strict_types=1);

namespace Differ\Formatters;

function format(string $formatName, array $diffData): string
{
    switch ($formatName) {
        case 'stylish':
            return Stylish\format($diffData);
        case 'plain':
            return Plain\format($diffData);
        case 'json':
            return json_encode($diffData, JSON_THROW_ON_ERROR);
        default:
            throw new \Exception("Unknown format name: {$formatName}!");
    }
}
