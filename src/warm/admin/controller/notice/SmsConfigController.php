<?php

namespace warm\admin\controller\notice;

use support\Request;
use support\Response;
use warm\admin\controller\AdminController;
use warm\admin\renderer\Form;
use warm\admin\renderer\Page;
use warm\admin\service\notice\SmsConfigService;

class SmsConfigController extends AdminController
{
    protected string $serviceName = SmsConfigService::class;

    public function index(): Response
    {
        if ($this->actionOfGetData()) {
            return $this->response()->success(SmsConfigService::make()->get());
        }

        if ($this->actionOfExport()) {
            return $this->export();
        }

        return $this->response()->success($this->list());
    }

    public function list(): Page
    {
        $crud = $this->baseCRUD()
            ->headerToolbar([
                $this->createButton(true),
                ...$this->baseHeaderToolBar(),
            ])
            ->columns([
                amis()->TableColumn('type', 'type')->toggled(false),
                amis()->TableColumn('name', admin_trans('notice.sms_channel')),
                amis()->SwitchControl('enable', admin_trans('notice.enable')),
                $this->rowActions([
                    $this->rowEditButton(true),
                    $this->rowDeleteButton(),
                ]),
            ]);

        return $this->baseList($crud);
    }

    public function form($bool): Form
    {
        return $this->baseForm()
            ->body([
                amis()->SelectControl('type', '短信渠道')->required()->disabled($bool)->options(['aliyun' => '阿里云', 'qcloud' => '腾讯云', 'smsbao' => '短信宝']),
                amis()->Wrapper()->visibleOn("this.type==='aliyun'")->body([
                    amis()->HiddenControl('name'),
                    amis()->TextControl('access_key_id', admin_trans('notice.access_key_id'))->required(),
                    amis()->TextControl('access_key_secret', admin_trans('notice.access_key_secret'))->required(),
                    amis()->TextControl('sign_name', admin_trans('notice.sign_name'))->required(),
                    amis()->SwitchControl('enable', admin_trans('notice.enable')),
                ]),
                amis()->Wrapper()->visibleOn("this.type==='qcloud'")->body([
                    amis()->HiddenControl('name'),
                    amis()->TextControl('sdk_app_id', admin_trans('notice.sdk_app_id'))->required(),
                    amis()->TextControl('secret_id', admin_trans('notice.secret_id'))->required(),
                    amis()->TextControl('secret_key', admin_trans('notice.secret_key'))->required(),
                    amis()->TextControl('sign_name', admin_trans('notice.sign_name'))->required(),
                    amis()->SwitchControl('enable', admin_trans('notice.enable')),
                ]),
                amis()->Wrapper()->visibleOn("this.type==='smsbao'")->body([
                    amis()->HiddenControl('name'),
                    amis()->TextControl('user', admin_trans('notice.user'))->required(),
                    amis()->TextControl('password', admin_trans('notice.password'))->required(),
                    amis()->SwitchControl('enable', admin_trans('notice.enable')),
                ]),
            ]);
    }

    /**
     * 新增保存
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(Request $request): Response
    {
        $response = fn($result) => $this->autoResponse($result, admin_trans('admin.save'));

        if ($this->actionOfQuickEdit()) {
            return $response($this->service->quickEdit($request->all()));
        }

        if ($this->actionOfQuickEditItem()) {
            return $response($this->service->quickEditItem($request->all()));
        }

        return $response($this->service->store($request->all()));
    }
}