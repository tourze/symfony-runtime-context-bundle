# Symfony Runtime Context Bundle

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
