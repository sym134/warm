<?php

namespace warm\admin\controller;

use Illuminate\Support\Str;
use support\Response;
use warm\admin\Admin;
use warm\admin\service\AdminApiService;

/**
 * 管理API控制器
 * 
 * 处理管理后台的API请求
 * 根据请求路径匹配并执行对应的API模板
 * 
 * @property AdminApiService $service 管理API服务类实例
 */
class AdminApiController extends AdminController
{
    /**
     * @var string $serviceName 服务类名称
     * 指定当前控制器使用的服务类
     */
    public string $serviceName = AdminApiService::class;

    /**
     * API请求处理
     * 
     * 根据请求路径查找并执行对应的API模板
     * 
     * @return Response 返回API处理结果
     */
    public function index(): Response
    {
        // 获取请求路径并去除管理前缀
        $path = Str::of(request()->path())->replace(Admin::config('app.route.prefix'), '')->value();
        // 根据路径获取API记录
        $api  = $this->service->getApiByPath($path);

        // 如果未找到API记录，返回空的成功响应
        if (!$api) {
            return $this->response()->success();
        }

        // 执行API模板处理
        return appw($api->template)->setApiRecord($api)->handle();
    }
}
