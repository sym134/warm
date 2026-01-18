<?php
namespace warm\admin\renderer;
/**
 * Popover 弹出提示
 *
 * popover 不是一个独立组件，它是嵌入到其它组件中使用的，目前可以在以下组件中配置
 * table 的 column
 * list 的 column
 * static
 * cards 里的字段
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/popover
 */
class Popover extends BaseRenderer
{
    public string $type = 'popover';

}
