<?php

namespace warm\service\system;

use warm\model\system\Attachment;
use warm\service\AdminService;

class AttachmentService extends AdminService
{
    public function __construct()
    {
        parent::__construct();
        $this->modelName = Attachment::class;
    }


}
