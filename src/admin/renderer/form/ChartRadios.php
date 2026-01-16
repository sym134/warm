<?php

declare(strict_types=1);

namespace warm\admin\renderer\form;

/**
 * ChartRadios 图表单选框
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/form/chart-radios
 */
class ChartRadios extends Radios
{
    public string $type = 'chart-radios';

    /**
     * echart 图表配置
     *
     * @param array $value
     * @return self
     */
    public function config(array $value = []): self
    {
        return $this->set('config', $value);
    }

    /**
     * 高亮的时候是否显示 tooltip
     *
     * @param bool $value
     * @return self
     */
    public function showTooltipOnHighlight(bool $value = true): self
    {
        return $this->set('showTooltipOnHighlight', $value);
    }

    /**
     * 图表数值字段名
     *
     * @param string $value
     * @return self
     */
    public function chartValueField(string $value = 'value'): self
    {
        return $this->set('chartValueField', $value);
    }
}
