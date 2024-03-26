<?php

declare(strict_types=1);

/**
 * Bench - Micro PHP library for benchmarking.
 *
 * @author    Eric Sizemore <admin@secondversion.com>
 * @version   3.0.0
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

namespace Esi\Bench;

use LogicException;

/**
 */
interface BenchInterface
{
    /**
     * Sets start (micro)time.
     */
    public function start(): void;

    /**
     * Sets end (micro)time.
     *
     * @throws LogicException Should be thrown if end() is called before start() has been called.
     *                        Can be checked with hasStarted().
     */
    public function end(): self;

    /**
     * Returns the elapsed time, readable or not.
     *
     * @param   bool          $readable  Whether the result must be human-readable.
     * @param   string|null   $format    The format to display (printf format).
     *
     * @throws LogicException Should be thrown if end() and/or start() has not been called.
     *                        Can be checked with hasStarted() and hasEnded().
     */
    public function getTime(bool $readable = false, string | null $format = null): float | string;

    /**
     * Returns the memory usage at the end checkpoint.
     *
     * @param   bool          $readable  Whether the result must be human-readable.
     * @param   string|null   $format    The format to display (printf format).
     *
     * @throws LogicException Should be thrown if end() and/or start() has not been called.
     *                        Can be checked with hasStarted() and hasEnded().
     */
    public function getMemoryUsage(bool $readable = false, string | null $format = null): int | string;

    /**
     * Returns the memory peak, readable or not
     *
     * @param  bool         $readable  Whether the result must be human-readable.
     * @param  string|null  $format    The format to display (printf format).
     */
    public function getMemoryPeak(bool $readable = false, string | null $format = null): string | int;

    /**
     * Wraps a callable with start() and end() calls.
     *
     * @param   callable  $callable   The callable to wrap.
     * @param   mixed     $arguments  Additional arguments that can be passed to the callable.
     * @return mixed
     */
    public function run(callable $callable, mixed ...$arguments): mixed;

    /**
     * Returns a human-readable memory size.
     *
     * @param   int          $size    The size that needs formatted.
     * @param   string|null  $format  The format to display (printf format).
     * @param   int          $round   Rounding precision (decimals).
     */
    public static function readableSize(int $size, string | null $format = null, int $round = 3): string;

    /**
     * Returns a human-readable elapsed time.
     *
     * @param   float        $seconds    Time (in seconds) that needs formatted.
     * @param   string|null  $format     The format to display (printf format).
     * @param   int          $round      Rounding precision (decimals).
     */
    public static function readableElapsedTime(float $seconds, string | null $format = null, int $round = 3): string;

    /**
     * Checks if a bench has ended.
     */
    public function hasEnded(): bool;

    /**
     * Checks if a bench has been started.
     */
    public function hasStarted(): bool;
}
