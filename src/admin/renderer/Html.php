<?php
namespace warm\admin\renderer;
/**
 * Html Html
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/html
 */
class Html extends BaseRenderer
{
    public string $type = 'html';

    /**
     * html
     *
     * @param string $param
     * @return $this
     */
    public function html(string $param): static
    {
        return $this->set('html', $param);
    }


}
