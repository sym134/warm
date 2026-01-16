<?php
namespace warm\admin\renderer;
/**
 * Calendar 日历日程
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/calendar
 */
class Calendar extends BaseRenderer
{
    public string $type = 'calendar';

    /**
     * 日历中展示日程，可设置静态数据或从上下文中取数据
     * startTime 和 endTime 格式参考文档，className 参考背景色
     *
     * @param array|string $value
     * @return self
     */
    public function schedules(array|string $value = []): self
    {
        return $this->set('schedules', $value);
    }

    /**
     * 日历中展示日程的颜色，参考背景色
     *
     * @param array $value
     * @return self
     */
    public function scheduleClassNames(array $value = ['bg-warning', 'bg-danger', 'bg-success', 'bg-info', 'bg-secondary']): self
    {
        return $this->set('scheduleClassNames', $value);
    }

    /**
     * 自定义日程展示
     *
     * @param array $value
     * @return self
     */
    public function scheduleAction(array $value = []): self
    {
        return $this->set('scheduleAction', $value);
    }

    /**
     * 放大模式
     *
     * @param bool $value
     * @return self
     */
    public function largeMode(bool $value = true): self
    {
        return $this->set('largeMode', $value);
    }

    /**
     * 今日激活时的自定义样式
     *
     * @param array $value
     * @return self
     */
    public function todayActiveStyle(array $value = []): self
    {
        return $this->set('todayActiveStyle', $value);
    }
}
