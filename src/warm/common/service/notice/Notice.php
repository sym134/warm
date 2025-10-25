<?php

namespace warm\common\service\notice;

use warm\common\service\BaseService;

/**
 * 通知服务类
 * 
 * 提供通知发送功能，支持根据场景发送不同类型的通知
 * 
 * @author 段誉
 * @date 2022/9/15 15:28
 */
class Notice extends BaseService
{
    /**
     * 根据场景发送短信
     * 
     * 根据指定场景发送相应的通知，支持短信等通知方式
     * 
     * @param array $params 通知参数
     * @return bool 发送是否成功
     * @author 段誉
     * @date 2022/9/15 15:28
     */
    public static function noticeByScene($params)
    {
        try {
            $noticeSetting = NoticeSetting::where('scene_id', $params['scene_id'])->findOrEmpty()->toArray();
            if (empty($noticeSetting)) {
                throw new \Exception('找不到对应场景的配置');
            }
            // 合并额外参数
            $params = self::mergeParams($params);
            $res = false;
            self::setError('发送通知失败');

            // 短信通知
            if (isset($noticeSetting['sms_notice']['status']) && $noticeSetting['sms_notice']['status'] == YesNoEnum::YES) {
                $res = (new SmsMessageService())->send($params);
            }

            return $res;
        } catch (\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }

    /**
     * 整理参数
     * 
     * 合并和处理通知参数，包括用户信息和跳转路径等
     * 
     * @param array $params 原始参数
     * @return array 处理后的参数
     * @author 段誉
     * @date 2022/9/15 15:28
     */
    public static function mergeParams($params)
    {
        // 用户相关
        if (!empty($params['params']['user_id'])) {
            $user = User::findOrEmpty($params['params']['user_id'])->toArray();
            $params['params']['nickname'] = $user['nickname'];
            $params['params']['user_name'] = $user['nickname'];
            $params['params']['user_sn'] = $user['sn'];
            $params['params']['mobile'] = $params['params']['mobile'] ?? $user['mobile'];
        }

        // 跳转路径
        $jumpPath = self::getPathByScene($params['scene_id'], $params['params']['order_id'] ?? 0);
        $params['url'] = $jumpPath['url'];
        $params['page'] = $jumpPath['page'];

        return $params;
    }

    /**
     * 根据场景获取跳转链接
     * 
     * 根据通知场景获取相应的跳转链接
     * 
     * @param string $sceneId 场景ID
     * @param int $extraId 额外ID（如订单ID）
     * @return string[] 跳转链接数组
     * @author 段誉
     * @date 2022/9/15 15:29
     */
    public static function getPathByScene($sceneId, $extraId)
    {
        // 小程序主页路径
        $page = '/pages/index/index';
        // 公众号主页路径
        $url = '/mobile/pages/index/index';
        return [
            'url' => $url,
            'page' => $page,
        ];
    }

    /**
     * 替换消息内容中的变量占位符
     * 
     * 将消息内容中的占位符替换为实际值
     * 
     * @param string $content 消息内容
     * @param array $params 参数数组
     * @return string 替换后的消息内容
     * @author 段誉
     * @date 2022/9/15 15:29
     */
    public static function contentFormat($content, $params)
    {
        foreach ($params['params'] as $k => $v) {
            $search = '{' . $k . '}';
            $content = str_replace($search, $v, $content);
        }
        return $content;
    }

    /**
     * 添加通知记录
     * 
     * 记录发送的通知信息
     * 
     * @param array $params 通知参数
     * @param array $noticeSetting 通知设置
     * @param string $sendType 发送类型
     * @param string $content 通知内容
     * @param string $extra 额外信息
     * @return NoticeRecord|\think\Model 通知记录模型
     * @author 段誉
     * @date 2022/9/15 15:29
     */
    public static function addNotice($params, $noticeSetting, $sendType, $content, $extra = '')
    {
        return NoticeRecord::create([
            'user_id' => $params['params']['user_id'] ?? 0,
            'title' => self::getTitleByScene($sendType, $noticeSetting),
            'content' => $content,
            'scene_id' => $noticeSetting['scene_id'],
            'read' => YesNoEnum::NO,
            'recipient' => $noticeSetting['recipient'],
            'send_type' => $sendType,
            'notice_type' => $noticeSetting['type'],
            'extra' => $extra,
        ]);
    }

    /**
     * 通知记录标题
     * 
     * 根据发送类型和通知设置获取通知标题
     * 
     * @param string $sendType 发送类型
     * @param array $noticeSetting 通知设置
     * @return string 通知标题
     * @author 段誉
     * @date 2022/9/15 15:30
     */
    public static function getTitleByScene($sendType, $noticeSetting)
    {
        switch ($sendType) {
            case NoticeEnum::SMS:
                $title = '';
                break;
            case NoticeEnum::OA:
                $title = $noticeSetting['oa_notice']['name'] ?? '';
                break;
            case NoticeEnum::MNP:
                $title = $noticeSetting['mnp_notice']['name'] ?? '';
                break;
            default:
                $title = '';
        }
        return $title;
    }
}