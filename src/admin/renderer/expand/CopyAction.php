<?php

namespace warm\admin\renderer\expand;
use warm\admin\renderer\Action;

/**
 * CopyAction
 * 
 * @version 6.13.0
 */
class CopyAction extends Action
{
    public function __construct()
    {
        parent::__construct();
//        $this->set('actionType', 'copy');
        $this->actionType('type');
    }
}
