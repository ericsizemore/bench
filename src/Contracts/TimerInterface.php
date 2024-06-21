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

namespace Esi\Bench\Contracts;

interface TimerInterface
{
    /**
     * Get the elapsed time.
     *
     * @template T of bool
     *
     * @param T $readable
     *
     * @return (T is true ? string : float)
     */
    public function getElapsedTime(bool $readable = false, ?string $format = null): float|string;

    /**
     * Get current laps data.
     *
     * @return float[]
     */
    public function getLaps(): array;

    /**
     * Get the lap times.
     *
     * @return array<int, float|string>
     */
    public function getLapTimes(bool $readable = false, ?string $format = null): array;

    /**
     * Get the memory usage.
     *
     * @template T of bool
     *
     * @param T $readable
     *
     * @return (T is true ? string : int)
     */
    public function getMemoryUsage(bool $readable = false, ?string $format = null): int|string;

    /**
     * Get the current status of timer, whether it is stopped (true) or running (false).
     */
    public function isStopped(): bool;

    /**
     * Record a lap time.
     */
    public function lap(): void;

    /**
     * Start the timer.
     */
    public function start(): void;

    /**
     * Stop the timer.
     */
    public function stop(): void;
}
