## CHANGELOG
A not so exhaustive list of changes for each release.

For a more detailed listing of changes between each version, 
you can use the following url: https://github.com/ericsizemore/bench/compare/v3.0.0...v3.1.0. 

Simply replace the version numbers depending on which set of changes you wish to see.

### 3.1.0 (2024-03-26)

  * Replace usage of `microtime` with `hrtime`
    * Noticed some potential issues that didn't make this too simple of a switch.
  * Reworked `readableElapsedTime` (in both `Bench` and `BenchInterface`) and `getTime`.
    * `readableElapsedTime` now accepts time as seconds, which is derived by `$endTime - $startTime / 1e9`.
      * Due to this, the named argument `$microtime` is now `$seconds`.

### 3.0.0 (2024-02-09)

  * Forked from [`devster/ubench`](https://github.com/devster/ubench) Version 2.1
  * Initial release for `Esi\Bench` will therefore be `3.0.0`.

  * Changes made in `Esi\Bench` in comparison to the original `devster/ubench`
    * Bumped PHP version requirement to 8.2
    * Added namespace `Esi\Bench` for the main class.
    * Added namespace `Esi\Bench\Tests` for the unit tests.
    * Renamed from `Ubench` to simply `Bench`
    * Added the `BenchInterface` interface.
    * Added dev-dependencies for:
      * PHPStan
        * PHPStan PHPUnit
        * PHPStan Strict Rules
    * Updated PHPUnit to `11.x` and updated the unit tests and `phpunit.xml` config.
    * Created imports for all used functions and class names.
    * Added Github workflows for testing and static analysis.
    * CS fixes and a bit of refactoring.
    * Updated README.md
    * Added LICENSE.md
    * Added SECURITY.md