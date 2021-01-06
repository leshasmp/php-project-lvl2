<?php

declare(strict_types=1);

namespace Differ\Tests\DifferTest;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    public function getFixturesPath(): string
    {
        return __DIR__ . '/fixtures';
    }

    /**
     * @dataProvider additionProvider
     */
    public function testPowerOfString($expected, $argument1, $argument2, $argument3)
    {
        $this->assertEquals($expected, genDiff($argument1, $argument2, $argument3));
    }

    public function additionProvider(): array
    {
        $fixturesPath = $this->getFixturesPath();
        $pathFile1 = "{$fixturesPath}/filepath1.json";
        $pathFile2 = "{$fixturesPath}/filepath2.json";
        $pathFile3 = "{$fixturesPath}/filepath1.yml";
        $pathFile4 = "{$fixturesPath}/filepath2.yml";
        $pathFile5 = "{$fixturesPath}/file1.json";
        $pathFile6 = "{$fixturesPath}/file2.json";

        return [
            [file_get_contents("{$fixturesPath}/diff-actual.txt"), $pathFile1, $pathFile2, 'stylish'],
            [file_get_contents("{$fixturesPath}/diff-actual.txt"), $pathFile1, $pathFile4, 'stylish'],
            [file_get_contents("{$fixturesPath}/diff-actual.txt"), $pathFile3, $pathFile4, 'stylish'],
            [file_get_contents("{$fixturesPath}/diff-actual-1.txt"), $pathFile5, $pathFile6, 'stylish'],
            [file_get_contents("{$fixturesPath}/diff-actual-plain.txt"), $pathFile5, $pathFile6, 'plain'],
            [file_get_contents("{$fixturesPath}/diff-actual-json.txt"), $pathFile5, $pathFile6, 'json'],
        ];
    }
}
