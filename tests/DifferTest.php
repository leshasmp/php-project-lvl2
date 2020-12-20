<?php

declare(strict_types=1);

namespace Php\Project\Lvl2\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    public function testDifferString(): void
    {
        $diff = genDiff('tests/fixtures/filepath1.json', 'tests/fixtures/filepath2.json');
        $actual = file_get_contents('tests/fixtures/diff.txt');

        $this->assertEquals(
            $actual,
            $diff
        );
    }
}
