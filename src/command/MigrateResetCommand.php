<?php

namespace warm\command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * 重置迁移命令
 * 
 * 回滚所有数据库迁移
 * 使用方式: php webman migrate:reset
 */
class MigrateResetCommand extends BaseCommand
{
    protected static string $defaultName = 'migrate:reset';
    protected static string $defaultDescription = 'Rollback all database migrations';

    protected function configure(): void
    {
        $this->addOption('database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use', 'default')
            ->addOption('force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production')
            ->addOption('path', null, InputOption::VALUE_OPTIONAL, 'The path to the migrations files to be executed')
            ->addOption('pretend', null, InputOption::VALUE_NONE, 'Dump the SQL queries that would be run');
    }

    public function handle(InputInterface $input, OutputInterface $output): int
    {
        // 获取数据库连接名称
        $database = $this->getDatabaseConnection();
        
        // 创建迁移器实例（复用当前数据库连接）
        $migrator = $this->createMigrator($database);

        $path = $this->option('path');
        if (!$path) {
            $path = database_path('migrations');
        } else {
            $path = str_starts_with($path, '/') ? $path : base_path($path);
        }

        $migrator->reset([$path], $this->option('pretend'));

        foreach ($migrator->getNotes() as $note) {
            $this->line($note);
        }

        return self::SUCCESS;
    }

    /**
     * 获取数据库连接名称
     * 
     * @return string
     */
    protected function getDatabaseConnection(): string
    {
        $database = $this->option('database');
        
        // 如果指定的是 'default'，则从配置中获取实际连接名
        if ($database === 'default') {
            $database = config('database.default', 'mysql');
        }
        
        return $database;
    }
}

