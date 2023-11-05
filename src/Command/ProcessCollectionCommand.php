<?php

namespace App\Command;

use App\Model\CollectionResultInterface;
use App\Provider\ProviderInterface;
use App\Provider\SourceFileProvider;
use App\Service\CollectionService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:process-collection',
    description: 'Process a file with a collection of failure and review reports.'
)]
class ProcessCollectionCommand extends Command
{
    public function __construct(
        private readonly ProviderInterface $fileProvider,
        private readonly CollectionService $collectionService,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $file = $this->selectFile($io);

        try {
            $io->info('Processing file: ' . $file);

            $result = $this->collectionService->processCollection($file);
        } catch (\Throwable $th) {
            $io->error($th->getMessage());

            return Command::FAILURE;
        }

        $this->drawTable($io, $result);

        $io->success('File processed successfully');
        return Command::SUCCESS;
    }

    private function selectFile(SymfonyStyle $io): string
    {
        $choices = array_reduce(
            $this->fileProvider->provide(),
            static fn(array $carry, \SplFileInfo $file) => array_merge($carry, [$file->getRealPath()]), []
        );

        $choice = new ChoiceQuestion(
            question: 'Please select a file to process',
            choices: $choices,
            default: $choices[0] ?? null,
        );

        return $io->askQuestion($choice);
    }

    private function drawTable(SymfonyStyle $io, CollectionResultInterface $result): void
    {
        $rows = [];

        $paths = $result->getPaths();

        foreach ($result->getCounters() as $type => $count) {
            $rows[] = [$type, $count, $paths[$type] ?? null];
        }

        $io->table(
            headers: ['Task type', 'Number', 'File path'],
            rows: $rows
        );
    }
}
