<?php

namespace warm\common\service\notice;

use warm\admin\service\AdminService;

class SmsConfigService extends AdminService
{
    public function primaryKey(): string
    {
        return 'type';
    }

    private static string $key = 'sms_config';

    /**
     * 获取短信配置
     * @return array
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

    public function getEditData($id): array
    {
        $data = array_column($this->get(), null, 'type');
        return $data[$id] ?? [];
    }

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

    public function store($data): bool
    {
        $get = $this->get();
        $get[] = $data;
        foreach ($get as $val) {
            if ($val['type'] === $data['type']) {
                $this->setError(admin_trans('notice.sms_channel_already_exists'));
                return false;
            }
        }
        return warmConfig()->set(self::$key, $get);
    }

    public function getNameDesc($value): string
    {
        $desc = ['aliyun' => '阿里云', 'qcloud' => '腾讯云', 'smsbao' => '短信宝'];
        return $desc[$value] ?? '';
    }
}