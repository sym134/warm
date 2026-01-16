<?php

namespace warm\admin\renderer\expand;
use warm\admin\renderer\BaseRenderer;

/**
 * LinkAction
 * 
 * @version 6.13.0
 */
class LinkAction extends BaseRenderer
{
    public function __construct()
    {
        parent::__construct();
        $this->set('actionType', 'link');
    }

}
