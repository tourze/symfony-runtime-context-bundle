<?php

declare(strict_types=1);

namespace Tourze\Symfony\RuntimeContextBundle\Tests\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitBase\AbstractExceptionTestCase;
use Tourze\Symfony\RuntimeContextBundle\Exception\DeferCallExecutionException;

/**
 * @internal
 */
#[CoversClass(DeferCallExecutionException::class)]
final class DeferCallExecutionExceptionTest extends AbstractExceptionTestCase
{
    public function testCanCreateWithMessage(): void
    {
        $message = 'Test exception message';
        $exception = new DeferCallExecutionException($message);

        $this->assertInstanceOf(\RuntimeException::class, $exception);
        $this->assertSame($message, $exception->getMessage());
        $this->assertSame(0, $exception->getCode());
        $this->assertNull($exception->getPrevious());
    }

    public function testCanCreateWithMessageAndCode(): void
    {
        $message = 'Test exception message';
        $code = 123;
        $exception = new DeferCallExecutionException($message, $code);

        $this->assertInstanceOf(\RuntimeException::class, $exception);
        $this->assertSame($message, $exception->getMessage());
        $this->assertSame($code, $exception->getCode());
        $this->assertNull($exception->getPrevious());
    }

    public function testCanCreateWithMessageCodeAndPrevious(): void
    {
        $previousException = new \Exception('Previous exception');
        $message = 'Test exception message';
        $code = 456;
        $exception = new DeferCallExecutionException($message, $code, $previousException);

        $this->assertInstanceOf(\RuntimeException::class, $exception);
        $this->assertSame($message, $exception->getMessage());
        $this->assertSame($code, $exception->getCode());
        $this->assertSame($previousException, $exception->getPrevious());
    }
}
