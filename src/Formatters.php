<?php

declare(strict_types=1);

namespace Differ\Formatters;

function format(string $formatName, array $diffData)
{
    switch ($formatName) {
        case 'stylish':
            return Stylish\format($diffData);
        case 'plain':
            return Plain\format($diffData);
        case 'json':
            return json_encode($diffData);
        default:
            throw new \Exception("Unknown format name: {$formatName}!");
    }
}
