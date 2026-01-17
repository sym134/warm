<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\trait\OnEvent;
use warm\admin\renderer\trait\FormItem;

/**
 * Editor
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/editor
 */
class Editor extends BaseRenderer
{
    use FormItem;
    use OnEvent;

    public string $type = 'editor';

    /**
     * 编辑器高亮的语言，支持通过 `${xxx}` 变量获取
     *
     * @param string $value
     * @return self
     */
    public function language(string $value = 'javascript'): static
    {
        return $this->set('language', $value);
    }

    /**
     * 编辑器高度，取值可以是 `md`、`lg`、`xl`、`xxl`
     *
     * @param string $value
     * @return self
     */
    public function size(string $value = 'md'): static
    {
        return $this->set('size', $value);
    }

    /**
     * 是否显示全屏模式开关
     *
     * @param bool $value
     * @return self
     */
    public function allowFullscreen(bool $value = true): static
    {
        return $this->set('allowFullscreen', $value);
    }

    /**
     * monaco 编辑器的其它配置，比如是否显示行号等，请参考[这里](https://microsoft.github.io/monaco-editor/docs.html#interfaces/editor.IEditorOptions.html)，不过无法设置 readOnly，只读模式需要使用 `disabled: true`
     *
     * @param array $value
     * @return self
     */
    public function options(array $value = []): static
    {
        return $this->set('options', $value);
    }

    /**
     * 占位描述，没有值的时候展示
     *
     * @param string $value
     * @return self
     */
    public function placeholder(string $value = ''): static
    {
        return $this->set('placeholder', $value);
    }
}
