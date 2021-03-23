# GrumPHP rector task

This package extends [GrumPHP](https://github.com/phpro/grumphp) 
with a task that runs [RectorPHP](https://github.com/rectorphp/rector) for
your Laravel projects or any PHP application.

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
    tasks:
        rector:
            whitelist_patterns: []
            config: 'rector.php'
            triggered_by: ['php']
            clear-cache: false            
            ignore_patterns: []
            no-progress-bar: false
            files_on_pre_commit: false
    extensions:
        - palPalani\GrumPhpRectorTask\ExtensionLoader
````

By default, this won't update your code, you need to do it manually.

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

#### files_on_pre_commit

`Default: false`

This option makes it possible to use the changed files as paths during pre-commits. It will use the paths option to make sure only committed files that match the path are validated.

### Sample RectorPhp configuration

Create `rector.php` in your project root and configure as follows. This example file iam using for my [Laravel](https://laravel.com/) project, but you can use library with any [PHP](https://www.php.net/) project. Also you no need to set all these settings, Please add or remove as per your requirements.

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
        //SetList::ARRAY_STR_FUNCTIONS_TO_STATIC_CALL,
        SetList::CODE_QUALITY,
        //SetList::PHP_53,
        //SetList::PHP_54,
        //SetList::PHP_56,
        //SetList::PHP_70,
        //SetList::PHP_71,
        //SetList::PHP_72,
        SetList::PHP_73,
        SetList::PHP_74,
        //SetList::PHP_80,
        SetList::PHPSTAN,
        //SetList::PHPUNIT_CODE_QUALITY,
        SetList::SOLID,
    ]);
    
    /*
    // is there single rule you don't like from a set you use?
    $parameters->set(Option::EXCLUDE_RECTORS, [
        SimplifyIfReturnBoolRector::class,
    ]);
    */

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
    
    // auto import fully qualified class names? [default: false]
    //$parameters->set(Option::AUTO_IMPORT_NAMES, true);

    // skip root namespace classes, like \DateTime or \Exception [default: true]
    //$parameters->set(Option::IMPORT_SHORT_CLASSES, false);
    
    // Run Rector only on changed files
    //$parameters->set(Option::ENABLE_CACHE, true);
};
```

Please visit [RectorPhp](https://github.com/rectorphp/rector#features) for more configuration examples.

## Uninstall

If you want to uninstall this library remove configuration files first: `rector.php` from your application.

then remove package:

    ``composer remove palpalani/grumphp-rector-task``

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/palpalani/grumphp-rector-task/tags).

## Credits

- [palPalani](https://github.com/palpalani)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Todo
- Add memory restriction
- Add more tests
- xdebug
