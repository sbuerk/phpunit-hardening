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

use PHPUnit\Event\Test\NoticeTriggered;
use PHPUnit\Event\Test\NoticeTriggeredSubscriber as NoticeTriggeredSubscriberInterface;
use SBUERK\PHPUnitHardening\ConvertToExceptions\Exceptions\NoticeTriggeredErrorException;

final class NoticeTriggeredSubscriber implements NoticeTriggeredSubscriberInterface
{
    public function notify(NoticeTriggered $event): void
    {
        throw new NoticeTriggeredErrorException(
            $event->asString(),
            0,
            E_USER_NOTICE,
            $event->file(),
            $event->line(),
            null,
        );
    }
}
