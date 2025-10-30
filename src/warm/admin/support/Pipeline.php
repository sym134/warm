<?php

namespace warm\admin\support;

use Illuminate\Support\Traits\Conditionable;

/**
 * 管道处理类
 * 
 * 扩展自Laravel的Pipeline类，提供更便捷的管道处理功能。
 * 支持条件性操作，可以基于条件决定是否执行某些步骤。
 * 
 * 主要用于处理一系列连续的操作，将一个值通过多个处理步骤。
 */
class Pipeline extends \Illuminate\Pipeline\Pipeline
{
    use Conditionable;

    /**
     * 处理管道流程
     * 
     * 创建管道实例并发送初始值，开始管道处理流程
     * 
     * @param mixed $passable 需要通过管道处理的初始值
     * @return Pipeline 管道实例
     */
    public static function handle(mixed $passable): Pipeline
    {
        return appw(self::class)->send($passable);
    }
}