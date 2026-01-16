<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\form\FormItemTrait;

/**
 * DiffEditor
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/diff-editor
 */
class DiffEditor extends BaseRenderer
{
    use FormItemTrait;

    public string $type = 'diff-editor';

    /**
     * 编辑器高亮的语言，可选 [支持的语言](./editor#%E6%94%AF%E6%8C%81%E7%9A%84%E8%AF%AD%E8%A8%80)
     *
     * @param string $value
     * @return self
     */
    public function language(string $value = 'javascript'): self
    {
        return $this->set('language', $value);
    }

    /**
     * 左侧值
     *
     * @param mixed $value
     * @return self
     */
    public function diffValue(mixed $value = null): self
    {
        return $this->set('diffValue', $value);
    }
}
