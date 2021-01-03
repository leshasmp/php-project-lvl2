<?php

declare(strict_types=1);

namespace Differ\Tests\DifferTest;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    public function testDifferString(): void
    {

        $pathFile1 = 'tests/fixtures/filepath1.json';
        $pathFile2 = 'tests/fixtures/filepath2.json';

        $diff = trim(genDiff($pathFile1, $pathFile2));

        $actual = trim(file_get_contents('tests/fixtures/diff-actual.txt'));

        $this->assertEquals(
            $actual,
            $diff
        );

        $pathFile1 = 'tests/fixtures/filepath1.json';
        $pathFile2 = 'tests/fixtures/filepath2.yml';

        $diff = trim(genDiff($pathFile1, $pathFile2));

        $this->assertEquals(
            $actual,
            $diff
        );

        $pathFile1 = 'tests/fixtures/filepath1.yml';
        $pathFile2 = 'tests/fixtures/filepath2.yml';

        $diff = trim(genDiff($pathFile1, $pathFile2));

        $this->assertEquals(
            $actual,
            $diff
        );

        $pathFile1 = 'tests/fixtures/file1.json';
        $pathFile2 = 'tests/fixtures/file2.json';

        $diff = trim(genDiff($pathFile1, $pathFile2));

        $actual = trim(file_get_contents('tests/fixtures/diff-actual-1.txt'));

        $this->assertEquals(
            $actual,
            $diff
        );

        $diff = trim(genDiff($pathFile1, $pathFile2, 'plain'));

        $actual = trim(file_get_contents('tests/fixtures/diff-actual-plain.txt'));

        $this->assertEquals(
            $actual,
            $diff
        );

        $diff = trim(genDiff($pathFile1, $pathFile2, 'json'));

        $actual = trim(file_get_contents('tests/fixtures/diff-actual-json.txt'));

        $this->assertEquals(
            $actual,
            $diff
        );
    }
}
