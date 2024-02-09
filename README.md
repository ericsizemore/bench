Bench
=====

[![Build Status](https://scrutinizer-ci.com/g/ericsizemore/bench/badges/build.png?b=develop)](https://scrutinizer-ci.com/g/ericsizemore/bench/build-status/develop)
[![Code Coverage](https://scrutinizer-ci.com/g/ericsizemore/bench/badges/coverage.png?b=develop)](https://scrutinizer-ci.com/g/ericsizemore/bench/?branch=develop)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ericsizemore/bench/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/ericsizemore/bench/?branch=develop)
[![PHPStan](https://github.com/ericsizemore/bench/actions/workflows/ci.yml/badge.svg)](https://github.com/ericsizemore/bench/actions/workflows/ci.yml)
[![Tests](https://github.com/ericsizemore/bench/actions/workflows/tests.yml/badge.svg)](https://github.com/ericsizemore/bench/actions/workflows/tests.yml)
[![Latest Stable Version](https://img.shields.io/packagist/v/esi/bench.svg)](https://packagist.org/packages/esi/bench)
[![Downloads per Month](https://img.shields.io/packagist/dm/esi/bench.svg)](https://packagist.org/packages/esi/bench)
[![License](https://img.shields.io/packagist/l/esi/bench.svg)](https://packagist.org/packages/esi/bench)

`Bench` is a PHP micro library for benchmark.

## Acknowledgements / Credits

`Bench` is a fork of [devster/ubench](https://github.com/devster/ubench), and uses the same license as the original repository by @devster (MIT).
Thanks to them and all the contributors!

## Installation

### Composer ###

Add this to your composer.json

```json
{
    "require": {
        "esi/bench": "~3.0.0"
    }
}
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

Bench accepts contributions of code and documentation from the community. 
These contributions can be made in the form of Issues or [Pull Requests](http://help.github.com/send-pull-requests/) on the [Bench repository](https://github.com/ericsizemore/bench).

Bench is licensed under the MIT license. When submitting new features or patches to Bench, you are giving permission to license those features or patches under the MIT license.

Bench tries to adhere to PHPStan level 9 with strict rules and bleeding edge. Please ensure any contributions do as well.

#### Guidelines

Before we look into how, here are the guidelines. If your Pull Requests fail to pass these guidelines it will be declined, and you will need to re-submit when you’ve made the changes. This might sound a bit tough, but it is required for me to maintain quality of the code-base.

#### PHP Style

Please ensure all new contributions match the [PSR-12](https://www.php-fig.org/psr/psr-12/) coding style guide. The project is not fully PSR-12 compatible, yet; however, to ensure the easiest transition to the coding guidelines, I would like to go ahead and request that any contributions follow them.

#### Documentation

If you change anything that requires a change to documentation then you will need to add it. New methods, parameters, changing default values, adding constants, etc. are all things that will require a change to documentation. The change-log must also be updated for every change. Also, PHPDoc blocks must be maintained.

##### Documenting functions/variables (PHPDoc)

Please ensure all new contributions adhere to:

* [PSR-5 PHPDoc](https://github.com/php-fig/fig-standards/blob/master/proposed/phpdoc.md)
* [PSR-19 PHPDoc Tags](https://github.com/php-fig/fig-standards/blob/master/proposed/phpdoc-tags.md)

when documenting new functions, or changing existing documentation.

#### Branching

One thing at a time: A pull request should only contain one change. That does not mean only one commit, but one change - however many commits it took. The reason for this is that if you change X and Y but send a pull request for both at the same time, we might really want X but disagree with Y, meaning we cannot merge the request. Using the Git-Flow branching model you can create new branches for both of these features and send two requests.

### Author

Eric Sizemore - <admin@secondversion.com> - <https://www.secondversion.com>

### License

Bench is licensed under the MIT License - see the `LICENSE.md` file for details.
