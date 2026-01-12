<?php

namespace warm\admin\support\code_generator;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Migrations\MigrationCreator as BaseMigrationCreator;
use Illuminate\Support\Arr;
use warm\admin\model\AdminCodeGenerator;

/**
 * 数据库迁移文件生成器
 * 
 * 用于生成数据库迁移文件，继承自Laravel的MigrationCreator
 * 提供创建数据表的迁移代码生成功能
 */
class MigrationGenerator extends BaseMigrationCreator
{
    /** @var AdminCodeGenerator 模型实例 */
    protected AdminCodeGenerator $model;

    /**
     * 构造函数
     * 
     * @param AdminCodeGenerator $model 模型实例
     */
    public function __construct($model)
    {
        $this->model = $model;

        parent::__construct((new \Illuminate\Filesystem\Filesystem), __DIR__ . '/stubs');
    }

    /**
     * 创建静态实例
     * 
     * @param AdminCodeGenerator $model 模型实例
     * @return static 静态实例
     */
    public static function make(AdminCodeGenerator $model): static
    {
        return new self($model);
    }

    /**
     * 生成迁移文件
     *
     * @return string 生成的迁移文件路径
     * @throws \Exception
     */
    public function generate(): string
    {
        $name = 'create_' . $this->model->table_name . '_table';
        // 是否是app目录
        if ($this->model->save_path['directory'] !== 'app') {
            $path = plugin_path($this->model->save_path['directory']) . '/database/migrations/';
        } else {
            $path = database_path('migrations') ;
        }

        return $this->create($name, $path, $this->model->table_name, null);
    }

    /**
     * 填充模板内容
     *
     * @param string $stub 模板内容
     * @param string $table 表名
     * @return array|string 填充后的模板内容
     * @throws \Exception
     */
    protected function populateStub($stub, $table): array|string
    {
        return str_replace(['{{ content }}', '{{ table }}'], [$this->generateContent(), $table], $stub);
    }

    /**
     * 预览迁移代码
     *
     * @return array|string 迁移代码
     * @throws FileNotFoundException
     */
    public function preview(): array|string
    {
        return $this->populateStub($this->getStub($this->model->table_name, false), $this->model->table_name);
    }

    /**
     * 生成迁移内容
     *
     * @return string 迁移内容代码
     * @throws \Exception
     */
    public function generateContent(): string
    {
        blank($this->model->columns) && abort(400, 'Table fields can\'t be empty');

        $rows   = [];
        $rows[] = "\$table->comment('{$this->model->title}');\n";
        $rows[] = "\$table->increments('{$this->model->primary_key}');\n";

        foreach ($this->model->columns as $field) {
            $additional = Arr::get($field, 'additional');

            $column = "\$table->{$field['type']}('{$field['name']}'";
            if ($additional && $additional != '') {
                $column .= ', ' . $additional;
            }
            $column .= ')';

            $column_index = Arr::get($field, 'column_index');
            if ($column_index) {
                $column .= "->{$column_index}()";
            }

            $hasDefault = isset($field['default']) && $field['default'] !== '';
            if ($hasDefault) {
                $default = "'{$field['default']}'";

                if (is_numeric($field['default'])) {
                    $default = "new \Illuminate\Database\Query\Expression({$default})";
                }

                $column .= "->default({$default})";
            }

            if (Arr::get($field, 'nullable', false)) {
                $column .= '->nullable()';
            } else if (!$hasDefault && $field['type'] === 'string') {
                $column .= "->default('')";
            }

            if (isset($field['comment']) && $field['comment']) {
                $column .= "->comment('{$field['comment']}')";
            }

            $rows[] = $column . ";\n";
        }

        if ($this->model->need_timestamps) {
            $rows[] = "\$table->timestamps();\n";
        }

        if ($this->model->soft_delete) {
            $rows[] = "\$table->softDeletes();\n";
        }

        return trim(implode(str_repeat(' ', 12), $rows), "\n");
    }

    /**
     * 获取模板内容
     *
     * @param string $table 表名
     * @param bool $create 是否为创建表
     * @return string 模板内容
     * @throws FileNotFoundException
     */
    protected function getStub($table, $create): string
    {
        $stub = $this->files->exists($customPath = $this->customStubPath . '/migration.stub')
            ? $customPath
            : $this->stubPath() . '/migration.stub';

        return $this->files->get($stub);
    }

    /**
     * 获取模板路径
     * 
     * @return string 模板路径
     */
    public function stubPath(): string
    {
        return __DIR__ . '/stubs';
    }
}