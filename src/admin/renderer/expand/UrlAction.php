<?php

namespace warm\admin\renderer\expand;
use warm\admin\renderer\Action;

/**
 * UrlAction
 * 
 * @version 6.13.0
 */
class UrlAction extends Action
{
    public function __construct()
    {
        parent::__construct();
        $this->set('actionType', 'url');
    }
}
