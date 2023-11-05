<?php

namespace App\Provider;

use Symfony\Component\Finder\Finder;

final readonly class SourceFileProvider implements ProviderInterface
{
    public function __construct(
        private string $projectDir,
        private string $sourceDir,
    )
    {
    }

    public function provide(): array
    {
        $filesPath = $this->projectDir . '/' . $this->sourceDir;

        $finder = new Finder();
        $finder->files()->in($filesPath)->depth(0);

        return iterator_to_array($finder);
    }
}
