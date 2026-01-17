<?php
namespace warm\admin\renderer;
/**
 * Steps
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/steps
 */
class Steps extends BaseRenderer
{
    public string $type = 'steps';

    /**
     * 数组，配置步骤信息
     *
     * @param array $value
     * @return self
     */
    public function steps(array $value = []): static
    {
        return $this->set('steps', $value);
    }

    /**
     * 选项组源，可通过数据映射获取当前数据域变量、或者配置 API 对象
     *
     * @param mixed $value
     * @return self
     */
    public function source(mixed $value = null): static
    {
        return $this->set('source', $value);
    }

    /**
     * 关联上下文变量
     *
     * @param string $value
     * @return self
     */
    public function name(string $value = ''): static
    {
        return $this->set('name', $value);
    }

    /**
     * `-`
     *
     * @param mixed $value
     * @return self
     */
    public function value(mixed $value = null): static
    {
        return $this->set('value', $value);
    }

    /**
     * `-`
     *
     * @param mixed $value
     * @return self
     */
    public function status(mixed $value = null): static
    {
        return $this->set('status', $value);
    }

    /**
     * 自定义类名
     *
     * @param mixed $value
     * @return self
     */
    public function className(mixed $value = '-'): static
    {
        return $this->set('className', $value);
    }

    /**
     * `simple`
     *
     * @param mixed $value
     * @return self
     */
    public function mode(mixed $value = null): static
    {
        return $this->set('mode', $value);
    }

    /**
     * `horizontal`
     *
     * @param mixed $value
     * @return self
     */
    public function labelPlacement(mixed $value = null): static
    {
        return $this->set('labelPlacement', $value);
    }

    /**
     * 点状步骤条
     *
     * @param bool $value
     * @return self
     */
    public function progressDot(bool $value = true): static
    {
        return $this->set('progressDot', $value);
    }
}
