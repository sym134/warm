<?php

namespace warm\admin\service\system;

use support\Db;
use warm\admin\Admin;
use warm\admin\model\system\SystemFile;
use warm\admin\service\AdminService;
use warm\framework\filesystem\facade\Storage;

/**
 * 系统文件服务类
 * 
 * 提供系统文件管理功能，包括文件删除等
 */
class SystemFileService extends AdminService
{
    /**
     * 构造函数
     * 
     * 初始化文件服务，设置模型名称
     */
    public function __construct()
    {
        parent::__construct();
        $this->modelName = SystemFile::class;
    }
}