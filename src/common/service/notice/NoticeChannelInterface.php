<?php

namespace warm\common\service\notice;

/**
 * 通知渠道接口
 * 
 * 定义所有通知渠道必须实现的方法
 */
interface NoticeChannelInterface
{
    /**
     * 发送通知
     * 
     * @param string $scene 场景ID
     * @param array $params 通知参数
     * @param array $config 渠道配置
     * @return bool 是否发送成功
     */
    public function send(string $scene, array $params, array $config): bool;
    
    /**
     * 获取渠道名称
     * 
     * @return string
     */
    public function getName(): string;
    
    /**
     * 获取渠道描述
     * 
     * @return string
     */
    public function getDescription(): string;
    
    /**
     * 验证配置是否完整
     * 
     * @param array $config 配置参数
     * @return bool
     */
    public function validateConfig(array $config): bool;
}