<?php

namespace Lens\Bundle\KiyohBundle;

use Exception;
use Lens\Bundle\KiyohBundle\Command\UpdateKiyohStatisticsCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class KiyohCacheWarmer implements CacheWarmerInterface
{
    public function __construct(
        private readonly KernelInterface $kernel,
    ) {
    }

    public function isOptional(): bool
    {
        return true;
    }

    public function warmUp(string $cacheDir): array
    {
        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => UpdateKiyohStatisticsCommand::NAME,
        ]);

        try {
            $application->run($input, new NullOutput());
        } catch (Exception) {
        }

        return [];
    }
}
