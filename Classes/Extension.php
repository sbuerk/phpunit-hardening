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

namespace SBUERK\PHPUnitHardening;

use PHPUnit\Runner\Extension\Extension as PhpunitExtension;
use PHPUnit\Runner\Extension\Facade;
use PHPUnit\Runner\Extension\ParameterCollection;
use PHPUnit\TextUI\Configuration\Configuration;
use SBUERK\PHPUnitHardening\ConvertToExceptions\Test\DeprecationTriggeredSubscriber as TestDeprecationTriggeredSubscriber;
use SBUERK\PHPUnitHardening\ConvertToExceptions\Test\ErrorTriggeredSubscriber as TestErrorTriggeredSubscriber;
use SBUERK\PHPUnitHardening\ConvertToExceptions\Test\NoticeTriggeredSubscriber as TestNoticeTriggeredSubscriber;
use SBUERK\PHPUnitHardening\ConvertToExceptions\Test\PhpDeprecationTriggeredSubscriber as TestPhpDeprecationTriggeredSubscriber;
use SBUERK\PHPUnitHardening\ConvertToExceptions\Test\PhpNoticeTriggeredSubscriber as TestPhpNoticeTriggeredSubscriber;
use SBUERK\PHPUnitHardening\ConvertToExceptions\Test\PhpunitDeprecationTriggeredSubscriber as TestPhpunitDeprecationTriggeredSubscriber;
use SBUERK\PHPUnitHardening\ConvertToExceptions\Test\PhpunitWarningTriggeredSubscriber as TestPhpunitWarningTriggeredSubscriber;
use SBUERK\PHPUnitHardening\ConvertToExceptions\Test\PhpWarningTriggeredSubscriber as TestPhpWarningTriggeredSubscriber;
use SBUERK\PHPUnitHardening\ConvertToExceptions\Test\WarningTriggeredSubscriber as TestWarningTriggeredSubscriber;
use SBUERK\PHPUnitHardening\ConvertToExceptions\TestRunner\DeprecationTriggeredSubscriber as TestRunnerDeprecationTriggeredSubscriber;
use SBUERK\PHPUnitHardening\ConvertToExceptions\TestRunner\WarningTriggeredSubscriber as TestRunnerWarningTriggeredSubscriber;
use SBUERK\PHPUnitHardening\Options\ConvertType;
use SBUERK\PHPUnitHardening\TestRunner\EventFacadeSealedSubscriber;

final class Extension implements PhpunitExtension
{
    public function bootstrap(
        Configuration $configuration,
        Facade $facade,
        ParameterCollection $parameters
    ): void {
        $convertDeprecationsToExceptions = $this->getParameterConvertType($parameters, 'convertDeprecationsToExceptions');
        $convertNoticesToExceptions = $this->getParameterConvertType($parameters, 'convertNoticesToExceptions');
        $convertWarningsToExceptions = $this->getParameterConvertType($parameters, 'convertWarningsToExceptions');
        $convertErrorsToExceptions = $this->getParameterConvertType($parameters, 'convertErrorsToExceptions');

        // convert error to exception
        if ($convertDeprecationsToExceptions === ConvertType::Convert) {
            $facade->registerSubscribers(
                new TestDeprecationTriggeredSubscriber(),
                new TestPhpDeprecationTriggeredSubscriber(),
                new TestPhpunitDeprecationTriggeredSubscriber(),
                new TestRunnerDeprecationTriggeredSubscriber(),
            );
        }
        if ($convertNoticesToExceptions === ConvertType::Convert) {
            $facade->registerSubscribers(
                new TestNoticeTriggeredSubscriber(),
                new TestPhpNoticeTriggeredSubscriber(),
            );
        }
        if ($convertWarningsToExceptions === ConvertType::Convert) {
            $facade->registerSubscribers(
                new TestPhpunitWarningTriggeredSubscriber(),
                new TestPhpWarningTriggeredSubscriber(),
                new TestWarningTriggeredSubscriber(),
                new TestRunnerWarningTriggeredSubscriber(),
            );
        }
        if ($convertErrorsToExceptions === ConvertType::Convert) {
            $facade->registerSubscribers(
                new TestErrorTriggeredSubscriber(),
            );
        }

        $facade->registerSubscriber(
            new EventFacadeSealedSubscriber(
                '>> PHPUnit Hardening Extension loaded'
            )
        );
    }

    private function getParameterConvertType(
        ParameterCollection $parameterCollection,
        string $parameter
    ): ConvertType {
        $parameter = $parameterCollection->has($parameter)
            ? $parameterCollection->get($parameter)
            : '';
        return ConvertType::tryFrom($parameter) ?? ConvertType::None;
    }
}
