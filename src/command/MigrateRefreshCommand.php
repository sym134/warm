<?php

namespace warm\command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use warm\command\MigrateCommand;
use warm\command\MigrateResetCommand;

/**
 * 刷新迁移命令
 * 
 * 回滚所有迁移并重新运行它们
 * 使用方式: php webman migrate:refresh
 */
class MigrateRefreshCommand extends BaseCommand
{
    protected static string $defaultName = 'migrate:refresh';
    protected static string $defaultDescription = 'Reset and re-run all migrations';

    protected function configure(): void
    {
        $this->addOption('database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use', 'default')
            ->addOption('force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production')
            ->addOption('path', null, InputOption::VALUE_OPTIONAL, 'The path to the migrations files to be executed')
            ->addOption('seed', null, InputOption::VALUE_NONE, 'Indicates if the seed task should be re-run')
            ->addOption('seeder', null, InputOption::VALUE_OPTIONAL, 'The class name of the root seeder');
    }

    public function handle(InputInterface $input, OutputInterface $output): int
    {
        // 回滚所有迁移
        $resetCommand = new MigrateResetCommand();
        $resetResult = $resetCommand->run($input, $output);

        if ($resetResult !== self::SUCCESS) {
            return $resetResult;
        }

        // 重新运行迁移
        $migrateCommand = new MigrateCommand();
        return $migrateCommand->run($input, $output);
    }
}

