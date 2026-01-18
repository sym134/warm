<?php
namespace warm\admin\renderer;
use warm\admin\renderer\trait\DataDomain;
use warm\admin\renderer\trait\OnEvent;

/**
 * Dialog 对话框
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/dialog
 */
class Dialog extends BaseRenderer
{
    use OnEvent;
    use DataDomain;

    public string $type = 'dialog';

    /**
     * 弹出层标题
     *
     * @param mixed $value
     * @return self
     */
    public function title(mixed $value = null): static
    {
        return $this->set('title', $value);
    }

    /**
     * 往 Dialog 内容区加内容
     *
     * @param mixed $value
     * @return self
     */
    public function body(mixed $value = null): static
    {
        return $this->set('body', $value);
    }

    /**
     * 指定 dialog 大小，支持: `xs`、`sm`、`md`、`lg`、`xl`、`full`、`custom`
     *
     * @param string $value
     * @return self
     */
    public function size(string $value = ''): static
    {
        return $this->set('size', $value);
    }

    /**
     * 
     *
     * @param mixed $value
     * @return self
     */
    public function width(mixed $value = null): static
    {
        return $this->set('width', $value);
    }

    /**
     * 
     *
     * @param mixed $value
     * @return self
     */
    public function height(mixed $value = null): static
    {
        return $this->set('height', $value);
    }

    /**
     * Dialog body 区域的样式类名
     *
     * @param string $value
     * @return self
     */
    public function bodyClassName(string $value = 'modal-body'): static
    {
        return $this->set('bodyClassName', $value);
    }

    /**
     * 是否支持按 `Esc` 关闭 Dialog
     *
     * @param bool $value
     * @return self
     */
    public function closeOnEsc(bool $value = true): static
    {
        return $this->set('closeOnEsc', $value);
    }

    /**
     * 是否显示右上角的关闭按钮
     *
     * @param bool $value
     * @return self
     */
    public function showCloseButton(bool $value = true): static
    {
        return $this->set('showCloseButton', $value);
    }

    /**
     * 是否在弹框左下角显示报错信息
     *
     * @param bool $value
     * @return self
     */
    public function showErrorMsg(bool $value = true): static
    {
        return $this->set('showErrorMsg', $value);
    }

    /**
     * 是否在弹框左下角显示 loading 动画
     *
     * @param bool $value
     * @return self
     */
    public function showLoading(bool $value = true): static
    {
        return $this->set('showLoading', $value);
    }

    /**
     * 如果设置此属性，则该 Dialog 只读没有提交操作。
     *
     * @param bool $value
     * @return self
     */
    public function disabled(bool $value = true): static
    {
        return $this->set('disabled', $value);
    }

    /**
     * 是否支持拖拽 Dialog
     *
     * @param bool $value
     * @return self
     */
    public function draggable(bool $value = true): static
    {
        return $this->set('draggable', $value);
    }

    /**
     * 如果想不显示底部按钮，可以配置：`[]`
     *
     * @param mixed $value
     * @return self
     */
    public function actions(mixed $value = null): static
    {
        return $this->set('actions', $value);
    }

    /**
     * 支持[数据映射](../../docs/concepts/data-mapping)，如果不设定将默认将触发按钮的上下文中继承数据。
     *
     * @param array $value
     * @return self
     */
    public function data(array $value = []): static
    {
        return $this->set('data', $value);
    }
}
