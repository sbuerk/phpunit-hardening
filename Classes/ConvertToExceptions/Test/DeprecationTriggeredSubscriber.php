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

namespace SBUERK\PHPUnitHardening\ConvertToExceptions\Test;

use PHPUnit\Event\Test\DeprecationTriggered;
use PHPUnit\Event\Test\DeprecationTriggeredSubscriber as DeprecationTriggeredSubscriberInterface;
use SBUERK\PHPUnitHardening\ConvertToExceptions\Exceptions\DeprecationTriggeredErrorException;

final class DeprecationTriggeredSubscriber implements DeprecationTriggeredSubscriberInterface
{
    public function notify(DeprecationTriggered $event): void
    {
        throw new DeprecationTriggeredErrorException(
            $event->asString(),
            0,
            E_USER_DEPRECATED,
            $event->file(),
            $event->line(),
            null,
        );
    }
}
