<?php

declare(strict_types=1);

namespace palPalani\GrumPhpRectorTask;

use GrumPHP\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ExtensionLoader implements ExtensionInterface
{
    /**
     * @param  ContainerBuilder  $container
     */
    public function load(ContainerBuilder $container): void
    {
        $container->register('task.rector', RectorTask::class)
            ->addArgument(new Reference('process_builder'))
            ->addArgument(new Reference('formatter.raw_process'))
            ->addTag('grumphp.task', ['task' => 'rector']);
    }
}
