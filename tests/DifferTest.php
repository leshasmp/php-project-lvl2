<?php

declare(strict_types=1);

namespace Differ\Tests\DifferTest;

use PHPUnit\Framework\TestCase;

use function Differ\Parsers\parseFile;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    public function testDifferString(): void
    {
        $firstFile = parseFile('tests/fixtures/filepath1.json');
        $secondFile = parseFile('tests/fixtures/filepath2.json');

        ksort($firstFile);
        ksort($secondFile);

        $diff = genDiff($firstFile, $firstFile);

        $actual = file_get_contents('tests/fixtures/diff-actual.txt');

        $this->assertEquals(
            $actual,
            $diff
        );

        $firstFile = parseFile('tests/fixtures/filepath1.json');
        $secondFile = parseFile('tests/fixtures/filepath2.yml');

        ksort($firstFile);
        ksort($secondFile);

        $diff = genDiff($firstFile, $firstFile);

        $this->assertEquals(
            $actual,
            $diff
        );

        $firstFile = parseFile('tests/fixtures/filepath1.yml');
        $secondFile = parseFile('tests/fixtures/filepath2.yml');

        ksort($firstFile);
        ksort($secondFile);

        $diff = genDiff($firstFile, $firstFile);

        $this->assertEquals(
            $actual,
            $diff
        );
    }
}
