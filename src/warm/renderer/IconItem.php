<?php

namespace warm\renderer;

/**
 * IconItem
 *
 * @author  slowlyo
 * @version 6.8.0
 */
class IconItem extends BaseRenderer
{
    public function __construct()
    {


    }

    /**
     * iconfont 里面的类名。
     */
    public function icon($value = '')
    {
        return $this->set('icon', $value);
    }

    /**
     *
     */
    public function position($value = '')
    {
        return $this->set('position', $value);
    }


}
