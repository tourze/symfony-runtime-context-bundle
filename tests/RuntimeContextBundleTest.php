<?php

declare(strict_types=1);

namespace Tourze\Symfony\RuntimeContextBundle\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractBundleTestCase;
use Tourze\Symfony\RuntimeContextBundle\RuntimeContextBundle;

/**
 * @internal
 */
#[CoversClass(RuntimeContextBundle::class)]
#[RunTestsInSeparateProcesses]
final class RuntimeContextBundleTest extends AbstractBundleTestCase
{
}
