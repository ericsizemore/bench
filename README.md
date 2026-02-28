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

> [!NOTE]
> This library is a fork of [devster/ubench](https://github.com/devster/ubench) v2.1.0.

## Installation

### Composer ###

Run the following command to install the package

```shell
composer require esi/bench:~4.0.0
```

## Usage
```php
require_once 'vendor/autoload.php';

$bench = new Bench;

$bench->start('testTimer');

// Execute some code

// Record a lap
$bench->lap('testTimer');

// Execute more code

$bench->stop('testTimer');

// Get elapsed time and memory.
// Get the elapsed time
echo $bench->getElapsedTime('testTimer', true); // e.g., "2.001s"

// Get lap times
print_r($bench->getLapTimes('testTimer', true)); // e.g., ["1.000ms", "1.001ms"]

// Get memory usage
echo $bench->getMemoryUsage('testTimer', true); // e.g., "2.00MB"

/**
 * Runs `Bench::start()` and `Bench::stop()` around a callable.
 * Accepts a name for the timer as the first parameter, and a callable as the second parameter.
 * Any additional parameters will be passed to the callable.
 */
$result = $bench->run('callableTimer', static function (int $x): int {
    return $x;
}, 1);
echo $bench->getElapsedTime('callableTimer');
```

## About

### Requirements

* PHP >= 8.2

### Credits

- [Eric Sizemore](https://github.com/ericsizemore)
- [All Contributors](https://github.com/ericsizemore/bench/contributors)

And thanks to the library this is a fork of, [devster/ubench](https://github.com/devster/ubench):

- [Jeremy Perret](https://github.com/devster)
- [All uBench Contributors](https://github.com/devster/ubench/graphs/contributors)

### Contributing

See [CONTRIBUTING](./CONTRIBUTING.md).

Bugs and feature requests are tracked on [GitHub](https://github.com/ericsizemore/bench/issues).

### Contributor Covenant Code of Conduct

See [CODE_OF_CONDUCT.md](./CODE_OF_CONDUCT.md)

### Backward Compatibility Promise

See [backward-compatibility.md](./backward-compatibility.md) for more information on Backwards Compatibility.

### Changelog

See the [CHANGELOG](./CHANGELOG.md) for more information on what has changed recently.

### License

See the [LICENSE](./LICENSE.md) for more information on the license that applies to this project.

### Security

See [SECURITY](./SECURITY.md) for more information on the security disclosure process.
