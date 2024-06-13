#!/usr/bin/env php
<?php

declare(strict_types=1);

/**
 * This file is part of Esi\Bench.
 *
 * (c) Eric Sizemore <admin@secondversion.com>
 * (c) Jeremy Perret <jeremy@devster.org>
 *
 * This source file is subject to the MIT license. For the full copyright,
 * license information, and credits/acknowledgements, please view the LICENSE
 * and README files that were distributed with this source code.
 */

if ($argc !== 2) {
    fprintf(STDERR, '%s <tag>%s', $argv[0], PHP_EOL);

    exit(1);
}

$version = $argv[1];
$file    = __DIR__ . '/../CHANGELOG.md';

if (!is_file($file) || !is_readable($file)) {
    fprintf(STDERR, '%s cannot be read%s', $file, PHP_EOL);

    exit(1);
}

$buffer = '';
$append = false;

foreach (new SplFileObject($file) as $line) {
    if (!is_string($line)) {
        continue;
    }

    if (str_starts_with($line, '## [' . $version . ']')) {
        $append = true;

        continue;
    }

    if ($append && (str_starts_with($line, '## ') || str_starts_with($line, '['))) {
        break;
    }

    if ($append) {
        $buffer .= $line;
    }
}

$buffer = trim($buffer);

if ($buffer === '') {
    fprintf(STDERR, 'Unable to extract release notes%s', PHP_EOL);

    exit(1);
}

printf(
    '%s%s%s',
    PHP_EOL,
    $buffer,
    PHP_EOL
);
