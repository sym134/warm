<?php

namespace warm\command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * 控制台命令基类
 * 
 * 为所有控制台命令提供基础功能和通用方法的抽象类
 * 封装了常用的输出方法和命令执行流程
 */
abstract class BaseCommand extends Command
{
    /**
     * 输出接口实例
     * 
     * @var OutputInterface
     */
    protected OutputInterface $output;
    
    /**
     * 输入接口实例
     * 
     * @var InputInterface
     */
    protected InputInterface $input;
    
    /**
     * Symfony样式实例，用于美化控制台输出
     * 
     * @var SymfonyStyle
     */
    protected SymfonyStyle $io;


    /**
     * 执行命令
     * 
     * 初始化输入输出对象，并调用具体的命令处理方法
     * 
     * @param InputInterface $input 输入对象
     * @param OutputInterface $output 输出对象
     * @return int 命令执行结果状态码
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->output = $output;
        $this->input = $input;
        $this->io = new SymfonyStyle($input, $output);
        return $this->handle($input, $output);
    }

    /**
     * 输出普通文本行
     * 
     * 向控制台输出一行普通文本信息
     * 
     * @param string $string 要输出的文本
     * @return void
     */
    protected function line(string $string): void
    {
        $this->output->writeln($string);
    }

    /**
     * 输出警告信息
     * 
     * 以警告样式向控制台输出信息
     * 
     * @param string $string 警告信息内容
     * @return void
     */
    protected function warn(string $string): void
    {
        $this->io->warning($string);
    }

    /**
     * 输出错误信息
     * 
     * 以错误样式向控制台输出信息
     * 
     * @param string $string 错误信息内容
     * @return void
     */
    protected function error(string $string): void
    {
        $this->io->error($string);
    }

    /**
     * 输出成功信息
     * 
     * 以成功样式向控制台输出信息
     * 
     * @param string $string 成功信息内容
     * @return void
     */
    protected function success(string $string): void
    {
        $this->io->success($string);
    }

    /**
     * 抽象的命令处理方法
     * 
     * 子类必须实现此方法来定义具体的命令逻辑
     * 
     * @param InputInterface $input 输入对象
     * @param OutputInterface $output 输出对象
     * @return mixed
     */
    abstract public function handle(InputInterface $input, OutputInterface $output): mixed;

    /**
     * 获取命令选项值
     * 
     * 获取指定名称的命令行选项的值
     * 
     * @param string $name 选项名称
     * @return mixed 选项值
     */
    protected function option(string $name): mixed
    {
        return $this->input->getOption($name);
    }

    /**
     * 调用其他命令
     *
     * 在当前命令中调用执行另一个控制台命令
     *
     * @param string $command 要调用的命令名称
     * @return void
     * @throws ExceptionInterface
     */
    protected function call(string $command): void
    {
        // 获取当前应用程序实例
        $application = $this->getApplication();
        // 使用 find 方法获取其他命令的实例
        $otherCommand = $application->find($command); // 替换为实际的命令名称
        $otherCommandInput = new \Symfony\Component\Console\Input\ArrayInput([]);
        // 调用其他命令的 run 方法执行它
        $otherCommand->run($otherCommandInput, $this->output);
    }
}