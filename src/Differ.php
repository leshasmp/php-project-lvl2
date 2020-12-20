<?php

declare(strict_types=1);

namespace Differ\Differ;

function genDiff(string $file1, string $file2): string
{
    $array1 = json_decode(file_get_contents($file1), true);
    $array2 = json_decode(file_get_contents($file2), true);
    ksort($array1);
    ksort($array2);

    $result = [];
    foreach ($array1 as $key1 => $value1) {
        if (!array_key_exists($key1, $array2)) {
            $result .= "  - {$key1}: $value1 \n";
        }
        foreach ($array2 as $key2 => $value2) {
            if ($key1 == $key2 && $value1 !== $value2) {
                $result .= "  - {$key1}: $value1 \n";
                $result .= "  + {$key2}: $value2 \n";
            }
            if ($key1 == $key2 && $value1 == $value2) {
                $result .= "    {$key1}: $value1 \n";
            }
        }
    }
    foreach ($array2 as $key2 => $value2) {
        if (!array_key_exists($key2, $array1)) {
            $result .= "  + {$key2}: $value2 \n";
        }
    }

    return "{ \n{$result}}\n";
}
