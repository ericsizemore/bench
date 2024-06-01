Bench
=====

[![Build Status](https://scrutinizer-ci.com/g/ericsizemore/bench/badges/build.png?b=master)](https://scrutinizer-ci.com/g/ericsizemore/bench/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/ericsizemore/bench/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/ericsizemore/bench/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ericsizemore/bench/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ericsizemore/bench/?branch=master)
[![Continuous Integration](https://github.com/ericsizemore/bench/actions/workflows/continuous-integration.yml/badge.svg?branch=master)](https://github.com/ericsizemore/bench/actions/workflows/continuous-integration.yml)
[![Type Coverage](https://shepherd.dev/github/ericsizemore/bench/coverage.svg)](https://shepherd.dev/github/ericsizemore/bench)
[![Psalm Level](https://shepherd.dev/github/ericsizemore/bench/level.svg)](https://shepherd.dev/github/ericsizemore/bench)
[![Latest Stable Version](https://img.shields.io/packagist/v/esi/bench.svg)](https://packagist.org/packages/esi/bench)
[![Downloads per Month](https://img.shields.io/packagist/dm/esi/bench.svg)](https://packagist.org/packages/esi/bench)
[![License](https://img.shields.io/packagist/l/esi/bench.svg)](https://packagist.org/packages/esi/bench)

`Bench` is a PHP micro library for benchmark.

## Acknowledgements / Credits

`Bench` is a fork of [devster/ubench](https://github.com/devster/ubench), and uses the same license as the original repository by @devster (MIT).
Thanks to them and all the contributors!

## Installation

### Composer ###

Run the following command to install the package

```shell
composer require esi/bench:~3.0.0
```

## Usage
```php
require_once 'vendor/autoload.php';

$bench = new Bench;

$bench->start();

// Execute some code

$bench->end();

// Get elapsed time and memory.
echo $bench->getTime(); // 156ms or 1.123s
echo $bench->getTime(true); // elapsed microtime in float
echo $bench->getTime(false, '%d%s'); // 156ms or 1s

echo $bench->getMemoryPeak(); // 152B or 90.00Kb or 15.23Mb
echo $bench->getMemoryPeak(true); // memory peak in bytes
echo $bench->getMemoryPeak(false, '%.3f%s'); // 152B or 90.152Kb or 15.234Mb

// Returns the memory usage at the end mark.
echo $bench->getMemoryUsage(); // 152B or 90.00Kb or 15.23Mb

// Runs `Bench::start()` and `Bench::end()` around a callable.
// Accepts a callable as the first parameter.  Any additional parameters will be passed to the callable.
$result = $bench->run(function (int $x): int {
    return $x;
}, 1);
echo $bench->getTime();
```

## About

### Requirements

- Bench works with PHP 8.2.0 or above.

### Submitting bugs and feature requests

Bugs and feature requests are tracked on [GitHub](https://github.com/ericsizemore/bench/issues)

Issues are the quickest way to report a bug. If you find a bug or documentation error, please check the following first:

* That there is not an Issue already open concerning the bug
* That the issue has not already been addressed (within closed Issues, for example)

### Contributing

See [CONTRIBUTING](CONTRIBUTING.md)

### Author

Eric Sizemore - <admin@secondversion.com> - <https://www.secondversion.com>

### License

Bench is licensed under the MIT License - see the `LICENSE.md` file for details.
