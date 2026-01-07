<?php

namespace warm\command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * 迁移状态命令
 * 
 * 显示迁移状态
 * 使用方式: php webman migrate:status
 */
class MigrateStatusCommand extends BaseCommand
{
    protected static string $defaultName = 'migrate:status';
    protected static string $defaultDescription = 'Show the status of each migration';

    protected function configure(): void
    {
        $this->addOption('database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use', 'default')
            ->addOption('path', null, InputOption::VALUE_OPTIONAL, 'The path to the migrations files to be executed');
    }

    public function handle(InputInterface $input, OutputInterface $output): int
    {
        // 获取数据库连接名称
        $database = $this->getDatabaseConnection();
        
        // 创建迁移器实例（复用当前数据库连接）
        $migrator = $this->createMigrator($database);
        $repository = $this->createMigrationRepository($database);

        if (!$repository->repositoryExists()) {
            $this->error('No migrations found.');
            return self::FAILURE;
        }

        $path = $this->option('path');
        if (!$path) {
            $path = database_path('migrations');
        } else {
            $path = str_starts_with($path, '/') ? $path : base_path($path);
        }

        $ran = $repository->getRan();
        $batches = $repository->getMigrationBatches();
        $files = $migrator->getMigrationFiles([$path]);

        if (count($files) === 0) {
            $this->error('No migrations found.');
            return self::FAILURE;
        }

        $this->newLine();
        $this->line('<fg=gray>Migration status:</fg>');
        $this->newLine();

        $table = [];
        foreach ($files as $file) {
            $migration = $migrator->getMigrationName($file);
            $status = in_array($migration, $ran) ? '<fg=green>Ran</fg>' : '<fg=yellow>Pending</fg>';
            $batch = $batches[$migration] ?? null;
            $table[] = [$status, $migration, $batch ? "Batch {$batch}" : ''];
        }

        $this->table(['Status', 'Migration', 'Batch'], $table);

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

    protected function newLine(): void
    {
        $this->output->writeln('');
    }

    protected function table(array $headers, array $rows): void
    {
        $table = new \Symfony\Component\Console\Helper\Table($this->output);
        $table->setHeaders($headers);
        $table->setRows($rows);
        $table->render();
    }
}

