<?php
namespace warm\admin\renderer;
/**
 * Service
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/service
 */
class Service extends BaseRenderer
{
    public string $type = 'service';

    /**
     * 外层 Dom 的类名
     *
     * @param string $value
     * @return self
     */
    public function className(string $value = ''): self
    {
        return $this->set('className', $value);
    }

    /**
     * 内容容器
     *
     * @param mixed $value
     * @return self
     */
    public function body(mixed $value = null): self
    {
        return $this->set('body', $value);
    }

    /**
     * 初始化数据域接口地址
     *
     * @param mixed $value
     * @return self
     */
    public function api(mixed $value = null): self
    {
        return $this->set('api', $value);
    }

    /**
     * WebScocket 地址
     *
     * @param string $value
     * @return self
     */
    public function ws(string $value = ''): self
    {
        return $this->set('ws', $value);
    }

    /**
     * "onApiFetched" \
     *
     * @param mixed $value
     * @return self
     */
    public function dataProvider(mixed $value = null): self
    {
        return $this->set('dataProvider', $value);
    }

    /**
     * 是否默认拉取
     *
     * @param bool $value
     * @return self
     */
    public function initFetch(bool $value = true): self
    {
        return $this->set('initFetch', $value);
    }

    /**
     * 用来获取远程 Schema 接口地址
     *
     * @param mixed $value
     * @return self
     */
    public function schemaApi(mixed $value = null): self
    {
        return $this->set('schemaApi', $value);
    }

    /**
     * 是否默认拉取 Schema
     *
     * @param bool $value
     * @return self
     */
    public function initFetchSchema(bool $value = true): self
    {
        return $this->set('initFetchSchema', $value);
    }

    /**
     * 消息提示覆写，默认消息读取的是接口返回的 toast 提示文字，但是在此可以覆写它。
     *
     * @param array $value
     * @return self
     */
    public function messages(array $value = []): self
    {
        return $this->set('messages', $value);
    }

    /**
     * 轮询时间间隔，单位 ms(最低 1000)
     *
     * @param int|float $value
     * @return self
     */
    public function interval(int|float $value = 0): self
    {
        return $this->set('interval', $value);
    }

    /**
     * 配置轮询时是否显示加载动画
     *
     * @param bool $value
     * @return self
     */
    public function silentPolling(bool $value = true): self
    {
        return $this->set('silentPolling', $value);
    }

    /**
     * 配置停止轮询的条件
     *
     * @param mixed $value
     * @return self
     */
    public function stopAutoRefreshWhen(mixed $value = null): self
    {
        return $this->set('stopAutoRefreshWhen', $value);
    }

    /**
     * 是否以 Alert 的形式显示 api 接口响应的错误信息，默认展示
     *
     * @param bool $value
     * @return self
     */
    public function showErrorMsg(bool $value = true): self
    {
        return $this->set('showErrorMsg', $value);
    }
}
