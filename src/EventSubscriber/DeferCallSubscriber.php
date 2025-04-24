<?php

namespace Tourze\Symfony\RuntimeContextBundle\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\KernelEvents;
use Tourze\BacktraceHelper\ExceptionPrinter;

class DeferCallSubscriber
{
    /**
     * @var array 延迟执行的所有信息
     */
    private array $deferCalls = [];

    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    public function addDeferCall(callable $callback): void
    {
        $this->deferCalls[] = $callback;
    }

    /**
     * 在各个时机，尝试执行逻辑
     */
    #[AsEventListener(event: KernelEvents::FINISH_REQUEST)]
    #[AsEventListener(event: KernelEvents::TERMINATE)]
    #[AsEventListener(event: KernelEvents::EXCEPTION)]
    #[AsEventListener(event: ConsoleEvents::TERMINATE)]
    #[AsEventListener(event: ConsoleEvents::ERROR)]
    public function executeDeferCalls(): void
    {
        while (!empty($this->deferCalls)) {
            $func = array_shift($this->deferCalls);
            try {
                call_user_func($func);
            } catch (\Throwable $exception) {
                // 这里抛出异常是不对的，我们不处理
                $this->logger->error('延迟执行逻辑发生异常', [
                    'exception' => ExceptionPrinter::exception($exception),
                ]);
            }
        }
    }
}
