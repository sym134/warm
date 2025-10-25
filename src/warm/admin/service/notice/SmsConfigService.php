<?php

namespace warm\admin\service\notice;

use warm\admin\service\AdminService;

/**
 * 短信配置服务类
 * 
 * 提供短信配置管理功能
 */
class SmsConfigService extends AdminService
{
    /**
     * 获取主键字段名
     * 
     * @return string 主键字段名
     */
    public function primaryKey(): string
    {
        return 'type';
    }

    /**
     * 配置键名
     * 
     * @var string
     */
    private static string $key = 'sms_config';

    /**
     * 获取短信配置
     * 
     * @return array 短信配置数组
     *
     * @author heimiao
     * @date 2025-01-09 11:07
     */
    public function get(): array
    {
        $data = warmConfig()->get(self::$key, [
            ['type' => 'aliyun', 'name' => '阿里云', 'access_key_id' => '', 'access_key_secret' => '', 'sign_name' => '', 'enable' => 0],
            [
                'type' => 'qcloud',
                'name' => '腾讯云',
                'sdk_app_id' => '',
                'secret_id' => '',
                'secret_key' => '',
                'sign_name' => '',
                'enable' => 0
            ],
            ['type' => 'smsbao', 'name' => '短信宝', 'user' => '', 'password' => '', 'enable' => 0],
        ]);
        foreach ($data as &$value) {
            $value['name'] = $this->getNameDesc($value['type']);
        }
        return $data;
    }

    /**
     * 获取编辑数据
     * 
     * @param mixed $id 数据ID
     * @return array 编辑数据
     */
    public function getEditData($id): array
    {
        $data = array_column($this->get(), null, 'type');
        return $data[$id] ?? [];
    }

    /**
     * 更新短信配置
     * 
     * @param mixed $primaryKey 主键值
     * @param array $data 更新的数据
     * @return bool 是否更新成功
     */
    public function update($primaryKey, $data): bool
    {
        return warmConfig()->set(self::$key, array_map(function ($val) use ($primaryKey, $data) {
            ;
            if ($val['type'] == $primaryKey) {
                return $data;
            }
            return $val;
        }, $this->get()));
    }

    /**
     * 删除短信配置
     * 
     * @param string $ids 删除的ID列表
     * @return bool 是否删除成功
     */
    public function delete($ids): bool
    {
        $data = $this->get();
        foreach ($data as $key => $value) {
            if (str_contains($value['type'], $ids)) {
                unset($data[$key]);
            }
        }
        return warmConfig()->set(self::$key, array_values($data));
    }

    /**
     * 存储短信配置
     * 
     * @param array $data 存储的数据
     * @return bool 是否存储成功
     */
    public function store($data): bool
    {
        $get = $this->get();
        $get[] = $data;
        foreach ($get as $val) {
            if ($val['type'] === $data['type']) {
                $this->setError(translator('notice.sms_channel_already_exists'));
                return false;
            }
        }
        return warmConfig()->set(self::$key, $get);
    }

    /**
     * 获取名称描述
     * 
     * @param string $value 类型值
     * @return string 名称描述
     */
    public function getNameDesc($value): string
    {
        $desc = ['aliyun' => '阿里云', 'qcloud' => '腾讯云', 'smsbao' => '短信宝'];
        return $desc[$value] ?? '';
    }
}