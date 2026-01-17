<?php

namespace warm\admin\renderer;

use warm\admin\renderer\trait\OnEvent;

/**
 * Action 行为按钮
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/action
 */
class Action extends BaseRenderer
{
    use OnEvent;
    public string $type = 'action';

    /**
     * 【必填】这是 action 最核心的配置，来指定该 action 的作用类型
     * 支持：ajax、link、url、drawer、dialog、confirm、cancel、prev、next、copy、close
     *
     * @param string $value
     * @return self
     */
    public function actionType(string $value): static
    {
        return $this->set('actionType', $value);
    }

//    /**
//     * 按钮文本。可用 ${xxx} 取值
//     *
//     * @param string $value
//     * @return self
//     */
//    public function label(string $value): static
//    {
//        return $this->set('label', $value);
//    }

    /**
     * 按钮样式
     * 支持：link、primary、secondary、info、success、warning、danger、light、dark、default
     *
     * @param string $value
     * @return self
     */
    public function level(string $value = 'default'): static
    {
        return $this->set('level', $value);
    }

    /**
     * 按钮大小
     * 支持：xs、sm、md、lg
     *
     * @param string $value
     * @return self
     */
    public function size(string $value): static
    {
        return $this->set('size', $value);
    }

    /**
     * 设置图标，例如 fa fa-plus
     *
     * @param string $value
     * @return self
     */
    public function icon(string $value): static
    {
        return $this->set('icon', $value);
    }

    /**
     * 给图标上添加类名
     *
     * @param string $value
     * @return self
     */
    public function iconClassName(string $value): static
    {
        return $this->set('iconClassName', $value);
    }

    /**
     * 在按钮文本右侧设置图标，例如 fa fa-plus
     *
     * @param string $value
     * @return self
     */
    public function rightIcon(string $value): static
    {
        return $this->set('rightIcon', $value);
    }

    /**
     * 给右侧图标上添加类名
     *
     * @param string $value
     * @return self
     */
    public function rightIconClassName(string $value): static
    {
        return $this->set('rightIconClassName', $value);
    }

    /**
     * 按钮是否高亮
     *
     * @param bool $value
     * @return self
     */
    public function active(bool $value = true): static
    {
        return $this->set('active', $value);
    }

    /**
     * 按钮高亮时的样式，配置支持同 level
     *
     * @param string $value
     * @return self
     */
    public function activeLevel(string $value): static
    {
        return $this->set('activeLevel', $value);
    }

    /**
     * 给按钮高亮添加类名
     *
     * @param string $value
     * @return self
     */
    public function activeClassName(string $value = 'is-active'): static
    {
        return $this->set('activeClassName', $value);
    }

    /**
     * 用 display:"block" 来显示按钮
     *
     * @param bool $value
     * @return self
     */
    public function block(bool $value = true): static
    {
        return $this->set('block', $value);
    }

    /**
     * 当设置后，操作在开始前会询问用户。可用 ${xxx} 取值
     *
     * @param string $value
     * @return self
     */
    public function confirmText(string $value): static
    {
        return $this->set('confirmText', $value);
    }

    /**
     * 确认框标题，前提是 confirmText 有内容，支持模版语法
     *
     * @param string $value
     * @return self
     */
    public function confirmTitle(string $value): static
    {
        return $this->set('confirmTitle', $value);
    }

    /**
     * 指定此次操作完后，需要刷新的目标组件名字（组件的name值，自己配置的），多个请用 , 号隔开
     *
     * @param string $value
     * @return self
     */
    public function reload(string $value): static
    {
        return $this->set('reload', $value);
    }

    /**
     * 鼠标停留时弹出该段文字，也可以配置对象类型：字段为 title 和 content。可用 ${xxx} 取值
     *
     * @param string|array $value
     * @return self
     */
    public function tooltip(string|array $value): static
    {
        return $this->set('tooltip', $value);
    }

    /**
     * 被禁用后鼠标停留时弹出该段文字，也可以配置对象类型：字段为 title 和 content。可用 ${xxx} 取值
     *
     * @param string|array $value
     * @return self
     */
    public function disabledTip(string|array $value): static
    {
        return $this->set('disabledTip', $value);
    }

    /**
     * 如果配置了 tooltip 或者 disabledTip，指定提示信息位置，可配置 top、bottom、left、right
     *
     * @param string $value
     * @return self
     */
    public function tooltipPlacement(string $value = 'top'): static
    {
        return $this->set('tooltipPlacement', $value);
    }

    /**
     * 当 action 配置在 dialog 或 drawer 的 actions 中时，配置为 true 指定此次操作完后关闭当前 dialog 或 drawer
     * 当值为字符串，并且是祖先层弹框的名字的时候，会把祖先弹框关闭掉
     *
     * @param bool|string $value
     * @return self
     */
    public function close(bool|string $value = true): static
    {
        return $this->set('close', $value);
    }

    /**
     * 配置字符串数组，指定在 form 中进行操作之前，需要指定的字段名的表单项通过验证
     *
     * @param array $value
     * @return self
     */
    public function required(array $value): static
    {
        return $this->set('required', $value);
    }

    /**
     * 键盘快捷键触发
     *
     * @param string $value
     * @return self
     */
    public function hotKey(string $value): static
    {
        return $this->set('hotKey', $value);
    }

    /**
     * 自定义点击事件，字符串会转成 JavaScript 函数
     *
     * @param string $value
     * @return self
     */
    public function onClick(string $value): static
    {
        return $this->set('onClick', $value);
    }

    /**
     * 子内容，Action 也可以作为容器组件
     *
     * @param mixed $value
     * @return self
     */
    public function body(mixed $value): static
    {
        return $this->set('body', $value);
    }

    /**
     * 请求地址，参考 api 格式说明
     *
     * @param string|array $value
     * @return self
     */
    public function api(string|array $value): static
    {
        return $this->set('api', $value);
    }

    /**
     * 指定当前请求结束后跳转的路径，可用 ${xxx} 取值
     *
     * @param string $value
     * @return self
     */
    public function redirect(string $value): static
    {
        return $this->set('redirect', $value);
    }

    /**
     * 如果 ajax 类型的，当 ajax 返回正常后，还能接着弹出一个 dialog 做其他交互
     *
     * @param array $value
     * @return self
     */
    public function feedback(array $value): static
    {
        return $this->set('feedback', $value);
    }

    /**
     * success：ajax 操作成功后提示，可以不指定，不指定时以 api 返回为准。failed：ajax 操作失败提示
     *
     * @param array $value
     * @return self
     */
    public function messages(array $value): static
    {
        return $this->set('messages', $value);
    }

    /**
     * 下载文件名
     *
     * @param string $value
     * @return self
     */
    public function downloadFileName(string $value): static
    {
        return $this->set('downloadFileName', $value);
    }

    /**
     * 指定弹框内容，格式可参考 Dialog
     *
     * @param mixed $value
     * @return self
     */
    public function dialog(mixed $value): static
    {
        return $this->set('dialog', $value);
    }

    /**
     * 指定抽屉内容，格式可参考 Drawer
     *
     * @param mixed $value
     * @return self
     */
    public function drawer(mixed $value): static
    {
        return $this->set('drawer', $value);
    }

    /**
     * 可以用来设置下一条数据的条件，默认为 true
     *
     * @param bool $value
     * @return self
     */
    public function nextCondition(bool $value = true): static
    {
        return $this->set('nextCondition', $value);
    }

    /**
     * 用来指定跳转地址，跟 url 不同的是，这是单页跳转方式
     *
     * @param string $value
     * @return self
     */
    public function link(string $value): static
    {
        return $this->set('link', $value);
    }

    /**
     * 按钮点击后，会打开指定页面。可用 ${xxx} 取值
     *
     * @param string $value
     * @return self
     */
    public function url(string $value): static
    {
        return $this->set('url', $value);
    }

    /**
     * 如果为 true 将在新 tab 页面打开
     *
     * @param bool $value
     * @return self
     */
    public function blank(bool $value = true): static
    {
        return $this->set('blank', $value);
    }

    /**
     * 收件人邮箱，可用 ${xxx} 取值
     *
     * @param string $value
     * @return self
     */
    public function to(string $value): static
    {
        return $this->set('to', $value);
    }

    /**
     * 抄送邮箱，可用 ${xxx} 取值
     *
     * @param string $value
     * @return self
     */
    public function cc(string $value): static
    {
        return $this->set('cc', $value);
    }

    /**
     * 匿名抄送邮箱，可用 ${xxx} 取值
     *
     * @param string $value
     * @return self
     */
    public function bcc(string $value): static
    {
        return $this->set('bcc', $value);
    }

    /**
     * 邮件主题，可用 ${xxx} 取值
     *
     * @param string $value
     * @return self
     */
    public function subject(string $value): static
    {
        return $this->set('subject', $value);
    }

    /**
     * 指定复制的内容。可用 ${xxx} 取值
     *
     * @param string $value
     * @return self
     */
    public function content(string $value): static
    {
        return $this->set('content', $value);
    }

    /**
     * 复制的格式，默认是文本
     *
     * @param string $value
     * @return self
     */
    public function copyFormat(string $value): static
    {
        return $this->set('copyFormat', $value);
    }

    /**
     * 需要刷新的目标组件名字
     *
     * @param string $value
     * @return self
     */
    public function target(string $value): static
    {
        return $this->set('target', $value);
    }

    /**
     * 倒计时（单位是秒）
     *
     * @param int $value
     * @return self
     */
    public function countDown(int $value): static
    {
        return $this->set('countDown', $value);
    }

    /**
     * 倒计时显示的文本，${timeLeft} 为剩余时间
     *
     * @param string $value
     * @return self
     */
    public function countDownTpl(string $value): static
    {
        return $this->set('countDownTpl', $value);
    }
}
