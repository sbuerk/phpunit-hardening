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

namespace SBUERK\PHPUnitHardening\Tests\Fixtures\Emitter;

use PHPUnit\Event\Code;
use PHPUnit\Event\Code\TestDoxBuilder;
use PHPUnit\Event\DirectDispatcher;
use PHPUnit\Event\Subscriber;
use PHPUnit\Event\Telemetry;
use PHPUnit\Event\TestData\TestDataCollection;
use PHPUnit\Event\TypeMap;
use PHPUnit\Metadata\MetadataCollection;

/**
 * Provides event emitting based methods.
 *
 * Taken from \PHPUnit\Event\DispatchingEmitterTest (tests/unit/Event/Emitter/DispatchingEmitterTest.php)
 */
trait EmitterBasedTestTrait
{
    protected function dispatcherWithoutRegisteredSubscriber(): DirectDispatcher
    {
        return new DirectDispatcher(new TypeMap());
    }

    protected function dispatcherWithRegisteredSubscriber(string $subscriberInterface, string $eventClass, Subscriber $subscriber): DirectDispatcher
    {
        $typeMap = new TypeMap();

        $typeMap->addMapping(
            $subscriberInterface,
            $eventClass
        );

        $dispatcher = new DirectDispatcher($typeMap);

        $dispatcher->registerSubscriber($subscriber);

        return $dispatcher;
    }

    protected function dispatcherWithRegisteredSubscribers(string $subscriberInterfaceOne, string $eventClassOne, Subscriber $subscriberOne, string $subscriberInterfaceTwo, string $eventClassTwo, Subscriber $subscriberTwo): DirectDispatcher
    {
        $typeMap = new TypeMap();

        $typeMap->addMapping(
            $subscriberInterfaceOne,
            $eventClassOne
        );

        $typeMap->addMapping(
            $subscriberInterfaceTwo,
            $eventClassTwo
        );

        $dispatcher = new DirectDispatcher($typeMap);

        $dispatcher->registerSubscriber($subscriberOne);
        $dispatcher->registerSubscriber($subscriberTwo);

        return $dispatcher;
    }

    protected function telemetrySystem(): Telemetry\System
    {
        return new Telemetry\System(
            new Telemetry\SystemStopWatch(),
            new Telemetry\SystemMemoryMeter()
        );
    }

    protected function testValueObject(
        string $className = 'FooTest',
        string $methodName = '',
        string $file = 'FooTest.php',
        int $line = 1,
    ): Code\TestMethod {
        return new Code\TestMethod(
            'FooTest',
            'testBar',
            'FooTest.php',
            1,
            TestDoxBuilder::fromClassNameAndMethodName('Foo', 'bar'),
            MetadataCollection::fromArray([]),
            TestDataCollection::fromArray([])
        );
    }
}
