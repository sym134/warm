<?php

namespace warm\admin\renderer\expand;
use warm\admin\renderer\Action;

/**
 * AjaxAction
 * 
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
