<?php

return [
    'title' => '微信菜单设置',
    
    'list' => [
        'menu_name' => '菜单名称',
        'type' => '类型',
        'type_click' => '关键字',
        'type_view' => '跳转链接',
        'type_miniprogram' => '小程序',
        'key' => '关键字',
        'url' => '链接',
        'appid' => '小程序AppID',
        'pagepath' => '小程序路径',
        'sort' => '排序',
        'actions' => '操作',
    ],
    
    'form' => [
        'menu_name' => '菜单名称',
        'menu_name_placeholder' => '请输入菜单名称',
        'parent_menu' => '父菜单',
        'parent_menu_description' => '选择父菜单，0表示一级菜单；选择一级菜单可添加二级菜单',
        'first_level_menu' => '一级菜单',
        'rule_type' => '规则状态',
        'rule_type_click' => '关键字',
        'rule_type_view' => '跳转链接',
        'rule_type_miniprogram' => '小程序',
        'key' => '关键字',
        'key_placeholder' => '请输入关键字',
        'url' => '链接地址',
        'url_placeholder' => '请输入链接地址',
        'appid' => '小程序AppID',
        'appid_placeholder' => '请输入小程序AppID',
        'pagepath' => '小程序路径',
        'pagepath_placeholder' => '请输入小程序路径，如：pages/index/index',
        'miniprogram_url' => '备用网址',
        'miniprogram_url_placeholder' => '请输入小程序备用网址',
        'sort' => '排序',
        'sort_description' => '数字越小越靠前',
        'parent_menu_description_edit' => '选择父菜单，0表示一级菜单',
    ],
    
    'actions' => [
        'publish_to_wechat' => '发布到微信',
        'publish_confirm' => '确定要发布菜单到微信吗？发布后将在微信公众号中生效。',
        'publish_success' => '菜单发布成功',
        'publish_failed' => '菜单发布失败：',
        'add_menu' => '添加菜单',
        'edit_menu' => '编辑菜单',
        'delete_menu' => '删除',
        'delete_confirm' => '确定要删除吗？',
    ],
    
    'messages' => [
        'publish_success' => '发布成功',
        'publish_failed' => '发布失败',
    ],
];
