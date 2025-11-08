<?php

namespace warm\command;

use support\Console;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use warm\admin\model\AdminMenu;

/**
 * 添加微信菜单命令
 * 
 * 用于向系统菜单中添加微信回复相关的菜单项
 */
class AddWechatMenuCommand extends Command
{
    /**
     * 配置命令
     */
    protected function configure()
    {
        $this->setName('warm:add-wechat-menu')
            ->setDescription('添加微信回复相关菜单项');
    }

    /**
     * 执行命令
     *
     * @param InputInterface $input 输入接口
     * @param OutputInterface $output 输出接口
     * @return int 命令执行结果
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            // 检查是否已存在微信菜单
            $wechatMenu = AdminMenu::where('title', '微信管理')->first();
            
            if (!$wechatMenu) {
                // 创建微信管理父菜单
                $wechatMenu = AdminMenu::create([
                    'parent_id' => 0,
                    'title' => '微信管理',
                    'icon' => 'fa-brands fa-weixin',
                    'type' => AdminMenu::TYPE_ROUTE,
                    'url' => '',
                    'order' => 80,
                    'status' => 1
                ]);
                
                $output->writeln('已创建微信管理父菜单');
            }
            
            // 检查是否已存在关键词回复菜单
            $replyMenu = AdminMenu::where('title', '关键词回复')
                ->where('parent_id', $wechatMenu->id)
                ->first();
            
            if (!$replyMenu) {
                // 创建关键词回复子菜单
                AdminMenu::create([
                    'parent_id' => $wechatMenu->id,
                    'title' => '关键词回复',
                    'icon' => 'fa-solid fa-comment',
                    'type' => AdminMenu::TYPE_ROUTE,
                    'url' => '/system/wechat_reply',
                    'order' => 0,
                    'status' => 1
                ]);
                
                $output->writeln('已创建关键词回复子菜单');
            }
            
            // 检查是否已存在微信设置菜单
            $settingMenu = AdminMenu::where('title', '微信设置')
                ->where('parent_id', $wechatMenu->id)
                ->first();
            
            if (!$settingMenu) {
                // 创建微信设置子菜单
                AdminMenu::create([
                    'parent_id' => $wechatMenu->id,
                    'title' => '微信设置',
                    'icon' => 'fa-solid fa-gear',
                    'type' => AdminMenu::TYPE_ROUTE,
                    'url' => '/system/wechat_reply/setting',
                    'order' => 1,
                    'status' => 1
                ]);
                
                $output->writeln('已创建微信设置子菜单');
            }
            
            $output->writeln('微信菜单项添加完成');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln('添加微信菜单项时出错: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}