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

use PHPUnit\Event\Test\PhpWarningTriggered;
use PHPUnit\Event\Test\PhpWarningTriggeredSubscriber as PhpWarningTriggeredSubscriberInterface;
use SBUERK\PHPUnitHardening\ConvertToExceptions\Exceptions\PhpWarningTriggeredErrorException;

final class PhpWarningTriggeredSubscriber implements PhpWarningTriggeredSubscriberInterface
{
    public function notify(PhpWarningTriggered $event): void
    {
        throw new PhpWarningTriggeredErrorException(
            $event->asString(),
            0,
            E_WARNING,
            $event->file(),
            $event->line(),
            null,
        );
    }
}
