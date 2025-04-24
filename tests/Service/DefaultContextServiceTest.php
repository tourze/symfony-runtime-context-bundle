<?php

declare(strict_types=1);

namespace Tourze\Symfony\RuntimeContextBundle\Tests\Service;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Tourze\Symfony\RuntimeContextBundle\EventSubscriber\DeferCallSubscriber;
use Tourze\Symfony\RuntimeContextBundle\Service\ContextServiceInterface;
use Tourze\Symfony\RuntimeContextBundle\Service\DefaultContextService;

class DefaultContextServiceTest extends TestCase
{
    public function testImplementsContextServiceInterface()
    {
        $logger = $this->createMock(LoggerInterface::class);
        $subscriber = new DeferCallSubscriber($logger);
        $service = new DefaultContextService($subscriber);
        $this->assertInstanceOf(ContextServiceInterface::class, $service);
    }

    public function testGetIdReturnsPidOrUniqueId()
    {
        $logger = $this->createMock(LoggerInterface::class);
        $subscriber = new DeferCallSubscriber($logger);
        $service = new DefaultContextService($subscriber);
        $id = $service->getId();
        $this->assertNotEmpty($id);
        $this->assertIsString($id);
    }

    public function testResetClearsId()
    {
        $logger = $this->createMock(LoggerInterface::class);
        $subscriber = new DeferCallSubscriber($logger);
        $service = new DefaultContextService($subscriber);
        $id1 = $service->getId();
        $service->reset();
        $id2 = $service->getId();
        $this->assertNotEmpty($id2);
        $this->assertIsString($id2);
        $this->assertEquals($id1, $id2); // 进程id情况下应一致
    }

    public function testDeferPushesCallbackToSubscriber()
    {
        $logger = $this->createMock(LoggerInterface::class);
        $subscriber = $this->getMockBuilder(DeferCallSubscriber::class)
            ->setConstructorArgs([$logger])
            ->onlyMethods(['addDeferCall'])
            ->getMock();
        $subscriber->expects($this->once())
            ->method('addDeferCall');
        $service = new DefaultContextService($subscriber);
        $service->defer(function () {});
    }

    public function testSupportCoroutineReturnsFalse()
    {
        $logger = $this->createMock(LoggerInterface::class);
        $subscriber = new DeferCallSubscriber($logger);
        $service = new DefaultContextService($subscriber);
        $this->assertFalse($service->supportCoroutine());
    }
}
