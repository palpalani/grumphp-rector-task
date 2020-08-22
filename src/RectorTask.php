<?php

namespace palPalani\GrumPhpRectorTask;

use GrumPHP\Runner\TaskResult;
use GrumPHP\Runner\TaskResultInterface;
use GrumPHP\Task\Context\ContextInterface;
use GrumPHP\Task\Context\GitPreCommitContext;
use GrumPHP\Task\Context\RunContext;
use GrumPHP\Task\AbstractExternalTask;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class RectorTask extends AbstractExternalTask
{
    public static function getConfigurableOptions(): OptionsResolver
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'whitelist_patterns' => [],
            'clear-cache' => false,
            //'no-progress-bar' => true,
            'config' => null,
            'level' => null,
            'triggered_by' => ['php'],
            'ignore_patterns' => [],
        ]);

        $resolver->addAllowedTypes('whitelist_patterns', ['array']);
        $resolver->addAllowedTypes('clear-cache', ['bool']);
        //$resolver->addAllowedTypes('no-progress-bar', ['bool']);
        $resolver->addAllowedTypes('config', ['null', 'string']);
        $resolver->addAllowedTypes('level', ['null', 'string']);
        $resolver->addAllowedTypes('triggered_by', ['array']);
        $resolver->addAllowedTypes('ignore_patterns', ['array']);

        return $resolver;
    }

    public function canRunInContext(ContextInterface $context): bool
    {
        return $context instanceof RunContext || $context instanceof GitPreCommitContext;
    }

    public function run(ContextInterface $context): TaskResultInterface
    {
        $config = $this->getConfig()->getOptions();

        $files = $context->getFiles()->extensions($config['triggered_by']);
        if (0 === \count($files)) {
            return TaskResult::createSkipped($this, $context);
        }

        $arguments = $this->processBuilder->createArgumentsForCommand('rector');
        //$arguments->add('check');
        //$arguments->add('--dry-run');

        foreach ($config['whitelist_patterns'] as $whitelistPattern) {
            $arguments->add($whitelistPattern);
        }

        $arguments->addOptionalArgument('--config=%s', $config['config']);
        $arguments->addOptionalArgument('--level=%s', $config['level']);
        $arguments->addOptionalArgument('--clear-cache', $config['clear-cache']);
        //$arguments->addOptionalArgument('--no-progress-bar', $config['no-progress-bar']);
        $arguments->addOptionalArgument('--ansi', true);
        //$arguments->addOptionalArgument('--no-interaction', true);

        $process = $this->processBuilder->buildProcess($arguments);
        $process->run();

        if (!$process->isSuccessful()) {
            return TaskResult::createFailed($this, $context, $this->formatter->format($process));
        }

        return TaskResult::createPassed($this, $context);
    }
}
