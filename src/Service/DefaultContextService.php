<?php

namespace Tourze\Symfony\RuntimeContextBundle\Service;

use Symfony\Component\DependencyInjection\Attribute\AsAlias;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\KernelEvents;
use Tourze\Symfony\RuntimeContextBundle\EventSubscriber\DeferCallSubscriber;

#[Autoconfigure(public: true)]
#[AsAlias(id: ContextServiceInterface::class)]
class DefaultContextService implements ContextServiceInterface
{
    private static int $maxId = 0;

    private string $id = '';

    public function __construct(
        private readonly DeferCallSubscriber $deferCallSubscriber,
    ) {
    }

    #[AsEventListener(event: KernelEvents::TERMINATE, priority: -11000)]
    public function reset(): void
    {
        $this->id = '';
    }

    public function getId(): string
    {
        // 默认是获取进程id来作为上下文id
        // 失败的话再自己生成
        $id = getmypid();
        if (false === $id) {
            if ('' === $this->id) {
                $this->id = $this->generateUniqueId();
            }

            return $this->id;
        }

        return "pid-{$id}";
    }

    private function generateUniqueId(): string
    {
        // 使用时间戳和自增的唯一标识符
        ++self::$maxId;

        return uniqid('', true) . '-' . self::$maxId;
    }

    public function defer(callable $callback): void
    {
        // 默认情况，可能是FPM，此时我们不要马上执行咯
        $this->deferCallSubscriber->addDeferCall($callback);
    }

    public function supportCoroutine(): bool
    {
        return false;
    }
}
