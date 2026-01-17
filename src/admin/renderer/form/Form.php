<?php

namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\trait\DataDomain;
use warm\admin\renderer\trait\FormItem;
use warm\admin\renderer\trait\OnEvent;

/**
 * Form 表单组件
 *
 * 表单是 amis 中核心组件之一，主要作用是提交或者展示表单数据。
 * 支持多种表单字段类型、表单数据验证、表单提交和重置、表单布局配置、动态表单字段等功能。
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/form
 */
class Form extends BaseRenderer
{
    use FormItem;
    use OnEvent;
    use DataDomain;

    public string $type = 'form';

    /**
     * 设置一个名字后，方便其他组件与其通信
     *
     * @param string $value 表单名称
     * @return self
     */
    public function name(string $value = ''): static
    {
        return $this->set('name', $value);
    }

    /**
     * 表单展示方式
     *
     * 可以是：`normal`、`horizontal` 或者 `inline`
     *
     * @param string $value 展示模式
     * @return self
     */
    public function mode(string $value = 'normal'): static
    {
        return $this->set('mode', $value);
    }

    /**
     * 当 mode 为 horizontal 时有用，用来控制 label 的展示占比
     *
     * 格式：`{"left":2, "right":10, "justify": false}` 或 `{"leftFixed": true}` 或 `{"leftFixed": "sm"}`
     *
     * @param array|object $value horizontal 配置
     * @return self
     */
    public function horizontal(array|object $value = null): static
    {
        return $this->set('horizontal', $value);
    }

    /**
     * 表单项标签对齐方式
     *
     * 默认右对齐，仅在 `mode` 为 `horizontal` 时生效。可选值：`right`、`left`
     *
     * @param string $value 对齐方式
     * @return self
     */
    public function labelAlign(string $value = 'right'): static
    {
        return $this->set('labelAlign', $value);
    }

    /**
     * 表单项标签自定义宽度
     *
     * 默认单位为 `px`。该属性的优先级：表单项 > 表单。
     *
     * @param int|string $value 宽度值
     * @return self
     */
    public function labelWidth(int|string $value = null): static
    {
        return $this->set('labelWidth', $value);
    }

    /**
     * Form 的标题
     *
     * @param string $value 标题文本
     * @return self
     */
    public function title(string $value = ''): static
    {
        return $this->set('title', $value);
    }

    /**
     * 默认的提交按钮名称
     *
     * 如果设置成空，则可以把默认按钮去掉。
     *
     * @param string $value 提交按钮文字
     * @return self
     */
    public function submitText(string $value = '提交'): static
    {
        return $this->set('submitText', $value);
    }

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
     * Form 表单项集合
     *
     * 支持多种表单字段类型，如：input-text、input-email、select、checkbox 等
     *
     * @param string|array $value 表单项数组
     * @return self
     */
    public function body(string|array $value = []): static
    {
        return $this->set('body', $value);
    }

    /**
     * Form 提交按钮
     *
     * 成员为 Action 组件，可以配置多个按钮
     *
     * @param array $value 按钮数组
     * @return self
     */
    public function actions(array $value = []): static
    {
        return $this->set('actions', $value);
    }

    /**
     * 消息提示覆写
     *
     * 默认消息读取的是 API 返回的消息，但是在此可以覆写它。
     *
     * @param array $value 消息配置（fetchSuccess、fetchFailed、saveSuccess、saveFailed、validateFailed）
     * @return self
     */
    public function messages(array $value = []): static
    {
        return $this->set('messages', $value);
    }

    /**
     * 是否让 Form 用 panel 包起来
     *
     * 设置为 false 后，actions 将无效。
     *
     * @param bool $value 是否包装
     * @return self
     */
    public function wrapWithPanel(bool $value = true): static
    {
        return $this->set('wrapWithPanel', $value);
    }

    /**
     * 外层 panel 的类名
     *
     * @param string $value 类名
     * @return self
     */
    public function panelClassName(string $value = ''): static
    {
        return $this->set('panelClassName', $value);
    }

    /**
     * Form 用来保存数据的 api
     *
     * 当表单执行提交行为时，会默认将当前表单数据域中的数据使用 `post` 方式发送给所配置 `api`
     *
     * @param mixed $value API 配置（字符串或对象）
     * @return self
     */
    public function api(mixed $value = null): static
    {
        return $this->set('api', $value);
    }

    /**
     * Form 用来获取初始数据的 api
     *
     * 表单初始化时请求接口，用于展示数据或初始化表单项
     *
     * @param mixed $value API 配置（字符串或对象）
     * @return self
     */
    public function initApi(mixed $value = null): static
    {
        return $this->set('initApi', $value);
    }

    /**
     * 表单组合校验规则
     *
     * 实现组合多个表单项的校验，格式：`[{"rule": "表达式", "message": "错误信息", "name": ["字段名"]}]`
     *
     * @param array $value 校验规则数组
     * @return self
     */
    public function rules(array $value = []): static
    {
        return $this->set('rules', $value);
    }

    /**
     * 刷新时间(最低 3000)
     *
     * 单位：毫秒。用于轮询初始化请求
     *
     * @param int|float $value 刷新间隔（毫秒）
     * @return self
     */
    public function interval(int|float $value = 3000): static
    {
        return $this->set('interval', $value);
    }

    /**
     * 配置刷新时是否显示加载动画
     *
     * @param bool $value 是否静默刷新
     * @return self
     */
    public function silentPolling(bool $value = false): static
    {
        return $this->set('silentPolling', $value);
    }

    /**
     * 通过表达式来配置停止刷新的条件
     *
     * @param string $value 表达式
     * @return self
     */
    public function stopAutoRefreshWhen(string $value = ''): static
    {
        return $this->set('stopAutoRefreshWhen', $value);
    }

    /**
     * Form 用来获取初始数据的 api
     *
     * 与 initApi 不同的是，会一直轮询请求该接口，直到返回 finished 属性为 true 才结束。
     *
     * @param mixed $value API 配置（字符串或对象）
     * @return self
     */
    public function initAsyncApi(mixed $value = null): static
    {
        return $this->set('initAsyncApi', $value);
    }

    /**
     * 设置了 initApi 或者 initAsyncApi 后，默认会开始就发请求
     *
     * 设置为 false 后就不会起始就请求接口
     *
     * @param bool $value 是否初始获取
     * @return self
     */
    public function initFetch(bool $value = true): static
    {
        return $this->set('initFetch', $value);
    }

    /**
     * 用表达式来配置是否初始获取
     *
     * @param string $value 表达式
     * @return self
     */
    public function initFetchOn(string $value = ''): static
    {
        return $this->set('initFetchOn', $value);
    }

    /**
     * 设置了 initAsyncApi 后，默认会从返回数据的 data.finished 来判断是否完成
     *
     * 也可以设置成其他的 xxx，就会从 data.xxx 中获取
     *
     * @param string $value 完成字段名
     * @return self
     */
    public function initFinishedField(string $value = 'finished'): static
    {
        return $this->set('initFinishedField', $value);
    }

    /**
     * 设置了 initAsyncApi 以后，默认拉取的时间间隔
     *
     * 单位：毫秒
     *
     * @param int|float $value 检查间隔（毫秒）
     * @return self
     */
    public function initCheckInterval(int|float $value = 3000): static
    {
        return $this->set('initCheckInterval', $value);
    }

    /**
     * 设置此属性后，表单提交发送保存接口后，还会继续轮询请求该接口
     *
     * 直到返回 `finished` 属性为 `true` 才结束。
     *
     * @param mixed $value API 配置（字符串或对象）
     * @return self
     */
    public function asyncApi(mixed $value = null): static
    {
        return $this->set('asyncApi', $value);
    }

    /**
     * 轮询请求的时间间隔
     *
     * 默认为 3 秒。设置 `asyncApi` 才有效
     *
     * @param int|float $value 检查间隔（毫秒）
     * @return self
     */
    public function checkInterval(int|float $value = 3000): static
    {
        return $this->set('checkInterval', $value);
    }

    /**
     * 如果决定结束的字段名不是 `finished` 请设置此属性
     *
     * 比如 `is_success`
     *
     * @param string $value 完成字段名
     * @return self
     */
    public function finishedField(string $value = 'finished'): static
    {
        return $this->set('finishedField', $value);
    }

    /**
     * 表单修改即提交
     *
     * 当表单项值发生变化时自动提交表单
     *
     * @param bool $value 是否开启
     * @return self
     */
    public function submitOnChange(bool $value = false): static
    {
        return $this->set('submitOnChange', $value);
    }

    /**
     * 初始就提交一次
     *
     * @param bool $value 是否初始提交
     * @return self
     */
    public function submitOnInit(bool $value = false): static
    {
        return $this->set('submitOnInit', $value);
    }

    /**
     * 提交后是否重置表单
     *
     * 如果想提交表单成功后，重置当前表单至初始状态，可以配置为 true
     *
     * @param bool $value 是否重置
     * @return self
     */
    public function resetAfterSubmit(bool $value = false): static
    {
        return $this->set('resetAfterSubmit', $value);
    }

    /**
     * 设置主键 id
     *
     * 当设置后，检测表单是否完成时（asyncApi），只会携带此数据。
     *
     * @param string $value 主键字段名
     * @return self
     */
    public function primaryField(string $value = 'id'): static
    {
        return $this->set('primaryField', $value);
    }

    /**
     * 默认表单提交自己会通过发送 api 保存数据
     *
     * 但是也可以设定另外一个 form 的 name 值，或者另外一个 `CRUD` 模型的 name 值。
     * 如果 target 目标是一个 `Form`，则目标 `Form` 会重新触发 `initApi`。
     * 如果目标是一个 `CRUD` 模型，则目标模型会重新触发搜索。
     * 当目标是 `window` 时，会把当前表单的数据附带到页面地址上。
     *
     * @param string $value 目标组件名称
     * @return self
     */
    public function target(string $value = ''): static
    {
        return $this->set('target', $value);
    }

    /**
     * 设置此属性后，Form 保存成功后，自动跳转到指定页面
     *
     * 支持相对地址，和绝对地址（相对于组内的）。
     *
     * @param string $value 跳转地址
     * @return self
     */
    public function redirect(string $value = ''): static
    {
        return $this->set('redirect', $value);
    }

    /**
     * 操作完后刷新目标对象
     *
     * 请填写目标组件设置的 name 值，如果填写为 `window` 则让当前页面整体刷新。
     *
     * @param string $value 目标组件名称
     * @return self
     */
    public function reload(string $value = ''): static
    {
        return $this->set('reload', $value);
    }

    /**
     * 是否自动聚焦
     *
     * @param bool $value 是否自动聚焦
     * @return self
     */
    public function autoFocus(bool $value = false): static
    {
        return $this->set('autoFocus', $value);
    }

    /**
     * 指定是否可以自动获取上层的数据并映射到表单项上
     *
     * 默认表单是可以获取到完整数据链中的数据的，但是该默认行为不适用于所有场景。
     *
     * @param bool $value 是否可以访问上层数据
     * @return self
     */
    public function canAccessSuperData(bool $value = true): static
    {
        return $this->set('canAccessSuperData', $value);
    }

    /**
     * 指定一个唯一的 key，来配置当前表单是否开启本地缓存
     *
     * 表单默认在重置之后（切换页面、弹框中表单关闭表单），会自动清空掉表单中的所有数据。
     * 如果想持久化保留当前表单项的数据而不清空它，可以通过该属性实现数据持久化保存。
     *
     * @param string $value 缓存键名
     * @return self
     */
    public function persistData(string $value = ''): static
    {
        return $this->set('persistData', $value);
    }

    /**
     * 指定只有哪些 key 缓存
     *
     * 如果只想存储部分 key，可以配置该属性，这样就只有 name 为指定 key 的表单项数据会持久化
     *
     * @param array $value 字段名数组
     * @return self
     */
    public function persistDataKeys(array $value = []): static
    {
        return $this->set('persistDataKeys', $value);
    }

    /**
     * 指定表单提交成功后是否清除本地缓存
     *
     * @param bool $value 是否清除缓存
     * @return self
     */
    public function clearPersistDataAfterSubmit(bool $value = true): static
    {
        return $this->set('clearPersistDataAfterSubmit', $value);
    }

    /**
     * 禁用回车提交表单
     *
     * 表单默认情况下回车就会提交，如果想阻止这个行为，可以设置为 true
     *
     * @param bool $value 是否禁用回车提交
     * @return self
     */
    public function preventEnterSubmit(bool $value = false): static
    {
        return $this->set('preventEnterSubmit', $value);
    }

    /**
     * trim 当前表单项的每一个值
     *
     * 自动去除表单项值的前后空格
     *
     * @param bool $value 是否 trim 值
     * @return self
     */
    public function trimValues(bool $value = false): static
    {
        return $this->set('trimValues', $value);
    }

    /**
     * form 还没保存，即将离开页面前是否弹框确认
     *
     * @param bool $value 是否提示离开
     * @return self
     */
    public function promptPageLeave(bool $value = false): static
    {
        return $this->set('promptPageLeave', $value);
    }

    /**
     * 表单项显示为几列
     *
     * 实现一行展示多个表单项的布局配置
     *
     * @param int $value 列数
     * @return self
     */
    public function columnCount(int $value = 0): static
    {
        return $this->set('columnCount', $value);
    }

    /**
     * 默认表单是采用数据链的形式创建个自己的数据域
     *
     * 表单提交的时候只会发送自己这个数据域的数据，如果希望共用上层数据域可以设置这个属性为 false
     *
     * @param bool $value 是否继承数据
     * @return self
     */
    public function inheritData(bool $value = true): static
    {
        return $this->set('inheritData', $value);
    }

    /**
     * 整个表单静态方式展示
     *
     * 2.4.0 版本支持。在一些场景，表单提交后需要将填写的内容静态展示
     *
     * @param bool $value 是否静态展示
     * @return self
     */
    public function static(bool $value = false): static
    {
        return $this->set('static', $value);
    }

    /**
     * 表单静态展示时使用的类名
     *
     * 2.4.0 版本支持
     *
     * @param string $value 类名
     * @return self
     */
    public function staticClassName(string $value = ''): static
    {
        return $this->set('staticClassName', $value);
    }

    /**
     * 提交的时候是否关闭弹窗
     *
     * 当 form 里面有且只有一个弹窗的时候，本身提交会触发弹窗关闭，此属性可以关闭此行为
     *
     * @param bool $value 是否关闭弹窗
     * @return self
     */
    public function closeDialogOnSubmit(bool $value = false): static
    {
        return $this->set('closeDialogOnSubmit', $value);
    }

    /**
     * 配置 debug:true 可以查看当前表单的数据域数据详情
     *
     * 方便数据映射、表达式等功能调试
     *
     * @param bool $value 是否开启调试
     * @return self
     */
    public function debug(bool $value = false): static
    {
        return $this->set('debug', $value);
    }

    /**
     * 可以额外配置 debug 区域的相关配置
     *
     * 具体配置请参考 Json 组件属性。2.2.0 及以上版本支持
     *
     * @param array $value debug 配置
     * @return self
     */
    public function debugConfig(array $value = []): static
    {
        return $this->set('debugConfig', $value);
    }

    /**
     * 表单初始数据
     *
     * 可以手动设置 form 的数据域来初始化多个表单项值
     *
     * @param array $value 初始数据
     * @return self
     */
    public function data(array $value = []): static
    {
        return $this->set('data', $value);
    }

    /**
     * 固定底部栏
     *
     * 如果表单项较多导致表单过长，而不方便操作底部的按钮栏，可以配置为 true，将底部按钮栏固定在浏览器底部
     *
     * @param bool $value 是否固定底部
     * @return self
     */
    public function affixFooter(bool $value = false): static
    {
        return $this->set('affixFooter', $value);
    }

    /**
     * 添加表单项
     *
     * 便捷方法：动态添加表单项到表单 body
     *
     * @param array|object $item 表单项配置
     * @return self
     * @throws \InvalidArgumentException 当表单项配置无效时抛出异常
     */
    public function addField(array|object $item): static
    {
        if (empty($item['type'])) {
            throw new \InvalidArgumentException('Form field type is required');
        }

        $body = $this->get('body', []);
        $body[] = $item;

        return $this->set('body', $body);
    }

    /**
     * 移除指定索引的表单项
     *
     * 便捷方法：动态删除表单项
     *
     * @param int $index 要删除的表单项索引
     * @return self
     * @throws \OutOfBoundsException 当索引超出范围时抛出异常
     */
    public function removeField(int $index): static
    {
        $body = $this->get('body', []);

        if (!isset($body[$index])) {
            throw new \OutOfBoundsException("Form field index {$index} does not exist");
        }

        unset($body[$index]);

        return $this->set('body', array_values($body));
    }

    /**
     * 添加表单提交按钮
     *
     * 便捷方法：动态添加提交按钮到 actions
     *
     * @param array|object $action 按钮配置
     * @return self
     * @throws \InvalidArgumentException 当按钮配置无效时抛出异常
     */
    public function addAction(array|object $action): static
    {
        if (empty($action['type']) && empty($action['actionType'])) {
            throw new \InvalidArgumentException('Action type or actionType is required');
        }

        $actions = $this->get('actions', []);
        $actions[] = $action;

        return $this->set('actions', $actions);
    }

    /**
     * 移除指定索引的提交按钮
     *
     * 便捷方法：动态删除提交按钮
     *
     * @param int $index 要删除的按钮索引
     * @return self
     * @throws \OutOfBoundsException 当索引超出范围时抛出异常
     */
    public function removeAction(int $index): static
    {
        $actions = $this->get('actions', []);

        if (!isset($actions[$index])) {
            throw new \OutOfBoundsException("Action index {$index} does not exist");
        }

        unset($actions[$index]);

        return $this->set('actions', array_values($actions));
    }

    /**
     * 添加表单校验规则
     *
     * 便捷方法：动态添加组合校验规则
     *
     * @param string $rule 校验表达式
     * @param string $message 错误提示信息
     * @param array $names 要高亮的字段名数组（可选）
     * @return self
     */
    public function addRule(string $rule, string $message, array $names = []): static
    {
        $rules = $this->get('rules', []);

        $ruleItem = [
            'rule' => $rule,
            'message' => $message,
        ];

        if (!empty($names)) {
            $ruleItem['name'] = $names;
        }

        $rules[] = $ruleItem;

        return $this->set('rules', $rules);
    }

    /**
     * 获取所有表单项
     *
     * @return array 表单项数组
     */
    public function getFields(): array
    {
        return $this->get('body', []);
    }

    /**
     * 获取表单项数量
     *
     * @return int 表单项数量
     */
    public function getFieldCount(): int
    {
        return count($this->getFields());
    }

    /**
     * 获取所有提交按钮
     *
     * @return array 按钮数组
     */
    public function getActions(): array
    {
        return $this->get('actions', []);
    }
}
