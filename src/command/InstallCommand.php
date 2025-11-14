<?php

namespace warm\command;

use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use warm\admin\Admin;
use warm\admin\support\cores\Database;

/**
 * 安装命令类
 * 
 * 用于执行 warm 框架的安装过程，包括数据库初始化和初始数据填充
 * 可通过命令行执行: php webman warm:install
 */
class InstallCommand extends BaseCommand
{
    /**
     * @var string 命令名称
     */
    protected static string $defaultName = 'warm:install';
    
    /**
     * @var string 命令描述
     */
    protected static string $defaultDescription = 'warm install';

    /**
     * 执行安装命令的主方法
     *
     * @param InputInterface $input 输入接口对象
     * @param OutputInterface $output 输出接口对象
     * @return int 返回执行状态码 (self::SUCCESS 或 self::FAILURE)
     * @throws ExceptionInterface
     */
    public function handle(InputInterface $input, OutputInterface $output): int
    {
        // 初始化数据库
        if ($this->initDatabase()) {
            // 数据库已存在，调用 auth:key 命令生成密钥
            $this->call('auth:key');
            return self::SUCCESS;
        } else {
            // 数据库初始化失败
            return self::FAILURE;
        }
    }

    /**
     * 数据库初始化方法
     * 
     * 执行数据库迁移并检查是否需要填充初始数据
     *
     * @return bool 返回true表示数据库已存在，false表示新安装并已填充数据
     * 
     * Author:sym
     * Date:2024/1/21 20:58
     * Company:极智网络科技
     */
    public function initDatabase(): bool
    {
        // 执行数据库迁移（创建数据表）
        Database::make()->up();

        // 检查管理员用户表是否为空
        if (Admin::adminUserModel()::query()->count() == 0) {
            // 表为空，填充初始数据（创建默认管理员账号等）
            Database::make()->fillInitialData();
            $this->io->success('Database installed successfully.');
            // 返回false表示这是新安装
            return false;
        } else {
            // 表中已有数据，提示数据库已安装
            $this->io->error('Database already installed.');
            // 返回true表示数据库已存在
            return true;
        }
    }

}