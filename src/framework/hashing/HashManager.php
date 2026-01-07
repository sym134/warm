<?php

namespace warm\framework\hashing;

use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Hashing\Argon2IdHasher;
use Illuminate\Hashing\ArgonHasher;
use Illuminate\Hashing\BcryptHasher;
use Illuminate\Support\Str;
use InvalidArgumentException;
use warm\admin\Admin;

/**
 * 哈希管理器类
 * 
 * 负责管理不同的哈希算法驱动，支持Bcrypt、Argon2i、Argon2id等哈希算法
 * 提供统一的接口进行密码哈希、验证和重新哈希等操作
 */
class HashManager implements Hasher
{

    /**
     * 已创建的哈希驱动实例
     *
     * @var array
     */
    protected $drivers = [];

    /**
     * 已注册的自定义驱动创建器
     *
     * @var array
     */
    protected $customCreators = [];

    /**
     * 获取指定的哈希驱动
     * 
     * @param string|null $driver 驱动名称，如果为null则使用默认驱动
     * @return mixed 哈希驱动实例
     * @throws InvalidArgumentException 当无法解析驱动时抛出异常
     */
    public function driver($driver = null)
    {
        $driver = $driver ?: $this->getDefaultDriver();

        if (is_null($driver)) {
            throw new InvalidArgumentException(sprintf(
                'Unable to resolve NULL driver for [%s].', static::class
            ));
        }
        // If the given driver has not been created before, we will create the instances
        // here and cache it so we can return it next time very quickly. If there is
        // already a driver created by this name, we'll just return that instance.
        if (!isset($this->drivers[$driver])) {
            $this->drivers[$driver] = $this->createDriver($driver);
        }

        return $this->drivers[$driver];
    }

    /**
     * 创建哈希驱动实例
     *
     * @param string $driver 驱动名称
     * @return mixed 驱动实例
     * @throws InvalidArgumentException 当不支持指定驱动时抛出异常
     */
    protected function createDriver($driver)
    {
        // First, we will determine if a custom driver creator exists for the given driver and
        // if it does not we will check for a creator method for the driver. Custom creator
        // callbacks allow developers to build their own "drivers" easily using Closures.
        if (isset($this->customCreators[$driver])) {
            return $this->callCustomCreator($driver);
        } else {
            $method = 'create' . Str::studly($driver) . 'Driver';
            if (method_exists($this, $method)) {
                return $this->$method();
            }
        }

        throw new InvalidArgumentException("Driver [$driver] not supported.");
    }

    /**
     * 调用自定义驱动创建器
     *
     * @param string $driver 驱动名称
     * @return mixed 自定义驱动创建器返回的实例
     */
    protected function callCustomCreator($driver)
    {
        return $this->customCreators[$driver]();
    }

    /**
     * 获取默认驱动名称
     *
     * @return string 默认驱动名称
     */
    public function getDefaultDriver()
    {
        return Admin::warmConfig('hashing.driver', 'bcrypt');
    }

    /**
     * 创建Bcrypt哈希驱动实例
     *
     * @return \Illuminate\Hashing\BcryptHasher Bcrypt哈希驱动实例
     */
    public function createBcryptDriver()
    {
        return new BcryptHasher(Admin::warmConfig('hashing.bcrypt', []));
    }

    /**
     * 创建Argon2i哈希驱动实例
     *
     * @return \Illuminate\Hashing\ArgonHasher Argon2i哈希驱动实例
     */
    public function createArgonDriver()
    {
        return new ArgonHasher(Admin::warmConfig('hashing.argon', []));
    }

    /**
     * 创建Argon2id哈希驱动实例
     *
     * @return \Illuminate\Hashing\Argon2IdHasher Argon2id哈希驱动实例
     */
    public function createArgon2idDriver()
    {
        return new Argon2IdHasher(Admin::warmConfig('hashing.argon',[]));
    }

    /**
     * 获取给定哈希值的信息
     *
     * @param string $hashedValue 哈希值
     * @return array 哈希信息数组
     */
    public function info($hashedValue)
    {
        return $this->driver()->info($hashedValue);
    }

    /**
     * 对给定值进行哈希
     *
     * @param string $value 需要哈希的值
     * @param array $options 哈希选项
     * @return string 哈希后的值
     */
    public function make($value, array $options = [])
    {
        return $this->driver()->make($value, $options);
    }

    /**
     * 验证给定的明文值与哈希值是否匹配
     *
     * @param string $value 明文值
     * @param string $hashedValue 哈希值
     * @param array $options 验证选项
     * @return bool 是否匹配
     */
    public function check($value, $hashedValue, array $options = [])
    {
        return $this->driver()->check($value, $hashedValue, $options);
    }

    /**
     * 检查给定的哈希值是否需要根据给定选项重新哈希
     *
     * @param string $hashedValue 哈希值
     * @param array $options 选项
     * @return bool 是否需要重新哈希
     */
    public function needsRehash($hashedValue, array $options = [])
    {
        return $this->driver()->needsRehash($hashedValue, $options);
    }

    /**
     * 注册自定义驱动创建器闭包
     *
     * @param string $driver 驱动名称
     * @param \Closure $callback 创建器闭包
     * @return $this
     */
    public function extend($driver, \Closure $callback)
    {
        $this->customCreators[$driver] = $callback;

        return $this;
    }

    /**
     * 动态调用默认驱动实例的方法
     *
     * @param string $method 方法名
     * @param array $parameters 方法参数
     * @return mixed 方法调用结果
     */
    public function __call($method, $parameters)
    {
        return $this->driver()->$method(...$parameters);
    }
}