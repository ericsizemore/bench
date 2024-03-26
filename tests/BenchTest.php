<?php

declare(strict_types=1);

/**
 * Bench - Micro PHP library for benchmarking.
 *
 * @author    Eric Sizemore <admin@secondversion.com>
 * @version   3.1.0
 * @copyright (C) 2024 Eric Sizemore
 * @license   The MIT License (MIT)
 *
 * Copyright (C) 2024 Eric Sizemore<https://www.secondversion.com/>.
 * Copyright (C) 2012-2020 Jeremy Perret<jeremy@devster.org>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to
 * deal in the Software without restriction, including without limitation the
 * rights to use, copy, modify, merge, publish, distribute, sublicense, and/or
 * sell copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
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
 * @internal
 */
#[CoversClass(Bench::class)]
class BenchTest extends TestCase
{
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

    #[DataProvider('sizeProvider')]
    public function testReadableSize(string $expected, int $size, string | null $format): void
    {
        self::assertSame($expected, Bench::readableSize($size, $format));
    }

    public static function timeProvider(): Iterator
    {
        yield ['900ms', 0.9004213];
        yield ['1.156s', 1.1557845];
    }

    #[DataProvider('timeProvider')]
    public function testReadableElapsedTime(string $expected, float $time): void
    {
        self::assertSame($expected, Bench::readableElapsedTime($time, '%.3f%s'));
    }

    public function testGetTime(): void
    {
        $bench = new Bench();
        $bench->start();
        $bench->end();

        self::assertMatchesRegularExpression('/^[0-9.]+ms/', $bench->getTime());

        $bench = new Bench();
        $bench->start();
        sleep(2);
        $bench->end();

        self::assertMatchesRegularExpression('/^[0-9.]+s/', $bench->getTime());
        self::assertIsFloat($bench->getTime(true));
        self::assertMatchesRegularExpression('/^\d+s/', $bench->getTime(false, '%d%s'));
    }

    public function testGetMemoryUsage(): void
    {
        $bench = new Bench();
        $bench->start();
        $bench->end();

        self::assertMatchesRegularExpression('/^[0-9.]+MB/', $bench->getMemoryUsage());
        self::assertIsInt($bench->getMemoryUsage(true));
        self::assertMatchesRegularExpression('/^\d+MB/', $bench->getMemoryUsage(false, '%d%s'));
    }

    public function testGetMemoryPeak(): void
    {
        $bench = new Bench();

        self::assertMatchesRegularExpression('/^[0-9.]+MB/', $bench->getMemoryPeak());
        self::assertIsInt($bench->getMemoryPeak(true));
        self::assertMatchesRegularExpression('/^\d+MB/', $bench->getMemoryPeak(false, '%d%s'));
    }

    public function testCallableWithoutArguments(): void
    {
        $bench  = new Bench();
        $result = $bench->run(static fn (): true => true);

        self::assertTrue($result);
    }

    public function testCallableWithArguments(): void
    {
        $bench  = new Bench();
        $result = $bench->run(static fn (int $one, int $two): int => $one + $two, 1, 2);

        self::assertSame(3, $result);
    }

    public function testWasStart(): void
    {
        $bench = new Bench();

        self::assertFalse($bench->hasStarted());
        $bench->start();
        self::assertTrue($bench->hasStarted());
    }

    public function testWasEnd(): void
    {
        $bench = new Bench();
        $bench->start();

        self::assertFalse($bench->hasEnded());
        $bench->end();
        self::assertTrue($bench->hasEnded());
    }

    public function testEndException(): void
    {
        $this->expectException(LogicException::class);
        $bench = new Bench();
        $bench->end();
    }

    public function testGetTimeExceptionWithoutStart(): void
    {
        $this->expectException(LogicException::class);
        $bench = new Bench();
        $bench->getTime();
    }


    public function testGetTimeExceptionWithoutEnd(): void
    {
        $this->expectException(LogicException::class);
        $bench = new Bench();
        $bench->start();
        $bench->getTime();
    }

    public function testGetMemoryUsageWithoutStart(): void
    {
        $this->expectException(LogicException::class);
        $bench = new Bench();
        $bench->getMemoryUsage();
    }

    public function testGetMemoryUsageWithoutEnd(): void
    {
        $this->expectException(LogicException::class);
        $bench = new Bench();
        $bench->start();
        $bench->getMemoryUsage();
    }
}
