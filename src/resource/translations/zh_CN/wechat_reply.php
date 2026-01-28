<?php

return [
    'title' => '微信回复管理',
    'keyword_reply' => '关键词回复',
    'subscribe_reply' => '关注回复',
    'default_reply' => '默认回复',
    
    'list' => [
        'title' => '关键词回复列表',
        'keyword' => '关键词',
        'status' => '状态',
        'status_enabled' => '启用',
        'status_disabled' => '禁用',
        'reply_type' => '回复类型',
        'reply_type_text' => '文本',
        'reply_type_image' => '图片',
        'reply_type_news' => '图文',
        'reply_type_voice' => '语音',
        'reply_type_video' => '视频',
        'created_at' => '创建时间',
    ],
    
    'form' => [
        'keyword' => '关键词',
        'keyword_description' => '用户发送的关键词，支持精确匹配',
        'reply_type' => '回复类型',
        'reply_content' => '回复内容',
        'reply_content_description' => '请输入要回复的文本内容',
        'media_id' => '媒体ID',
        'media_id_description' => '请输入微信素材的media_id',
        'status' => '状态',
        'hide' => '是否隐藏',
    ],
    
    'subscribe' => [
        'title' => '关注回复',
        'reply_type' => '回复类型',
        'reply_content' => '回复内容',
        'reply_content_description' => '请输入要回复的文本内容',
        'media_id' => '媒体ID',
        'media_id_description' => '请输入微信素材的media_id',
    ],
    
    'default' => [
        'title' => '默认回复',
        'reply_type' => '回复类型',
        'reply_content' => '回复内容',
        'reply_content_description' => '请输入要回复的文本内容',
        'media_id' => '媒体ID',
        'media_id_description' => '请输入微信素材的media_id',
    ],
    
    'messages' => [
        'subscribe_save_success' => '关注回复设置保存成功',
        'subscribe_save_failed' => '关注回复设置保存失败',
        'default_save_success' => '默认回复设置保存成功',
        'default_save_failed' => '默认回复设置保存失败',
    ],
];
