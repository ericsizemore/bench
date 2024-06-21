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

use function preg_replace;
use function round;
use function sprintf;

abstract class Utils
{
    /**
     * Returns a human-readable elapsed time.
     *
     * @param float       $seconds Time (in seconds) that needs formatted.
     * @param null|string $format  The format to display (printf format).
     * @param int         $round   Rounding precision (decimals).
     */
    public static function readableElapsedTime(float $seconds, ?string $format = null, int $round = 3): string
    {
        $format ??= '%.3f%s';

        if ($seconds >= 1) {
            return sprintf($format, round($seconds, $round), 's');
        }

        $format = (string) preg_replace('/(%.\d+f)/', '%d', $format);

        return sprintf($format, round($seconds * 1000, $round), 'ms');
    }

    /**
     * Returns a human-readable memory size.
     *
     * @param int         $size   The size that needs formatted.
     * @param null|string $format The format to display (printf format).
     * @param int         $round  Rounding precision (decimals).
     */
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
            return sprintf('%dB', round($size, $round));
        }

        $unit = 0;

        do {
            ++$unit;
            $size /= $mod;
        } while ($size > $mod);

        return sprintf($format, round($size, $round), $units[$unit]);
    }
}
