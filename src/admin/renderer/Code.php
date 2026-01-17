<?php
namespace warm\admin\renderer;
/**
 * Code
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/code
 */
class Code extends BaseRenderer
{
    public string $type = 'code';

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
     * 显示的颜色值
     *
     * @param string $value
     * @return self
     */
    public function value(string $value = ''): static
    {
        return $this->set('value', $value);
    }

    /**
     * 在其他组件中，时，用作变量映射
     *
     * @param string $value
     * @return self
     */
    public function name(string $value = ''): static
    {
        return $this->set('name', $value);
    }

    /**
     * 所使用的高亮语言，默认是 plaintext
     *
     * @param string $value
     * @return self
     */
    public function language(string $value = ''): static
    {
        return $this->set('language', $value);
    }

    /**
     * 默认 tab 大小
     *
     * @param int|float $value
     * @return self
     */
    public function tabSize(int|float $value = 4): static
    {
        return $this->set('tabSize', $value);
    }

    /**
     * 主题，还有 'vs-dark'
     *
     * @param string $value
     * @return self
     */
    public function editorTheme(string $value = 'vs'): static
    {
        return $this->set('editorTheme', $value);
    }

    /**
     * 是否折行
     *
     * @param string $value
     * @return self
     */
    public function wordWrap(string $value = 'true'): static
    {
        return $this->set('wordWrap', $value);
    }

    /**
     * 
     *
     * @param mixed $value
     * @return self
     */
    public function maxHeight(mixed $value = null): static
    {
        return $this->set('maxHeight', $value);
    }
}
