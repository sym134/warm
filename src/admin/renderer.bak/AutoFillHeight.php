<?php

namespace warm\admin\renderer\expand\renderer;

/**
 * AutoFillHeight
 * 
 * @author slowlyo
 * @version 6.13.0
 */
class AutoFillHeight extends BaseRenderer
{

    /**
     * Dialog 高度
     */
    public function height($value = '')
    {
        return $this->set('height', $value);
    }

    /**
     * 
     */
    public function maxHeight($value = '')
    {
        return $this->set('maxHeight', $value);
    }


}
