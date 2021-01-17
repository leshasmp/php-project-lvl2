<?php

declare(strict_types=1);

namespace Differ\Tests\DifferTest;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    public function getFixturesPath(string $fixtureName): string
    {
        $path = [ __DIR__, 'fixtures', $fixtureName];
        return implode('/', $path);
    }

    /**
     * @dataProvider additionProvider
     */
    public function testDiff($expected, $pathFile1, $pathFile2, $format)
    {
        $this->assertStringEqualsFile($expected, genDiff($pathFile1, $pathFile2, $format));
    }

    public function additionProvider(): array
    {
        $fixtureJsonPath1 = $this->getFixturesPath('file1.json');
        $fixtureJsonPath2 = $this->getFixturesPath('file2.json');
        $fixtureYmlPath1 = $this->getFixturesPath('file1.yml');
        $fixtureYmlPath2 = $this->getFixturesPath('file2.yml');

        return [
            [$this->getFixturesPath('diff-actual-stylish.txt'), $fixtureJsonPath1, $fixtureJsonPath2, 'stylish'],
            [$this->getFixturesPath('diff-actual-plain.txt'), $fixtureJsonPath1, $fixtureJsonPath2, 'plain'],
            [$this->getFixturesPath('diff-actual-stylish.txt'), $fixtureYmlPath1, $fixtureYmlPath2, 'stylish'],
            [$this->getFixturesPath('diff-actual-plain.txt'), $fixtureYmlPath1, $fixtureYmlPath2, 'plain'],
            [$this->getFixturesPath('diff-actual-json.txt'), $fixtureYmlPath1, $fixtureYmlPath2, 'json'],
        ];
    }
}
