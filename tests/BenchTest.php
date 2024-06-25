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

namespace Esi\Bench\Tests;

use Esi\Bench\Bench;
use Esi\Bench\Exceptions\TimerAlreadyStartedException;
use Esi\Bench\Exceptions\TimerDoesNotExistException;
use Esi\Bench\Exceptions\TimerNotStartedException;
use Esi\Bench\Timer;
use Esi\Bench\Utils;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

/**
 * Bench tests.
 *
 * @internal
 */
#[CoversClass(Bench::class)]
#[UsesClass(Timer::class)]
#[UsesClass(Utils::class)]
class BenchTest extends TestCase
{
    public function testCallableWithArguments(): void
    {
        $bench  = new Bench();
        $result = $bench->run('testTimer', static fn (int $one, int $two): int => $one + $two, 1, 2);

        self::assertSame(3, $result);
    }

    public function testCallableWithoutArguments(): void
    {
        $bench  = new Bench();
        $result = $bench->run('testTimer', static fn (): true => true);

        self::assertTrue($result);
    }

    public function testGetElapsedTime(): void
    {
        $bench = new Bench();
        $bench->start('testTimer');
        $bench->stop('testTimer');

        $elapsed = $bench->getElapsedTime('testTimer');
        self::assertGreaterThan(0, $elapsed);
    }

    public function testGetElapsedTimeException(): void
    {
        $this->expectException(TimerDoesNotExistException::class);
        $bench = new Bench();
        $bench->getElapsedTime('nonexistentTimer');
    }

    public function testGetElapsedTimeReadable(): void
    {
        $bench = new Bench();
        $bench->start('testTimer');
        $bench->stop('testTimer');

        $elapsed = $bench->getElapsedTime('testTimer', true);
        self::assertMatchesRegularExpression('/^[0-9.]+ms/', $elapsed);
    }

    public function testGetLapTimes(): void
    {
        $bench = new Bench();
        $bench->start('testTimer');
        $bench->lap('testTimer');
        $bench->stop('testTimer');

        $laps = $bench->getLapTimes('testTimer');

        self::assertCount(2, $laps);

        foreach ($laps as $lap) {
            self::assertIsFloat($lap);
        }
    }

    public function testGetLapTimesException(): void
    {
        $this->expectException(TimerDoesNotExistException::class);
        $bench = new Bench();
        $bench->getLapTimes('nonexistentTimer');
    }

    public function testGetLapTimesReadable(): void
    {
        $bench = new Bench();
        $bench->start('testTimer');
        $bench->lap('testTimer');
        $bench->stop('testTimer');

        $laps = $bench->getLapTimes('testTimer', true);

        self::assertCount(2, $laps);

        /**
         * @var string $lap
         */
        foreach ($laps as $lap) {
            self::assertMatchesRegularExpression('/^[0-9.]+ms/', $lap);
        }
    }

    public function testGetMemoryUsage(): void
    {
        $bench = new Bench();
        $bench->start('testTimer');
        $bench->stop('testTimer');

        $memory = $bench->getMemoryUsage('testTimer');
        self::assertGreaterThan(0, $memory);
    }

    public function testGetMemoryUsageException(): void
    {
        $this->expectException(TimerDoesNotExistException::class);
        $bench = new Bench();
        $bench->getMemoryUsage('nonexistentTimer');
    }

    public function testGetMemoryUsageReadable(): void
    {
        $bench = new Bench();
        $bench->start('testTimer');
        $bench->stop('testTimer');

        $memory = $bench->getMemoryUsage('testTimer', true);
        self::assertMatchesRegularExpression('/^[0-9.]+MB/', $memory);
    }

    public function testLapException(): void
    {
        $this->expectException(TimerNotStartedException::class);
        $bench = new Bench();
        $bench->lap('testTimer');
    }

    public function testLapTimer(): void
    {
        $bench = new Bench();
        $bench->start('testTimer');
        $bench->lap('testTimer');
        $bench->stop('testTimer');
        self::assertCount(2, $bench->getLapTimes('testTimer'));
    }

    public function testStartAndStopTimer(): void
    {
        $bench = new Bench();
        $bench->start('testTimer');
        $bench->stop('testTimer');
        self::assertCount(1, $bench->getLapTimes('testTimer'));
    }

    public function testStartException(): void
    {
        $this->expectException(TimerAlreadyStartedException::class);
        $bench = new Bench();
        $bench->start('testTimer');
        $bench->start('testTimer');
    }

    public function testStopException(): void
    {
        $this->expectException(TimerNotStartedException::class);
        $bench = new Bench();
        $bench->stop('testTimer');
    }
}
