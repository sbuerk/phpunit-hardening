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

use PHPUnit\Event\Test\PhpunitWarningTriggered;
use PHPUnit\Event\Test\PhpunitWarningTriggeredSubscriber as PhpunitWarningTriggeredSubscriberInterface;
use SBUERK\PHPUnitHardening\ConvertToExceptions\Exceptions\PhpunitWarningTriggeredErrorException;

final class PhpunitWarningTriggeredSubscriber implements PhpunitWarningTriggeredSubscriberInterface
{
    public function notify(PhpunitWarningTriggered $event): void
    {
        throw new PhpunitWarningTriggeredErrorException(
            $event->asString(),
            0,
            E_USER_WARNING,
            null,
            null,
            null,
        );
    }
}
