<?php

namespace warm\admin\support\code_generator;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use warm\admin\support\code_generator\BaseGenerator;

class ModelGenerator extends BaseGenerator
{
    public function generate(): bool|string
    {
        return $this->writeFile($this->model->model_name, 'Model');
    }

    public function preview(): string
    {
        return $this->assembly();
    }

    public function assembly(): string
    {
        $name  = $this->model->model_name;
        $class = Str::of($name)->explode('/')->last();

        $content = '<?php' . PHP_EOL . PHP_EOL;
        $content .= 'namespace ' . $this->getNamespace($name) . ';' . PHP_EOL . PHP_EOL;

        // 软删
        if ($this->model->soft_delete) {
            $content .= 'use Illuminate\\Database\\Eloquent\\SoftDeletes;' . PHP_EOL;
        }

        $content .= 'use warm\admin\model\BaseModel as Model;' . PHP_EOL . PHP_EOL;
        $content .= '/**' . PHP_EOL;
        $content .= ' * ' . $this->model->title . PHP_EOL;
        $content .= ' */' . PHP_EOL;
        $content .= "class {$class} extends Model" . PHP_EOL;
        $content .= '{' . PHP_EOL;

        if ($this->model->soft_delete) {
            $content .= "\tuse SoftDeletes;" . PHP_EOL;
        }

        // 表名
        if (Str::plural(strtolower($class)) !== $this->model->table_name) {
            $content .= PHP_EOL . "\tprotected \$table = '{$this->model->table_name}';" . PHP_EOL;
        }
        // 主键
        if ($this->model->primary_key != 'id') {
            $content .= PHP_EOL . "protected \$primaryKey = '{$this->model->primary_key}';" . PHP_EOL;
        }
        // 时间戳
        if (!$this->model->need_timestamps) {
            $content .= PHP_EOL . "\tpublic \$timestamps = false;" . PHP_EOL;
        }
        // 处理文件上传
        foreach ($this->model->columns as $column) {
            if (Arr::get($column, 'file_column', false)) {
                $_name = Str::camel($column['name']);
                $fun   = 'file_upload_handle';
                if (Arr::get($column, 'file_column_multi', false)) {
                    $fun = 'file_upload_handle_multi';
                }
                $content .= <<<EOF

    public function {$_name}():\Illuminate\Database\Eloquent\Casts\Attribute
    {
        return {$fun}();
    }

EOF;
            }
        }

        $content .= '}';

        return $content;
    }
}
