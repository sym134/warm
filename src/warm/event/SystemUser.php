<?php

namespace warm\event;

use warm\admin\Admin;
use warm\admin\model\AdminMenu;
use warm\admin\service\monitor\AdminLoginLogService;
use warm\admin\service\monitor\AdminOperationLogService;

/**
 * 系统用户事件处理类
 * 
 * 处理系统用户的登录和操作事件，记录相关日志信息
 * 包括登录日志、操作日志、IP地理位置解析、浏览器和操作系统识别等功能
 */
class SystemUser
{
    /**
     * 登录日志
     * 
     * 记录用户登录信息，包括用户名、IP地址、地理位置、操作系统、浏览器等信息
     *
     * @param array $item 登录信息数组
     * @return void
     */
    public function login($item): void
    {
        $ip = request()->getRealIp();
        $http_user_agent = request()->header('user-agent');
        $data['username'] = $item['username'];
        $data['ip'] = $ip;
        $data['ip_location'] = self::getIpLocation($ip);
        $data['os'] = self::getOs($http_user_agent);
        $data['browser'] = self::getBrowser($http_user_agent);
        $data['status'] = $item['status'];
        $data['message'] = $item['message'];
        $data['login_time'] = date('Y-m-d H:i:s');
        AdminLoginLogService::make()->store($data);
    }

    /**
     * 记录操作日志
     * 
     * 记录用户的操作日志，包括请求方法、路由、服务名称、IP地址等信息
     * 仅记录非GET请求的操作
     *
     * @param bool $flag 操作标志
     * @return bool 是否记录成功
     */
    public function operateLog($flag): bool
    {
        if (request()->method() === 'GET') {
            return false;
        }
        $info = request()->user->toArray();
        $ip = request()->getRealIp();
        $module = request()->plugin;
        $rule = trim(strtolower(request()->uri()));
        $data['username'] = $info['username'];
        $data['created_by'] = $info['id'];
        $data['method'] = request()->method();
        $data['router'] = $rule;
        $data['service_name'] = self::getServiceName();
        $data['app'] = $module;
        $data['ip'] = $ip;
        $data['ip_location'] = self::getIpLocation($ip);
        $data['request_data'] = $this->filterParams(request()->all());
        AdminOperationLogService::make()->store($data);
        return true;
    }

    /**
     * 获取服务名称
     * 
     * 根据请求路径获取对应的服务名称（菜单标题）
     *
     * @return string 服务名称
     */
    protected function getServiceName(): string
    {
        $path = request()->route->getPath();
        if (preg_match("/\{[^}]+\}/", $path)) {
            $path = rtrim(preg_replace("/\{[^}]+\}/", '', $path), '/');
        }
        $path = '/' . ltrim($path, '/' . Admin::config('app.route.prefix'));
        $menu = AdminMenu::where('url', $path)->first();
        if (!is_null($menu)) {
            return $menu->getAttribute('title');
        } else {
            return '未知';
        }
    }

    /**
     * 过滤字段
     * 
     * 过滤敏感参数，如密码等字段替换为星号
     *
     * @param array $params 请求参数
     * @return string 过滤后的JSON字符串
     */
    protected function filterParams($params): string
    {
        $blackList = ['password', 'oldPassword', 'newPassword', 'content'];
        foreach ($params as $key => $value) {
            if (in_array($key, $blackList)) {
                $params[$key] = '******';
            }
        }
        return json_encode($params, JSON_UNESCAPED_UNICODE);
    }

    /**
     * 获取IP地理位置
     * 
     * 通过IP地址获取对应的地理位置信息
     *
     * @param string $ip IP地址
     * @return string 地理位置信息
     */
    protected function getIpLocation($ip): string
    {
        $ip2region = new \Ip2Region();
        try {
            $region = $ip2region->memorySearch($ip);
        } catch (\Exception $e) {
            return '未知';
        }
        [$country, $number, $province, $city, $network] = explode('|', $region['region']);
        if ($network === '内网IP') {
            return $network;
        }
        if ($country == '中国') {
            return $province . '-' . $city . ':' . $network;
        } else if ($country == '0') {
            return '未知';
        } else {
            return $country;
        }
    }

    /**
     * 获取浏览器信息
     * 
     * 从User-Agent中解析出浏览器类型
     *
     * @param string $user_agent User-Agent字符串
     * @return string 浏览器名称
     */
    protected function getBrowser($user_agent): string
    {
        $br = 'Unknown';
        if (preg_match('/MSIE/i', $user_agent)) {
            $br = 'MSIE';
        } elseif (preg_match('/Firefox/i', $user_agent)) {
            $br = 'Firefox';
        } elseif (preg_match('/Chrome/i', $user_agent)) {
            $br = 'Chrome';
        } elseif (preg_match('/Safari/i', $user_agent)) {
            $br = 'Safari';
        } elseif (preg_match('/Opera/i', $user_agent)) {
            $br = 'Opera';
        } else {
            $br = 'Other';
        }
        return $br;
    }

    /**
     * 获取操作系统信息
     * 
     * 从User-Agent中解析出操作系统类型
     *
     * @param string $user_agent User-Agent字符串
     * @return string 操作系统名称
     */
    protected function getOs($user_agent): string
    {
        $os = 'Unknown';
        if (preg_match('/win/i', $user_agent)) {
            $os = 'Windows';
        } elseif (preg_match('/mac/i', $user_agent)) {
            $os = 'Mac';
        } elseif (preg_match('/linux/i', $user_agent)) {
            $os = 'Linux';
        } else {
            $os = 'Other';
        }
        return $os;
    }
}