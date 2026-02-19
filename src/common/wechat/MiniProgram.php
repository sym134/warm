<?php

namespace warm\common\api;

use EasyWeChat\MiniApp\Application;
use warm\common\api\WechatEndpoints;
use warm\common\config\ConfigDefaults;
use warm\common\service\SystemConfigService;
use Workerman\Coroutine\Context;

/**
 * 微信小程序 API 类
 * 
 * 封装微信小程序的所有 API 调用
 * 使用协程上下文缓存实例，确保协程安全且性能优化
 * 每个协程首次调用时从数据库获取最新配置
 */
class MiniProgram extends BaseWechat
{
    /**
     * 协程上下文中的键名
     */
    private const CONTEXT_KEY_APP = 'miniprogram_api.application';

    /**
     * 获取小程序应用实例
     * 使用协程上下文缓存，每个协程独立，确保协程安全
     * 支持链式调用
     * 
     * @return Application
     * @throws \RuntimeException
     */
    public function app(): Application
    {
        // 从协程上下文获取缓存的实例
        $application = Context::get(self::CONTEXT_KEY_APP);
        
        if ($application !== null) {
            return $application;
        }

        // 首次调用，从数据库获取最新配置并创建实例
        $config = SystemConfigService::get(ConfigDefaults::KEY_WECHAT_MINI_PROGRAM_CONFIG)
            ?? ConfigDefaults::getWechatMiniProgramConfigDefault();

        if (empty($config) || empty($config['app_id']) || empty($config['secret'])) {
            throw new \RuntimeException('微信小程序配置未设置或配置不完整，请检查数据库配置');
        }

        $application = new Application([
            'app_id' => $config['app_id'] ?? '',
            'secret' => $config['secret'] ?? '',
            'response_type' => 'array',
        ]);

        // 缓存到协程上下文
        Context::set(self::CONTEXT_KEY_APP, $application);

        return $application;
    }

    /**
     * 清除当前协程的缓存实例（强制重新从数据库获取配置）
     * 
     * @return $this
     */
    public function reset(): self
    {
        Context::set(self::CONTEXT_KEY_APP, null);
        return $this;
    }

    // ==================== 用户相关 API ====================

    /**
     * 通过 code 获取用户 openid 和 session_key
     * 
     * @param string $code 登录凭证 code
     * @return array
     * @throws \RuntimeException
     */
    public function codeToSession(string $code): array
    {
        $app = $this->app();
        $response = $app->getClient()->get(WechatEndpoints::miniProgram('jscode2session'), [
            'query' => [
                'appid' => $app->getConfig()->get('app_id'),
                'secret' => $app->getConfig()->get('secret'),
                'js_code' => $code,
                'grant_type' => 'authorization_code',
            ],
        ]);
        return $this->handleResponse($response->toArray());
    }

    /**
     * 获取用户手机号
     * 
     * @param string $code 手机号获取凭证
     * @return array
     * @throws \RuntimeException
     */
    public function getPhoneNumber(string $code): array
    {
        $response = $this->app()->getClient()->postJson(WechatEndpoints::miniProgram('getuserphonenumber'), [
            'code' => $code,
        ]);
        return $this->handleResponse($response->toArray());
    }

    // ==================== 消息相关 API ====================

    /**
     * 发送订阅消息
     * 
     * @param string $openid 用户 openid
     * @param string $templateId 模板 ID
     * @param array $data 模板数据
     * @param string|null $page 跳转页面路径
     * @return array
     * @throws \RuntimeException
     */
    public function sendSubscribeMessage(
        string $openid,
        string $templateId,
        array $data,
        ?string $page = null
    ): array {
        $params = [
            'touser' => $openid,
            'template_id' => $templateId,
            'data' => $data,
        ];

        if ($page) {
            $params['page'] = $page;
        }

        $response = $this->app()->getClient()->postJson(WechatEndpoints::miniProgram('message_subscribe_send'), $params);
        return $this->handleResponse($response->toArray());
    }

    /**
     * 发送客服消息
     * 
     * @param string $openid 用户 openid
     * @param string $msgtype 消息类型 (text, image, link, miniprogrampage)
     * @param array $message 消息内容
     * @return array
     * @throws \RuntimeException
     */
    public function sendCustomMessage(string $openid, string $msgtype, array $message): array
    {
        $params = [
            'touser' => $openid,
            'msgtype' => $msgtype,
            $msgtype => $message,
        ];

        $response = $this->app()->getClient()->postJson(WechatEndpoints::miniProgram('message_custom_send'), $params);
        return $this->handleResponse($response->toArray());
    }

    // ==================== 二维码相关 API ====================

    /**
     * 获取小程序码（永久有效，数量限制）
     * 
     * @param string $scene 场景值，最大32个字符
     * @param string $path 页面路径
     * @param int $width 二维码宽度，默认 430
     * @param bool $autoColor 自动配置线条颜色
     * @param array $lineColor 线条颜色 ['r' => 0, 'g' => 0, 'b' => 0]
     * @param bool $isHyaline 是否需要透明底色
     * @return string|array 返回图片二进制内容或错误信息
     * @throws \RuntimeException
     */
    public function getUnlimitedQrcode(
        string $scene,
        string $path = '',
        int $width = 430,
        bool $autoColor = false,
        array $lineColor = ['r' => 0, 'g' => 0, 'b' => 0],
        bool $isHyaline = false
    ) {
        $app = $this->app();
        $params = [
            'scene' => $scene,
            'width' => $width,
            'auto_color' => $autoColor,
            'line_color' => $lineColor,
            'is_hyaline' => $isHyaline,
        ];

        if ($path) {
            $params['page'] = $path;
        }

        $response = $app->getClient()->postJson(WechatEndpoints::miniProgram('getwxacodeunlimit'), $params);

        // 检查响应头，判断是否是图片
        $headers = $response->getHeaders();
        $contentType = $headers['Content-Type'][0] ?? '';

        if (strpos($contentType, 'image') !== false) {
            return $response->getBody()->getContents();
        }

        // 如果不是图片，尝试解析 JSON 错误信息
        $body = $response->getBody()->getContents();
        $data = json_decode($body, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $this->handleResponse($data);
        }

        return ['error' => '未知响应格式', 'body' => $body];
    }

    /**
     * 获取小程序码（永久有效，数量无限制）
     * 
     * @param string $path 页面路径
     * @param int $width 二维码宽度，默认 430
     * @return string|array 返回图片二进制内容或错误信息
     * @throws \RuntimeException
     */
    public function getQrcode(string $path, int $width = 430)
    {
        $app = $this->app();
        $params = [
            'path' => $path,
            'width' => $width,
        ];

        $response = $app->getClient()->postJson(WechatEndpoints::miniProgram('getwxacode'), $params);

        // 检查响应头，判断是否是图片
        $headers = $response->getHeaders();
        $contentType = $headers['Content-Type'][0] ?? '';

        if (strpos($contentType, 'image') !== false) {
            return $response->getBody()->getContents();
        }

        // 如果不是图片，尝试解析 JSON 错误信息
        $body = $response->getBody()->getContents();
        $data = json_decode($body, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $this->handleResponse($data);
        }

        return ['error' => '未知响应格式', 'body' => $body];
    }

    // ==================== 数据统计 API ====================

    /**
     * 获取小程序访问趋势
     * 
     * @param string $beginDate 开始日期 (格式: yyyymmdd)
     * @param string $endDate 结束日期 (格式: yyyymmdd)
     * @return array
     * @throws \RuntimeException
     */
    public function getVisitTrend(string $beginDate, string $endDate): array
    {
        $response = $this->app()->getClient()->postJson(WechatEndpoints::miniProgram('datacube_visittrend'), [
            'begin_date' => $beginDate,
            'end_date' => $endDate,
        ]);
        return $this->handleResponse($response->toArray());
    }

    /**
     * 获取小程序用户画像
     * 
     * @param string $beginDate 开始日期 (格式: yyyymmdd)
     * @param string $endDate 结束日期 (格式: yyyymmdd)
     * @return array
     * @throws \RuntimeException
     */
    public function getUserPortrait(string $beginDate, string $endDate): array
    {
        $response = $this->app()->getClient()->postJson(WechatEndpoints::miniProgram('datacube_userportrait'), [
            'begin_date' => $beginDate,
            'end_date' => $endDate,
        ]);
        return $this->handleResponse($response->toArray());
    }

    // ==================== 内容安全 API ====================

    /**
     * 文本内容安全检测
     * 
     * @param string $content 待检测文本
     * @return array
     * @throws \RuntimeException
     */
    public function msgSecCheck(string $content): array
    {
        $response = $this->app()->getClient()->postJson(WechatEndpoints::miniProgram('msg_sec_check'), [
            'content' => $content,
        ]);
        return $this->handleResponse($response->toArray());
    }

    /**
     * 图片内容安全检测
     * 
     * @param string $mediaPath 图片文件路径
     * @return array
     * @throws \RuntimeException
     */
    public function imgSecCheck(string $mediaPath): array
    {
        $response = $this->app()->getClient()->upload(WechatEndpoints::miniProgram('img_sec_check'), [
            'media' => $mediaPath,
        ]);
        return $this->handleResponse($response->toArray());
    }

    // ==================== 其他 API ====================

    /**
     * 获取 Access Token
     * 
     * @return string
     * @throws \RuntimeException
     */
    public function getAccessToken(): string
    {
        return $this->app()->getAccessToken()->getToken();
    }

    /**
     * 生成小程序 URL Scheme
     * 
     * @param string $path 小程序页面路径
     * @param array $query 页面参数
     * @param int $expireTime 过期时间戳
     * @return array
     * @throws \RuntimeException
     */
    public function generateUrlScheme(string $path, array $query = [], int $expireTime = 0): array
    {
        $params = [
            'jump_wxa' => [
                'path' => $path,
                'query' => http_build_query($query),
            ],
        ];

        if ($expireTime > 0) {
            $params['expire_time'] = $expireTime;
        }

        $response = $this->app()->getClient()->postJson(WechatEndpoints::miniProgram('generatescheme'), $params);
        return $this->handleResponse($response->toArray());
    }

    /**
     * 生成小程序 URL Link
     * 
     * @param string $path 小程序页面路径
     * @param array $query 页面参数
     * @param bool $isExpire 是否过期
     * @param int $expireTime 过期时间戳
     * @return array
     * @throws \RuntimeException
     */
    public function generateUrlLink(
        string $path,
        array $query = [],
        bool $isExpire = false,
        int $expireTime = 0
    ): array {
        $params = [
            'path' => $path,
            'query' => http_build_query($query),
        ];

        if ($isExpire && $expireTime > 0) {
            $params['expire_type'] = 1;
            $params['expire_time'] = $expireTime;
        } else {
            $params['expire_type'] = 0;
        }

        $response = $this->app()->getClient()->postJson(WechatEndpoints::miniProgram('generate_urllink'), $params);
        return $this->handleResponse($response->toArray());
    }
}
