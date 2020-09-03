<?php

declare(strict_types=1);

namespace Yiisoft\Yii\Db\Migration\Tests\Namespaces;

use Symfony\Component\Console\Tester\CommandTester;
use Yiisoft\Yii\Console\ExitCode;
use Yiisoft\Yii\Db\Migration\Tests\TestCase;

/**
 * @group namespaces
 */
final class DownCommandTest extends TestCase
{
    private string $namespace = 'Yiisoft\\Yii\Db\\Migration\\Tests\\NamespaceMigrationGenerated';

    protected function setUp(): void
    {
        parent::setUp();

        /** Set namespace for generate migration */
        $this->migrationService->updateNamespace([$this->namespace]);
        $this->migrationService->updatePath([$this->getMigrationFolder()]);
        $this->migrateUp();
    }

    public function testExecute(): void
    {
        $create = $this->application->find('migrate/down');

        $commandDown = new CommandTester($create);

        $commandDown->setInputs(['yes']);

        $this->assertEquals(ExitCode::OK, $commandDown->execute([]));

        $output = $commandDown->getDisplay(true);

        $this->assertStringContainsString('2 migrations were reverted.', $output);

        $this->assertEmpty($this->db->getSchema()->getTableSchema('department'));
        $this->assertEmpty($this->db->getSchema()->getTableSchema('student'));
    }

    public function testExecuteAgain(): void
    {
        $create = $this->application->find('migrate/down');

        $commandDown = new CommandTester($create);

        $commandDown->execute([]);
        $this->assertEquals(ExitCode::UNSPECIFIED_ERROR, $commandDown->execute([]));

        $output = $commandDown->getDisplay(true);

        $this->assertStringContainsString('Apply a new migration to run this command.', $output);
    }
}
