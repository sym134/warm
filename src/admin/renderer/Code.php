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
     * 外层 CSS 类名
     *
     * @param string $value
     * @return self
     */
    public function className(string $value = ''): self
    {
        return $this->set('className', $value);
    }

    /**
     * 显示的颜色值
     *
     * @param string $value
     * @return self
     */
    public function value(string $value = ''): self
    {
        return $this->set('value', $value);
    }

    /**
     * 在其他组件中，时，用作变量映射
     *
     * @param string $value
     * @return self
     */
    public function name(string $value = ''): self
    {
        return $this->set('name', $value);
    }

    /**
     * 所使用的高亮语言，默认是 plaintext
     *
     * @param string $value
     * @return self
     */
    public function language(string $value = ''): self
    {
        return $this->set('language', $value);
    }

    /**
     * 默认 tab 大小
     *
     * @param int|float $value
     * @return self
     */
    public function tabSize(int|float $value = 4): self
    {
        return $this->set('tabSize', $value);
    }

    /**
     * 主题，还有 'vs-dark'
     *
     * @param string $value
     * @return self
     */
    public function editorTheme(string $value = 'vs'): self
    {
        return $this->set('editorTheme', $value);
    }

    /**
     * 是否折行
     *
     * @param string $value
     * @return self
     */
    public function wordWrap(string $value = 'true'): self
    {
        return $this->set('wordWrap', $value);
    }

    /**
     * 
     *
     * @param mixed $value
     * @return self
     */
    public function maxHeight(mixed $value = null): self
    {
        return $this->set('maxHeight', $value);
    }
}
