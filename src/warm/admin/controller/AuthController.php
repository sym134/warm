<?php

namespace warm\admin\controller;

use support\Request;
use support\Response;
use warm\admin\Admin;
use warm\admin\service\AdminUserService;
use warm\admin\support\Captcha;
use warm\framework\support\facade\Hash;
use Webman\Event\Event;

/**
 * 认证控制器类
 * 
 * 处理用户登录、登出、验证码等相关功能
 * 继承自AdminController，提供完整的认证流程管理
 */
class AuthController extends AdminController
{
    /**
     * 服务类名称
     * 
     * @var string
     */
    protected string $serviceName = AdminUserService::class;

    /**
     * 用户登录处理
     * 
     * 验证用户提交的用户名和密码，处理验证码验证，执行登录操作
     * 
     * @param Request $request HTTP请求对象
     * @return Response 响应对象
     */
    public function login(Request $request)
    {
        // 检查是否启用了验证码功能
        if (Admin::config('app.auth.login_captcha')) {
            // 验证验证码是否填写
            if (!$request->post('captcha')) {
                return $this->response()
                    ->fail(translator('admin.required', ['attribute' => translator('admin.captcha')]));
            }
            // 验证验证码是否正确
            if (strtolower(cache()->pull($request->post('sys_captcha'))) != strtolower($request->post('captcha'))) {
                return $this->response()->fail(translator('admin.captcha_error'));
            }
        }

        try {
            // 验证用户名和密码是否填写
            $validator = validate([
                'username' => 'require',
                'password' => 'require',
            ], [
                'username.require' => translator('admin.required', ['attribute' => translator('admin.username')]),
                'password.require' => translator('admin.required', ['attribute' => translator('admin.password')]),
            ]);
            if (!$validator->check($request->all())) {
                abort(400, $validator->getError());
            }

            // 查询用户信息
            $user = Admin::adminUserModel()::query()->where('username', $request->post('username'))->first();

            // 验证用户密码
            if ($user && Hash::instance()->check($request->post('password'), $user->password)) {
                // 检查用户是否启用
                if (!$user->enabled) {
                    // 触发登录事件
                    Event::emit('user.login', ['username' => $user->name, 'status' => 3, 'message' => '用户未启用']);
                    return $this->response()->fail(translator('admin.user_disabled'));
                }

                // $module = Admin::currentModule(true);
                // $prefix = $module ? $module . '.' : '';
                // 生成访问令牌
                $token = $this->guard()->login($user)->access_token;

                // 触发登录成功事件
                Event::emit('user.login', ['username' => $user->name, 'status' => 1, 'message' => '登陆成功']);
                return $this->response()->success(compact('token'), translator('admin.login_successful'));
            }

            // 触发登录失败事件
            Event::emit('user.login', ['username' => $request->post('username'), 'status' => 2, 'message' => '登陆失败']);
            abort(400, translator('admin.login_failed'));
        } catch (\Exception $e) {
            return $this->response()->fail($e->getMessage());
        }
    }

    /**
     * 登录页面展示
     * 
     * 构建并返回登录页面的Amis结构
     * 
     * @return mixed 登录页面结构
     */
    public function loginPage()
    {
        // 构建登录表单
        $form = amis()->Form()
            ->panelClassName('border-none')
            ->id('login-form')
            ->title()
            ->api(admin_url('/login'))
            ->initApi('/no-content')
            ->body([
                amis()->TextControl()->name('username')->placeholder(translator('admin.username'))->required(),
                amis()
                    ->TextControl()
                    ->type('input-password')
                    ->name('password')
                    ->placeholder(translator('admin.password'))
                    ->required(),
                amis()->InputGroupControl('captcha_group')->body([
                    amis()->TextControl('captcha', translator('admin.captcha'))->placeholder(translator('admin.captcha'))->required(),
                    amis()->HiddenControl()->name('sys_captcha'),
                    amis()->Service()->id('captcha-service')->api('get:' . admin_url('/captcha'))->body(
                        amis()->Image()
                            ->src('${captcha_img}')
                            ->height('1.917rem')
                            ->className('p-0 captcha-box')
                            ->imageClassName('rounded-r')
                            ->set(
                                'clickAction',
                                ['actionType' => 'reload', 'target' => 'captcha-service']
                            )
                    ),
                ])->visibleOn('${!!login_captcha}'),
                amis()->CheckboxControl()->name('remember_me')->option(translator('admin.remember_me'))->value(true),

                // 登录按钮
                amis()->VanillaAction()
                    ->actionType('submit')
                    ->label(translator('admin.login'))
                    ->level('primary')
                    ->className('w-full'),
            ])
            // 清空默认的提交按钮
            ->actions([])
            ->onEvent([
                // 页面初始化事件
                'inited'     => [
                    'actions' => [
                        // 读取本地存储的登录参数
                        [
                            'actionType' => 'custom',
                            'script'     => <<<JS
let loginParams = localStorage.getItem(window.\$owl.getCacheKey('loginParams'))
if(loginParams){
    loginParams = JSON.parse(decodeURIComponent(window.atob(loginParams)))
    doAction({
        actionType: 'setValue',
        componentId: 'login-form',
        args: { value: loginParams }
    })
}
JS
                            ,

                        ],
                    ],
                ],
                // 登录成功事件
                'submitSucc' => [
                    'actions' => [
                        // 保存登录参数到本地, 并跳转到首页
                        [
                            'actionType' => 'custom',
                            'script'     => <<<JS
let _data = {}
if(event.data.remember_me){
    _data = { username: event.data.username, password: event.data.password }
}
window.\$owl.afterLoginSuccess(_data, event.data.result.data.token)
JS,

                        ],
                    ],
                ],

                // 登录失败事件
                'submitFail' => [
                    'actions' => [
                        // 刷新验证码外层Service
                        ['actionType' => 'reload', 'componentId' => 'captcha-service'],
                    ],
                ],
            ]);

        // 构建登录卡片
        $card = amis()->Card()->className('w-96 m:w-full')->body([
            amis()->Service()->api('/_settings')->body([
                amis()->Flex()->justify('space-between')->className('px-2.5 pb-2.5')->items([
                    amis()->Image()->src('${logo}')->width(40)->height(40),
                    amis()->Tpl()
                        ->className('font-medium')
                        ->tpl('<div style="font-size: 24px">${app_name}</div>'),
                ]),
                $form,
            ]),
        ]);
        
        // 返回登录页面
        return amis()->Page()->className('login-bg')->css([
            '.captcha-box .cxd-Image--thumb' => [
                'padding' => '0',
                'cursor'  => 'pointer',
                'border'  => 'var(--Form-input-borderWidth) solid var(--Form-input-borderColor)',

                'border-top-right-radius'    => '4px',
                'border-bottom-right-radius' => '4px',
            ],
            '.cxd-Image-thumb'               => ['width' => 'auto'],
            '.login-bg'                      => [
                'background' => 'var(--owl-body-bg)',
            ],
        ])->body(
            amis()->Wrapper()->className("h-screen w-full flex items-center justify-center")->body($card)
        );
    }

    /**
     * 刷新验证码
     *
     * 生成新的验证码图片和标识符
     *
     * @return Response 验证码响应
     */
    public function reloadCaptcha(): Response
    {
        // 创建验证码实例
        $captcha = new Captcha();

        // 生成验证码图片
        $captcha_img = $captcha->showImg();
        // 生成验证码标识符
        $sys_captcha = uniqid('captcha-');

        // 将验证码存储到缓存中，有效期10分钟
        cache()->put($sys_captcha, $captcha->getCaptcha(), 600);

        // 返回验证码数据
        return $this->response()->success(compact('captcha_img', 'sys_captcha'));
    }

    /**
     * 用户登出
     * 
     * 执行用户登出操作，清除用户会话
     * 
     * @return Response 响应对象
     */
    public function logout(): Response
    {
        // 调用认证守卫执行登出
        $this->guard()->logout();

        // 返回成功响应
        return $this->response()->successMessage();
    }

    /**
     * 获取认证守卫实例
     * 
     * @return mixed 认证守卫实例
     */
    protected function guard()
    {
        return Admin::guard();
    }

    /**
     * 获取当前用户信息
     * 
     * 返回当前登录用户的基本信息和操作菜单
     * 
     * @return Response 用户信息响应
     */
    public function currentUser(): Response
    {
        // 检查认证功能是否启用
        if (!Admin::config('app.auth.enable')) {
            return $this->response()->success([]);
        }

        // 获取用户信息
        $userInfo = Admin::user()->only(['name', 'avatar']);

        // 构建用户菜单
        $menus = amis()->DropdownButton()
            ->hideCaret()
            ->trigger('hover')
            ->label($userInfo['name'])
            ->className('h-full w-full')
            ->btnClassName('navbar-user w-full')
            ->menuClassName('min-w-0')
            ->set('icon', $userInfo['avatar'])
            ->buttons([
                amis()->VanillaAction()
                    ->iconClassName('pr-2')
                    ->icon('fa fa-user-gear')
                    ->label(translator('admin.user_setting'))
                    ->onClick('window.location.hash = "#/user_setting"'),
                amis()->VanillaAction()
                    ->iconClassName('pr-2')
                    ->label(translator('admin.logout'))
                    ->icon('fa-solid fa-right-from-bracket')
                    ->onClick('window.$owl.logout()'),
            ]);

        // 返回用户信息和菜单
        return $this->response()->success(array_merge($userInfo, compact('menus')));
    }

    /**
     * 用户设置页面
     * 
     * 展示用户个人设置表单
     * 
     * @return Response 设置页面响应
     */
    public function userSetting(): Response
    {
        // 构建用户设置表单
        $form = amis()->Form()
            ->title()
            ->panelClassName('px-48 m:px-0')
            ->mode('horizontal')
            ->initApi('/current-user')
            ->api('put:' . admin_url('/user_setting'))
            ->body([
                amis()->ImageControl()
                    ->label(translator('admin.admin_user.avatar'))
                    ->name('avatar')
                    ->receiver($this->uploadImagePath()),
                amis()->TextControl()->label(translator('admin.admin_user.name'))->name('name')->required(),
                amis()->TextControl()->type('input-password')->label(translator('admin.old_password'))->name('old_password'),
                amis()->TextControl()->type('input-password')->label(translator('admin.password'))->name('password'),
                amis()->TextControl()
                    ->type('input-password')
                    ->label(translator('admin.confirm_password'))
                    ->name('confirm_password'),
            ]);

        // 返回设置页面
        return $this->response()->success(amis()->Page()->body($form));
    }

    /**
     * 保存用户设置
     * 
     * 更新用户的个人信息和密码
     * 
     * @return Response 保存结果响应
     */
    public function saveUserSetting(): Response
    {
        // 更新用户设置
        $result = $this->service->updateUserSetting($this->user()->id,
            request()->only([
                'avatar',
                'name',
                'old_password',
                'password',
                'confirm_password',
            ]));

        // 返回自动响应结果
        return $this->autoResponse($result);
    }
}