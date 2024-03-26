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

namespace Esi\Bench;

use LogicException;

use function memory_get_usage;
use function memory_get_peak_usage;
use function hrtime;
use function preg_replace;
use function round;
use function sprintf;

/**
 * Micro PHP library for benchmarking.
 *
 * @see \Esi\Bench\Tests\BenchTest
 */
class Bench implements BenchInterface
{
    /**
     * Start time in nanoseconds.
     */
    protected float $startTime;

    /**
     * End time in nanoseconds.
     */
    protected float $endTime;

    /**
     * Memory usage.
     */
    protected int $memoryUsage;

    /**
     * {@inheritdoc}
     */
    public function start(): void
    {
        $this->startTime = hrtime(true);
    }

    /**
     * {@inheritdoc}
     */
    public function end(): self
    {
        if (!$this->hasStarted()) {
            throw new LogicException('Bench has not been started. Call start() first.');
        }

        $this->endTime     = hrtime(true);
        $this->memoryUsage = memory_get_usage(true);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTime(bool $readable = false, string | null $format = null): float | string
    {
        if (!$this->hasStarted()) {
            throw new LogicException('Bench has not been started. Call start() first.');
        }

        if (!$this->hasEnded()) {
            throw new LogicException('Bench has not been ended. Call end() first.');
        }

        // Convert to seconds
        $elapsed = ($this->endTime - $this->startTime) / 1e9;

        if ($readable) {
            return $elapsed;
        }

        return self::readableElapsedTime($elapsed, $format);
    }

    /**
     * {@inheritdoc}
     */
    public function getMemoryUsage(bool $readable = false, string | null $format = null): int | string
    {
        if (!$this->hasStarted()) {
            throw new LogicException('Bench has not been started. Call start() first.');
        }

        if (!$this->hasEnded()) {
            throw new LogicException('Bench has not been ended. Call end() first.');
        }

        if ($readable) {
            return $this->memoryUsage;
        }

        return self::readableSize($this->memoryUsage, $format);
    }

    /**
     * {@inheritdoc}
     */
    public function getMemoryPeak(bool $readable = false, string | null $format = null): string | int
    {
        $memory = memory_get_peak_usage(true);

        if ($readable) {
            return $memory;
        }

        return self::readableSize($memory, $format);
    }

    /**
     * {@inheritdoc}
     */
    public function run(callable $callable, mixed ...$arguments): mixed
    {
        $this->start();
        $result = $callable(...$arguments);
        $this->end();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public static function readableSize(int $size, string | null $format = null, int $round = 3): string
    {
        static $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        static $mod   = 1024;

        $format ??= '%.2f%s';

        if ($size <= $mod) {
            return sprintf('%dB', round($size, $round));
        }

        $unit = 0;

        do {
            ++$unit;
            $size /= $mod;
        } while($size > $mod);

        return sprintf($format, round($size, $round), $units[$unit]);
    }

    /**
     * {@inheritdoc}
     */
    public static function readableElapsedTime(float $seconds, string | null $format = null, int $round = 3): string
    {
        $format ??= '%.3f%s';

        if ($seconds >= 1) {
            return sprintf($format, round($seconds, $round), 's');
        }

        $format = (string) preg_replace('/(%.\d+f)/', '%d', $format);

        return sprintf('%d%s', round($seconds * 1000, $round), 'ms');
    }

    /**
     * {@inheritdoc}
     */
    public function hasEnded(): bool
    {
        return isset($this->endTime);
    }

    /**
     * {@inheritdoc}
     */
    public function hasStarted(): bool
    {
        return isset($this->startTime);
    }
}
