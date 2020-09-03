<?php

declare(strict_types=1);

namespace Yiisoft\Yii\Db\Migration\Tests\Paths;

use Symfony\Component\Console\Tester\CommandTester;
use Yiisoft\Yii\Console\ExitCode;
use Yiisoft\Yii\Db\Migration\Tests\TestCase;

use function explode;
use function trim;

/**
 * @group paths
 */
final class NewCommandTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        /** Set list path for update migration */
        $this->migrationService->updatePath([$this->getMigrationFolder()]);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    public function testExecute(): void
    {
        $command = $this->application->find('migrate/new');

        $commandNew = new CommandTester($command);
        $this->assertEquals(ExitCode::OK, $commandNew->execute([]));

        $output = $commandNew->getDisplay(true);

        $words = explode("\n", $output);

        $this->assertFileExists($this->getMigrationFolder() . trim($words[0]) . '.php');
        $this->assertFileExists($this->getMigrationFolder() . trim($words[1]) . '.php');
    }
}
