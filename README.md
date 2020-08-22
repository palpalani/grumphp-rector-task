# grumphp-rector-task

RectorPHP task runner form GrumPHP

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


### Composer

``composer require --dev palpalani/grumphp-rector-task``
