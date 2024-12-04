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
/**
 * Esi\Bench is a fork of Ubench (https://github.com/devster/ubench) which is:
 *     Copyright (c) 2012-2020 Jeremy Perret<jeremy@devster.org>
 *
 * For a list of changes in this library, in comparison to the original library, please {@see CHANGELOG.md}.
 */

namespace Esi\Bench\Tests;

use Esi\Bench\Bench;
use Iterator;
use LogicException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function sleep;

/**
 * Bench tests.
 *
 * @internal
 */
#[CoversClass(Bench::class)]
class BenchTest extends TestCase
{
    public function testCallableWithArguments(): void
    {
        $bench  = new Bench();
        $result = $bench->run(static fn (int $one, int $two): int => $one + $two, 1, 2);

        self::assertSame(3, $result);
    }

    public function testCallableWithoutArguments(): void
    {
        $bench  = new Bench();
        $result = $bench->run(static fn (): true => true);

        self::assertTrue($result);
    }

    public function testEndException(): void
    {
        $this->expectException(LogicException::class);
        $bench = new Bench();
        $bench->end();
    }

    public function testGetMemoryPeak(): void
    {
        $bench = new Bench();

        /**
         * @psalm-var string $actual
         */
        $actual = $bench->getMemoryPeak();
        self::assertMatchesRegularExpression('/^[0-9.]+MB/', $actual);

        self::assertIsInt($bench->getMemoryPeak(true));

        /**
         * @psalm-var string $actual
         */
        $actual = $bench->getMemoryPeak(false, '%d%s');
        self::assertMatchesRegularExpression('/^\d+MB/', $actual);
    }

    public function testGetMemoryUsage(): void
    {
        $bench = new Bench();
        $bench->start();
        $bench->end();

        /**
         * @psalm-var string $actual
         */
        $actual = $bench->getMemoryUsage();
        self::assertMatchesRegularExpression('/^[0-9.]+MB/', $actual);

        self::assertIsInt($bench->getMemoryUsage(true));

        /**
         * @psalm-var string $actual
         */
        $actual = $bench->getMemoryUsage(false, '%d%s');
        self::assertMatchesRegularExpression('/^\d+MB/', $actual);
    }

    public function testGetMemoryUsageWithoutEnd(): void
    {
        $this->expectException(LogicException::class);
        $bench = new Bench();
        $bench->start();
        $bench->getMemoryUsage();
    }

    public function testGetMemoryUsageWithoutStart(): void
    {
        $this->expectException(LogicException::class);
        $bench = new Bench();
        $bench->getMemoryUsage();
    }

    public function testGetTime(): void
    {
        $bench = new Bench();
        $bench->start();
        $bench->end();

        /**
         * @psalm-var string $actual
         */
        $actual = $bench->getTime();
        self::assertMatchesRegularExpression('/^[0-9.]+ms/', $actual);

        $bench = new Bench();
        $bench->start();
        sleep(2);
        $bench->end();

        /**
         * @psalm-var string $actual
         */
        $actual = $bench->getTime();
        self::assertMatchesRegularExpression('/^[0-9.]+s/', $actual);

        self::assertIsFloat($bench->getTime(true));

        /**
         * @psalm-var string $actual
         */
        $actual = $bench->getTime(false, '%d%s');
        self::assertMatchesRegularExpression('/^\d+s/', $actual);
    }

    public function testGetTimeExceptionWithoutEnd(): void
    {
        $this->expectException(LogicException::class);
        $bench = new Bench();
        $bench->start();
        $bench->getTime();
    }

    public function testGetTimeExceptionWithoutStart(): void
    {
        $this->expectException(LogicException::class);
        $bench = new Bench();
        $bench->getTime();
    }

    #[DataProvider('timeProvider')]
    public function testReadableElapsedTime(string $expected, float $time): void
    {
        self::assertSame($expected, Bench::readableElapsedTime($time, '%.3f%s'));
    }

    #[DataProvider('sizeProvider')]
    public function testReadableSize(string $expected, int $size, null|string $format): void
    {
        self::assertSame($expected, Bench::readableSize($size, $format));
    }

    public function testWasEnd(): void
    {
        $bench = new Bench();
        $bench->start();

        self::assertFalse($bench->hasEnded());
        $bench->end();
        self::assertTrue($bench->hasEnded());
    }

    public function testWasStart(): void
    {
        $bench = new Bench();

        self::assertFalse($bench->hasStarted());
        $bench->start();
        self::assertTrue($bench->hasStarted());
    }

    /**
     * @psalm-api
     */
    public static function sizeProvider(): Iterator
    {
        yield ['90B', 90, null];
        yield ['1024B', 1024, null];
        yield ['1.47KB', 1500, null];
        yield ['9.54MB', 10000000, null];
        yield ['9.31GB', 10000000000, null];
        yield ['9.10TB', 10000000000000, null];

        yield ['90B', 90, '%.3f%s'];
        yield ['1024B', 1024, '%.3f%s'];
        yield ['1.465KB', 1500, '%.3f%s'];
        yield ['9.537MB', 10000000, '%.3f%s'];
        yield ['9.313GB', 10000000000, '%.3f%s'];
        yield ['9.095TB', 10000000000000, '%.3f%s'];
    }

    /**
     * @psalm-api
     */
    public static function timeProvider(): Iterator
    {
        yield ['900ms', 0.9004213];
        yield ['1.156s', 1.1557845];
    }
}
