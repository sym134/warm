<?php

namespace warm\admin\renderer\expand;
use warm\admin\renderer\Action;

/**
 * ImageToolbarAction
 *
 * @version 6.13.0
 */
class ImageToolbarAction extends Action
{
    public function __construct()
    {
        parent::__construct();

        $this->set('key', 'ROTATE_RIGHT');
        $this->set('type', 'action');
    }
}
