<?php

namespace Tourze\Symfony\RuntimeContextBundle\Service;

use Symfony\Contracts\Service\ResetInterface;

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
