<?php

declare(strict_types=1);

namespace Tourze\Symfony\RuntimeContextBundle\Tests\DependencyInjection;

use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tourze\PHPUnitSymfonyUnitTest\AbstractDependencyInjectionExtensionTestCase;
use Tourze\Symfony\RuntimeContextBundle\DependencyInjection\RuntimeContextExtension;

/**
 * @internal
 */
#[CoversClass(RuntimeContextExtension::class)]
final class RuntimeContextExtensionTest extends AbstractDependencyInjectionExtensionTestCase
{
    private ContainerBuilder $container;

    private RuntimeContextExtension $extension;

    protected function setUp(): void
    {
        parent::setUp();

        $this->container = new ContainerBuilder();
        $this->container->setParameter('kernel.environment', 'test');
        $this->extension = new RuntimeContextExtension();
    }

    protected function tearDown(): void
    {
        unset($this->container, $this->extension);
        parent::tearDown();
    }

    public function testLoad(): void
    {
        // 直接测试 load 方法，确保不会抛出异常
        $this->extension->load([], $this->container);

        // 验证配置文件已被正确加载，检查是否有服务定义被加载
        $definitions = $this->container->getDefinitions();
        $this->assertNotEmpty($definitions, 'Extension should load service definitions');
    }

    public function testServicesAreLoaded(): void
    {
        // 测试扩展可以正常加载配置，不抛出异常
        $this->extension->load([], $this->container);

        // 验证服务定义文件已被加载
        // 检查是否有从 services.yaml 加载的定义
        $definitions = $this->container->getDefinitions();
        $this->assertNotEmpty($definitions);

        // 验证至少存在一些服务定义或别名
        $hasServiceDefinitions = $this->hasServiceDefinitions($this->container);
        $this->assertTrue($hasServiceDefinitions, 'Extension should load service definitions from services.yaml');
    }

    private function hasServiceDefinitions(ContainerBuilder $container): bool
    {
        // 检查定义
        foreach ($container->getDefinitions() as $id => $definition) {
            if ($this->isRuntimeContextService($id)) {
                return true;
            }
        }

        // 检查别名
        foreach ($container->getAliases() as $alias => $id) {
            if ($this->isRuntimeContextService($alias)) {
                return true;
            }
        }

        return false;
    }

    private function isRuntimeContextService(string $id): bool
    {
        return str_contains($id, 'Tourze\Symfony\RuntimeContextBundle\\');
    }
}
