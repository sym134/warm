<?php

namespace warm\admin\renderer\expand\renderer;

/**
 * AjaxAction
 * 
 * @author slowlyo
 * @version 6.13.0
 */
class AjaxAction extends Action
{
    public function __construct()
    {
        parent::__construct();
        $this->set('actionType', 'ajax');
    }
}
