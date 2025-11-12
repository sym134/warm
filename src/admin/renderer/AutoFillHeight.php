<?php

namespace warm\admin\renderer;

/**
 * AutoFillHeight
 *
 * @author  slowlyo
 * @version 6.8.0
 */
class AutoFillHeight extends BaseRenderer
{
    public function __construct()
    {


    }

    /**
     *
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
