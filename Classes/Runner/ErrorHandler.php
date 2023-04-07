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

namespace SBUERK\PHPUnitHardening\Runner;

use PHPUnit\Event;
use PHPUnit\Framework\TestCase;
use PHPUnit\Util\Exception;
use PHPUnit\Util\NoTestCaseObjectOnCallStackException;
use SBUERK\PHPUnitHardening\Parameters\ErrorConvert;
use SBUERK\PHPUnitHardening\Runner\Exceptions\PhpDeprecationErrorException;
use SBUERK\PHPUnitHardening\Runner\Exceptions\PhpNoticeErrorException;
use SBUERK\PHPUnitHardening\Runner\Exceptions\PhpWarningErrorException;
use SBUERK\PHPUnitHardening\Runner\Exceptions\UserDeprecationErrorException;
use SBUERK\PHPUnitHardening\Runner\Exceptions\UserErrorErrorException;
use SBUERK\PHPUnitHardening\Runner\Exceptions\UserNoticeErrorException;
use SBUERK\PHPUnitHardening\Runner\Exceptions\UserWarningErrorException;

/**
 * @internal
 */
class ErrorHandler
{
    private static ?self $instance = null;
    private bool $enabled          = false;

    public static function instance(): self
    {
        if (!self::$instance) {
            throw new \RuntimeException('ErrorHandler not setup yet.');
        }
        return self::$instance;
    }

    /**
     * @internal Only for bootstrapping and tests.
     */
    public static function set(self $instance): void
    {
        if (self::$instance !== null) {
            $enable = self::instance()->enabled;
            self::instance()->disable();
            $instance->enabled = $enable;
        }
        self::$instance = $instance;
    }

    public function __construct(
        public readonly ErrorConvert $userDeprecations = ErrorConvert::Default,
        public readonly ErrorConvert $phpDeprecations = ErrorConvert::Default,
        public readonly ErrorConvert $userNotices = ErrorConvert::Default,
        public readonly ErrorConvert $phpNotices = ErrorConvert::Default,
        public readonly ErrorConvert $userWarnings = ErrorConvert::Default,
        public readonly ErrorConvert $phpWarnings = ErrorConvert::Default,
        public readonly ErrorConvert $userErrors = ErrorConvert::Default,
        //        public readonly ErrorConvert $phpUnitDeprecations = ErrorConvert::Default,
        //        public readonly ErrorConvert $phpUnitWarnings = ErrorConvert::Default,
    ) {
    }

    /**
     * @throws Exception
     */
    public function __invoke(int $errorNumber, string $errorString, string $errorFile, int $errorLine): bool
    {
        $suppressed = !($errorNumber & error_reporting());
        //        if ($suppressed &&
        //            in_array($errorNumber, [E_DEPRECATED, E_NOTICE, E_STRICT, E_WARNING], true)) {
        //            return false;
        //        }

        switch ($errorNumber) {
            case E_NOTICE:
            case E_STRICT:
                $convertType = $this->phpNotices;
                if ($suppressed
                    && in_array($errorNumber, [E_DEPRECATED, E_NOTICE, E_STRICT, E_WARNING], true)
                    && $convertType !== ErrorConvert::WithSuppressed
                ) {
                    return false;
                }
                if ($suppressed && $convertType === ErrorConvert::NotSuppressed) {
                    return true;
                }
                Event\Facade::emitter()->testTriggeredPhpNotice(
                    $this->testValueObjectForEvents(),
                    $errorString,
                    $errorFile,
                    $errorLine
                );
                throw new PhpNoticeErrorException(
                    message: $errorString,
                    code: 0,
                    severity: $errorNumber,
                    filename: $errorFile,
                    line: $errorLine,
                );

                break;

            case E_USER_NOTICE:
                $convertType = $this->userNotices;
                if ($suppressed
                    && in_array($errorNumber, [E_DEPRECATED, E_NOTICE, E_STRICT, E_WARNING], true)
                    && $convertType !== ErrorConvert::WithSuppressed
                ) {
                    return false;
                }
                if ($suppressed && $convertType === ErrorConvert::NotSuppressed) {
                    return true;
                }
                Event\Facade::emitter()->testTriggeredNotice(
                    $this->testValueObjectForEvents(),
                    $errorString,
                    $errorFile,
                    $errorLine
                );
                throw new UserNoticeErrorException(
                    message: $errorString,
                    code: 0,
                    severity: $errorNumber,
                    filename: $errorFile,
                    line: $errorLine,
                );

                break;

            case E_WARNING:
                $convertType = $this->phpWarnings;
                if ($suppressed
                    && in_array($errorNumber, [E_DEPRECATED, E_NOTICE, E_STRICT, E_WARNING], true)
                    && $convertType !== ErrorConvert::WithSuppressed
                ) {
                    return false;
                }
                if ($suppressed && $convertType === ErrorConvert::NotSuppressed) {
                    return true;
                }
                Event\Facade::emitter()->testTriggeredPhpWarning(
                    $this->testValueObjectForEvents(),
                    $errorString,
                    $errorFile,
                    $errorLine
                );
                throw new PhpWarningErrorException(
                    message: $errorString,
                    code: 0,
                    severity: $errorNumber,
                    filename: $errorFile,
                    line: $errorLine,
                );

                break;

            case E_USER_WARNING:
                $convertType = $this->userWarnings;
                if ($suppressed
                    && in_array($errorNumber, [E_DEPRECATED, E_NOTICE, E_STRICT, E_WARNING], true)
                    && $convertType !== ErrorConvert::WithSuppressed
                ) {
                    return false;
                }
                if ($suppressed && $convertType === ErrorConvert::NotSuppressed) {
                    return true;
                }
                Event\Facade::emitter()->testTriggeredWarning(
                    $this->testValueObjectForEvents(),
                    $errorString,
                    $errorFile,
                    $errorLine
                );
                throw new UserWarningErrorException(
                    message: $errorString,
                    code: 0,
                    severity: $errorNumber,
                    filename: $errorFile,
                    line: $errorLine,
                );

                break;

            case E_DEPRECATED:
                $convertType = $this->phpDeprecations;
                if ($suppressed
                    && in_array($errorNumber, [E_DEPRECATED, E_NOTICE, E_STRICT, E_WARNING], true)
                    && $convertType !== ErrorConvert::WithSuppressed
                ) {
                    return false;
                }
                if ($suppressed && $convertType === ErrorConvert::NotSuppressed) {
                    return true;
                }
                Event\Facade::emitter()->testTriggeredPhpDeprecation(
                    $this->testValueObjectForEvents(),
                    $errorString,
                    $errorFile,
                    $errorLine
                );
                throw new PhpDeprecationErrorException(
                    message: $errorString,
                    code: 0,
                    severity: $errorNumber,
                    filename: $errorFile,
                    line: $errorLine,
                );

                break;

            case E_USER_DEPRECATED:
                $convertType = $this->userDeprecations;
                if ($suppressed
                    && in_array($errorNumber, [E_DEPRECATED, E_NOTICE, E_STRICT, E_WARNING], true)
                    && $convertType !== ErrorConvert::WithSuppressed
                ) {
                    return false;
                }
                if ($suppressed && $convertType === ErrorConvert::NotSuppressed) {
                    return true;
                }
                Event\Facade::emitter()->testTriggeredDeprecation(
                    $this->testValueObjectForEvents(),
                    $errorString,
                    $errorFile,
                    $errorLine
                );
                throw new UserDeprecationErrorException(
                    message: $errorString,
                    code: 0,
                    severity: $errorNumber,
                    filename: $errorFile,
                    line: $errorLine,
                );

                break;

            case E_USER_ERROR:
                $convertType = $this->userErrors;
                if ($suppressed
                    && in_array($errorNumber, [E_DEPRECATED, E_NOTICE, E_STRICT, E_WARNING], true)
                    && $convertType !== ErrorConvert::WithSuppressed
                ) {
                    return false;
                }
                if ($suppressed && $convertType === ErrorConvert::NotSuppressed) {
                    return true;
                }
                Event\Facade::emitter()->testTriggeredError(
                    $this->testValueObjectForEvents(),
                    $errorString,
                    $errorFile,
                    $errorLine
                );
                throw new UserErrorErrorException(
                    message: $errorString,
                    code: 0,
                    severity: $errorNumber,
                    filename: $errorFile,
                    line: $errorLine,
                );
                break;

            default:
                // @codeCoverageIgnoreStart
                return false;
                // @codeCoverageIgnoreEnd
        }
    }

    public function enable(): void
    {
        if ($this->enabled) {
            // @codeCoverageIgnoreStart
            return;
            // @codeCoverageIgnoreEnd
        }

        $oldErrorHandler = set_error_handler($this);

        if ($oldErrorHandler !== null) {
            // @codeCoverageIgnoreStart
            restore_error_handler();

            return;
            // @codeCoverageIgnoreEnd
        }

        $this->enabled = true;
    }

    public function state(): bool
    {
        return $this->enabled;
    }

    public function disable(): void
    {
        if (!$this->enabled) {
            // @codeCoverageIgnoreStart
            return;
            // @codeCoverageIgnoreEnd
        }

        restore_error_handler();

        $this->enabled = false;
    }

    /**
     * @throws NoTestCaseObjectOnCallStackException
     */
    protected function testValueObjectForEvents(): Event\Code\Test
    {
        foreach (debug_backtrace() as $frame) {
            if (isset($frame['object']) && $frame['object'] instanceof TestCase) {
                return $frame['object']->valueObjectForEvents();
            }
        }

        // @codeCoverageIgnoreStart
        throw new NoTestCaseObjectOnCallStackException();
        // @codeCoverageIgnoreEnd
    }
}
