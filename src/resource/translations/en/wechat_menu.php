<?php

return [
    'title' => 'WeChat Menu Settings',
    
    'list' => [
        'menu_name' => 'Menu Name',
        'type' => 'Type',
        'type_click' => 'Keyword',
        'type_view' => 'Link',
        'type_miniprogram' => 'Mini Program',
        'key' => 'Keyword',
        'url' => 'URL',
        'appid' => 'Mini Program AppID',
        'pagepath' => 'Mini Program Path',
        'sort' => 'Sort',
        'actions' => 'Actions',
    ],
    
    'form' => [
        'menu_name' => 'Menu Name',
        'menu_name_placeholder' => 'Please enter menu name',
        'parent_menu' => 'Parent Menu',
        'parent_menu_description' => 'Select parent menu, 0 means first-level menu; select first-level menu to add second-level menu',
        'first_level_menu' => 'First Level Menu',
        'rule_type' => 'Rule Type',
        'rule_type_click' => 'Keyword',
        'rule_type_view' => 'Link',
        'rule_type_miniprogram' => 'Mini Program',
        'key' => 'Keyword',
        'key_placeholder' => 'Please enter keyword',
        'url' => 'URL',
        'url_placeholder' => 'Please enter URL',
        'appid' => 'Mini Program AppID',
        'appid_placeholder' => 'Please enter Mini Program AppID',
        'pagepath' => 'Mini Program Path',
        'pagepath_placeholder' => 'Please enter Mini Program path, e.g.: pages/index/index',
        'miniprogram_url' => 'Fallback URL',
        'miniprogram_url_placeholder' => 'Please enter Mini Program fallback URL',
        'sort' => 'Sort',
        'sort_description' => 'Smaller numbers appear first',
        'parent_menu_description_edit' => 'Select parent menu, 0 means first-level menu',
    ],
    
    'actions' => [
        'publish_to_wechat' => 'Publish to WeChat',
        'publish_confirm' => 'Are you sure to publish the menu to WeChat? It will take effect in the WeChat Official Account after publishing.',
        'publish_success' => 'Menu published successfully',
        'publish_failed' => 'Menu publish failed: ',
        'add_menu' => 'Add Menu',
        'edit_menu' => 'Edit Menu',
        'delete_menu' => 'Delete',
        'delete_confirm' => 'Are you sure to delete?',
    ],
    
    'messages' => [
        'publish_success' => 'Published successfully',
        'publish_failed' => 'Publish failed',
    ],
];
