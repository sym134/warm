<?php

namespace warm\command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * 创建迁移表命令
 * 
 * 创建 migrations 表来记录迁移状态
 * 使用方式: php webman migrate:install
 */
class MigrateInstallCommand extends BaseCommand
{
    protected static string $defaultName = 'migrate:install';
    protected static string $defaultDescription = 'Create the migration repository';

    protected function configure(): void
    {
        $this->addOption('database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use', 'default');
    }

    public function handle(InputInterface $input, OutputInterface $output): int
    {
        // 获取数据库连接名称
        $database = $this->getDatabaseConnection();
        
        // 创建迁移仓库实例（复用当前数据库连接）
        $repository = $this->createMigrationRepository($database);
        $repository->createRepository();

        $this->info('Migration table created successfully.');

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

