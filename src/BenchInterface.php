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

namespace Esi\Bench;

use LogicException;

/**
 * @psalm-api
 */
interface BenchInterface
{
    /**
     * Sets end (micro)time.
     *
     * @throws LogicException Should be thrown if end() is called before start() has been called.
     *                        Can be checked with hasStarted().
     */
    public function end(): BenchInterface;

    /**
     * Returns the memory peak, readable or not.
     *
     * @param bool        $readable Whether the result must be human-readable.
     * @param null|string $format   The format to display (printf format).
     */
    public function getMemoryPeak(bool $readable = false, null|string $format = null): int|string;

    /**
     * Returns the memory usage at the end checkpoint.
     *
     * @param bool        $readable Whether the result must be human-readable.
     * @param null|string $format   The format to display (printf format).
     *
     * @throws LogicException Should be thrown if end() and/or start() has not been called.
     *                        Can be checked with hasStarted() and hasEnded().
     */
    public function getMemoryUsage(bool $readable = false, null|string $format = null): int|string;

    /**
     * Returns the elapsed time, readable or not.
     *
     * @param bool        $readable Whether the result must be human-readable.
     * @param null|string $format   The format to display (printf format).
     *
     * @throws LogicException Should be thrown if end() and/or start() has not been called.
     *                        Can be checked with hasStarted() and hasEnded().
     */
    public function getTime(bool $readable = false, null|string $format = null): float|string;

    /**
     * Checks if a bench has ended.
     */
    public function hasEnded(): bool;

    /**
     * Checks if a bench has been started.
     */
    public function hasStarted(): bool;

    /**
     * Wraps a callable with start() and end() calls.
     *
     * @param callable $callable  The callable to wrap.
     * @param mixed    $arguments Additional arguments that can be passed to the callable.
     *
     * @return mixed
     */
    public function run(callable $callable, mixed ...$arguments): mixed;

    /**
     * Sets start (micro)time.
     */
    public function start(): void;

    /**
     * Returns a human-readable elapsed time.
     *
     * @param float       $seconds Time (in seconds) that needs formatted.
     * @param null|string $format  The format to display (printf format).
     * @param int         $round   Rounding precision (decimals).
     */
    public static function readableElapsedTime(float $seconds, null|string $format = null, int $round = 3): string;

    /**
     * Returns a human-readable memory size.
     *
     * @param int         $size   The size that needs formatted.
     * @param null|string $format The format to display (printf format).
     * @param int         $round  Rounding precision (decimals).
     */
    public static function readableSize(int $size, null|string $format = null, int $round = 3): string;
}
