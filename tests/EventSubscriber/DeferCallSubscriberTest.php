<?php

declare(strict_types=1);

namespace Tourze\Symfony\RuntimeContextBundle\Tests\EventSubscriber;

use PHPUnit\Framework\TestCase;
use Tourze\Symfony\RuntimeContextBundle\EventSubscriber\DeferCallSubscriber;

class DeferCallSubscriberTest extends TestCase
{
    public function testAddAndExecuteDeferCalls()
    {
        $subscriber = new DeferCallSubscriber();
        $executed = [];
        $subscriber->addDeferCall(function () use (&$executed) { $executed[] = 'a'; });
        $subscriber->addDeferCall(function () use (&$executed) { $executed[] = 'b'; });
        $subscriber->executeDeferCalls();
        $this->assertEquals(['a', 'b'], $executed);
    }

    public function testExceptionInDeferCallIsSwallowed()
    {
        $subscriber = new DeferCallSubscriber();
        $executed = false;
        $subscriber->addDeferCall(function () { throw new \RuntimeException('fail'); });
        $subscriber->addDeferCall(function () use (&$executed) { $executed = true; });
        $subscriber->executeDeferCalls();
        $this->assertTrue($executed);
    }
}
