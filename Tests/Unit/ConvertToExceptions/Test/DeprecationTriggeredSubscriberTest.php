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

namespace SBUERK\PHPUnitHardening\Tests\Unit\ConvertToExceptions\Test;

use PHPUnit\Event\Code\TestDoxBuilder;
use PHPUnit\Event\Code\TestMethod;
use PHPUnit\Event\DirectDispatcher;
use PHPUnit\Event\DispatchingEmitter;
use PHPUnit\Event\Subscriber;
use PHPUnit\Event\Telemetry;
use PHPUnit\Event\Test\DeprecationTriggered as TestDeprecationTriggered;
use PHPUnit\Event\Test\DeprecationTriggeredSubscriber as DeprecationTriggeredSubscriberInterface;
use PHPUnit\Event\TestData\TestDataCollection;
use PHPUnit\Event\TypeMap;
use PHPUnit\Framework\TestCase;
use PHPUnit\Metadata\MetadataCollection;
use SBUERK\PHPUnitHardening\ConvertToExceptions\Exceptions\DeprecationTriggeredErrorException;
use SBUERK\PHPUnitHardening\ConvertToExceptions\Test\DeprecationTriggeredSubscriber as ExtensionTestDeprecationTriggeredSubscriber;

final class DeprecationTriggeredSubscriberTest extends TestCase
{
    /**
     * @test
     */
    public function deprecationTriggeredConvertsToDeprecationTriggeredErrorException(): void
    {
        $dispatcher = $this->dispatcherWithRegisteredSubscriber(
            DeprecationTriggeredSubscriberInterface::class,
            TestDeprecationTriggered::class,
            new ExtensionTestDeprecationTriggeredSubscriber()
        );
        $telemetrySystem = $this->telemetrySystem();
        $emitter = new DispatchingEmitter(
            $dispatcher,
            $telemetrySystem
        );
        $testValueObject = $this->testValueObject();

        $this->expectException(DeprecationTriggeredErrorException::class);
        $emitter->testTriggeredDeprecation(
            $testValueObject,
            'test-user-deprecation',
            $testValueObject->file(),
            $testValueObject->line(),
        );

        // @todo WTF !! DirectDispatcher->dispatch() catches throwable and silencing all exceptions thrown in a
        //       event subscriber, which is not part of PHPUnit.
    }

    private function dispatcherWithRegisteredSubscriber(string $subscriberInterface, string $eventClass, Subscriber $subscriber): DirectDispatcher
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

    private function telemetrySystem(): Telemetry\System
    {
        return new Telemetry\System(
            new Telemetry\SystemStopWatch(),
            new Telemetry\SystemMemoryMeter(),
        );
    }

    private function testValueObject(): TestMethod
    {
        return new TestMethod(
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
