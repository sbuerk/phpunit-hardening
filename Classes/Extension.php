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
use SBUERK\PHPUnitHardening\Parameters\ErrorConvert;
use SBUERK\PHPUnitHardening\Runner\ErrorHandler;
use SBUERK\PHPUnitHardening\TestRunner\EventFacadeSealedSubscriber;

final class Extension implements PhpunitExtension
{
    public function bootstrap(
        Configuration $configuration,
        Facade $facade,
        ParameterCollection $parameters
    ): void {
        $userDeprecations = $this->getParameterConvertType($parameters, 'deprecations');
        $phpDeprecations = $this->getParameterConvertType($parameters, 'php_deprecations');
        $userNotices = $this->getParameterConvertType($parameters, 'notices');
        $phpNotices = $this->getParameterConvertType($parameters, 'php_notices');
        $userWarnings = $this->getParameterConvertType($parameters, 'warnings');
        $phpWarnings = $this->getParameterConvertType($parameters, 'php_warnings');
        $userErrors = $this->getParameterConvertType($parameters, 'errors');

        // @todo    phpunit_* not possible, as these are only events. Throwing exceptions in event subscribers are
        //          swallowed, which is the reason we cannot convert them to exceptions. PHPUnit is working on making
        //          itself useless.
        //$phpUnitWarnings = $this->getParameterConvertType($parameters, 'phpunit_warnings');
        //$phpUnitDeprecations = $this->getParameterConvertType($parameters, 'phpunit_deprecations');

        ErrorHandler::set(new ErrorHandler(
            userDeprecations: $userDeprecations,
            phpDeprecations: $phpDeprecations,
            userNotices: $userNotices,
            phpNotices: $phpNotices,
            userWarnings: $userWarnings,
            phpWarnings: $phpWarnings,
            userErrors: $userErrors,
        ));

        $facade->registerSubscriber(
            new EventFacadeSealedSubscriber(
                '>> PHPUnit Hardening Extension loaded'
            )
        );
    }

    private function getParameterConvertType(
        ParameterCollection $parameterCollection,
        string $parameter
    ): ErrorConvert {
        $parameter = $parameterCollection->has($parameter)
            ? $parameterCollection->get($parameter)
            : '';
        return ErrorConvert::tryFrom($parameter) ?? ErrorConvert::Default;
    }
}
