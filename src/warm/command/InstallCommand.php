<?php

namespace warm\command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use warm\admin\Admin;
use warm\admin\support\cores\Database;

class InstallCommand extends BaseCommand
{
    protected static string $defaultName = 'warm:install';
    protected static string $defaultDescription = 'warm install';
    /**
     * @var array|mixed|null
     */
    private mixed $directory;


    /**
     * @return void
     */
    protected function configure()
    {
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    public function handle(InputInterface $input, OutputInterface $output): int
    {
        if ($this->initDatabase()) {
            $this->call('auth:key');
            return self::SUCCESS;
        } else {
            return self::FAILURE;
        }
    }

    /**
     * 数据发布
     *
     * @return bool Author:sym
     *
     * Author:sym
     * Date:2024/1/21 20:58
     * Company:极智网络科技
     */
    public function initDatabase(): bool
    {
        $this->call('migrate:run');

        if (Admin::adminUserModel()::query()->count() == 0) {
            Database::make()->fillInitialData();
            $this->io->success('Database installed successfully.');
            return false;
        } else {
            $this->io->error('Database already installed.');
            return true;
        }
    }

}
