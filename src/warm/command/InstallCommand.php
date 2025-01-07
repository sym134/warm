<?php

namespace warm\command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use warm\Admin;
use warm\support\cores\Database;

class InstallCommand extends BaseCommand
{
    protected static string $defaultName = 'admin:install';
    protected static string $defaultDescription = 'admin install';
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
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    public function handle(InputInterface $input, OutputInterface $output): int
    {
        $this->initDatabase();
        return self::SUCCESS;
    }

    /**
     * 数据发布
     *
     * @return void
     *
     * Author:sym
     * Date:2024/1/21 20:58
     * Company:极智网络科技
     */
    public function initDatabase(): void
    {
        $this->call('migrate:run');

        if (Admin::adminUserModel()::query()->count() == 0) {
            Database::make()->fillInitialData();
            $this->io->success('Database installed successfully.');
        }else{
            $this->io->warning('Database already installed.');
        }
    }

}
