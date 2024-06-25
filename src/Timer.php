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

namespace Esi\Bench;

use Esi\Bench\Contracts\TimerInterface;
use Esi\Bench\Exceptions\TimerAlreadyStartedException;
use Esi\Bench\Exceptions\TimerDoesNotExistException;
use Esi\Bench\Exceptions\TimerNotStartedOrIsStoppedException;

use function hrtime;
use function memory_get_usage;

/**
 * @see Tests\TimerTest
 * @see Tests\TimerTest
 */
class Timer implements TimerInterface
{
    /**
     * End time in nanoseconds.
     */
    protected float $endTime = 0.0;

    /**
     * @var float[]
     */
    protected array $laps = [];

    /**
     * Memory usage.
     */
    protected int $memoryUsage = 0;

    /**
     * Start time in nanoseconds.
     */
    protected float $startTime = 0.0;

    /**
     * Whether the timer is running.
     */
    protected bool $stopped = false;

    /**
     * @inheritDoc
     */
    public function getElapsedTime(bool $readable = false, ?string $format = null): float|string
    {
        if ($this->startTime === 0.0) {
            throw new TimerDoesNotExistException('Timer must be started first.');
        }

        $endTime = $this->isStopped() ? $this->endTime : hrtime(true);
        $elapsed = ($endTime - $this->startTime) / 1e9;

        if ($readable) {
            return Utils::readableElapsedTime($elapsed, $format);
        }

        return $elapsed;
    }

    /**
     * @inheritDoc
     */
    public function getLaps(): array
    {
        return $this->laps;
    }

    /**
     * @inheritDoc
     */
    public function getLapTimes(bool $readable = false, ?string $format = null): array
    {
        if ($this->startTime === 0.0) {
            throw new TimerDoesNotExistException('Timer must be started first.');
        }

        $previousTime = $this->startTime;
        $lapTimes     = [];

        foreach ($this->getLaps() as $lap) {
            $lapTime      = ($lap - $previousTime) / 1e9;
            $lapTimes[]   = $readable ? Utils::readableElapsedTime($lapTime, $format) : $lapTime;
            $previousTime = $lap;
        }

        if ($this->isStopped()) {
            $finalTime  = ($this->endTime - $previousTime) / 1e9;
            $lapTimes[] = $readable ? Utils::readableElapsedTime($finalTime, $format) : $finalTime;
        }

        return $lapTimes;
    }

    /**
     * @inheritDoc
     */
    public function getMemoryUsage(bool $readable = false, ?string $format = null): int|string
    {
        if ($this->startTime === 0.0) {
            throw new TimerDoesNotExistException('Timer must be started first.');
        }

        if ($readable) {
            return Utils::readableSize($this->memoryUsage, $format);
        }

        return $this->memoryUsage;
    }

    /**
     * @inheritDoc
     */
    public function isStopped(): bool
    {
        return $this->stopped;
    }

    /**
     * @inheritDoc
     */
    public function lap(): void
    {
        if ($this->startTime === 0.0 || $this->isStopped()) {
            throw new TimerNotStartedOrIsStoppedException('Timer has not been started or is already stopped.');
        }

        $this->laps[] = hrtime(true);
    }

    /**
     * @inheritDoc
     */
    public function start(): void
    {
        if ($this->startTime !== 0.0 && !$this->isStopped()) {
            throw new TimerAlreadyStartedException('Timer is already started. Stop it before starting it again.');
        }

        $this->startTime = hrtime(true);
        $this->stopped   = false;
    }

    /**
     * @inheritDoc
     */
    public function stop(): void
    {
        if ($this->startTime === 0.0 || $this->isStopped()) {
            throw new TimerNotStartedOrIsStoppedException('Timer has not been started or is already stopped.');
        }

        $this->endTime     = hrtime(true);
        $this->memoryUsage = memory_get_usage(true);
        $this->stopped     = true;
    }
}
