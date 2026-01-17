<?php
namespace warm\admin\renderer;
/**
 * Collapse 折叠器
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/collapse
 */
class Collapse extends BaseRenderer
{up';

    /**
     * 禁用
     *
     * @param bool $value
     * @return self
     */
    public function disabled(bool $value = true): static
    {
        return $this->set('disabled', $value);
    }

    /**
     * 初始状态是否折叠
     *
     * @param bool $value
     * @return self
     */
    public function collapsed(bool $value = true): static
    {
        return $this->set('collapsed', $value);
    }

    /**
     * 标识
     *
     * @param string|int $value
     * @return self
     */
    public function key(string|int $value): static
    {
        return $this->set('key', $value);
    }

    /**
     * 标题
     *
     * @param string|array $value
     * @return self
     */
    public function header(string|array $value): static
    {
        return $this->set('header', $value);
    }

    /**
     * 内容
     *
     * @param string|array $value
     * @return self
     */
    public function body(string|array $value): static
    {
        return $this->set('body', $value);
    }

    /**
     * 是否展示图标
     *
     * @param bool $value
     * @return self
     */
    public function showArrow(bool $value = true): static
    {
        return $this->set('showArrow', $value);
    }
}
