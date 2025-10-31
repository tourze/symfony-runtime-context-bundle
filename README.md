# Symfony Runtime Context Bundle

[English](README.md) | [中文](README.zh-CN.md)

[![Latest Version](https://img.shields.io/packagist/v/tourze/symfony-runtime-context-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/symfony-runtime-context-bundle)
[![Total Downloads](https://img.shields.io/packagist/dt/tourze/symfony-runtime-context-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/symfony-runtime-context-bundle)
[![PHP Version Require](https://img.shields.io/packagist/php-v/tourze/symfony-runtime-context-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/symfony-runtime-context-bundle)
[![License](https://img.shields.io/packagist/l/tourze/symfony-runtime-context-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/symfony-runtime-context-bundle)
[![Coverage Status](https://img.shields.io/codecov/c/github/tourze/symfony-runtime-context-bundle.svg?style=flat-square)](https://codecov.io/gh/tourze/symfony-runtime-context-bundle)

> Context management module for Symfony applications, supporting deferred task execution and unique context identification.

## Features

- Obtain a unique ID for the current runtime context
- Support for deferred (defer) task execution
- Reset mechanism compatible with Symfony lifecycle
- Extensible for different contexts (e.g., FPM, CLI)

## Installation

- Requires PHP 8.1+
- Requires Symfony 6.4+ core components
- Install via Composer:

```bash
composer require tourze/symfony-runtime-context-bundle
```

## Quick Start

1. Register the bundle in `config/bundles.php`:

   ```php
   return [
       Tourze\Symfony\RuntimeContextBundle\RuntimeContextBundle::class => ['all' => true],
   ];
   ```

2. Inject and use `ContextServiceInterface`:

   ```php
   use Tourze\Symfony\RuntimeContextBundle\Service\ContextServiceInterface;

   public function index(ContextServiceInterface $contextService)
   {
       $id = $contextService->getId();
       $contextService->defer(function () {
           // Deferred task
       });
   }
   ```

## Documentation

- `ContextServiceInterface`: Defines the core context service interface, including unique ID retrieval, deferred execution, and coroutine support
- `DefaultContextService`: Default implementation based on process ID or generated unique ID
- `DeferCallSubscriber`: Event subscriber responsible for executing all deferred tasks at the end of the lifecycle

## Contributing

- Issues and PRs are welcome
- Follow PSR-12 code style
- Please ensure PHPUnit tests pass before submitting

## License

- MIT License
- (c) tourze

## Changelog

- See CHANGELOG.md or commit history for details
