<?php

declare(strict_types=1);

namespace Tourze\Symfony\RuntimeContextBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Tourze\Symfony\RuntimeContextBundle\DependencyInjection\RuntimeContextExtension;
use Tourze\Symfony\RuntimeContextBundle\EventSubscriber\DeferCallSubscriber;
use Tourze\Symfony\RuntimeContextBundle\Service\ContextServiceInterface;
use Tourze\Symfony\RuntimeContextBundle\Service\DefaultContextService;

class RuntimeContextExtensionTest extends TestCase
{
    public function testServicesAreLoaded(): void
    {
        $container = new ContainerBuilder();
        $extension = new RuntimeContextExtension();
        
        // 测试扩展可以正常加载配置，不抛出异常
        $extension->load([], $container);
        
        // 验证服务定义文件已被加载
        // 检查是否有从 services.yaml 加载的定义
        $definitions = $container->getDefinitions();
        $this->assertNotEmpty($definitions);
        
        // 验证至少存在一些服务定义或别名
        $hasServiceDefinitions = false;
        foreach ($definitions as $id => $definition) {
            if (str_contains($id, 'Tourze\\Symfony\\RuntimeContextBundle\\')) {
                $hasServiceDefinitions = true;
                break;
            }
        }
        
        // 如果没有找到定义，检查别名
        if (!$hasServiceDefinitions) {
            $aliases = $container->getAliases();
            foreach ($aliases as $alias => $id) {
                if (str_contains($alias, 'Tourze\\Symfony\\RuntimeContextBundle\\')) {
                    $hasServiceDefinitions = true;
                    break;
                }
            }
        }
        
        $this->assertTrue($hasServiceDefinitions, 'Extension should load service definitions from services.yaml');
    }
}