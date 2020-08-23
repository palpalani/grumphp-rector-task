# GrumPHP rector task

[RectorPHP](https://github.com/rectorphp/rector) task runner for [GrumPHP](https://github.com/phpro/grumphp).

### grumphp.yml:

````yml
parameters:
    tasks:
        rector:
            whitelist_patterns:  [],
            clear-cache: false,
            config: null,
            level: null,
            triggered_by:  [php],
            ignore_patterns: []
    extensions:
        - palPalani\GrumPhpRectorTask\ExtensionLoader
````

### Install via Composer

``composer require --dev palpalani/grumphp-rector-task``

### Sample RectorPhp configuration

Create `rector.php` in your project root and configure as follows.

```php
<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Set\ValueObject\SetList;
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

    $parameters->set(Option::PATHS, [__DIR__.'/app', __DIR__.'/tests']);
};
```

Please [RectorPhp](https://github.com/rectorphp/rector#features) for more configuration examples.
