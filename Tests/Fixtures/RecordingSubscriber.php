<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace SBUERK\PHPUnitHardening\Tests\Fixtures;

use PHPUnit\Event;

abstract class RecordingSubscriber
{
    /**
     * @var array<int, Event\Event>
     */
    private array $events = [];

    final public function recordedEventCount(): int
    {
        return count($this->events);
    }

    final public function lastRecordedEvent(): ?Event\Event
    {
        if ($this->events === []) {
            return null;
        }

        return end($this->events);
    }

    final protected function record($event): void
    {
        $this->events[] = $event;
    }
}
