<?php
namespace warm\admin\renderer;

use warm\admin\renderer\trait\OnEvent;

/**
 * OfficeViewer 文档渲染
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/office-viewer
 */
class OfficeViewer extends BaseRenderer
{
    use OnEvent;
    public string $type = 'office-viewer';

    /**
     * 文档地址
     *
     * @param mixed $value
     * @return self
     */
    public function src(mixed $value = null): static
    {
        return $this->set('src', $value);
    }

    /**
     * 是否显示 loading 图标
     *
     * @param bool $value
     * @return self
     */
    public function loading(bool $value = true): static
    {
        return $this->set('loading', $value);
    }

    /**
     * 是否开启变量替换功能
     *
     * @param bool $value
     * @return self
     */
    public function enableVar(bool $value = true): static
    {
        return $this->set('enableVar', $value);
    }

    /**
     * Word 渲染配置
     *array{
     *      classPrefix?: string,      // 渲染的 class 类前缀，默认值：'docx-viewer'
     *      ignoreWidth?: boolean,     // 忽略文档里的宽度设置，用于更好嵌入到页面里，但会减低还原度，默认值：false
     *      padding?: string,          // 设置页面间距，忽略文档中的设置，默认值：无
     *      bulletUseFont?: boolean,   // 列表使用字体渲染，请参考下面的乱码说明，默认值：true
     *      fontMapping?: object,      // 字体映射，是个键值对，用于替换文档中的字体，默认值：无
     *      forceLineHeight?: string,  // 设置段落行高，忽略文档中的设置，默认值：无
     *      enableVar?: boolean,       // 是否开启变量替换功能，默认值：true
     *      printOptions?: object      // 针对打印的特殊设置，可以覆盖其它所有设置项，默认值：无
     *  }
     *
     */
    public function wordOptions(array $value = []): static
    {
        return $this->set('wordOptions', $value);
    }

    /**
     * Excel 渲染配置 [‘fontURL’=>['等线'=>'/static/font/DengXian.ttf','仿宋'=>'/static/font/STFANGSO.TTF','黑体'=>'/static/font/simhei.ttf']
     * @param array $value
     * @return self
     */

    public function excelOptions(array $value): static
    {
        return $this->set('excelOptions', $value);
    }
}
