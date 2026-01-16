<?php
namespace warm\admin\renderer;
/**
 * Page
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/page
 */
class Page extends BaseRenderer
{
    public string $type = 'page';

    /**
     * 页面标题
     *
     * @param mixed $value
     * @return self
     */
    public function title(mixed $value = null): self
    {
        return $this->set('title', $value);
    }

    /**
     * 页面副标题
     *
     * @param mixed $value
     * @return self
     */
    public function subTitle(mixed $value = null): self
    {
        return $this->set('subTitle', $value);
    }

    /**
     * 标题附近会出现一个提示图标，鼠标放上去会提示该内容。
     *
     * @param mixed $value
     * @return self
     */
    public function remark(mixed $value = null): self
    {
        return $this->set('remark', $value);
    }

    /**
     * 往页面的边栏区域加内容
     *
     * @param mixed $value
     * @return self
     */
    public function aside(mixed $value = null): self
    {
        return $this->set('aside', $value);
    }

    /**
     * 页面的边栏区域宽度是否可调整
     *
     * @param bool $value
     * @return self
     */
    public function asideResizor(bool $value = true): self
    {
        return $this->set('asideResizor', $value);
    }

    /**
     * 页面边栏区域的最小宽度
     *
     * @param int|float $value
     * @return self
     */
    public function asideMinWidth(int|float $value = 0): self
    {
        return $this->set('asideMinWidth', $value);
    }

    /**
     * 页面边栏区域的最大宽度
     *
     * @param int|float $value
     * @return self
     */
    public function asideMaxWidth(int|float $value = 0): self
    {
        return $this->set('asideMaxWidth', $value);
    }

    /**
     * 用来控制边栏固定与否
     *
     * @param bool $value
     * @return self
     */
    public function asideSticky(bool $value = true): self
    {
        return $this->set('asideSticky', $value);
    }

    /**
     * `"left"`
     *
     * @param mixed $value
     * @return self
     */
    public function asidePosition(mixed $value = null): self
    {
        return $this->set('asidePosition', $value);
    }

    /**
     * 往页面的右上角加内容，需要注意的是，当有 title 时，该区域在右上角，没有时该区域在顶部
     *
     * @param mixed $value
     * @return self
     */
    public function toolbar(mixed $value = null): self
    {
        return $this->set('toolbar', $value);
    }

    /**
     * 往页面的内容区域加内容
     *
     * @param mixed $value
     * @return self
     */
    public function body(mixed $value = null): self
    {
        return $this->set('body', $value);
    }

    /**
     * 外层 dom 类名
     *
     * @param string $value
     * @return self
     */
    public function className(string $value = ''): self
    {
        return $this->set('className', $value);
    }

    /**
     * 自定义 CSS 变量，请参考[样式](../style)
     *
     * @param array $value
     * @return self
     */
    public function cssVars(array $value = []): self
    {
        return $this->set('cssVars', $value);
    }

    /**
     * Toolbar dom 类名
     *
     * @param string $value
     * @return self
     */
    public function toolbarClassName(string $value = 'v-middle wrapper text-right bg-light b-b'): self
    {
        return $this->set('toolbarClassName', $value);
    }

    /**
     * Body dom 类名
     *
     * @param string $value
     * @return self
     */
    public function bodyClassName(string $value = 'wrapper'): self
    {
        return $this->set('bodyClassName', $value);
    }

    /**
     * Aside dom 类名
     *
     * @param string $value
     * @return self
     */
    public function asideClassName(string $value = 'w page-aside-region bg-auto'): self
    {
        return $this->set('asideClassName', $value);
    }

    /**
     * Header 区域 dom 类名
     *
     * @param string $value
     * @return self
     */
    public function headerClassName(string $value = 'bg-light b-b wrapper'): self
    {
        return $this->set('headerClassName', $value);
    }

    /**
     * Page 用来获取初始数据的 api。返回的数据可以整个 page 级别使用。
     *
     * @param mixed $value
     * @return self
     */
    public function initApi(mixed $value = null): self
    {
        return $this->set('initApi', $value);
    }

    /**
     * 是否起始拉取 initApi
     *
     * @param bool $value
     * @return self
     */
    public function initFetch(bool $value = true): self
    {
        return $this->set('initFetch', $value);
    }

    /**
     * 是否起始拉取 initApi, 通过表达式配置
     *
     * @param mixed $value
     * @return self
     */
    public function initFetchOn(mixed $value = null): self
    {
        return $this->set('initFetchOn', $value);
    }

    /**
     * 刷新时间(最小 1000)
     *
     * @param int|float $value
     * @return self
     */
    public function interval(int|float $value = 3000): self
    {
        return $this->set('interval', $value);
    }

    /**
     * 配置刷新时是否显示加载动画
     *
     * @param bool $value
     * @return self
     */
    public function silentPolling(bool $value = true): self
    {
        return $this->set('silentPolling', $value);
    }

    /**
     * 通过表达式来配置停止刷新的条件
     *
     * @param mixed $value
     * @return self
     */
    public function stopAutoRefreshWhen(mixed $value = null): self
    {
        return $this->set('stopAutoRefreshWhen', $value);
    }

    /**
     * 下拉刷新配置（仅用于移动端）
     *
     * @param array $value
     * @return self
     */
    public function pullRefresh(array $value = []): self
    {
        return $this->set('pullRefresh', $value);
    }
}
