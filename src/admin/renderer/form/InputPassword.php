<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\trait\FormItem;
use warm\admin\renderer\trait\OnEvent;

/**
 * InputPassword
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/input-password
 */
class InputPassword extends BaseRenderer
{
    use FormItem;
    use OnEvent;

    public string $type = 'input-password';

    /**
     * 是否展示密码显/隐按钮
     *
     * @param bool $value
     * @return self
     */
    public function revealPassword(bool $value = true): static
    {
        return $this->set('revealPassword', $value);
    }
}
