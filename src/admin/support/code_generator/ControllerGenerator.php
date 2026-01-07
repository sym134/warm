<?php

namespace warm\admin\support\code_generator;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
/**
 * 控制器代码生成器
 * 
 * 用于生成Admin控制器类，包括列表、表单和详情页面的代码
 * 继承自BaseGenerator，提供控制器特定的生成逻辑
 */
class ControllerGenerator extends BaseGenerator
{
    /**
     * 生成控制器文件
     * 
     * @return bool|string 生成的文件路径
     */
    public function generate(): bool|string
    {
        return $this->writeFile($this->model->controller_name, 'Controller');
    }

    /**
     * 预览控制器代码
     * 
     * @return string 生成的控制器代码
     */
    public function preview(): string
    {
        return $this->assembly();
    }

    /**
     * 组装控制器代码
     * 
     * @return string 完整的控制器代码
     */
    public function assembly(): string
    {
        $name             = $this->model->controller_name;
        $class            = Str::of($name)->explode('/')->last();
        $serviceClass     = str_replace('/', '\\', $this->model->service_name);
        $serviceClassName = Str::of($serviceClass)->explode('\\')->last();

        $content = '<?php' . PHP_EOL . PHP_EOL;
        $content .= 'namespace ' . $this->getNamespace($name) . ';' . PHP_EOL . PHP_EOL;
        $content .= "use {$serviceClass};" . PHP_EOL;
        $content .= 'use warm\admin\controller\AdminController;' . PHP_EOL . PHP_EOL;
        $content .= '/**' . PHP_EOL;
        $content .= ' * ' . $this->model->title . PHP_EOL;
        $content .= ' *' . PHP_EOL;
        $content .= " * @property {$serviceClassName} \$service" . PHP_EOL;
        $content .= ' */' . PHP_EOL;
        $content .= "class {$class} extends AdminController" . PHP_EOL;
        $content .= '{' . PHP_EOL;
        $content .= "\tprotected string \$serviceName = {$serviceClassName}::class;" . PHP_EOL . PHP_EOL;

        $this->replaceListContent($content);
        $this->replaceFormContent($content);
        $this->replaceDetailContent($content);

        $content .= '}';

        return $content;
    }

    /**
     * 生成列表页面代码
     * 
     * @param string $content 控制器代码内容（引用传递）
     * @return void
     */
    protected function replaceListContent(&$content): void
    {
        $content .= "\tpublic function list()" . PHP_EOL;
        $content .= "\t{" . PHP_EOL;
        $content .= "\t\t\$crud = \$this->baseCRUD()" . PHP_EOL;

        // 筛选
        $filter = FilterGenerator::make($this->model)->renderComponent();
        if (blank($filter)) {
            $content .= "\t\t\t->filterTogglable(false)" . PHP_EOL;
        } else {
            $content .= $filter;
        }

        // 批量操作
        if (!in_array('batch_delete', $this->model->page_info['row_actions'])) {
            $content .= "\t\t\t->bulkActions([])" . PHP_EOL;
        }

        // 顶部工具栏
        $dialog = $this->model->page_info['dialog_form'];
        if ($dialog != 'page' && in_array('create', $this->model->page_info['row_actions'])) {
            $content .= "\t\t\t->headerToolbar([" . PHP_EOL;
            $content .= "\t\t\t\t\$this->createButton('{$dialog}'{$this->getDialogSize()})," . PHP_EOL;
            $content .= "\t\t\t\t...\$this->baseHeaderToolBar()" . PHP_EOL;
            $content .= "\t\t\t])" . PHP_EOL;
        }

        // 字段
        $content .= "\t\t\t->columns([" . PHP_EOL;

        $primaryKey     = $this->model->primary_key ?? 'id';
        $primaryKeyName = strtoupper($primaryKey);

        $content .= "\t\t\t\t" . "amis()->TableColumn('{$primaryKey}', '{$primaryKeyName}')->sortable()," . PHP_EOL;

        foreach ($this->model->columns as $column) {
            if (!$this->columnInTheScope($column, 'list')) {
                continue;
            }

            $item = $this->getColumnComponent('list_component', $column);

            if ($column['type'] == 'integer' && !Str::contains($column['name'], '_id')) {
                $item .= '->sortable()';
            }

            $content .= "\t\t\t\t" . $item . ',' . PHP_EOL;
        }

        if ($this->model->need_timestamps && $this->model->page_info['list_display_created_at']) {
            $content .= "\t\t\t\t" . "amis()->TableColumn('created_at', translator('admin.created_at'))->type('datetime')->sortable()" . ',' . PHP_EOL;
        }

        if ($this->model->need_timestamps && $this->model->page_info['list_display_updated_at']) {
            $content .= "\t\t\t\t" . "amis()->TableColumn('updated_at', translator('admin.updated_at'))->type('datetime')->sortable()" . ',' . PHP_EOL;
        }

        // 操作按钮
        $rowActions = $this->makeRowButton($this->model->page_info);
        if (filled($rowActions)) {
            $content .= "\t\t\t\t" . $rowActions . PHP_EOL;
        }
        $content .= "\t\t\t]);" . PHP_EOL . PHP_EOL;
        $content .= "\t\treturn \$this->baseList(\$crud);" . PHP_EOL;
        $content .= "\t}" . PHP_EOL;
    }

    /**
     * 生成表单页面代码
     * 
     * @param string $content 控制器代码内容（引用传递）
     * @return void
     */
    protected function replaceFormContent(&$content): void
    {
        $content .= PHP_EOL;
        $content .= "\tpublic function form(\$isEdit = false)" . PHP_EOL;
        $content .= "\t{" . PHP_EOL;
        if ($this->model->page_info['dialog_form'] == 'drawer') {
            $content .= "\t\treturn \$this->baseForm()->mode('normal')->body([" . PHP_EOL;
        } else {
            $content .= "\t\treturn \$this->baseForm()->body([" . PHP_EOL;
        }

        foreach ($this->model->columns as $column) {
            if (data_get($column, 'index') == 'primary') {
                continue;
            }

            if (!$this->columnInTheScope($column, 'create') && !$this->columnInTheScope($column, 'edit')) {
                continue;
            }

            $item = $this->getColumnComponent('form_component', $column);

            if (!$this->columnInTheScope($column, 'create') && $this->columnInTheScope($column, 'edit')) {
                $item .= '->visibleOn($isEdit)';
            } else if ($this->columnInTheScope($column, 'create') && !$this->columnInTheScope($column, 'edit')) {
                $item .= '->visibleOn(!$isEdit)';
            }

            $content .= "\t\t\t" . $item . ',' . PHP_EOL;
        }

        $content .= "\t\t]);" . PHP_EOL;
        $content .= "\t}" . PHP_EOL;
    }

    /**
     * 生成详情页面代码
     * 
     * @param string $content 控制器代码内容（引用传递）
     * @return void
     */
    protected function replaceDetailContent(&$content): void
    {
        $content .= PHP_EOL;
        $content .= "\tpublic function detail()" . PHP_EOL;
        $content .= "\t{" . PHP_EOL;
        $content .= "\t\treturn \$this->baseDetail()->body([" . PHP_EOL;

        $primaryKey     = $this->model->primary_key ?? 'id';
        $primaryKeyName = strtoupper($primaryKey);

        $content .= "\t\t\t" . "amis()->InputText('{$primaryKey}', translator('{$primaryKeyName}'))->static()," . PHP_EOL;

        foreach ($this->model->columns as $column) {
            if (!$this->columnInTheScope($column, 'detail')) {
                continue;
            }

            $item = $this->getColumnComponent('detail_component', $column);

            $content .= "\t\t\t" . $item . ',' . PHP_EOL;
        }

        if ($this->model->need_timestamps) {
            $content .= "\t\t\tamis()->InputText('created_at', translator('admin.created_at'))->static()," . PHP_EOL;
            $content .= "\t\t\tamis()->InputText('updated_at', translator('admin.updated_at'))->static()," . PHP_EOL;
        }

        $content .= "\t\t]);" . PHP_EOL;
        $content .= "\t}" . PHP_EOL;
    }

    /**
     * 判断字段是否在指定作用域内
     * 
     * @param array $column 字段信息
     * @param string $scope 作用域（list, create, edit, detail等）
     * @return bool 是否在作用域内
     */
    public function columnInTheScope($column, $scope): bool
    {
        if (!Arr::has($column, 'action_scope')) {
            return true;
        }

        return in_array($scope, Arr::get($column, 'action_scope', []));
    }

    /**
     * 获取字段对应的组件
     * 
     * @param string $type 组件类型（list_component, form_component, detail_component）
     * @param array $column 字段信息
     * @return string 组件代码
     */
    public function getColumnComponent($type, $column): string
    {
        $label = Arr::get($column, 'name');

        $component = data_get($column, $type);
        if ($componentType = data_get($component, $type . '_type')) {
            $item = "amis()->{$componentType}('{$column['name']}', translator('{$label}'))";
            if ($property = Arr::get($component, $type . '_property')) {
                $item .= $this->buildComponentProperty($property);
            }

            return $item;
        }
        $label = $this->model->table_name . '.' . $label;
        return match ($type) {
            'list_component'   => "amis()->TableColumn('{$column['name']}', translator('$label'))",
            'form_component'   => "amis()->InputText('{$column['name']}', translator('$label'))",
            'detail_component' => "amis()->InputText('{$column['name']}', translator('$label'))->static()",
        };
    }

    /**
     * 生成行操作按钮
     * 
     * @param array $pageInfo 页面信息
     * @return string 按钮代码
     */
    private function makeRowButton($pageInfo): string
    {
        $hasRowAction = false;
        $_actions     = data_get($pageInfo, 'row_actions');
        $dialog       = $pageInfo['dialog_form'] ? "'{$pageInfo['dialog_form']}'" : '';
        $dialogSize   = $this->getDialogSize();

        if (in_array('show', $_actions) && in_array('edit', $_actions) && in_array('delete', $_actions)) {
            return "\$this->rowActions({$dialog}{$dialogSize})";
        }

        $str = "\$this->rowActions([\n\t\t\t\t";

        if (in_array('show', $_actions)) {
            $hasRowAction = true;
            $str          .= "\t\$this->rowShowButton({$dialog}{$dialogSize}),\n\t\t\t\t";
        }
        if (in_array('edit', $_actions)) {
            $hasRowAction = true;
            $str          .= "\t\$this->rowEditButton({$dialog}{$dialogSize}),\n\t\t\t\t";
        }
        if (in_array('delete', $_actions)) {
            $hasRowAction = true;
            $str          .= "\t\$this->rowDeleteButton({$dialog}{$dialogSize}),\n\t\t\t\t";
        }
        $str .= "])";

        if (!$hasRowAction) return '';

        return $str;
    }

    /**
     * 获取对话框尺寸参数
     * 
     * @return string 尺寸参数
     */
    private function getDialogSize(): string
    {
        $pageInfo   = $this->model->page_info;
        $dialogSize = $pageInfo['dialog_size'] ?? 'md';
        $dialogSize = $dialogSize == 'md' ? '' : ', \'' . $dialogSize . '\'';

        return $pageInfo['dialog_form'] ? $dialogSize : '';
    }
}