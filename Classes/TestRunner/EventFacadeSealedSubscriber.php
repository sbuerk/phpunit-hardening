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

namespace SBUERK\PHPUnitHardening\TestRunner;

use PHPUnit\Event\TestRunner\EventFacadeSealed;
use PHPUnit\Event\TestRunner\EventFacadeSealedSubscriber as EventFacadeSealedSubscriberInterface;
use SBUERK\PHPUnitHardening\Runner\ErrorHandler;

final class EventFacadeSealedSubscriber implements EventFacadeSealedSubscriberInterface
{
    public function __construct(
        private readonly string $message
    ) {
    }

    public function notify(EventFacadeSealed $event): void
    {
        ErrorHandler::instance()->enable();
        print $this->message . PHP_EOL . PHP_EOL;
    }
}
