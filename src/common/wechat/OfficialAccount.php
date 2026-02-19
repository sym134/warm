<?php

namespace warm\common\api;

use EasyWeChat\OfficialAccount\Application;
use warm\common\api\WechatEndpoints;
use warm\common\config\ConfigDefaults;
use warm\common\service\SystemConfigService;
use Workerman\Coroutine\Context;

/**
 * 微信公众号 API 类
 * 
 * 封装微信公众号的所有 API 调用
 * 使用协程上下文缓存实例，确保协程安全且性能优化
 * 每个协程首次调用时从数据库获取最新配置
 */
class OfficialAccount extends BaseWechat
{
    /**
     * 协程上下文中的键名
     */
    private const CONTEXT_KEY_APP = 'officialaccount_api.application';

    /**
     * 获取公众号应用实例
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
        $config = SystemConfigService::get(ConfigDefaults::KEY_WECHAT_OFFICIAL_ACCOUNT_CONFIG)
            ?? ConfigDefaults::getWechatOfficialAccountConfigDefault();
            
        if (empty($config) || empty($config['app_id']) || empty($config['app_secret'])) {
            throw new \RuntimeException('微信公众号配置未设置或配置不完整，请检查数据库配置');
        }

        $application = new Application([
            'app_id' => $config['app_id'] ?? '',
            'secret' => $config['app_secret'] ?? '',
            'token' => $config['token'] ?? '',
            'aes_key' => $config['aes_key'] ?? '',
            'response_type' => 'array',
            'http' => ['timeout' => 5.0, 'retry' => true],
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

    // ==================== 菜单相关 API ====================

    /**
     * 创建自定义菜单
     * 
     * @param array $buttons 菜单按钮数组
     * @return array
     * @throws \RuntimeException
     */
    public function createMenu(array $buttons): array
    {
        $response = $this->app()->getClient()->postJson(WechatEndpoints::officialAccount('menu_create'), ['button' => $buttons]);
        return $this->handleResponse($response->toArray());
    }

    /**
     * 获取当前菜单配置
     * 
     * @return array
     * @throws \RuntimeException
     */
    public function getMenu(): array
    {
        $response = $this->app()->getClient()->get(WechatEndpoints::officialAccount('menu_get'));
        return $this->handleResponse($response->toArray());
    }

    /**
     * 删除所有菜单
     * 
     * @return array
     * @throws \RuntimeException
     */
    public function deleteMenu(): array
    {
        $response = $this->app()->getClient()->get(WechatEndpoints::officialAccount('menu_delete'));
        return $this->handleResponse($response->toArray());
    }

    // ==================== 用户相关 API ====================

    /**
     * 获取用户信息
     * 
     * @param string $openid 用户 openid
     * @param string $lang 语言，默认为 'zh_CN'
     * @return array
     * @throws \RuntimeException
     */
    public function getUserInfo(string $openid, string $lang = 'zh_CN'): array
    {
        $response = $this->app()->getClient()->get(WechatEndpoints::officialAccount('user_info'), [
            'query' => [
                'openid' => $openid,
                'lang' => $lang,
            ],
        ]);
        return $this->handleResponse($response->toArray());
    }

    /**
     * 批量获取用户信息
     * 
     * @param array $openids 用户 openid 数组
     * @param string $lang 语言，默认为 'zh_CN'
     * @return array
     * @throws \RuntimeException
     */
    public function batchGetUserInfo(array $openids, string $lang = 'zh_CN'): array
    {
        $app = $this->app();
        $userList = array_map(function ($openid) use ($lang) {
            return [
                'openid' => $openid,
                'lang' => $lang,
            ];
        }, $openids);

        $response = $app->getClient()->postJson(WechatEndpoints::officialAccount('user_batchget'), [
            'user_list' => $userList,
        ]);
        return $this->handleResponse($response->toArray());
    }

    /**
     * 获取用户列表
     * 
     * @param string|null $nextOpenid 第一个拉取的 openid，不填默认从头开始拉取
     * @return array
     * @throws \RuntimeException
     */
    public function getUserList(?string $nextOpenid = null): array
    {
        $query = [];
        if ($nextOpenid) {
            $query['next_openid'] = $nextOpenid;
        }

        $response = $this->app()->getClient()->get(WechatEndpoints::officialAccount('user_get'), [
            'query' => $query,
        ]);
        return $this->handleResponse($response->toArray());
    }

    // ==================== 消息相关 API ====================

    /**
     * 发送模板消息
     * 
     * @param string $openid 用户 openid
     * @param string $templateId 模板 ID
     * @param array $data 模板数据
     * @param string|null $url 跳转链接
     * @param array $miniprogram 小程序信息 ['appid' => '', 'pagepath' => '']
     * @return array
     * @throws \RuntimeException
     */
    public function sendTemplateMessage(
        string $openid,
        string $templateId,
        array $data,
        ?string $url = null,
        array $miniprogram = []
    ): array {
        $params = [
            'touser' => $openid,
            'template_id' => $templateId,
            'data' => $data,
        ];

        if ($url) {
            $params['url'] = $url;
        }

        if (!empty($miniprogram)) {
            $params['miniprogram'] = $miniprogram;
        }

        $response = $this->app()->getClient()->postJson(WechatEndpoints::officialAccount('message_template_send'), $params);
        return $this->handleResponse($response->toArray());
    }

    /**
     * 发送客服消息
     * 
     * @param string $openid 用户 openid
     * @param string $msgtype 消息类型 (text, image, voice, video, music, news, mpnews, wxcard)
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

        $response = $this->app()->getClient()->postJson(WechatEndpoints::officialAccount('message_custom_send'), $params);
        return $this->handleResponse($response->toArray());
    }

    // ==================== 素材管理 API ====================

    /**
     * 上传临时素材
     * 
     * @param string $type 媒体文件类型 (image, voice, video, thumb)
     * @param string $path 文件路径
     * @return array
     * @throws \RuntimeException
     */
    public function uploadMedia(string $type, string $path): array
    {
        $response = $this->app()->getClient()->upload(WechatEndpoints::officialAccount('media_upload'), [
            'media' => $path,
        ], [
            'query' => ['type' => $type],
        ]);
        return $this->handleResponse($response->toArray());
    }

    /**
     * 获取临时素材
     * 
     * @param string $mediaId 媒体文件 ID
     * @return array|string 返回文件内容或下载 URL
     * @throws \RuntimeException
     */
    public function getMedia(string $mediaId)
    {
        $app = $this->app();
        $response = $app->getClient()->get(WechatEndpoints::officialAccount('media_get'), [
            'query' => ['media_id' => $mediaId],
        ]);
        
        // 检查是否是文件流
        $contentType = $response->getHeader('Content-Type')[0] ?? '';
        if (strpos($contentType, 'image') !== false || strpos($contentType, 'video') !== false) {
            return $response->getBody()->getContents();
        }
        
        return $this->handleResponse($response->toArray());
    }

    /**
     * 上传永久素材（图片）
     * 
     * @param string $path 文件路径
     * @return array
     * @throws \RuntimeException
     */
    public function uploadMaterial(string $path): array
    {
        $response = $this->app()->getClient()->upload(WechatEndpoints::officialAccount('material_add_material'), [
            'media' => $path,
        ]);
        return $this->handleResponse($response->toArray());
    }

    // ==================== 二维码相关 API ====================

    /**
     * 创建临时二维码
     * 
     * @param int|string $sceneValue 场景值（整数或字符串）
     * @param int $expireSeconds 过期时间（秒），最大 2592000（30天）
     * @return array
     * @throws \RuntimeException
     */
    public function createTemporaryQrcode($sceneValue, int $expireSeconds = 2592000): array
    {
        $params = [
            'expire_seconds' => $expireSeconds,
            'action_name' => is_int($sceneValue) ? 'QR_SCENE' : 'QR_STR_SCENE',
            'action_info' => [
                'scene' => is_int($sceneValue) 
                    ? ['scene_id' => $sceneValue]
                    : ['scene_str' => $sceneValue],
            ],
        ];

        $response = $this->app()->getClient()->postJson(WechatEndpoints::officialAccount('qrcode_create'), $params);
        return $this->handleResponse($response->toArray());
    }

    /**
     * 创建永久二维码
     * 
     * @param int|string $sceneValue 场景值（整数或字符串）
     * @return array
     * @throws \RuntimeException
     */
    public function createPermanentQrcode($sceneValue): array
    {
        $params = [
            'action_name' => is_int($sceneValue) ? 'QR_LIMIT_SCENE' : 'QR_LIMIT_STR_SCENE',
            'action_info' => [
                'scene' => is_int($sceneValue) 
                    ? ['scene_id' => $sceneValue]
                    : ['scene_str' => $sceneValue],
            ],
        ];

        $response = $this->app()->getClient()->postJson(WechatEndpoints::officialAccount('qrcode_create'), $params);
        return $this->handleResponse($response->toArray());
    }

    /**
     * 获取二维码图片 URL
     * 
     * @param string $ticket 二维码 ticket
     * @return string
     */
    public function getQrcodeUrl(string $ticket): string
    {
        return WechatEndpoints::officialAccount('qrcode_show') . '?ticket=' . urlencode($ticket);
    }

    // ==================== OAuth 相关 API ====================

    /**
     * 获取 OAuth 授权 URL
     * 
     * @param string $redirectUri 回调地址
     * @param string $scope 授权作用域 (snsapi_base, snsapi_userinfo)
     * @param string|null $state 状态参数
     * @return string
     * @throws \RuntimeException
     */
    public function getOAuthUrl(string $redirectUri, string $scope = 'snsapi_base', ?string $state = null): string
    {
        $app = $this->app();
        $config = $app->getConfig();
        
        $params = [
            'appid' => $config->get('app_id'),
            'redirect_uri' => urlencode($redirectUri),
            'response_type' => 'code',
            'scope' => $scope,
        ];
        
        if ($state) {
            $params['state'] = $state;
        }
        
        return WechatEndpoints::officialAccount('oauth_authorize') . '?' . http_build_query($params) . '#wechat_redirect';
    }

    /**
     * 通过 code 获取用户 openid
     * 
     * @param string $code 授权码
     * @return array
     * @throws \RuntimeException
     */
    public function getUserByCode(string $code): array
    {
        $app = $this->app();
        $config = $app->getConfig();
        
        $response = $app->getClient()->get(WechatEndpoints::officialAccount('oauth_access_token'), [
            'query' => [
                'appid' => $config->get('app_id'),
                'secret' => $config->get('secret'),
                'code' => $code,
                'grant_type' => 'authorization_code',
            ],
        ]);
        return $this->handleResponse($response->toArray());
    }

    // ==================== 服务器相关 API ====================

    /**
     * 验证服务器配置（验证微信服务器签名）
     * 
     * @param string $signature 微信加密签名
     * @param string $timestamp 时间戳
     * @param string $nonce 随机数
     * @param string $token 配置的 token
     * @return bool
     */
    public function verifyServer(string $signature, string $timestamp, string $nonce, string $token): bool
    {
        $tmpArr = [$token, $timestamp, $nonce];
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        return $tmpStr === $signature;
    }

    /**
     * 处理服务器消息（需要配合消息处理器使用）
     * 
     * 注意：easywechat6x 不再内置消息处理逻辑，需要自行实现
     * 建议使用消息处理器来处理不同类型的消息
     * 
     * @return array 返回消息数据
     * @throws \RuntimeException
     */
    public function parseServerMessage(): array
    {
        $app = $this->app();
        $config = $app->getConfig();
        
        // 获取原始消息数据
        $xml = file_get_contents('php://input');
        
        // 这里需要根据实际情况解析 XML 消息
        // easywechat6x 不再内置解析逻辑，需要自行实现或使用第三方库
        return [
            'raw' => $xml,
            'config' => $config->all(),
        ];
    }
}
