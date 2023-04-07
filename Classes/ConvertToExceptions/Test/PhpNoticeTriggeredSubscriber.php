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

use PHPUnit\Event\Test\PhpNoticeTriggered;
use PHPUnit\Event\Test\PhpNoticeTriggeredSubscriber as PhpNoticeTriggeredSubscriberInterface;
use SBUERK\PHPUnitHardening\ConvertToExceptions\Exceptions\PhpNoticeTriggeredErrorException;

final class PhpNoticeTriggeredSubscriber implements PhpNoticeTriggeredSubscriberInterface
{
    public function notify(PhpNoticeTriggered $event): void
    {
        throw new PhpNoticeTriggeredErrorException(
            $event->asString(),
            0,
            E_USER_NOTICE,
            $event->file(),
            $event->line(),
            null,
        );
    }
}
