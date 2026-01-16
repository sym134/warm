<?php

namespace warm\admin\renderer\expand\renderer;

/**
 * Button https://baidu.github.io/amis/zh-CN/components/button
 * 
 * @author slowlyo
 * @version 6.13.0
 */
class Button extends Action
{
    public function __construct()
    {
        $this->set('type', 'button');
    }


}
