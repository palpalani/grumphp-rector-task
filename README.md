# GrumPHP rector task

This package extends [GrumPHP](https://github.com/phpro/grumphp) 
with a task that runs [RectorPHP](https://github.com/rectorphp/rector).

## Installation

The easiest way to install this package is through composer:

``composer require --dev palpalani/grumphp-rector-task``

### Config

Add the extension loader to your `grumphp.yml` or `grumphp.yml.dist`.
The task lives under the `rector` namespace and has following 
configurable parameters:

````yml
# grumphp.yml
grumphp:
    tesks:
        rector:
            whitelist_patterns: []
            config: 'rector.php'
            triggered_by: ['php']
            clear-cache: false            
            ignore_patterns: []
            no-progress-bar: false
    extensions:
        - palPalani\GrumPhpRectorTask\ExtensionLoader
````

#### whitelist_patterns

`Default: []`

If you want to run on particular directories only, specify it with this option.

#### config

`Default: 'rector.php'`

If you want to use a different config file than the default `rector.php`, you can specify your custom config file location with this option.

#### triggered_by

`Default: [php]`

This option will specify which file extensions will trigger this task.

#### clear-cache

`Default: false`

Clear cache for already checked files.

#### no-progress-bar

`Default: false`

Hide progress bar. Useful e.g. for nicer CI output.

### Sample RectorPhp configuration

Create `rector.php` in your project root and configure as follows.
Also you no need to set all these settings, Please add or remove as per your
requirements.

```php
<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Set\ValueObject\SetList;
use Rector\CodeQuality\Rector\If_\SimplifyIfReturnBoolRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Rector\Php74\Rector\Property\TypedPropertyRector;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    $services = $containerConfigurator->services();
    $services->set(TypedPropertyRector::class);

    $parameters->set(Option::SETS, [
        SetList::ACTION_INJECTION_TO_CONSTRUCTOR_INJECTION,
        SetList::ARRAY_STR_FUNCTIONS_TO_STATIC_CALL,
        SetList::CODE_QUALITY,
        SetList::PHP_53,
        SetList::PHP_54,
        SetList::PHP_56,
        SetList::PHP_70,
        SetList::PHP_71,
        SetList::PHP_72,
        SetList::PHP_73,
        SetList::PHP_74,
        SetList::PHPSTAN,
        SetList::PHPUNIT_CODE_QUALITY,
        SetList::SOLID,
    ]);
    
    // is there single rule you don't like from a set you use?
    $parameters->set(Option::EXCLUDE_RECTORS, [
        SimplifyIfReturnBoolRector::class,
    ]);

    // paths to refactor;
    $parameters->set(Option::PATHS, [__DIR__.'/app', __DIR__.'/tests']);
    
    // is there a file you need to skip?
    $parameters->set(Option::EXCLUDE_PATHS, [
        // single file
        __DIR__ . '/src/ComplicatedFile.php',
        // or directory
        __DIR__ . '/src/ComplicatedFile.php',
        // or fnmatch
        __DIR__ . '/src/*/Tests/*',
    ]);
};
```

Please visit [RectorPhp](https://github.com/rectorphp/rector#features) for more configuration examples.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Todo
- Run only changed files
- Add memory restriction
- Add more tests
