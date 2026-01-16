<?php
namespace warm\admin\renderer;
/**
 * Wizard
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/wizard
 */
class Wizard extends BaseRenderer
{
    public string $type = 'wizard';

    /**
     * 展示模式，选择：`horizontal` 或者 `vertical`
     *
     * @param string $value
     * @return self
     */
    public function mode(string $value = 'horizontal'): self
    {
        return $this->set('mode', $value);
    }

    /**
     * 最后一步保存的接口。
     *
     * @param mixed $value
     * @return self
     */
    public function api(mixed $value = null): self
    {
        return $this->set('api', $value);
    }

    /**
     * 初始化数据接口
     *
     * @param mixed $value
     * @return self
     */
    public function initApi(mixed $value = null): self
    {
        return $this->set('initApi', $value);
    }

    /**
     * 初始是否拉取数据。
     *
     * @param mixed $value
     * @return self
     */
    public function initFetch(mixed $value = null): self
    {
        return $this->set('initFetch', $value);
    }

    /**
     * 初始是否拉取数据，通过表达式来配置
     *
     * @param mixed $value
     * @return self
     */
    public function initFetchOn(mixed $value = null): self
    {
        return $this->set('initFetchOn', $value);
    }

    /**
     * 上一步按钮文本
     *
     * @param string $value
     * @return self
     */
    public function actionPrevLabel(string $value = '上一步'): self
    {
        return $this->set('actionPrevLabel', $value);
    }

    /**
     * 下一步按钮文本
     *
     * @param string $value
     * @return self
     */
    public function actionNextLabel(string $value = '下一步'): self
    {
        return $this->set('actionNextLabel', $value);
    }

    /**
     * 保存并下一步按钮文本
     *
     * @param string $value
     * @return self
     */
    public function actionNextSaveLabel(string $value = '保存并下一步'): self
    {
        return $this->set('actionNextSaveLabel', $value);
    }

    /**
     * 完成按钮文本
     *
     * @param string $value
     * @return self
     */
    public function actionFinishLabel(string $value = '完成'): self
    {
        return $this->set('actionFinishLabel', $value);
    }

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
     * 按钮 CSS 类名
     *
     * @param string $value
     * @return self
     */
    public function actionClassName(string $value = 'btn-sm btn-default'): self
    {
        return $this->set('actionClassName', $value);
    }

    /**
     * 操作完后刷新目标对象。请填写目标组件设置的 name 值，如果填写为 `window` 则让当前页面整体刷新。
     *
     * @param string $value
     * @return self
     */
    public function reload(string $value = ''): self
    {
        return $this->set('reload', $value);
    }

    /**
     * 操作完后跳转。
     *
     * @param mixed $value
     * @return self
     */
    public function redirect(mixed $value = null): self
    {
        return $this->set('redirect', $value);
    }

    /**
     * 可以把数据提交给别的组件而不是自己保存。请填写目标组件设置的 name 值，如果填写为 `window` 则把数据同步到地址栏上，同时依赖这些数据的组件会自动重新刷新。
     *
     * @param string $value
     * @return self
     */
    public function target(string $value = 'false'): self
    {
        return $this->set('target', $value);
    }

    /**
     * 数组，配置步骤信息
     *
     * @param array $value
     * @return self
     */
    public function steps(array $value = []): self
    {
        return $this->set('steps', $value);
    }

    /**
     * 起始默认值，从第几步开始。可支持模版，但是只有在组件创建时渲染模版并设置当前步数，在之后组件被刷新时，当前 step 不会根据 startStep 改变
     *
     * @param string $value
     * @return self
     */
    public function startStep(string $value = '1'): self
    {
        return $this->set('startStep', $value);
    }
}
