<?php

namespace warm\command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Illuminate\Database\Migrations\MigrationCreator;

/**
 * 创建迁移文件命令
 * 
 * 创建新的数据库迁移文件
 * 使用方式: php webman make:migration create_users_table
 */
class MigrateMakeCommand extends BaseCommand
{
    protected static string $defaultName = 'make:migration';
    protected static string $defaultDescription = 'Create a new migration file';

    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'The name of the migration')
            ->addOption('create', null, InputOption::VALUE_OPTIONAL, 'The table to be created')
            ->addOption('table', null, InputOption::VALUE_OPTIONAL, 'The table to migrate')
            ->addOption('path', null, InputOption::VALUE_OPTIONAL, 'The location where the migration file should be created');
    }

    public function handle(InputInterface $input, OutputInterface $output): int
    {
        $name = $this->argument('name');
        $table = $this->option('table');
        $create = $this->option('create') ?: false;

        if (!$table && is_string($create)) {
            $table = $create;
            $create = true;
        }

        $path = $this->option('path');
        if (!$path) {
            $path = database_path('migrations');
        } else {
            $path = str_starts_with($path, '/') ? $path : base_path($path);
        }

        // 创建迁移创建器实例
        $creator = $this->createMigrationCreator($path);
        $file = $creator->create($name, $path, $table, $create);

        $this->info("Created Migration: {$file}");

        return self::SUCCESS;
    }

    protected function argument(string $name): mixed
    {
        return $this->input->getArgument($name);
    }
}

