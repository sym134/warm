<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\trait\FormItem;

/**
 * InputRepeat 重复输入框
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/input-repeat
 */
class InputRepeat extends BaseRenderer
{
    use FormItem;

    public string $type = 'input-repeat';

    /**
     * 可用配置 `secondly,minutely,hourly,daily,weekdays,weekly,monthly,yearly`
     *
     * @param string $value
     * @return self
     */
    public function options(string $value = 'hourly,daily,weekly,monthly'): static
    {
        return $this->set('options', $value);
    }

    /**
     * 当不指定值时的说明。
     *
     * @param string $value
     * @return self
     */
    public function placeholder(string $value = '不重复'): static
    {
        return $this->set('placeholder', $value);
    }
}
