<?php

declare(strict_types=1);

namespace Tourze\Symfony\RuntimeContextBundle\Tests\Unit\Exception;

use PHPUnit\Framework\TestCase;
use Tourze\Symfony\RuntimeContextBundle\Exception\DeferCallExecutionException;

class DeferCallExecutionExceptionTest extends TestCase
{
    public function testExceptionCanBeCreated(): void
    {
        $message = 'Test exception message';
        $exception = new DeferCallExecutionException($message);

        $this->assertInstanceOf(\RuntimeException::class, $exception);
        $this->assertSame($message, $exception->getMessage());
    }

    public function testExceptionCanBeCreatedWithCode(): void
    {
        $message = 'Test exception message';
        $code = 123;
        $exception = new DeferCallExecutionException($message, $code);

        $this->assertSame($message, $exception->getMessage());
        $this->assertSame($code, $exception->getCode());
    }

    public function testExceptionCanBeCreatedWithPrevious(): void
    {
        $previousException = new \Exception('Previous exception');
        $message = 'Test exception message';
        $exception = new DeferCallExecutionException($message, 0, $previousException);

        $this->assertSame($message, $exception->getMessage());
        $this->assertSame($previousException, $exception->getPrevious());
    }
}