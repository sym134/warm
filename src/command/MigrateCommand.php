<?php

namespace warm\command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Illuminate\Database\Migrations\Migrator;

/**
 * 数据库迁移命令
 * 
 * 执行数据库迁移，类似 Laravel 的 php artisan migrate
 * 使用方式: php webman migrate
 */
class MigrateCommand extends BaseCommand
{
    protected static string $defaultName = 'migrate';
    protected static string $defaultDescription = 'Run the database migrations';

    protected function configure(): void
    {
        $this->addOption('database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use', 'default')
            ->addOption('force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production')
            ->addOption('path', null, InputOption::VALUE_OPTIONAL, 'The path to the migrations files to be executed')
            ->addOption('pretend', null, InputOption::VALUE_NONE, 'Dump the SQL queries that would be run')
            ->addOption('step', null, InputOption::VALUE_NONE, 'Force the migrations to be run so they can be rolled back individually');
    }

    public function handle(InputInterface $input, OutputInterface $output): int
    {
        // 获取数据库连接名称
        $database = $this->getDatabaseConnection();
        
        // 创建迁移器实例（复用当前数据库连接）
        $migrator = $this->createMigrator($database);
        $repository = $this->createMigrationRepository($database);

        $this->prepareDatabase($database);

        // 获取迁移路径
        $path = $this->option('path');
        if (!$path) {
            $path = database_path('migrations');
        } else {
            $path = str_starts_with($path, '/') ? $path : base_path($path);
        }

        // 设置输出接口（新版本的 Migrator 使用 setOutput 而不是 getNotes）
        $migrator->setOutput($output);
        
        // 检查是否有待执行的迁移
        $files = $migrator->getMigrationFiles([$path]);
        $ran = $repository->getRan();
        
        // 将文件路径转换为迁移名称进行比较
        $pending = [];
        foreach ($files as $file) {
            $migration = $migrator->getMigrationName($file);
            if (!in_array($migration, $ran)) {
                $pending[] = $file;
            }
        }
        
        if (empty($pending)) {
            $this->info('Nothing to migrate.');
            return self::SUCCESS;
        }
        
        // 显示将要执行的迁移
        $this->info('Running migrations...');
        foreach ($pending as $file) {
            $migrationName = $migrator->getMigrationName($file);
            $this->line("  <fg=cyan>-</> {$migrationName}");
        }
        $this->line('');
        
        // 执行迁移
        $migrator->run([$path], [
            'pretend' => $this->option('pretend'),
            'step' => $this->option('step'),
        ]);
        
        // 显示完成信息
        $this->line('');
        $this->info('Migration completed successfully.');

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

    /**
     * 准备数据库迁移表
     */
    protected function prepareDatabase(string $database): void
    {
        $repository = $this->createMigrationRepository($database);
        
        if (!$repository->repositoryExists()) {
            $command = new \warm\command\MigrateInstallCommand();
            $command->setApplication($this->getApplication());
            $command->run(new \Symfony\Component\Console\Input\ArrayInput(['--database' => $database]), $this->output);
        }
    }
}

