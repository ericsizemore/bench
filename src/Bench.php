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

use function hrtime;
use function memory_get_peak_usage;
use function memory_get_usage;
use function preg_replace;
use function round;

/**
 * Micro PHP library for benchmarking.
 *
 * @see Tests\BenchTest
 */
class Bench implements BenchInterface
{
    /**
     * End time in nanoseconds.
     */
    protected float $endTime = 0.0;

    /**
     * Memory usage.
     */
    protected int $memoryUsage = 0;

    /**
     * Start time in nanoseconds.
     */
    protected float $startTime = 0.0;

    /**
     * @inheritDoc
     */
    #[\Override]
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
     * @inheritDoc
     */
    #[\Override]
    public function getMemoryPeak(bool $readable = false, ?string $format = null): int|string
    {
        $memory = memory_get_peak_usage(true);

        if ($readable) {
            return $memory;
        }

        return self::readableSize($memory, $format);
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    public function getMemoryUsage(bool $readable = false, ?string $format = null): int|string
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
     * @inheritDoc
     */
    #[\Override]
    public function getTime(bool $readable = false, ?string $format = null): float|string
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
     * @inheritDoc
     */
    #[\Override]
    public function hasEnded(): bool
    {
        return $this->endTime !== 0.0;
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    public function hasStarted(): bool
    {
        return $this->startTime !== 0.0;
    }

    /**
     * @inheritDoc
     *
     * @psalm-template T
     *
     * @param T $arguments
     */
    #[\Override]
    public function run(callable $callable, mixed ...$arguments): mixed
    {
        $this->start();
        /** @psalm-var T $result */
        $result = $callable(...$arguments);
        $this->end();

        return $result;
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    public function start(): void
    {
        $this->startTime = hrtime(true);
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    public static function readableElapsedTime(float $seconds, ?string $format = null, int $round = 3): string
    {
        $format ??= '%.3f%s';

        if ($seconds >= 1) {
            return \sprintf($format, round($seconds, $round), 's');
        }

        $format = (string) preg_replace('/(%.\d+f)/', '%d', $format);

        return \sprintf($format, round($seconds * 1000.0, $round), 'ms');
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    public static function readableSize(int $size, ?string $format = null, int $round = 3): string
    {
        /**
         * @psalm-var array<array-key, string> $units
         */
        static $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        /**
         * @psalm-var int $mod
         */
        static $mod = 1024;

        $format ??= '%.2f%s';

        if ($size <= $mod) {
            return \sprintf('%dB', (int) round($size, $round));
        }

        $unit = 0;

        do {
            ++$unit;
            $size /= $mod;
        } while ($size > $mod);

        return \sprintf($format, round($size, $round), $units[$unit]);
    }
}
