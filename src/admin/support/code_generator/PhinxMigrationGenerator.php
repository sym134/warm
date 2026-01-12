<?php

namespace warm\admin\support\code_generator;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use warm\admin\model\AdminCodeGenerator;

/**
 * Phinx 数据库迁移文件生成器
 * 
 * 基于 robmorgan/phinx 生成数据库迁移文件
 * 提供创建数据表的迁移代码生成功能
 */
class PhinxMigrationGenerator
{
    /** @var AdminCodeGenerator 模型实例 */
    protected AdminCodeGenerator $model;

    /** @var Filesystem 文件系统实例 */
    protected Filesystem $files;

    /**
     * 字段类型映射（Laravel类型 -> Phinx类型）
     * 
     * @var array
     */
    protected static array $typeMap = [
        'string' => 'string',
        'char' => 'char',
        'text' => 'text',
        'mediumText' => 'text',
        'longText' => 'text',
        'integer' => 'integer',
        'tinyInteger' => 'integer',
        'smallInteger' => 'integer',
        'mediumInteger' => 'integer',
        'bigInteger' => 'biginteger',
        'unsignedInteger' => 'integer',
        'unsignedTinyInteger' => 'integer',
        'unsignedSmallInteger' => 'integer',
        'unsignedMediumInteger' => 'integer',
        'unsignedBigInteger' => 'biginteger',
        'float' => 'float',
        'double' => 'decimal',
        'decimal' => 'decimal',
        'boolean' => 'boolean',
        'date' => 'date',
        'dateTime' => 'datetime',
        'timestamp' => 'timestamp',
        'time' => 'time',
        'json' => 'json',
        'binary' => 'binary',
        'enum' => 'string',
    ];

    /**
     * 构造函数
     * 
     * @param AdminCodeGenerator $model 模型实例
     */
    public function __construct(AdminCodeGenerator $model)
    {
        $this->model = $model;
        $this->files = new Filesystem();
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
        $className = $this->getClassName($name);
        
        // 确定保存路径
        if ($this->model->save_path['directory'] !== 'app') {
            $path = plugin_path($this->model->save_path['directory']) . '/database/migrations/';
        } else {
            $path = database_path('migrations');
        }

        // 确保目录存在
        if (!is_dir($path)) {
            $this->files->makeDirectory($path, 0755, true);
        }

        // 生成文件名（带时间戳）
        $fileName = $this->getDatePrefix() . '_' . Str::snake($name) . '.php';
        $filePath = $path . '/' . $fileName;

        // 检查文件是否已存在
        if ($this->files->exists($filePath)) {
            throw new \Exception("Migration file [{$fileName}] already exists!");
        }

        // 生成文件内容
        $content = $this->populateStub($this->getStub(), $this->model->table_name, $className);
        
        // 写入文件
        $this->files->put($filePath, $content);
        $this->files->chmod($filePath, 0644);

        return $filePath;
    }

    /**
     * 预览迁移代码
     *
     * @return string 迁移代码
     * @throws \Exception
     */
    public function preview(): string
    {
        $name = 'create_' . $this->model->table_name . '_table';
        $className = $this->getClassName($name);
        
        return $this->populateStub($this->getStub(), $this->model->table_name, $className);
    }

    /**
     * 填充模板内容
     *
     * @param string $stub 模板内容
     * @param string $table 表名
     * @param string $className 类名
     * @return string 填充后的模板内容
     * @throws \Exception
     */
    protected function populateStub(string $stub, string $table, string $className): string
    {
        return str_replace(
            ['{{ class }}', '{{ content }}', '{{ table }}'],
            [$className, $this->generateContent($table), $table],
            $stub
        );
    }

    /**
     * 生成迁移内容
     *
     * @param string $table 表名
     * @return string 迁移内容代码
     * @throws \Exception
     */
    protected function generateContent(string $table): string
    {
        blank($this->model->columns) && abort(400, 'Table fields can\'t be empty');

        $rows = [];
        $indent = '        ';
        
        // 创建表，设置注释
        $tableOptions = [];
        if (!empty($this->model->title)) {
            $tableOptions['comment'] = $this->model->title;
        }
        
        $tableDef = '$table = $this->table(\'' . $table . '\'';
        if (!empty($tableOptions)) {
            $tableDef .= ', ' . $this->arrayToString($tableOptions);
        }
        $tableDef .= ')';
        $rows[] = $tableDef;

        // 添加主键（自增）
        if (!empty($this->model->primary_key)) {
            $primaryKeyOptions = ['identity' => true, 'signed' => false];
            $rows[] = '            ->addColumn(\'' . $this->model->primary_key . '\', \'integer\', ' 
                . $this->arrayToString($primaryKeyOptions) . ')';
        }

        // 添加字段
        foreach ($this->model->columns as $field) {
            $columnCode = $this->generateColumnCode($field);
            $rows[] = '            ' . $columnCode;
        }

        // 添加时间戳
        if ($this->model->need_timestamps) {
            $rows[] = '            ->addColumn(\'created_at\', \'timestamp\', [\'null\' => true, \'default\' => \'CURRENT_TIMESTAMP\'])';
            $rows[] = '            ->addColumn(\'updated_at\', \'timestamp\', [\'null\' => true, \'default\' => \'CURRENT_TIMESTAMP\', \'update\' => \'CURRENT_TIMESTAMP\'])';
        }

        // 添加软删除
        if ($this->model->soft_delete) {
            $rows[] = '            ->addColumn(\'deleted_at\', \'timestamp\', [\'null\' => true, \'default\' => null])';
        }

        // 添加主键索引
        if (!empty($this->model->primary_key)) {
            $rows[] = '            ->setPrimaryKey([\'' . $this->model->primary_key . '\'])';
        }

        // 创建表
        $rows[] = '            ->create();';

        return implode("\n", $rows);
    }

    /**
     * 生成字段代码
     *
     * @param array $field 字段配置
     * @return string 字段代码
     */
    protected function generateColumnCode(array $field): string
    {
        $columnName = $field['name'];
        $columnType = $this->mapTypeToPhinx(Arr::get($field, 'type', 'string'));
        $options = [];

        // 处理额外参数（如长度）
        $additional = Arr::get($field, 'additional');
        if ($additional && $additional != '') {
            // 如果是数字，可能是长度限制
            if (is_numeric($additional)) {
                $options['limit'] = (int)$additional;
            } else {
                // 尝试解析字符串参数
                $options = array_merge($options, $this->parseAdditionalParams($additional));
            }
        }

        // 处理默认值
        $hasDefault = isset($field['default']) && $field['default'] !== '';
        if ($hasDefault) {
            $defaultValue = $field['default'];
            // 检查是否是数字字符串
            if (is_numeric($defaultValue)) {
                // 判断是整数还是浮点数
                $options['default'] = (str_contains($defaultValue, '.')) ? (float)$defaultValue : (int)$defaultValue;
            } elseif (in_array(strtoupper($defaultValue), ['CURRENT_TIMESTAMP', 'NOW()'])) {
                $options['default'] = 'CURRENT_TIMESTAMP';
            } else {
                // 字符串默认值需要引号
                $options['default'] = $defaultValue;
            }
        } elseif (!$hasDefault && Arr::get($field, 'type') === 'string' && !Arr::get($field, 'nullable', false)) {
            $options['default'] = '';
        }

        // 处理可空
        if (Arr::get($field, 'nullable', false)) {
            $options['null'] = true;
        }

        // 处理注释
        if (isset($field['comment']) && $field['comment']) {
            $options['comment'] = $field['comment'];
        }

        // 处理索引
        $columnIndex = Arr::get($field, 'column_index');
        if ($columnIndex) {
            // Phinx 索引在 addColumn 后单独添加
            // 这里先记录，后续处理
        }

        $code = '->addColumn(\'' . $columnName . '\', \'' . $columnType . '\'';
        if (!empty($options)) {
            $code .= ', ' . $this->arrayToString($options);
        }
        $code .= ')';

        // 处理索引（Phinx 索引需要单独添加，这里返回代码片段）
        // 注意：索引需要在所有字段添加完成后统一处理，或使用链式调用
        if ($columnIndex) {
            if ($columnIndex === 'unique') {
                $code .= "\n            ->addIndex(['" . $columnName . "'], ['unique' => true])";
            } elseif ($columnIndex === 'index') {
                $code .= "\n            ->addIndex(['" . $columnName . "'])";
            }
        }

        return $code;
    }

    /**
     * 将字段类型映射到 Phinx 类型
     *
     * @param string $type Laravel 字段类型
     * @return string Phinx 字段类型
     */
    protected function mapTypeToPhinx(string $type): string
    {
        return static::$typeMap[$type] ?? 'string';
    }

    /**
     * 解析额外参数字符串
     *
     * @param string $additional 参数字符串
     * @return array 解析后的选项数组
     */
    protected function parseAdditionalParams(string $additional): array
    {
        $options = [];
        // 简单解析，可以根据需要扩展
        if (is_numeric($additional)) {
            $options['limit'] = (int)$additional;
        }
        return $options;
    }

    /**
     * 将数组转换为字符串表示
     *
     * @param array $array 数组
     * @return string 字符串表示
     */
    protected function arrayToString(array $array): string
    {
        $parts = [];
        foreach ($array as $key => $value) {
            if (is_bool($value)) {
                $parts[] = "'$key' => " . ($value ? 'true' : 'false');
            } elseif (is_int($value)) {
                $parts[] = "'$key' => $value";
            } elseif (is_float($value)) {
                $parts[] = "'$key' => " . number_format($value, 10, '.', '');
            } elseif (is_string($value)) {
                $parts[] = "'$key' => '" . addslashes($value) . "'";
            } elseif (is_null($value)) {
                $parts[] = "'$key' => null";
            } elseif (is_array($value)) {
                $parts[] = "'$key' => " . $this->arrayToString($value);
            }
        }
        return '[' . implode(', ', $parts) . ']';
    }

    /**
     * 获取类名
     *
     * @param string $name 名称
     * @return string 类名
     */
    protected function getClassName(string $name): string
    {
        return Str::studly($name);
    }

    /**
     * 获取日期前缀（用于文件名）
     *
     * @return string 日期前缀
     */
    protected function getDatePrefix(): string
    {
        return date('Y_m_d_His');
    }

    /**
     * 获取模板内容
     *
     * @return string 模板内容
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \Exception
     */
    protected function getStub(): string
    {
        $stubPath = __DIR__ . '/stubs/migration_phinx.stub';
        
        if (!$this->files->exists($stubPath)) {
            throw new \Exception("Stub file not found: {$stubPath}");
        }

        return $this->files->get($stubPath);
    }
}
