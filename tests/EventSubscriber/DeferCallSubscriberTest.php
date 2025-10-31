<?php

declare(strict_types=1);

namespace Tourze\Symfony\RuntimeContextBundle\Tests\EventSubscriber;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractEventSubscriberTestCase;
use Tourze\Symfony\RuntimeContextBundle\EventSubscriber\DeferCallSubscriber;
use Tourze\Symfony\RuntimeContextBundle\Exception\DeferCallExecutionException;

/**
 * @internal
 */
#[CoversClass(DeferCallSubscriber::class)]
#[RunTestsInSeparateProcesses]
final class DeferCallSubscriberTest extends AbstractEventSubscriberTestCase
{
    protected function onSetUp(): void
    {
        // 父类方法调用已自动处理
    }

    public function testAddDeferCall(): void
    {
        $subscriber = self::getService(DeferCallSubscriber::class);
        $executed = false;

        // 测试添加延迟调用
        $subscriber->addDeferCall(function () use (&$executed): void { $executed = true; });

        // 验证回调未立即执行
        $this->assertFalse($executed);

        // 执行延迟调用
        $subscriber->executeDeferCalls();
        $this->assertTrue($executed);
    }

    public function testExecuteDeferCalls(): void
    {
        $subscriber = self::getService(DeferCallSubscriber::class);
        $executed = [];

        // 添加多个延迟调用
        $subscriber->addDeferCall(function () use (&$executed): void { $executed[] = 'first'; });
        $subscriber->addDeferCall(function () use (&$executed): void { $executed[] = 'second'; });

        // 执行所有延迟调用
        $subscriber->executeDeferCalls();

        // 验证按顺序执行
        $this->assertEquals(['first', 'second'], $executed);
    }

    public function testAddAndExecuteDeferCalls(): void
    {
        $subscriber = self::getService(DeferCallSubscriber::class);
        $executed = [];
        $subscriber->addDeferCall(function () use (&$executed): void { $executed[] = 'a'; });
        $subscriber->addDeferCall(function () use (&$executed): void { $executed[] = 'b'; });
        $subscriber->executeDeferCalls();
        $this->assertEquals(['a', 'b'], $executed);
    }

    public function testExceptionInDeferCallIsSwallowed(): void
    {
        $subscriber = self::getService(DeferCallSubscriber::class);
        $executed = false;
        $subscriber->addDeferCall(function (): void { throw new DeferCallExecutionException('fail'); });
        $subscriber->addDeferCall(function () use (&$executed): void { $executed = true; });
        $subscriber->executeDeferCalls();
        $this->assertTrue($executed);
    }

    public function testExecuteDeferCallsWithEmptyQueue(): void
    {
        $subscriber = self::getService(DeferCallSubscriber::class);

        // 在没有任何延迟调用的情况下执行
        $subscriber->executeDeferCalls();

        // 应该没有任何错误或异常
        $this->assertTrue(true); // 验证没有抛出异常
    }

    public function testExecuteDeferCallsMultipleTimes(): void
    {
        $subscriber = self::getService(DeferCallSubscriber::class);
        $executed = [];

        $subscriber->addDeferCall(function () use (&$executed): void { $executed[] = 'call1'; });
        $subscriber->addDeferCall(function () use (&$executed): void { $executed[] = 'call2'; });

        // 第一次执行
        $subscriber->executeDeferCalls();
        $this->assertEquals(['call1', 'call2'], $executed);

        // 再次执行应该没有任何调用
        $subscriber->executeDeferCalls();
        $this->assertEquals(['call1', 'call2'], $executed); // 应该保持不变
    }

    public function testDifferentTypesOfExceptionsAreSwallowed(): void
    {
        $subscriber = self::getService(DeferCallSubscriber::class);
        $executed = [];

        // 添加各种类型的异常
        $subscriber->addDeferCall(function (): void { throw new \RuntimeException('runtime error'); });
        $subscriber->addDeferCall(function () use (&$executed): void { $executed[] = 'after_runtime'; });
        $subscriber->addDeferCall(function (): void { throw new \InvalidArgumentException('invalid arg'); });
        $subscriber->addDeferCall(function () use (&$executed): void { $executed[] = 'after_invalid_arg'; });

        $subscriber->executeDeferCalls();

        // 验证即使有异常，后续调用仍然执行
        $this->assertEquals(['after_runtime', 'after_invalid_arg'], $executed);
    }
}
