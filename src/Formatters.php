<?php

declare(strict_types=1);

namespace Differ\Formatters;

function formatting($formatName, $diffData): string
{
    if ($formatName == 'stylish') {
        return \Differ\Formatters\Stylish\formatDiff($diffData);
    }

    if ($formatName == 'plain') {
        return \Differ\Formatters\Plain\formatDiff($diffData);
    }
}
