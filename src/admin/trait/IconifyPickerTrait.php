<?php

namespace warm\admin\trait;

use warm\admin\renderer\Picker;

/**
 * Iconify图标选择器Trait
 * 
 * 提供Iconify图标选择器功能，用于在表单中选择图标
 * 支持从Iconify图标库中搜索和选择图标
 */
trait IconifyPickerTrait
{
    /**
     * iconify 图标选择器
     *
     * @param string $name 字段名
     * @param string $label 标签
     *
     * @return Picker 图标选择器控件实例
     */
    public function iconifyPicker(string $name = '', string $label = ''): Picker
    {
        $schema = amis()->CRUDCards()
            ->perPage(40)
            ->loadDataOnce()
            ->set('columnsCount', 8)
            ->footerToolbar(['statistics', 'pagination'])
            ->api('/_iconify_search')
            ->filter(
                amis()->Form()->wrapWithPanel(false)->body([
                    amis()->GroupControl()->className('pt-3 pb-3')->body([
                        amis()->TextControl('query')
                            ->size('md')
                            ->value('${' . $name . ' || "home"}')
                            ->clearable()
                            ->required(),
                        amis()->Button()
                            ->label(translator('admin.search'))
                            ->level('primary')
                            ->actionType('submit')
                            ->icon('fa fa-search'),
                        amis()->UrlAction()
                            ->className('ml-2')
                            ->icon('fa fa-external-link-alt')
                            ->label('Icones')
                            ->blank()
                            ->url('https://icones.js.org/collection/all'),
                    ]),
                ])
            )
            ->card(
                amis()->Card()->body([
                    amis()->SvgIcon()->icon('${icon}')->className('text-2xl'),
                ])
            );

        return amis()->PickerControl($name, $label)
            ->pickerSchema($schema)
            ->source('/_iconify_search')
            ->size('lg')
            ->labelField('icon')
            ->valueField('icon');
    }
}