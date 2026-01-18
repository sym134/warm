<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\trait\FormItem;

/**
 * InputVerificationCode 验证码输入框
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/input-verification-code
 */
class InputVerificationCode extends BaseRenderer
{
    use FormItem;

    public string $type = 'input-verification-code';

    /**
     * 验证码的长度，根据长度渲染对应个数的输入框
     *
     * @param int|float $value
     * @return self
     */
    public function length(int|float $value = 6): static
    {
        return $this->set('length', $value);
    }

    /**
     * 是否是密码模式
     *
     * @param bool $value
     * @return self
     */
    public function masked(bool $value = true): static
    {
        return $this->set('masked', $value);
    }

    /**
     * 分隔符，支持表达式, 表达式`只`可以访问 index、character 变量, 参考自定义分隔符
     *
     * @param string $value
     * @return self
     */
    public function separator(string $value = ''): static
    {
        return $this->set('separator', $value);
    }
}
