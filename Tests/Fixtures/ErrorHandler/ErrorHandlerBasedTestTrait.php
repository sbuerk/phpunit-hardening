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

namespace SBUERK\PHPUnitHardening\Tests\Fixtures\ErrorHandler;

use SBUERK\PHPUnitHardening\Runner\ErrorHandler;

trait ErrorHandlerBasedTestTrait
{
    protected static ErrorHandler|null $savedErrorHandlerInstance = null;
    protected static bool|null $savedErrorHandlerState = null;

    protected function saveErrorHandler(): void
    {
        if (self::$savedErrorHandlerState !== null || self::$savedErrorHandlerInstance !== null) {
            self::markTestSkipped('Previous test did not properly reset the error handler. Therefore, skipping test.');
        }
        self::$savedErrorHandlerState = ErrorHandler::instance()->state();
        self::$savedErrorHandlerInstance = ErrorHandler::instance();
    }

    protected function restoreErrorHandler(): void
    {
        if (self::$savedErrorHandlerInstance !== null) {
            ErrorHandler::set(self::$savedErrorHandlerInstance);
            if (self::$savedErrorHandlerState !== null) {
                if (self::$savedErrorHandlerState) {
                    ErrorHandler::instance()->enable();
                } else {
                    ErrorHandler::instance()->disable();
                }
            }
            self::$savedErrorHandlerState = null;
            self::$savedErrorHandlerInstance = null;
        }
    }
}
