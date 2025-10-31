# Symfony Runtime Context Bundle

[English](README.md) | [中文](README.zh-CN.md)

[![Latest Version](https://img.shields.io/packagist/v/tourze/symfony-runtime-context-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/symfony-runtime-context-bundle)
[![Total Downloads](https://img.shields.io/packagist/dt/tourze/symfony-runtime-context-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/symfony-runtime-context-bundle)
[![PHP Version Require](https://img.shields.io/packagist/php-v/tourze/symfony-runtime-context-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/symfony-runtime-context-bundle)
[![License](https://img.shields.io/packagist/l/tourze/symfony-runtime-context-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/symfony-runtime-context-bundle)
[![Coverage Status](https://img.shields.io/codecov/c/github/tourze/symfony-runtime-context-bundle.svg?style=flat-square)](https://codecov.io/gh/tourze/symfony-runtime-context-bundle)

> 上下文管理模块，适用于 Symfony 应用，支持延迟执行任务和上下文唯一标识管理。

## 功能特性

- 获取当前运行上下文唯一 ID
- 支持延迟执行（defer）任务
- 提供 Reset 机制，兼容 Symfony 生命周期
- 可扩展，适配不同上下文（如 FPM、CLI）

## 安装说明

- 依赖 PHP 8.1 及以上版本
- 依赖 Symfony 6.4 及以上核心组件
- 使用 Composer 安装：

```bash
composer require tourze/symfony-runtime-context-bundle
```

## 快速开始

1. 在 `config/bundles.php` 注册 Bundle：

   ```php
   return [
       Tourze\Symfony\RuntimeContextBundle\RuntimeContextBundle::class => ['all' => true],
   ];
   ```

2. 注入 `ContextServiceInterface` 使用：

   ```php
   use Tourze\Symfony\RuntimeContextBundle\Service\ContextServiceInterface;

   public function index(ContextServiceInterface $contextService)
   {
       $id = $contextService->getId();
       $contextService->defer(function () {
           // 延迟执行任务
       });
   }
   ```

## 详细文档

- `ContextServiceInterface`：定义上下文服务的核心接口，包括获取唯一 ID、延迟执行、是否支持协程等
- `DefaultContextService`：默认实现，基于进程 ID 或唯一生成 ID
- `DeferCallSubscriber`：事件订阅者，负责在生命周期结束时统一执行所有延迟任务

## 贡献指南

- 欢迎提交 Issue 和 PR
- 遵循 PSR-12 代码风格
- 提交前请确保通过 PHPUnit 测试

## 版权和许可

- MIT License
- (c) tourze

## 更新日志

- 详见 CHANGELOG.md 或提交历史
