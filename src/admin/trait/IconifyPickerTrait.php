<?php

namespace warm\admin\trait;

use warm\admin\renderer\form\Picker;

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
        $schema = amis()->CRUD()
            ->perPage(10)
            ->loadDataOnce()
            ->footerToolbar(['statistics', 'pagination'])
            ->api('/_iconify_search')
            ->filter(
                amis()->Form()->wrapWithPanel(false)->body([
                    amis()->Group()->className('pt-3 pb-3')->body([
                        amis()->InputText('query')
                            ->size('md')
                            ->value('${' . $name . ' || "home"}')
                            ->clearable()
                            ->required(),
                        amis()->Button()
                            ->label(translator('admin.search'))
                            ->level('primary')
                            ->actionType('submit')
                            ->icon('fa fa-search'),
                        amis()->Action()->actionType('url')
                            ->className('ml-2')
                            ->icon('fa fa-external-link-alt')
                            ->label('Icones')
                            ->blank()
                            ->url('https://icones.js.org/collection/all'),
                    ]),
                ])
            )
            ->columns([
                amis()->Flex()->justify('flex-start')->alignItems('center')->items([
                    amis()->CustomSvgIcon()->icon('${icon}')->className('text-2xl'),
                    amis()->Tpl()->className('ml-3')->tpl('${icon}')
                ])
            ]);

        return amis()->Picker($name, $label)
            ->pickerSchema($schema)
            ->modalSize('md')
            ->source('/_iconify_search')
            ->labelField('icon')
            ->valueField('icon');
    }
}
