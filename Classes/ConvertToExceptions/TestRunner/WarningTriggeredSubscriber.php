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

namespace SBUERK\PHPUnitHardening\ConvertToExceptions\TestRunner;

use PHPUnit\Event\TestRunner\WarningTriggered;
use PHPUnit\Event\TestRunner\WarningTriggeredSubscriber as WarningTriggeredSubscriberInterface;
use SBUERK\PHPUnitHardening\ConvertToExceptions\Exceptions\WarningTriggeredErrorException;

final class WarningTriggeredSubscriber implements WarningTriggeredSubscriberInterface
{
    public function notify(WarningTriggered $event): void
    {
        throw new WarningTriggeredErrorException(
            $event->asString(),
            0,
            E_USER_WARNING,
            null,
            null,
            null,
        );
    }
}
