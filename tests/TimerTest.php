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

use Esi\Bench\Exceptions\TimerAlreadyStartedException;
use Esi\Bench\Exceptions\TimerDoesNotExistException;
use Esi\Bench\Exceptions\TimerNotStartedOrIsStoppedException;
use Esi\Bench\Timer;
use Esi\Bench\Utils;
use Iterator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(Timer::class)]
#[CoversClass(Utils::class)]
class TimerTest extends TestCase
{
    public function testGetElapsedTime(): void
    {
        $timer = new Timer();
        $timer->start();
        usleep(1000);
        $elapsedWhileRunning = $timer->getElapsedTime();

        $timer->stop();
        $elapsedAfterStop = $timer->getElapsedTime();

        self::assertGreaterThan(0, $elapsedWhileRunning);
        self::assertGreaterThan(0, $elapsedAfterStop);
        self::assertGreaterThan($elapsedWhileRunning, $elapsedAfterStop);
    }

    public function testGetElapsedTimeException(): void
    {
        $this->expectException(TimerDoesNotExistException::class);
        $timer = new Timer();
        $timer->getElapsedTime();
    }

    public function testGetElapsedTimeReadable(): void
    {
        $timer = new Timer();
        $timer->start();
        usleep(1000);
        $timer->stop();
        $elapsed = $timer->getElapsedTime(true);
        self::assertMatchesRegularExpression('/^[0-9.]+ms/', $elapsed);
    }

    public function testGetElapsedTimeReadableException(): void
    {
        $this->expectException(TimerDoesNotExistException::class);
        $timer = new Timer();
        $timer->getElapsedTime(true);
    }

    public function testGetLapTimes(): void
    {
        $timer = new Timer();
        $timer->start();
        usleep(1000);
        $timer->lap();
        usleep(1000);
        $timer->lap();
        $timer->stop();
        $laps = $timer->getLapTimes();
        self::assertCount(3, $laps);
        foreach ($laps as $lap) {
            self::assertIsFloat($lap);
        }
    }

    public function testGetLapTimesException(): void
    {
        $this->expectException(TimerDoesNotExistException::class);
        $timer = new Timer();
        $timer->getLapTimes();
    }

    public function testGetLapTimesReadable(): void
    {
        $timer = new Timer();
        $timer->start();
        usleep(1000);
        $timer->lap();
        usleep(1000);
        $timer->lap();
        $timer->stop();
        $laps = $timer->getLapTimes(true);
        self::assertCount(3, $laps);

        /**
         * @var string $lap
         */
        foreach ($laps as $lap) {
            self::assertMatchesRegularExpression('/^[0-9.]+ms/', $lap);
        }
    }

    public function testGetMemoryUsage(): void
    {
        $timer = new Timer();
        $timer->start();
        $timer->stop();
        $memory = $timer->getMemoryUsage();
        self::assertGreaterThan(0, $memory);
    }

    public function testGetMemoryUsageException(): void
    {
        $this->expectException(TimerDoesNotExistException::class);
        $timer = new Timer();
        $timer->getMemoryUsage();
    }

    public function testGetMemoryUsageReadable(): void
    {
        $timer = new Timer();
        $timer->start();
        $timer->stop();
        $memory = $timer->getMemoryUsage(true);
        self::assertMatchesRegularExpression('/^[0-9.]+MB/', $memory);
    }

    public function testLap(): void
    {
        $timer = new Timer();
        $timer->start();
        $timer->lap();
        self::assertCount(1, $timer->getLaps());
    }

    public function testLapException(): void
    {
        $this->expectException(TimerNotStartedOrIsStoppedException::class);
        $timer = new Timer();
        $timer->lap();
    }

    #[DataProvider('timeProvider')]
    public function testReadableElapsedTime(string $expected, float $time): void
    {
        self::assertSame($expected, Utils::readableElapsedTime($time, '%.3f%s'));
    }

    #[DataProvider('sizeProvider')]
    public function testReadableSize(string $expected, int $size, null|string $format): void
    {
        self::assertSame($expected, Utils::readableSize($size, $format));
    }

    public function testStart(): void
    {
        $timer = new Timer();
        $timer->start();
        self::assertFalse($timer->isStopped());
    }

    public function testStartException(): void
    {
        $this->expectException(TimerAlreadyStartedException::class);
        $timer = new Timer();
        $timer->start();
        $timer->start();
    }

    public function testStop(): void
    {
        $timer = new Timer();
        $timer->start();
        $timer->stop();
        self::assertTrue($timer->isStopped());
    }

    public function testStopException(): void
    {
        $this->expectException(TimerNotStartedOrIsStoppedException::class);
        $timer = new Timer();
        $timer->stop();
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
