<?php

namespace App\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ProcessCollectionCommandTest extends KernelTestCase
{
    public function setUp(): void
    {
        register_shutdown_function(static function () {
            $files = glob('/app/tests/Command/fixture/processed/*.json');

            foreach ($files as $file) {
                unlink($file);
            }
        });
    }

    public function testExecute(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('app:process-collection');
        $commandTester = new CommandTester($command);

        $commandTester->setInputs(['/app/tests/data/messages.json']);
        $commandTester->execute(['command' => $command->getName()]);


        $commandTester->assertCommandIsSuccessful();

        $output = $commandTester->getDisplay();

        $this->assertStringContainsString('Processing file: /app/tests/data/messages.json', $output);
        $this->assertStringContainsString('File processed successfully', $output);

        $this->assertStringContainsString('2', $output);
        $this->assertStringContainsString('0', $output);
    }

    public function testExecuteWithInvalidFile(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('app:process-collection');
        $commandTester = new CommandTester($command);

        $commandTester->setInputs(['/app/tests/data/messages_invalid.json']);
        $commandTester->execute(['command' => $command->getName()]);

        $output = $commandTester->getDisplay();

        $this->assertStringContainsString('Processing file: /app/tests/data/messages_invalid.json', $output);
        $this->assertStringContainsString('Error reading file: /app/tests/data/messages_invalid.json', $output);
    }
}
