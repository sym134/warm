<?php

namespace warm\admin\controller;

use BadMethodCallException;
use Illuminate\Contracts\Auth\Authenticatable;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use support\Request;
use support\Response;
use warm\admin\Admin;
use warm\admin\model\AdminUser;
use warm\admin\trait\CheckActionTrait;
use warm\admin\trait\ElementTrait;
use warm\admin\trait\ExportTrait;
use warm\admin\trait\QueryPathTrait;
use warm\admin\trait\UploadTrait;
use warm\admin\support\cores\JsonResponse;

/**
 * 管理后台控制器基类
 * 提供后台管理系统的通用操作方法，如增删改查、导入导出等
 * 所有管理后台控制器应继承此类
 */
abstract class AdminController
{
    use ExportTrait;
    use UploadTrait;
    use ElementTrait;
    use QueryPathTrait;
    use CheckActionTrait;

    /** 
     * @var object 服务类实例
     * 用于处理业务逻辑的对象，通过serviceName指定的服务类创建
     */
    protected $service;

    /** 
     * @var string $serviceName 服务类名称 
     * 指定当前控制器使用的服务类，用于处理具体业务逻辑
     */
    protected string $serviceName = '';

    /** 
     * @var string $queryPath 当前请求路径（不包含管理前缀）
     * 例如：如果访问 /admin/users，则$queryPath为users
     */
    protected string $queryPath;

    /** 
     * @var string|mixed $adminPrefix 管理后台路由前缀 
     * 从配置中获取，通常为/admin
     */
    protected string $adminPrefix;

    /** 
     * @var bool $isCreate 是否是新增页面, 页面模式时生效
     * 在create方法中设置为true，用于区分当前是否在创建页面
     */
    protected bool $isCreate = false;

    /** 
     * @var bool $isEdit 是否是编辑页面, 页面模式时生效
     * 在edit方法中设置为true，用于区分当前是否在编辑页面
     */
    protected bool $isEdit = false;

    /**
     * 控制器构造函数
     * 初始化服务类实例、管理前缀和查询路径
     */
    public function __construct()
    {
        // 如果子类定义了serviceName属性且不为空，则初始化对应的服务类实例
        if (property_exists($this, 'serviceName') && $this->serviceName) {
            $this->service = $this->serviceName::make();
        }

        // 获取管理后台路由前缀配置
        $this->adminPrefix = Admin::config('app.route.prefix');

        // 计算当前查询路径（去除前缀部分）
        $this->queryPath = str_replace($this->adminPrefix . '/', '', request()->path());
    }

    /**
     * 获取当前登录用户信息
     *
     * @return Authenticatable|AdminUser|null 返回认证用户对象或null
     */
    public function user(): AdminUser|Authenticatable|null
    {
        return Admin::user();
    }

    /**
     * 从请求中获取主键值
     *
     * @param Request $request HTTP请求对象
     * @return mixed 返回主键值
     */
    public function getPrimaryValue($request)
    {
        // 获取服务类定义的主键字段名
        $primaryKey = $this->service->primaryKey();

        // 从请求中获取主键对应的值
        return $request->input($primaryKey);
    }

    /**
     * 获取后台响应对象
     *
     * @return JsonResponse 返回JSON响应构建器实例
     */
    protected function response(): JsonResponse
    {
        return Admin::response();
    }

    /**
     * 自动生成响应结果
     *
     * @param bool $flag 操作结果标志，true表示成功，false表示失败
     * @param string $text 操作描述文本
     * @return Response 返回HTTP响应对象
     */
    protected function autoResponse($flag, $text = ''): Response
    {
        // 如果未提供文本描述，默认使用"操作"
        if (!$text) {
            $text = translator('admin.actions');
        }

        // 根据操作结果返回成功或失败响应
        if ($flag) {
            return $this->response()->successMessage($text . translator('admin.successfully'));
        }

        // 失败时优先返回服务层错误信息，否则使用默认失败消息
        return $this->response()->fail($this->service->getError() ?? $text . translator('admin.failed'));
    }

    /**
     * 列表页/首页处理方法
     * 支持数据获取、导出等功能
     *
     * @return Response 返回HTTP响应
     */
    public function index()
    {
        // 如果是获取数据的操作，返回列表数据
        if ($this->actionOfGetData()) {
            return $this->response()->success($this->service->list());
        }

        // 如果是导出操作，执行导出逻辑
        if ($this->actionOfExport()) {
            return $this->export();
        }

        // 默认返回列表页面
        return $this->response()->success($this->list());
    }

    /**
     * 获取新增页面
     *
     * @throws ContainerExceptionInterface 容器异常接口
     * @throws NotFoundExceptionInterface 未找到异常接口
     * @return Response 返回新增页面响应
     */
    public function create()
    {
        // 设置当前为创建页面状态
        $this->isCreate = true;

        // 构建表单页面结构
        $form = amis()
            ->Card() // 使用卡片组件
            ->header(['title' => translator('admin.create'), 'className' => 'border-b']) // 设置标题
            ->toolbar([$this->backButton()]) // 添加返回按钮工具栏
            ->body($this->form(false)->api($this->getStorePath())); // 设置表单内容和提交API地址

        // 将表单包装到页面中
        $page = $this->basePage()->body($form);

        return $this->response()->success($page);
    }

    /**
     * 新增数据保存
     *
     * @param Request $request HTTP请求对象
     * @return Response 返回操作结果响应
     */
    public function store(Request $request)
    {
        // 定义响应处理闭包
        $response = fn($result) => $this->autoResponse($result, translator('admin.save'));

        // 快速编辑处理
        if ($this->actionOfQuickEdit()) {
            return $response($this->service->quickEdit($request->all()));
        }

        // 单项快速编辑处理
        if ($this->actionOfQuickEditItem()) {
            return $response($this->service->quickEditItem($request->all()));
        }

        // 常规新增处理
        return $response($this->service->store($request->all()));
    }

    /**
     * 显示详情页面
     *
     * @param mixed $id 数据主键ID
     * @throws ContainerExceptionInterface 容器异常接口
     * @throws NotFoundExceptionInterface 未找到异常接口
     * @return Response 返回详情页面响应
     */
    public function show($id)
    {
        // 如果是获取数据操作，返回详情数据
        if ($this->actionOfGetData()) {
            return $this->response()->success($this->service->getDetail($id));
        }

        // 构建详情页面结构
        $detail = amis()
            ->Card() // 使用卡片组件
            ->header(['title' => translator('admin.detail'), 'className' => 'border-b']) // 设置标题
            ->body($this->detail()) // 设置详情内容
            ->toolbar([$this->backButton()]); // 添加返回按钮工具栏

        // 将详情内容包装到页面中
        $page = $this->basePage()->body($detail);

        return $this->response()->success($page);
    }

    /**
     * 获取编辑页面
     *
     * @param mixed $id 数据主键ID
     * @throws ContainerExceptionInterface 容器异常接口
     * @throws NotFoundExceptionInterface 未找到异常接口
     * @return Response 返回编辑页面响应
     */
    public function edit($id)
    {
        // 设置当前为编辑页面状态
        $this->isEdit = true;

        // 如果是获取数据操作，返回编辑所需数据
        if ($this->actionOfGetData()) {
            return $this->response()->success($this->service->getEditData($id));
        }

        // 构建编辑表单结构
        $form = amis()
            ->Card() // 使用卡片组件
            ->header(['title' => translator('admin.edit'), 'className' => 'border-b']) // 设置标题
            ->toolbar([$this->backButton()]) // 添加返回按钮工具栏
            ->body($this->form(true)->api($this->getUpdatePath())->initApi($this->getEditGetDataPath()) // 设置表单内容、提交API和初始化数据API

            );

        // 将表单包装到页面中
        $page = $this->basePage()->body($form);

        return $this->response()->success($page);
    }

    /**
     * 更新数据保存
     *
     * @param Request $request HTTP请求对象
     * @param mixed $id 数据主键ID
     * @return Response 返回操作结果响应
     */
    public function update(Request $request, $id)
    {
        // 获取主键值（优先从请求参数获取，其次使用路由参数）
        $primaryKey = $this->getPrimaryValue($request) ?: $id;
        
        // 执行更新操作
        $result = $this->service->update($primaryKey, $request->all());

        // 返回自动响应结果
        return $this->autoResponse($result, translator('admin.save'));
    }

    /**
     * 删除数据
     *
     * @param mixed $id 数据主键ID或ID数组
     * @return Response 返回操作结果响应
     */
    public function destroy($id)
    {
        // 执行删除操作
        $rows = $this->service->delete($id);

        // 返回自动响应结果
        return $this->autoResponse($rows, translator('admin.delete'));
    }

    /**
     * 调用控制器方法
     *
     * @param string $method 方法名
     * @param array $parameters 参数数组
     * @return mixed 返回方法调用结果
     */
    public function callAction($method, $parameters)
    {
        return $this->{$method}(...array_values($parameters));
    }

    /**
     * 处理对控制器不存在方法的调用
     *
     * @param string $method 被调用的方法名
     * @param array $parameters 方法参数
     * @return mixed
     * @throws \BadMethodCallException 当调用不存在的方法时抛出异常
     */
    public function __call(string $method, array $parameters)
    {
        throw new BadMethodCallException(sprintf(
            'Method %s::%s does not exist.', static::class, $method
        ));
    }
}