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

/**
 * Boilerplate for a functional test phpunit boostrap file.
 *
 * This file is loosely maintained within TYPO3 testing-framework, extensions
 * are encouraged to not use it directly, but to copy it to an own place,
 * usually in parallel to a UnitTests.xml file.
 *
 * This file is defined in UnitTests.xml and called by phpunit
 * before instantiating the test suites.
 *
 * The recommended way to execute the suite is "runTests.sh". See the
 * according script within TYPO3 core's Build/Scripts directory and
 * adapt to extensions needs.
 */
(static function () {

    if (!defined('TEST_FILES_PATH')) {
        define('TEST_FILES_PATH', __DIR__ . DIRECTORY_SEPARATOR . 'Fixtures' . DIRECTORY_SEPARATOR);
    }

    $composer = file_exists(__DIR__ . '/../../vendor/autoload.php');
    $phar     = file_exists(__DIR__ . '/autoload.php');

    if ($composer && $phar) {
        print 'More than one test fixture autoloader is available, exiting.' . PHP_EOL;

        exit(1);
    }

    if (!$composer && !$phar) {
        print 'No test fixture autoloader was registered, exiting.' . PHP_EOL;

        exit(1);
    }

    if ($composer) {
        if (!defined('PHPUNIT_COMPOSER_INSTALL')) {
            define('PHPUNIT_COMPOSER_INSTALL', dirname(__DIR__) . '/vendor/autoload.php');
        }

        require_once __DIR__ . '/../../vendor/autoload.php';
    }

    if ($phar) {
        if (!defined('__PHPUNIT_PHAR__')) {
            require_once __DIR__ . '/../build/artifacts/phpunit-snapshot.phar';
        }

        require_once __DIR__ . '/autoload.php';

        $jsonFile = dirname(__DIR__) . '/composer.json';
        $base     = dirname($jsonFile);

        foreach (json_decode(file_get_contents($jsonFile), true)['autoload-dev']['files'] as $file) {
            require_once $base . DIRECTORY_SEPARATOR . $file;
        }
    }

    unset($composer, $phar, $jsonFile, $base, $file);

})();
