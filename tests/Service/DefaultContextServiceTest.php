<?php

declare(strict_types=1);

namespace Tourze\Symfony\RuntimeContextBundle\Tests\Service;

use PHPUnit\Framework\TestCase;
use Tourze\Symfony\RuntimeContextBundle\EventSubscriber\DeferCallSubscriber;
use Tourze\Symfony\RuntimeContextBundle\Service\ContextServiceInterface;
use Tourze\Symfony\RuntimeContextBundle\Service\DefaultContextService;

class DefaultContextServiceTest extends TestCase
{
    public function testImplementsContextServiceInterface()
    {
        $subscriber = new DeferCallSubscriber();
        $service = new DefaultContextService($subscriber);
        $this->assertInstanceOf(ContextServiceInterface::class, $service);
    }

    public function testGetIdReturnsPidOrUniqueId()
    {
        $subscriber = new DeferCallSubscriber();
        $service = new DefaultContextService($subscriber);
        $id = $service->getId();
        $this->assertNotEmpty($id);
        $this->assertIsString($id);
    }

    public function testResetClearsId()
    {
        $subscriber = new DeferCallSubscriber();
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
        $subscriber = $this->getMockBuilder(DeferCallSubscriber::class)
            ->onlyMethods(['addDeferCall'])
            ->getMock();
        $subscriber->expects($this->once())
            ->method('addDeferCall');
        $service = new DefaultContextService($subscriber);
        $service->defer(function () {});
    }

    public function testSupportCoroutineReturnsFalse()
    {
        $subscriber = new DeferCallSubscriber();
        $service = new DefaultContextService($subscriber);
        $this->assertFalse($service->supportCoroutine());
    }
}
