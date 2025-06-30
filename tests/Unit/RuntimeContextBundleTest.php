<?php

declare(strict_types=1);

namespace Tourze\Symfony\RuntimeContextBundle\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tourze\Symfony\RuntimeContextBundle\RuntimeContextBundle;

class RuntimeContextBundleTest extends TestCase
{
    public function testBundleCanBeInstantiated(): void
    {
        $bundle = new RuntimeContextBundle();

        $this->assertInstanceOf(Bundle::class, $bundle);
        $this->assertInstanceOf(RuntimeContextBundle::class, $bundle);
    }

    public function testBundleHasCorrectName(): void
    {
        $bundle = new RuntimeContextBundle();
        
        $this->assertSame('RuntimeContextBundle', $bundle->getName());
    }
}