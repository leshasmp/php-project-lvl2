<?php

declare(strict_types=1);

namespace Differ\Formatters;

function format(string $formatName, array $diffData): string
{
    switch ($formatName) {
        case 'stylish':
            return (string) Stylish\format($diffData);
        case 'plain':
            return (string) Plain\format($diffData);
        case 'json':
            return (string) json_encode($diffData);
        default:
            throw new \Exception("Unknown format name: {$formatName}!");
    }
}
