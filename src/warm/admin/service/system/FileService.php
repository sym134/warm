<?php

namespace warm\admin\service\system;

use warm\admin\model\system\File;
use warm\admin\service\AdminService;

class FileService extends AdminService
{
    public function __construct()
    {
        parent::__construct();
        $this->modelName = File::class;
    }


}
