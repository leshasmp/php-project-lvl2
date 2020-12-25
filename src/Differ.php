<?php

declare(strict_types=1);

namespace Differ\Differ;

function buildDiff(array $firstFile, array $secondFile): array
{
    ksort($firstFile);
    ksort($secondFile);

    $resArray = [];
    foreach ($secondFile as $secondFileKey => $secondFileValue) {
        if (!array_key_exists($secondFileKey, $firstFile)) {
            $resArray["+ {$secondFileKey}"] = $secondFileValue;
        }
        foreach ($firstFile as $firstFileKey => $firstFileValue) {
            if (!array_key_exists($firstFileKey, $secondFile)) {
                $resArray["- {$firstFileKey}"] = $firstFileValue;
            }
            if ($firstFileKey == $secondFileKey && $secondFileValue !== $firstFileValue) {
                $resArray["- {$firstFileKey}"] = $firstFileValue;
                $resArray["+ {$secondFileKey}"] = $secondFileValue;
            }
            if (array_key_exists($firstFileKey, $secondFile) && $secondFileValue === $firstFileValue) {
                $resArray["  {$firstFileKey}"] = $firstFileValue;
            }
        }
    }

    return $resArray;
}

function genDiff(array $firstFile, array $secondFile): string
{

    $resArray = buildDiff($firstFile, $secondFile);

    $resStr = (string) json_encode($resArray, JSON_PRETTY_PRINT);
    $resStr = str_replace('"', '', $resStr);

    return "{$resStr} \n";
}
