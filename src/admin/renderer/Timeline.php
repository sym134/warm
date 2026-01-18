<?php
namespace warm\admin\renderer;
/**
 * Timeline 时间线
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/timeline
 */
class Timeline extends BaseRenderer
{
    public string $type = 'timeline';

    /**
     * 配置节点数据
     *
     * @param array $value
     * @return self
     */
    public function items(array $value = []): static
    {
        return $this->set('items', $value);
    }

    /**
     * 数据源，可通过数据映射获取当前数据域变量、或者配置 API 对象
     *
     * @param mixed $value
     * @return self
     */
    public function source(mixed $value = null): static
    {
        return $this->set('source', $value);
    }

    /**
     * `alternate` \
     *
     * @param mixed $value
     * @return self
     */
    public function mode(mixed $value = null): static
    {
        return $this->set('mode', $value);
    }

    /**
     * `vertical`
     *
     * @param mixed $value
     * @return self
     */
    public function direction(mixed $value = null): static
    {
        return $this->set('direction', $value);
    }

    /**
     * 根据时间倒序显示
     *
     * @param bool $value
     * @return self
     */
    public function reverse(bool $value = true): static
    {
        return $this->set('reverse', $value);
    }

    /**
     * 统一配置的节点图标 CSS 类（3.4.0 版本支持）名
     *
     * @param string $value
     * @return self
     */
    public function iconClassName(string $value = ''): static
    {
        return $this->set('iconClassName', $value);
    }

    /**
     * 统一配置的节点时间 CSS 类（3.4.0 版本支持）名
     *
     * @param string $value
     * @return self
     */
    public function timeClassName(string $value = ''): static
    {
        return $this->set('timeClassName', $value);
    }

    /**
     * 统一配置的节点标题 CSS 类（3.4.0 版本支持）名
     *
     * @param string $value
     * @return self
     */
    public function titleClassName(string $value = ''): static
    {
        return $this->set('titleClassName', $value);
    }

    /**
     * 统一配置的节点详情 CSS 类（3.4.0 版本支持）名
     *
     * @param string $value
     * @return self
     */
    public function detailClassName(string $value = ''): static
    {
        return $this->set('detailClassName', $value);
    }

    /**
     * 统一配置子节点渲染卡片模板。配置后  itemTitleSchema、titleClassName、detailClassName 将不生效。配置后 timeline item 中的数据都将可以在 cardSchema 中通过数据方式引用。如果子节点也配置了 cardSchema，子节点的 cardSchema 优先级高于 timeline 的 cardSchema。（v6.12.1 之后支持）
     *
     * @param string $value
     * @return self
     */
    public function cardSchema(string $value = ''): static
    {
        return $this->set('cardSchema', $value);
    }
}
