<?php

namespace warm\admin\renderer\expand\renderer;

/**
 * Action 行为按钮 https://aisuda.bce.baidu.com/amis/zh-CN/components/action
 *
 * Action 行为按钮，是触发页面行为的主要方法之一
 * 
 * @author slowlyo
 * @version 6.13.0
 */
class Action extends BaseRenderer
{
    public function __construct()
    {
        $this->set('type', 'action');
    }

    /**
     * 指定按钮类型
     *
     * @param mixed $value
     * @return $this
     */
    public function type(mixed $value = 'action'): static
    {
        return $this->set('type', $value);
    }

    /**
     * 【必填】这是 action 最核心的配置，来指定该 action 的作用类型，支持：`ajax`、`link`、`url`、`drawer`、`dialog`、`confirm`、`cancel`、`prev`、`next`、`copy`、`close`。
     *
     * @param mixed $value
     * @return $this
     */
    public function actionType(mixed $value): static
    {
        return $this->set('actionType', $value);
    }

    /**
     * 按钮文本。可用 `${xxx}` 取值。
     *
     * @param mixed $value
     * @return $this
     */
    public function label(mixed $value): static
    {
        return $this->set('label', $value);
    }

    /**
     * 按钮样式，支持：`link`、`primary`、`secondary`、`info`、`success`、`warning`、`danger`、`light`、`dark`、`default`。
     *
     * @param mixed $value
     * @return $this
     */
    public function level(mixed $value): static
    {
        return $this->set('level', $value);
    }

    /**
     * 按钮大小，支持：`xs`、`sm`、`md`、`lg`。
     *
     * @param mixed $value
     * @return $this
     */
    public function size(mixed $value): static
    {
        return $this->set('size', $value);
    }

    /**
     * 设置图标，例如`fa fa-plus`。
     *
     * @param mixed $value
     * @return $this
     */
    public function icon(mixed $value): static
    {
        return $this->set('icon', $value);
    }

    /**
     * 给图标上添加类名。
     *
     * @param mixed $value
     * @return $this
     */
    public function iconClassName(mixed $value): static
    {
        return $this->set('iconClassName', $value);
    }

    /**
     * 在按钮文本右侧设置图标，例如`fa fa-plus`。
     *
     * @param mixed $value
     * @return $this
     */
    public function rightIcon(mixed $value): static
    {
        return $this->set('rightIcon', $value);
    }

    /**
     * 给右侧图标上添加类名。
     *
     * @param mixed $value
     * @return $this
     */
    public function rightIconClassName(mixed $value): static
    {
        return $this->set('rightIconClassName', $value);
    }

    /**
     * 按钮是否高亮。
     *
     * @param mixed $value
     * @return $this
     */
    public function active(mixed $value = true): static
    {
        return $this->set('active', $value);
    }

    /**
     * 按钮高亮时的样式，配置支持同`level`。
     *
     * @param mixed $value
     * @return $this
     */
    public function activeLevel(mixed $value): static
    {
        return $this->set('activeLevel', $value);
    }

    /**
     * 给按钮高亮添加类名。
     *
     * @param mixed $value
     * @return $this
     */
    public function activeClassName(mixed $value): static
    {
        return $this->set('activeClassName', $value);
    }

    /**
     * 用`display:"block"`来显示按钮。
     *
     * @param mixed $value
     * @return $this
     */
    public function block(mixed $value = true): static
    {
        return $this->set('block', $value);
    }

    /**
     * 当设置后，操作在开始前会询问用户。可用 `${xxx}` 取值。
     *
     * @param mixed $value
     * @return $this
     */
    public function confirmText(mixed $value): static
    {
        return $this->set('confirmText', $value);
    }

    /**
     * 确认框标题，前提是 confirmText 有内容，支持模版语法
     *
     * @param mixed $value
     * @return $this
     */
    public function confirmTitle(mixed $value): static
    {
        return $this->set('confirmTitle', $value);
    }

    /**
     * 指定此次操作完后，需要刷新的目标组件名字（组件的`name`值，自己配置的），多个请用 `,` 号隔开。
     *
     * @param mixed $value
     * @return $this
     */
    public function reload(mixed $value): static
    {
        return $this->set('reload', $value);
    }

    /**
     * 鼠标停留时弹出该段文字，也可以配置对象类型：字段为`title`和`content`。可用 `${xxx}` 取值。
     *
     * @param mixed $value
     * @return $this
     */
    public function tooltip(mixed $value): static
    {
        return $this->set('tooltip', $value);
    }

    /**
     * 被禁用后鼠标停留时弹出该段文字，也可以配置对象类型：字段为`title`和`content`。可用 `${xxx}` 取值。
     *
     * @param mixed $value
     * @return $this
     */
    public function disabledTip(mixed $value): static
    {
        return $this->set('disabledTip', $value);
    }

    /**
     * 如果配置了`tooltip`或者`disabledTip`，指定提示信息位置，可配置`top`、`bottom`、`left`、`right`。
     *
     * @param mixed $value
     * @return $this
     */
    public function tooltipPlacement(mixed $value): static
    {
        return $this->set('tooltipPlacement', $value);
    }

    /**
     * 当`action`配置在`dialog`或`drawer`的`actions`中时，配置为`true`指定此次操作完后关闭当前`dialog`或`drawer`。当值为字符串，并且是祖先层弹框的名字的时候，会把祖先弹框关闭掉。
     *
     * @param mixed $value
     * @return $this
     */
    public function close(mixed $value): static
    {
        return $this->set('close', $value);
    }

    /**
     * 配置字符串数组，指定在`form`中进行操作之前，需要指定的字段名的表单项通过验证
     *
     * @param mixed $value
     * @return $this
     */
    public function required(mixed $value): static
    {
        return $this->set('required', $value);
    }

    /**
     * 请求地址，参考 [api](../../docs/types/api) 格式说明。
     *
     * @param mixed $value
     * @return $this
     */
    public function api(mixed $value): static
    {
        return $this->set('api', $value);
    }

    /**
     * 指定当前请求结束后跳转的路径，可用 `${xxx}` 取值。
     *
     * @param mixed $value
     * @return $this
     */
    public function redirect(mixed $value): static
    {
        return $this->set('redirect', $value);
    }

    /**
     * 如果 ajax 类型的，当 ajax 返回正常后，还能接着弹出一个 dialog 做其他交互。返回的数据可用于这个 dialog 中。格式可参考[Dialog](./Dialog)
     *
     * @param mixed $value
     * @return $this
     */
    public function feedback(mixed $value): static
    {
        return $this->set('feedback', $value);
    }

    /**
     * `success`：ajax 操作成功后提示，可以不指定，不指定时以 api 返回为准。`failed`：ajax 操作失败提示。
     *
     * @param mixed $value
     * @return $this
     */
    public function messages(mixed $value): static
    {
        return $this->set('messages', $value);
    }

    /**
     * 用来指定跳转地址，跟 url 不同的是，这是单页跳转方式，不会渲染浏览器，请指定 amis 平台内的页面。可用 `${xxx}` 取值。
     *
     * @param mixed $value
     * @return $this
     */
    public function link(mixed $value): static
    {
        return $this->set('link', $value);
    }

    /**
     * 按钮点击后，会打开指定页面。可用 `${xxx}` 取值。
     *
     * @param mixed $value
     * @return $this
     */
    public function url(mixed $value): static
    {
        return $this->set('url', $value);
    }

    /**
     * 如果为 `true` 将在新 tab 页面打开。
     *
     * @param mixed $value
     * @return $this
     */
    public function blank(mixed $value = true): static
    {
        return $this->set('blank', $value);
    }

    /**
     * 收件人邮箱，可用 ${xxx} 取值。
     *
     * @param mixed $value
     * @return $this
     */
    public function to(mixed $value): static
    {
        return $this->set('to', $value);
    }

    /**
     * 抄送邮箱，可用 ${xxx} 取值。
     *
     * @param mixed $value
     * @return $this
     */
    public function cc(mixed $value): static
    {
        return $this->set('cc', $value);
    }

    /**
     * 匿名抄送邮箱，可用 ${xxx} 取值。
     *
     * @param mixed $value
     * @return $this
     */
    public function bcc(mixed $value): static
    {
        return $this->set('bcc', $value);
    }

    /**
     * 邮件主题，可用 ${xxx} 取值。
     *
     * @param mixed $value
     * @return $this
     */
    public function subject(mixed $value): static
    {
        return $this->set('subject', $value);
    }

    /**
     * 邮件正文，可用 ${xxx} 取值。
     *
     * @param mixed $value
     * @return $this
     */
    public function body(mixed $value): static
    {
        return $this->set('body', $value);
    }

    /**
     * 指定弹框内容，格式可参考[Dialog](./dialog)
     *
     * @param mixed $value
     * @return $this
     */
    public function dialog(mixed $value): static
    {
        return $this->set('dialog', $value);
    }

    /**
     * 用于渲染按钮内容的组件
     *
     * @param mixed $value
     * @return $this
     */
    public function bodyComponent(mixed $value): static
    {
        return $this->set('body', $value);
    }

    /**
     * 可以用来设置下一条数据的条件，默认为 `true`。
     *
     * @param mixed $value
     * @return $this
     */
    public function nextCondition(mixed $value): static
    {
        return $this->set('nextCondition', $value);
    }

    /**
     * 指定弹框内容，格式可参考[Drawer](./drawer)
     *
     * @param mixed $value
     * @return $this
     */
    public function drawer(mixed $value): static
    {
        return $this->set('drawer', $value);
    }

    /**
     * 指定复制的内容。可用 `${xxx}` 取值。
     *
     * @param mixed $value
     * @return $this
     */
    public function content(mixed $value): static
    {
        return $this->set('content', $value);
    }

    /**
     * 指定复制的格式，默认是文本
     *
     * @param mixed $value
     * @return $this
     */
    public function copyFormat(mixed $value): static
    {
        return $this->set('copyFormat', $value);
    }

    /**
     * 需要刷新的目标组件名字（组件的`name`值，自己配置的），多个请用 `,` 号隔开。
     *
     * @param mixed $value
     * @return $this
     */
    public function target(mixed $value): static
    {
        return $this->set('target', $value);
    }

    /**
     * 通过设置倒计时 `countDown`（单位是秒），让点击按钮后禁用一段时间：
     *
     * @param mixed $value
     * @return $this
     */
    public function countDown(mixed $value): static
    {
        return $this->set('countDown', $value);
    }

    /**
     * 同时还能通过 `countDownTpl` 来控制显示的文本，其中 `${timeLeft}` 变量是剩余时间。
     *
     * @param mixed $value
     * @return $this
     */
    public function countDownTpl(mixed $value): static
    {
        return $this->set('countDownTpl', $value);
    }

    /**
     * 如果上面的的行为不满足需求，还可以通过字符串形式的 `onClick` 来定义点击事件，这个字符串会转成 JavaScript 函数，并支持异步（如果是用 sdk 需要自己编译一个 es2017 版本）。
     *
     * @param mixed $value
     * @return $this
     */
    public function onClick(mixed $value): static
    {
        return $this->set('onClick', $value);
    }

    /**
     * 可以通过 `hotKey` 属性来配置键盘快捷键触发，比如下面的例子
     *
     * @param mixed $value
     * @return $this
     */
    public function hotKey(mixed $value): static
    {
        return $this->set('hotKey', $value);
    }

    /**
     * 按钮组件内容（避免与上面的bodyComponent混淆）
     *
     * @param mixed $value
     * @return $this
     */
    public function actionBody(mixed $value): static
    {
        return $this->set('body', $value);
    }
}
