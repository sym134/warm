<?php

namespace warm\admin\controller;

use Illuminate\Support\Str;
use support\Response;
use warm\admin\Admin;
use warm\admin\service\AdminApiService;

/**
 * @property AdminApiService $service
 */
class AdminApiController extends AdminController
{
    public string $serviceName = AdminApiService::class;

    public function index(): Response
    {
        $path = Str::of(request()->path())->replace(Admin::config('app.route.prefix'), '')->value();
        $api  = $this->service->getApiByPath($path);

        if (!$api) {
            return $this->response()->success();
        }

        return appw($api->template)->setApiRecord($api)->handle();
    }
}
