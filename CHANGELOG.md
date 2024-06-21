# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

The library has been refactored, with numerous changes. See [UPGRADING](/.UPGRADING.md) for more information.

### Added

  * (feat) `Contracts`
    * `BenchInterface`
    * `TimerInterface`
  * (feat) ` Exceptions`
    * `BaseException` which extends `LogicException`.
    * `ExceptionInterface`
    * `TimerAlreadyStartedException`
    * `TimerDoesNotExistException`
    * `TimerNotStartedException`
    * `TimerNotStartedOrIsStoppedException`
  * (feat) `Timer` class which holds timer data for each new Timer added.
  * (feat) `Utils` class which holds utility functions.
  * (feat) The ability to add named timers and run laps. Adds:
    * `Bench::getLapTimes()`
    * `Bench::getElapsedTime()`
  * Added upgrade information in [UPGRADING](./UPGRADING.md).

### Changed

  * `readableElapsedTime()` and `readableSize()` were extracted to the new `Utils` class.
  * `end()` changed to `stop()`
  * Methods no longer throw `LogicException`, will instead be one of the new `Exceptions` listed above.
  * `BenchInterface` has been updated to be inline with the `Bench` changes.
  * Unit tests have been updated.

### Removed

  * `Bench::run()` which allowed running a bench with a given callable has been removed, with no current replacement.
  * `Bench::hasStarted()`, `Bench::hasEnded()`, and `Bench::getTime()`


## [3.1.1] - 2024-06-13

### Added

  * Added new dev dependencies:
    * PHP-CS-Fixer
    * vimeo/psalm
  * Added new Issue and Pull Request templates.
  * Added `CONTRIBUTING.md` and `CODE_OF_CONDUCT.md`

### Changed

  * Updated dev dependencies:
    * esi/phpunit-coverage-check
    * PHPUnit
  * Updated source files to make the header docblock more compact.
  * The `startTime`, `endTime`, and `memoryUsage` properties of `Bench` are now initialized with default values.
    * `hasStarted()` and `hasEnded()` now check for default value, instead of an `isset` check.
  * Update `README.md` to split contributing information to its own file.
  * Updated this changelog to more closely follow the [Keep a Changelog](https://keepachangelog.com/en/1.1.0/) format.
  * Update `.gitattributes` to reduce tarball size.
  * Update `.gitignore`
  * Workflows combined into `continuous-integration.yml`

### Fixed

  * Fixes to resolve issues reported by Psalm.

### Removed

  * Removed `.php-cs-fixer.cache`, should never have been added to repo.
  * Removed workflows: `main.yml`, `psalm.yml` and `tests.yml`


## [3.1.0] - 2024-03-26

### Changed

  * Replace usage of `microtime` with `hrtime`
    * Noticed some potential issues that didn't make this too simple of a switch.
  * Reworked `readableElapsedTime` (in both `Bench` and `BenchInterface`) and `getTime`.
    * `readableElapsedTime` now accepts time as seconds, which is derived by `$endTime - $startTime / 1e9`.
      * Due to this, the named argument `$microtime` is now `$seconds`.


## [3.0.0] - 2024-02-09

This initial release is forked from [`devster/ubench v2.1`](https://github.com/devster/ubench) by [Jeremy Perret](https://github.com/devster).
This is the CHANGELOG for changes in comparison to the original library.

Initial release for `Esi\Bench` is set to `3.0.0`.

### Added

  * Added namespace `Esi\Bench` for the main class.
  * Added namespace `Esi\Bench\Tests` for the unit tests.
  * Added the `BenchInterface` interface.
  * Added dev-dependencies for:
    * PHPStan
      * PHPStan PHPUnit
      * PHPStan Strict Rules
  * Added Github workflows for testing and static analysis.
  * Added LICENSE.md
  * Added SECURITY.md

### Changed

  * Bumped PHP version requirement to 8.2
  * Renamed from `Ubench` to simply `Bench`
  * Updated PHPUnit to `11.x` and updated the unit tests and `phpunit.xml` config.
  * Created imports for all used functions and class names.
  * Updated README.md
  * CS fixes and a bit of refactoring.

[unreleased]: https://github.com/ericsizemore/bench/tree/4.x-dev
[3.1.1]: https://github.com/ericsizemore/bench/releases/tag/v3.1.1
[3.1.0]: https://github.com/ericsizemore/bench/releases/tag/v3.1.0
[3.0.0]: https://github.com/ericsizemore/bench/releases/tag/v3.0.0
