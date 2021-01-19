### Hexlet tests and linter status:
[![Actions Status](https://github.com/leshasmp/php-project-lvl2/workflows/hexlet-check/badge.svg)](https://github.com/leshasmp/php-project-lvl2/actions)   [![Maintainability](https://api.codeclimate.com/v1/badges/4c0170aa0674460b9e3c/maintainability)](https://codeclimate.com/github/leshasmp/php-project-lvl2/maintainability)   [![](https://github.com/leshasmp/php-project-lvl2/workflows/lint/badge.svg)](https://github.com/leshasmp/php-project-lvl2/actions?query=workflow%3Alint)   [![Test Coverage](https://api.codeclimate.com/v1/badges/4c0170aa0674460b9e3c/test_coverage)](https://codeclimate.com/github/leshasmp/php-project-lvl2/test_coverage)



# Difference calculator

Difference Calculator - A program that determines the difference between two data structures. This is a popular problem, for which there are many online services, for example: http://www.jsondiff.com/. A similar mechanism is used when outputting tests or automatically tracking changes in configuration files.

### Installation

#### Global

composer global require php-project-lvl2/php-project-lvl2

#### In project

composer require php-project-lvl2/php-project-lvl2

## Usage

### In CLI:

gendiff [--format <fmt>] <path_to_file1> <path_to_file2>

### In project:

use function Differ\Differ\genDiff;

genDiff($filepath1, $filepath2, $formatName = 'stylish');

### Comparing flat files

[![asciicast](https://asciinema.org/a/385395.svg)](https://asciinema.org/a/385395)

### Comparison of flat file format yml and json

[![asciicast](https://asciinema.org/a/385396.svg)](https://asciinema.org/a/385396)

### Compare attachments

[![asciicast](https://asciinema.org/a/385398.svg)](https://asciinema.org/a/385398)

### Comparing file attachments with format output 'plain'

[![asciicast](https://asciinema.org/a/385400.svg)](https://asciinema.org/a/385400)

### Comparing file attachments with format output 'json'

[![asciicast](https://asciinema.org/a/385402.svg)](https://asciinema.org/a/385402)
