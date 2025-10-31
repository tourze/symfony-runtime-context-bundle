<?php

declare(strict_types=1);

namespace Tourze\Symfony\RuntimeContextBundle\Tests\Service;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractEventSubscriberTestCase;
use Tourze\Symfony\RuntimeContextBundle\EventSubscriber\DeferCallSubscriber;
use Tourze\Symfony\RuntimeContextBundle\Service\DefaultContextService;

/**
 * @internal
 */
#[CoversClass(DefaultContextService::class)]
#[RunTestsInSeparateProcesses]
final class DefaultContextServiceTest extends AbstractEventSubscriberTestCase
{
    protected function onSetUp(): void
    {
        // 父类方法调用已自动处理
    }

    public function testImplementsContextServiceInterface(): void
    {
        $service = self::getService(DefaultContextService::class);
        $this->assertInstanceOf(DefaultContextService::class, $service);

        // 验证接口方法返回正确类型
        $this->assertIsString($service->getId());
        $this->assertIsBool($service->supportCoroutine());

        // 验证可以调用defer方法而不抛出异常
        $callable = fn () => true;
        $service->defer($callable);
        $this->assertTrue(true); // 验证没有抛出异常
    }

    public function testGetIdReturnsPidOrUniqueId(): void
    {
        $service = self::getService(DefaultContextService::class);
        $id = $service->getId();
        $this->assertNotEmpty($id);
    }

    public function testResetClearsId(): void
    {
        $service = self::getService(DefaultContextService::class);
        $id1 = $service->getId();
        $this->assertNotEmpty($id1);

        $service->reset();
        $id2 = $service->getId();
        $this->assertNotEmpty($id2);

        // 对于使用进程ID的情况，reset后ID应该保持一致
        // 因为进程ID不会改变，只有自生成的ID会被清空
        if (str_starts_with($id1, 'pid-')) {
            $this->assertEquals($id1, $id2);
        } else {
            // 如果是自生成的ID，reset后会重新生成，所以可能不同
            $this->assertIsString($id2);
        }
    }

    public function testDeferPushesCallbackToSubscriber(): void
    {
        $service = self::getService(DefaultContextService::class);
        $executed = false;

        // 测试 defer 功能：通过验证回调能被推迟执行来间接验证
        $service->defer(function () use (&$executed): void { $executed = true; });

        // defer 调用不应立即执行
        $this->assertFalse($executed);

        // 手动触发延迟执行
        $subscriber = self::getService(DeferCallSubscriber::class);
        $subscriber->executeDeferCalls();

        // 现在应该执行了
        $this->assertTrue($executed);
    }

    public function testSupportCoroutineReturnsFalse(): void
    {
        $service = self::getService(DefaultContextService::class);
        $this->assertFalse($service->supportCoroutine());
    }

    public function testMultipleDeferCallsAreExecutedInOrder(): void
    {
        $service = self::getService(DefaultContextService::class);
        $subscriber = self::getService(DeferCallSubscriber::class);
        $executed = [];

        // 添加多个defer调用
        $service->defer(function () use (&$executed): void { $executed[] = 'first'; });
        $service->defer(function () use (&$executed): void { $executed[] = 'second'; });
        $service->defer(function () use (&$executed): void { $executed[] = 'third'; });

        // 验证还未执行
        $this->assertEmpty($executed);

        // 手动触发执行
        $subscriber->executeDeferCalls();

        // 验证按顺序执行
        $this->assertEquals(['first', 'second', 'third'], $executed);
    }

    public function testGetIdIsConsistentAcrossMultipleCalls(): void
    {
        $service = self::getService(DefaultContextService::class);
        $id1 = $service->getId();
        $id2 = $service->getId();
        $id3 = $service->getId();

        $this->assertEquals($id1, $id2);
        $this->assertEquals($id2, $id3);
    }
}
