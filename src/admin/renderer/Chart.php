<?php
namespace warm\admin\renderer;
use warm\admin\renderer\trait\DataDomain;
use warm\admin\renderer\trait\OnEvent;

/**
 * Chart
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/chart
 */
class Chart extends BaseRenderer
{
    use OnEvent;
    use DataDomain;

    public string $type = 'chart';
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
     * 内容容器
     *
     * @param mixed $value
     * @return static
     */
    public function body(mixed $value = null): static
    {
        return $this->set('body', $value);
    }

    /**
     * 配置项接口地址
     *
     * @param mixed $value
     * @return static
     */
    public function api(mixed $value = null): static
    {
        return $this->set('api', $value);
    }

    /**
     * 通过数据映射获取数据链中变量值作为配置
     *
     * @param mixed $value
     * @return static
     */
    public function source(mixed $value = null): static
    {
        return $this->set('source', $value);
    }

    /**
     * 组件初始化时，是否请求接口
     *
     * @param bool $value
     * @return static
     */
    public function initFetch(bool $value = true): static
    {
        return $this->set('initFetch', $value);
    }

    /**
     * 刷新时间(最小 1000)
     *
     * @param int|float $value
     * @return static
     */
    public function interval(int|float $value = 0): static
    {
        return $this->set('interval', $value);
    }

    /**
     * 设置 eschars 的配置项,当为`string`的时候可以设置 function 等配置项
     *
     * @param mixed $value
     * @return static
     */
    public function config(mixed $value = null): static
    {
        return $this->set('config', $value);
    }

    /**
     * 设置根元素的 style
     *
     * @param array $value
     * @return static
     */
    public function style(array $value = []): static
    {
        return $this->set('style', $value);
    }

    /**
     * 设置根元素的宽度
     *
     * @param string|int $value
     * @return static
     */
    public function width(string|int $value = ''): static
    {
        return $this->set('width', $value);
    }

    /**
     * 设置根元素的高度
     *
     * @param string|int $value
     * @return static
     */
    public function height(string|int $value = ''): static
    {
        return $this->set('height', $value);
    }

    /**
     * 每次更新是完全覆盖配置项还是追加？
     *
     * @param bool $value
     * @return static
     */
    public function replaceChartOption(bool $value = true): static
    {
        return $this->set('replaceChartOption', $value);
    }

    /**
     * 当这个表达式的值有变化时更新图表
     *
     * @param string $value
     * @return static
     */
    public function trackExpression(string $value = ''): static
    {
        return $this->set('trackExpression', $value);
    }

    /**
     * 自定义 echart config 转换，函数签名：function(config, echarts, data) {return config;} 配置时直接写函数体。其中 config 是当前 echart 配置，echarts 就是 echarts 对象，data 为上下文数据。
     *
     * @param string $value
     * @return static
     */
    public function dataFilter(string $value = ''): static
    {
        return $this->set('dataFilter', $value);
    }

    /**
     * 地图 geo json 地址
     *
     * @param mixed $value
     * @return static
     */
    public function mapURL(mixed $value = null): static
    {
        return $this->set('mapURL', $value);
    }

    /**
     * 地图名称
     *
     * @param string|int $value
     * @return static
     */
    public function mapName(string|int $value = ''): static
    {
        return $this->set('mapName', $value);
    }

    /**
     * 加载百度地图
     *
     * @param bool $value
     * @return static
     */
    public function loadBaiduMap(bool $value = true): static
    {
        return $this->set('loadBaiduMap', $value);
    }

    /**
     * 点击行为
     *
     * @param array $array
     * @return static
     */
    public function clickAction(array $array): static
    {
        return $this->set('clickAction', $array);
    }
}
