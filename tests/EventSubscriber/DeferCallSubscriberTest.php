<?php

declare(strict_types=1);

namespace Tourze\Symfony\RuntimeContextBundle\Tests\EventSubscriber;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Tourze\Symfony\RuntimeContextBundle\EventSubscriber\DeferCallSubscriber;
use Tourze\Symfony\RuntimeContextBundle\Exception\DeferCallExecutionException;

class DeferCallSubscriberTest extends TestCase
{
    public function testAddAndExecuteDeferCalls()
    {
        $logger = $this->createMock(LoggerInterface::class);
        $subscriber = new DeferCallSubscriber($logger);
        $executed = [];
        $subscriber->addDeferCall(function () use (&$executed) { $executed[] = 'a'; });
        $subscriber->addDeferCall(function () use (&$executed) { $executed[] = 'b'; });
        $subscriber->executeDeferCalls();
        $this->assertEquals(['a', 'b'], $executed);
    }

    public function testExceptionInDeferCallIsSwallowed()
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('error');

        $subscriber = new DeferCallSubscriber($logger);
        $executed = false;
        $subscriber->addDeferCall(function () { throw new DeferCallExecutionException('fail'); });
        $subscriber->addDeferCall(function () use (&$executed) { $executed = true; });
        $subscriber->executeDeferCalls();
        $this->assertTrue($executed);
    }
}
