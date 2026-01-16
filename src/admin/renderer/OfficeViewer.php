<?php
namespace warm\admin\renderer;
/**
 * OfficeViewer
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/office-viewer
 */
class OfficeViewer extends BaseRenderer
{
    public string $type = 'office-viewer';

    /**
     * 文档地址
     *
     * @param mixed $value
     * @return self
     */
    public function src(mixed $value = null): self
    {
        return $this->set('src', $value);
    }

    /**
     * 是否显示 loading 图标
     *
     * @param bool $value
     * @return self
     */
    public function loading(bool $value = true): self
    {
        return $this->set('loading', $value);
    }

    /**
     * 是否开启变量替换功能
     *
     * @param bool $value
     * @return self
     */
    public function enableVar(bool $value = true): self
    {
        return $this->set('enableVar', $value);
    }

    /**
     * Word 渲染配置
     *
     * @param array $value
     * @return self
     */
    public function wordOptions(array $value = []): self
    {
        return $this->set('wordOptions', $value);
    }
}
