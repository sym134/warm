<?php

namespace warm\admin\renderer;

/**
 * RowSelectionOptions
 *
 * @author  slowlyo
 * @version 6.8.0
 */
class RowSelectionOptions extends BaseRenderer
{
    public function __construct()
    {


    }

    /**
     * 选择类型 选择全部
     */
    public function key($value = '')
    {
        return $this->set('key', $value);
    }

    /**
     * 选项显示文本
     */
    public function text($value = '')
    {
        return $this->set('text', $value);
    }


}
