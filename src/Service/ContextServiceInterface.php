<?php

namespace Tourze\Symfony\RuntimeContextBundle\Service;

use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Symfony\Contracts\Service\ResetInterface;

#[Autoconfigure(public: true)]
interface ContextServiceInterface extends ResetInterface
{
    /**
     * 获取上下文唯一ID
     */
    public function getId(): string;

    /**
     * 延迟执行
     */
    public function defer(callable $callback): void;

    /**
     * 是否支持协程
     */
    public function supportCoroutine(): bool;
}
