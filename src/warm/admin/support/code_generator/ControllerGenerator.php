<?php

namespace warm\admin\support\code_generator;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use warm\admin\support\code_generator\BaseGenerator;
use warm\admin\support\code_generator\FilterGenerator;

class ControllerGenerator extends BaseGenerator
{
    public function generate(): bool|string
    {
        return $this->writeFile($this->model->controller_name, 'Controller');
    }

    public function preview(): string
    {
        return $this->assembly();
    }

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
            $content .= "\t\t\t\t" . "amis()->TableColumn('created_at', admin_trans('admin.created_at'))->type('datetime')->sortable()" . ',' . PHP_EOL;
        }

        if ($this->model->need_timestamps && $this->model->page_info['list_display_updated_at']) {
            $content .= "\t\t\t\t" . "amis()->TableColumn('updated_at', admin_trans('admin.updated_at'))->type('datetime')->sortable()" . ',' . PHP_EOL;
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

    protected function replaceDetailContent(&$content): void
    {
        $content .= PHP_EOL;
        $content .= "\tpublic function detail()" . PHP_EOL;
        $content .= "\t{" . PHP_EOL;
        $content .= "\t\treturn \$this->baseDetail()->body([" . PHP_EOL;

        $primaryKey     = $this->model->primary_key ?? 'id';
        $primaryKeyName = strtoupper($primaryKey);

        $content .= "\t\t\t" . "amis()->TextControl('{$primaryKey}', admin_trans('{$primaryKeyName}'))->static()," . PHP_EOL;

        foreach ($this->model->columns as $column) {
            if (!$this->columnInTheScope($column, 'detail')) {
                continue;
            }

            $item = $this->getColumnComponent('detail_component', $column);

            $content .= "\t\t\t" . $item . ',' . PHP_EOL;
        }

        if ($this->model->need_timestamps) {
            $content .= "\t\t\tamis()->TextControl('created_at', admin_trans('admin.created_at'))->static()," . PHP_EOL;
            $content .= "\t\t\tamis()->TextControl('updated_at', admin_trans('admin.updated_at'))->static()," . PHP_EOL;
        }

        $content .= "\t\t]);" . PHP_EOL;
        $content .= "\t}" . PHP_EOL;
    }

    public function columnInTheScope($column, $scope): bool
    {
        if (!Arr::has($column, 'action_scope')) {
            return true;
        }

        return in_array($scope, Arr::get($column, 'action_scope', []));
    }

    public function getColumnComponent($type, $column): string
    {
        $label = Arr::get($column, 'name');

        $component = data_get($column, $type);
        if ($componentType = data_get($component, $type . '_type')) {
            $item = "amis()->{$componentType}('{$column['name']}', admin_trans('{$label}'))";
            if ($property = Arr::get($component, $type . '_property')) {
                $item .= $this->buildComponentProperty($property);
            }

            return $item;
        }
        $label = $this->model->table_name . '.' . $label;
        return match ($type) {
            'list_component'   => "amis()->TableColumn('{$column['name']}', admin_trans('$label'))",
            'form_component'   => "amis()->TextControl('{$column['name']}', admin_trans('$label'))",
            'detail_component' => "amis()->TextControl('{$column['name']}', admin_trans('$label'))->static()",
        };
    }

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

    private function getDialogSize(): string
    {
        $pageInfo   = $this->model->page_info;
        $dialogSize = $pageInfo['dialog_size'] ?? 'md';
        $dialogSize = $dialogSize == 'md' ? '' : ', \'' . $dialogSize . '\'';

        return $pageInfo['dialog_form'] ? $dialogSize : '';
    }
}
