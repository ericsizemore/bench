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

use Esi\Bench\Contracts\BenchInterface;
use Esi\Bench\Exceptions\TimerAlreadyStartedException;
use Esi\Bench\Exceptions\TimerDoesNotExistException;
use Esi\Bench\Exceptions\TimerNotStartedException;

use function sprintf;

class Bench implements BenchInterface
{
    /**
     * Array to hold multiple timers, identified by name.
     *
     * @var array<string, Timer>
     */
    protected array $timers = [];

    /**
     * @inheritDoc
     */
    public function getElapsedTime(string $name = 'default', bool $readable = false, ?string $format = null): float|string
    {
        if (!isset($this->timers[$name])) {
            throw new TimerDoesNotExistException(sprintf("Timer '%s' does not exist.", $name));
        }

        return $this->timers[$name]->getElapsedTime($readable, $format);
    }

    /**
     * @inheritDoc
     */
    public function getLapTimes(string $name = 'default', bool $readable = false, ?string $format = null): array
    {
        if (!isset($this->timers[$name])) {
            throw new TimerDoesNotExistException(sprintf("Timer '%s' does not exist.", $name));
        }

        return $this->timers[$name]->getLapTimes($readable, $format);
    }

    /**
     * @inheritDoc
     */
    public function getMemoryUsage(string $name = 'default', bool $readable = false, ?string $format = null): int|string
    {
        if (!isset($this->timers[$name])) {
            throw new TimerDoesNotExistException(sprintf("Timer '%s' does not exist.", $name));
        }

        return $this->timers[$name]->getMemoryUsage($readable, $format);
    }

    /**
     * @inheritDoc
     */
    public function lap(string $name = 'default'): void
    {
        if (!isset($this->timers[$name])) {
            throw new TimerNotStartedException(sprintf("Timer '%s' has not been started.", $name));
        }

        $this->timers[$name]->lap();
    }

    /**
     * @inheritDoc
     */
    public function start(string $name = 'default'): void
    {
        if (isset($this->timers[$name])) {
            throw new TimerAlreadyStartedException(sprintf("Timer '%s' is already started. Stop it before starting it again.", $name));
        }

        $this->timers[$name] = new Timer();
        $this->timers[$name]->start();
    }

    /**
     * @inheritDoc
     */
    public function stop(string $name = 'default'): void
    {
        if (!isset($this->timers[$name])) {
            throw new TimerNotStartedException(sprintf("Timer '%s' has not been started.", $name));
        }

        $this->timers[$name]->stop();
    }
}
