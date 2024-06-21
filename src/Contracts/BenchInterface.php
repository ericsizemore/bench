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

use Esi\Bench\Exceptions\TimerAlreadyStartedException;
use Esi\Bench\Exceptions\TimerDoesNotExistException;
use Esi\Bench\Exceptions\TimerNotStartedException;

interface BenchInterface
{
    /**
     * Get the elapsed time for a named timer.
     *
     * @template T of bool
     *
     * @param T $readable
     *
     * @throws TimerDoesNotExistException
     *
     * @return (T is true ? string : float)
     */
    public function getElapsedTime(string $name = 'default', bool $readable = false, ?string $format = null): float|string;

    /**
     * Get the lap times for a named timer.
     *
     * @throws TimerDoesNotExistException
     *
     * @return array<int, float|string>
     */
    public function getLapTimes(string $name = 'default', bool $readable = false, ?string $format = null): array;

    /**
     * Get the memory usage for a named timer.
     *
     * @template T of bool
     *
     * @param T $readable
     *
     * @throws TimerDoesNotExistException
     *
     * @return (T is true ? string : int)
     */
    public function getMemoryUsage(string $name = 'default', bool $readable = false, ?string $format = null): int|string;

    /**
     * Record a lap time for a named timer.
     *
     * @throws TimerNotStartedException
     */
    public function lap(string $name = 'default'): void;

    /**
     * Start a named timer.
     *
     * @throws TimerAlreadyStartedException
     */
    public function start(string $name = 'default'): void;

    /**
     * Stop a named timer.
     *
     * @throws TimerNotStartedException
     */
    public function stop(string $name = 'default'): void;
}
