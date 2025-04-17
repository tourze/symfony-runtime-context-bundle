<?php

namespace Tourze\Symfony\RuntimeContextBundle\EventSubscriber;

use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\KernelEvents;

class DeferCallSubscriber
{
    /**
     * @var array 延迟执行的所有信息
     */
    private array $deferCalls = [];

    public function addDeferCall(callable $callback): void
    {
        $this->deferCalls[] = $callback;
    }

    /**
     * 在各个时机，尝试执行逻辑
     */
    #[AsEventListener(event: KernelEvents::FINISH_REQUEST, priority: -1)]
    #[AsEventListener(event: KernelEvents::TERMINATE, priority: -1)]
    #[AsEventListener(event: KernelEvents::EXCEPTION, priority: -1)]
    #[AsEventListener(event: ConsoleEvents::TERMINATE, priority: -1)]
    #[AsEventListener(event: ConsoleEvents::ERROR, priority: -1)]
    public function executeDeferCalls(): void
    {
        while (!empty($this->deferCalls)) {
            $func = array_shift($this->deferCalls);
            try {
                call_user_func($func);
            } catch (\Throwable $exception) {
                // 这里抛出异常是不对的，我们不处理
            }
        }
    }
}
