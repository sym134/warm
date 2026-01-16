<?php

namespace warm\admin\renderer\expand;
use warm\admin\renderer\BaseRenderer;

/**
 * EmailAction
 *
 * @author slowlyo
 * @version 6.13.0
 */
class EmailAction extends BaseRenderer
{
    public function __construct()
    {
        parent::__construct();

        $this->set('actionType', 'email');
    }
}
