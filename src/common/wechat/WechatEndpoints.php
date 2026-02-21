<?php

namespace warm\common\wechat;

/**
 * 微信 API 端点管理类
 *
 * 从 config/plugin/jizhi/warm/wechat_endpoints.php 读取配置，用户可随时修改。
 * 配置键与类方法使用的 key 一致，仅需覆盖要修改的项即可，未覆盖的使用默认值。
 *
 * @see config/plugin/jizhi/warm/wechat_endpoints.php
 */
class WechatEndpoints
{
    /** 配置键 */
    private const CONFIG_KEY = 'plugin.jizhi.warm.wechat_endpoints';

    /**
     * 获取公众号 API 端点配置（合并用户配置与默认值）
     *
     * @return array<string, string>
     */
    private static function getOfficialAccountEndpoints(): array
    {
        $defaults = self::defaultOfficialAccountEndpoints();
        $custom = config(self::CONFIG_KEY . '.official_account', []);
        return array_merge($defaults, is_array($custom) ? $custom : []);
    }

    /**
     * 获取小程序 API 端点配置（合并用户配置与默认值）
     *
     * @return array<string, string>
     */
    private static function getMiniProgramEndpoints(): array
    {
        $defaults = self::defaultMiniProgramEndpoints();
        $custom = config(self::CONFIG_KEY . '.mini_program', []);
        return array_merge($defaults, is_array($custom) ? $custom : []);
    }

    /**
     * 公众号 API 默认端点（与 wechat_endpoints 配置一致，作回退用）
     *
     * @return array<string, string>
     */
    private static function defaultOfficialAccountEndpoints(): array
    {
        return [
            'menu_create' => '/cgi-bin/menu/create',
            'menu_get' => '/cgi-bin/menu/get',
            'menu_delete' => '/cgi-bin/menu/delete',
            'menu_addconditional' => '/cgi-bin/menu/addconditional',
            'menu_delconditional' => '/cgi-bin/menu/delconditional',
            'menu_trymatch' => '/cgi-bin/menu/trymatch',
            'user_info' => '/cgi-bin/user/info',
            'user_batchget' => '/cgi-bin/user/info/batchget',
            'user_get' => '/cgi-bin/user/get',
            'user_updateremark' => '/cgi-bin/user/info/updateremark',
            'message_template_send' => '/cgi-bin/message/template/send',
            'message_custom_send' => '/cgi-bin/message/custom/send',
            'message_mass_sendall' => '/cgi-bin/message/mass/sendall',
            'message_mass_send' => '/cgi-bin/message/mass/send',
            'message_mass_delete' => '/cgi-bin/message/mass/delete',
            'message_mass_preview' => '/cgi-bin/message/mass/preview',
            'message_mass_get' => '/cgi-bin/message/mass/get',
            'media_upload' => '/cgi-bin/media/upload',
            'media_get' => '/cgi-bin/media/get',
            'media_uploadimg' => '/cgi-bin/media/uploadimg',
            'material_add_news' => '/cgi-bin/material/add_news',
            'material_add_material' => '/cgi-bin/material/add_material',
            'material_get_material' => '/cgi-bin/material/get_material',
            'material_del_material' => '/cgi-bin/material/del_material',
            'material_update_news' => '/cgi-bin/material/update_news',
            'material_get_materialcount' => '/cgi-bin/material/get_materialcount',
            'material_batchget_material' => '/cgi-bin/material/batchget_material',
            'qrcode_create' => '/cgi-bin/qrcode/create',
            'qrcode_show' => 'https://mp.weixin.qq.com/cgi-bin/showqrcode',
            'oauth_authorize' => 'https://open.weixin.qq.com/connect/oauth2/authorize',
            'oauth_access_token' => '/sns/oauth2/access_token',
            'oauth_refresh_token' => '/sns/oauth2/refresh_token',
            'oauth_userinfo' => '/sns/userinfo',
            'oauth_auth' => '/sns/auth',
            'tags_create' => '/cgi-bin/tags/create',
            'tags_get' => '/cgi-bin/tags/get',
            'tags_update' => '/cgi-bin/tags/update',
            'tags_delete' => '/cgi-bin/tags/delete',
            'tags_getidlist' => '/cgi-bin/tags/getidlist',
            'tags_members_batchtagging' => '/cgi-bin/tags/members/batchtagging',
            'tags_members_batchuntagging' => '/cgi-bin/tags/members/batchuntagging',
            'tags_members_getidlist' => '/cgi-bin/tags/members/getidlist',
            'kfaccount_add' => '/customservice/kfaccount/add',
            'kfaccount_update' => '/customservice/kfaccount/update',
            'kfaccount_del' => '/customservice/kfaccount/del',
            'kfaccount_list' => '/cgi-bin/customservice/getkflist',
            'kfaccount_online_list' => '/cgi-bin/customservice/getonlinekflist',
            'kfaccount_inviteworker' => '/cgi-bin/customservice/kfaccount/inviteworker',
            'kfaccount_kf_session_create' => '/customservice/kfsession/create',
            'kfaccount_kf_session_close' => '/customservice/kfsession/close',
            'kfaccount_kf_session_get' => '/customservice/kfsession/getsession',
            'kfaccount_kf_session_list' => '/customservice/kfsession/getsessionlist',
            'kfaccount_kf_session_waitcase' => '/customservice/kfsession/getwaitcase',
            'kfaccount_msgrecord_getmsglist' => '/customservice/msgrecord/getmsglist',
            'datacube_usersummary' => '/datacube/getusersummary',
            'datacube_usercumulate' => '/datacube/getusercumulate',
            'datacube_articlesummary' => '/datacube/getarticlesummary',
            'datacube_articletotal' => '/datacube/getarticletotal',
            'datacube_userread' => '/datacube/getuserread',
            'datacube_userreadhour' => '/datacube/getuserreadhour',
            'datacube_usershare' => '/datacube/getusershare',
            'datacube_usersharehour' => '/datacube/getusersharehour',
            'datacube_upstreammsg' => '/datacube/getupstreammsg',
            'datacube_upstreammsghour' => '/datacube/getupstreammsghour',
            'datacube_upstreammsgweek' => '/datacube/getupstreammsgweek',
            'datacube_upstreammsgmonth' => '/datacube/getupstreammsgmonth',
            'datacube_upstreammsgdist' => '/datacube/getupstreammsgdist',
            'datacube_upstreammsgdistweek' => '/datacube/getupstreammsgdistweek',
            'datacube_upstreammsgdistmonth' => '/datacube/getupstreammsgdistmonth',
            'datacube_interface_summary' => '/datacube/getinterfacesummary',
            'datacube_interface_summaryhour' => '/datacube/getinterfacesummaryhour',
            'getcallbackip' => '/cgi-bin/getcallbackip',
            'clear_quota' => '/cgi-bin/clear_quota',
            'get_api_quota' => '/cgi-bin/openapi/quota/get',
            'clear_rid' => '/cgi-bin/openapi/rid/get',
        ];
    }

    /**
     * 小程序 API 默认端点（与 wechat_endpoints 配置一致，作回退用）
     *
     * @return array<string, string>
     */
    private static function defaultMiniProgramEndpoints(): array
    {
        return [
            'jscode2session' => '/sns/jscode2session',
            'getuserphonenumber' => '/wxa/business/getuserphonenumber',
            'checkencryptedmsg' => '/wxa/business/checkencryptedmsg',
            'decrypt' => '/wxa/business/decrypt',
            'message_subscribe_send' => '/cgi-bin/message/subscribe/send',
            'message_custom_send' => '/cgi-bin/message/custom/send',
            'message_wxopen_template_send' => '/cgi-bin/message/wxopen/template/send',
            'message_wxopen_template_uniform_send' => '/cgi-bin/message/wxopen/template/uniform_send',
            'getwxacode' => '/wxa/getwxacode',
            'getwxacodeunlimit' => '/wxa/getwxacodeunlimit',
            'createwxaqrcode' => '/cgi-bin/wxaapp/createwxaqrcode',
            'datacube_visittrend' => '/datacube/getweanalysisappiddailyvisittrend',
            'datacube_visitpage' => '/datacube/getweanalysisappiddailyvisitpage',
            'datacube_userportrait' => '/datacube/getweanalysisappiduserportrait',
            'datacube_usershare' => '/datacube/getweanalysisappiddailyshareinfo',
            'datacube_retain' => '/datacube/getweanalysisappiddailyretaininfo',
            'datacube_visitdistribution' => '/datacube/getweanalysisappidvisitdistribution',
            'datacube_visitdistribution_site' => '/datacube/getweanalysisappidvisitpage',
            'datacube_summarytrend' => '/datacube/getweanalysisappidmonthlyvisittrend',
            'datacube_visitdistribution_new' => '/datacube/getweanalysisappidvisitdistribution',
            'msg_sec_check' => '/wxa/msg_sec_check',
            'img_sec_check' => '/wxa/img_sec_check',
            'media_check_async' => '/wxa/media_check_async',
            'media_check_async_result' => '/wxa/media_check_async_result',
            'generatescheme' => '/wxa/generatescheme',
            'query_scheme' => '/wxa/queryscheme',
            'generate_urllink' => '/wxa/generate_urllink',
            'query_urllink' => '/wxa/queryurllink',
            'getpaidunionid' => '/wxa/getpaidunionid',
            'get_session_key' => '/sns/jscode2session',
            'get_access_token' => '/cgi-bin/token',
            'get_api_quota' => '/cgi-bin/openapi/quota/get',
            'clear_quota' => '/cgi-bin/clear_quota',
            'getcallbackip' => '/cgi-bin/getcallbackip',
        ];
    }

    /**
     * 获取公众号 API 端点
     *
     * @param string $key 端点键名
     * @return string
     * @throws \InvalidArgumentException
     */
    public static function officialAccount(string $key): string
    {
        $endpoints = self::getOfficialAccountEndpoints();
        if (!isset($endpoints[$key])) {
            throw new \InvalidArgumentException("公众号 API 端点 '{$key}' 不存在");
        }
        return $endpoints[$key];
    }

    /**
     * 获取小程序 API 端点
     *
     * @param string $key 端点键名
     * @return string
     * @throws \InvalidArgumentException
     */
    public static function miniProgram(string $key): string
    {
        $endpoints = self::getMiniProgramEndpoints();
        if (!isset($endpoints[$key])) {
            throw new \InvalidArgumentException("小程序 API 端点 '{$key}' 不存在");
        }
        return $endpoints[$key];
    }

    /**
     * 获取所有公众号 API 端点
     *
     * @return array<string, string>
     */
    public static function getAllOfficialAccountEndpoints(): array
    {
        return self::getOfficialAccountEndpoints();
    }

    /**
     * 获取所有小程序 API 端点
     *
     * @return array<string, string>
     */
    public static function getAllMiniProgramEndpoints(): array
    {
        return self::getMiniProgramEndpoints();
    }
}
