<?php

declare(strict_types=1);

namespace Yiisoft\Yii\Db\Migration\Tests;

use Symfony\Component\Console\Tester\CommandTester;
use Yiisoft\Yii\Console\ExitCode;

final class DownCommandTest extends TestCase
{
    public function testExecute(): void
    {
        $create = $this->application->find('migrate/down');

        $commandDown = new CommandTester($create);

        $commandDown->setInputs(['yes']);

        $this->assertEquals(ExitCode::OK, $commandDown->execute([]));

        $output = $commandDown->getDisplay(true);

        $this->assertStringContainsString('>>> 2 migrations were reverted.', $output);

        $this->assertEmpty($this->db->getSchema()->getTableSchema('department'));
        $this->assertEmpty($this->db->getSchema()->getTableSchema('student'));
    }

    public function testExecuteAgain(): void
    {
        $create = $this->application->find('migrate/down');

        $commandDown = new CommandTester($create);

        $this->assertEquals(ExitCode::UNSPECIFIED_ERROR, $commandDown->execute([]));

        $output = $commandDown->getDisplay(true);

        $this->assertStringContainsString('>>> Apply a new migration to run this command.', $output);
    }
}