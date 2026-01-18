<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\trait\NameAndLabel;

/**
 * StaticClass 静态展示
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/static
 */
class StaticClass extends BaseRenderer
{
    use NameAndLabel;

    public string $type = 'static';

    /**
     * 静态值
     *
     * @param string|array $value
     * @return StaticClass
     */
    public function value(string|array $value): StaticClass
    {
        return $this->set('value', $value);
    }

    /**
     * 弹层配置
     *
     * @param array $value
     * @return StaticClass
     */
    public function popOver(array $value): StaticClass
    {
        return $this->set('popOver', $value);
    }


}
