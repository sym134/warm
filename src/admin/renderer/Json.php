<?php
namespace warm\admin\renderer;
/**
 * Json
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/json
 */
class Json extends BaseRenderer
{
    public string $type = 'json';

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
     * json 值，如果是 string 会自动 parse
     *
     * @param mixed $value
     * @return self
     */
    public function value(mixed $value = null): self
    {
        return $this->set('value', $value);
    }

    /**
     * 通过数据映射获取数据链中的值
     *
     * @param string $value
     * @return self
     */
    public function source(string $value = ''): self
    {
        return $this->set('source', $value);
    }

    /**
     * 占位文本
     *
     * @param string $value
     * @return self
     */
    public function placeholder(string $value = '-'): self
    {
        return $this->set('placeholder', $value);
    }

    /**
     * 默认展开的层级
     *
     * @param int|bool $value
     * @return self
     */
    public function levelExpand(int|bool $value = 1): self
    {
        return $this->set('levelExpand', $value);
    }

    /**
     * 主题，可选`twilight`和`eighties`
     *
     * @param string $value
     * @return self
     */
    public function jsonTheme(string $value = 'twilight'): self
    {
        return $this->set('jsonTheme', $value);
    }

    /**
     * 是否可修改
     *
     * @param bool $value
     * @return self
     */
    public function mutable(bool $value = true): self
    {
        return $this->set('mutable', $value);
    }

    /**
     * 是否显示数据类型
     *
     * @param bool $value
     * @return self
     */
    public function displayDataTypes(bool $value = true): self
    {
        return $this->set('displayDataTypes', $value);
    }

    /**
     * 设置字符串的最大展示长度，点击字符串可以切换全量/部分展示方式，默认展示全量字符串
     *
     * @param int|bool $value
     * @return self
     */
    public function ellipsisThreshold(int|bool $value = false): self
    {
        return $this->set('ellipsisThreshold', $value);
    }
}
