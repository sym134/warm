<?php
namespace warm\admin\renderer;
/**
 * Log 实时日志
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/log
 */
class Log extends BaseRenderer
{
    public string $type = 'log';

    /**
     * 展示区域高度
     *
     * @param int|float $value
     * @return self
     */
    public function height(int|float $value = 500): static
    {
        return $this->set('height', $value);
    }

    /**
     * 设置组件样式
     *
     * @param mixed $value
     * @return $this
     */
    public function className(mixed $value): static
    {
        return $this->set('className', $value);
    }

    /**
     * 是否自动滚动
     *
     * @param bool $value
     * @return self
     */
    public function autoScroll(bool $value = true): static
    {
        return $this->set('autoScroll', $value);
    }

    /**
     * 是否禁用 ansi 颜色支持
     *
     * @param bool $value
     * @return self
     */
    public function disableColor(bool $value = true): static
    {
        return $this->set('disableColor', $value);
    }

    /**
     * 加载中的文字
     *
     * @param string $value
     * @return self
     */
    public function placeholder(string $value = ''): static
    {
        return $this->set('placeholder', $value);
    }

    /**
     * 返回内容的字符编码
     *
     * @param string $value
     * @return self
     */
    public function encoding(string $value = 'utf-8'): static
    {
        return $this->set('encoding', $value);
    }

    /**
     * 接口
     *
     * @param string $value
     * @return self
     */
    public function source(string $value = ''): static
    {
        return $this->set('source', $value);
    }

    /**
     * fetch 的 credentials 设置
     *
     * @param string $value
     * @return self
     */
    public function credentials(string $value = 'include'): static
    {
        return $this->set('credentials', $value);
    }

    /**
     * 设置每行高度，将会开启虚拟渲染
     *
     * @param int|float $value
     * @return self
     */
    public function rowHeight(int|float $value = 0): static
    {
        return $this->set('rowHeight', $value);
    }

    /**
     * 最大显示行数
     *
     * @param int|float $value
     * @return self
     */
    public function maxLength(int|float $value = 0): static
    {
        return $this->set('maxLength', $value);
    }

    /**
     * 可选日志操作：['stop','restart',clear','showLineNumber','filter']
     *
     * @param array $value
     * @return self
     */
    public function operation(array $value = []): static
    {
        return $this->set('operation', $value);
    }
}
