<?php
namespace warm\framework\support\facade;

/**
 * 哈希门面类
 * 
 * 提供简化的哈希操作接口，封装了底层的哈希管理器
 * 支持密码哈希、验证、信息获取等操作
 * 
 * @method static array info(string $hashedValue) 获取哈希信息
 * @method static bool check(string $value, string $hashedValue, array $options = []) 验证值与哈希是否匹配
 * @method static bool needsRehash(string $hashedValue, array $options = []) 检查哈希是否需要重新生成
 * @method static string make(string $value, array $options = []) 生成哈希值
 * @method static extend($driver, \Closure $callback) 扩展自定义驱动
 */
class Hash extends Facade
{
    /**
     * 获取门面对应的类名
     * 
     * @return string 哈希管理器类名
     */
    protected static function getFacadeClass(): string
    {
        return 'hash';
    }
}