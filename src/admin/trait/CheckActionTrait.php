<?php

namespace warm\admin\trait;

/**
 * 操作检查Trait
 * 
 * 提供各种操作类型的检查方法，用于判断当前请求的操作类型
 * 便于在控制器中根据不同的操作类型执行相应的逻辑
 */
trait CheckActionTrait
{
    /**
     * 是否为列表数据请求
     *
     * @return bool 如果是列表数据请求返回true，否则返回false
     */
    public function actionOfGetData(): bool
    {
        return request()->input('_action') == 'getData';
    }

    /**
     * 是否为导出数据请求
     *
     * @return bool 如果是导出数据请求返回true，否则返回false
     */
    public function actionOfExport(): bool
    {
        return request()->input('_action') == 'export';
    }

    /**
     * 是否为快速编辑数据请求
     *
     * @return bool 如果是快速编辑数据请求返回true，否则返回false
     */
    public function actionOfQuickEdit(): bool
    {
        return request()->input('_action') == 'quickEdit';
    }

    /**
     * 是否为快速编辑单项数据请求
     *
     * @return bool 如果是快速编辑单项数据请求返回true，否则返回false
     */
    public function actionOfQuickEditItem(): bool
    {
        return request()->input('_action') == 'quickEditItem';
    }
}