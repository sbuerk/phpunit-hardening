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

use PHPUnit\Event\Test\PhpDeprecationTriggered;
use PHPUnit\Event\Test\PhpDeprecationTriggeredSubscriber as PhpDeprecationTriggeredSubscriberInterface;
use SBUERK\PHPUnitHardening\ConvertToExceptions\Exceptions\PhpDeprecationTriggeredErrorException;

final class PhpDeprecationTriggeredSubscriber implements PhpDeprecationTriggeredSubscriberInterface
{
    public function notify(PhpDeprecationTriggered $event): void
    {
        throw new PhpDeprecationTriggeredErrorException(
            $event->asString(),
            0,
            E_DEPRECATED,
            $event->file(),
            $event->line(),
            null,
        );
    }
}
