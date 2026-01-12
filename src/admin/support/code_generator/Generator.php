<?php

namespace warm\admin\support\code_generator;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use support\Db as DB;
use Throwable;
use warm\admin\model\AdminCodeGenerator;
use warm\admin\trait\MakeTrait;

/**
 * 代码生成器主类
 * 
 * 协调各个代码生成器的工作，提供统一的生成和预览接口
 * 包含数据库字段类型映射、数据库信息获取等功能
 */
class Generator
{
    use MakeTrait;

    /** @var array 数据类型映射 */
    public static array $dataTypeMap = [
        'int'                => 'integer',
        'int@unsigned'       => 'unsignedInteger',
        'tinyint'            => 'tinyInteger',
        'tinyint@unsigned'   => 'unsignedTinyInteger',
        'smallint'           => 'smallInteger',
        'smallint@unsigned'  => 'unsignedSmallInteger',
        'mediumint'          => 'mediumInteger',
        'mediumint@unsigned' => 'unsignedMediumInteger',
        'bigint'             => 'bigInteger',
        'bigint@unsigned'    => 'unsignedBigInteger',
        'date'               => 'date',
        'time'               => 'time',
        'datetime'           => 'dateTime',
        'timestamp'          => 'timestamp',
        'enum'               => 'enum',
        'json'               => 'json',
        'binary'             => 'binary',
        'float'              => 'float',
        'double'             => 'double',
        'decimal'            => 'decimal',
        'varchar'            => 'string',
        'char'               => 'char',
        'text'               => 'text',
        'mediumtext'         => 'mediumText',
        'longtext'           => 'longText',
    ];

    /**
     * 获取需要创建的选项
     * 
     * @return array 选项数组
     */
    public function needCreateOptions(): array
    {
        return [
            [
                'label' => translator('admin.code_generators.create_database_migration'),
                'value' => 'need_database_migration',
            ],
            [
                'label' => translator('admin.code_generators.create_table'),
                'value' => 'need_create_table',
            ],
            [
                'label' => translator('admin.code_generators.create_model'),
                'value' => 'need_model',
            ],
            [
                'label' => translator('admin.code_generators.create_lang'),
                'value' => 'need_lang',
            ],
            [
                'label' => translator('admin.code_generators.create_controller'),
                'value' => 'need_controller',
            ],
            [
                'label' => translator('admin.code_generators.create_service'),
                'value' => 'need_service',
            ],
        ];
    }

    /**
     * 获取可用的字段类型
     * 
     * @return array 字段类型数组
     */
    public function availableFieldTypes(): array
    {
        return collect(self::$dataTypeMap)
            ->values()
            ->map(fn($value) => ['label' => $value, 'value' => $value])
            ->toArray();
    }

    /**
     * 获取数据库字段信息
     * 
     * @param string|null $db 数据库名
     * @param string|null $tb 表名
     * @return \think\Collection|Collection 数据库字段信息集合
     */
    public function getDatabaseColumns(string $db = null, string $tb = null): \think\Collection|Collection
    {
        $databases = Arr::where(config('database.connections', []), function ($value) {
            $supports = ['mysql'];

            return in_array(strtolower(Arr::get($value, 'driver')), $supports);
        });

        $data = [];

        try {
            foreach ($databases as $connectName => $value) {
                if ($db && $db != $value['database']) continue;

                $sql = sprintf('SELECT * FROM information_schema.columns WHERE table_schema = "%s"',
                    $value['database']);

                if ($tb) {
                    $p = Arr::get($value, 'prefix');

                    $sql .= " AND TABLE_NAME = '{$p}{$tb}'";
                }

                $sql .= ' ORDER BY `ORDINAL_POSITION` ASC';

                $collection = $this->getCollection($connectName, $sql, $value);

                $data[$value['database']] = $collection->groupBy('TABLE_NAME')->map(function ($v) {
                    return collect($v)
                        ->keyBy('COLUMN_NAME')
                        ->where('COLUMN_KEY', '<>', 'PRI')
                        ->whereNotIn('COLUMN_NAME', ['created_at', 'updated_at', 'deleted_at'])
                        ->map(function ($v) {
                            $v['COLUMN_TYPE'] = strtolower($v['COLUMN_TYPE']);
                            $v['DATA_TYPE'] = strtolower($v['DATA_TYPE']);

                            if (Str::contains($v['COLUMN_TYPE'], 'unsigned')) {
                                $v['DATA_TYPE'] .= '@unsigned';
                            }


                            return [
                                'name'     => $v['COLUMN_NAME'],
                                'type'     => Arr::get(Generator::$dataTypeMap, $v['DATA_TYPE'], 'string'),
                                'default'  => $v['COLUMN_DEFAULT'],
                                'nullable' => $v['IS_NULLABLE'] == 'YES',
                                'comment'  => $v['COLUMN_COMMENT'],
                            ];
                        })
                        ->values();
                });
            }
        } catch (\Throwable) {
        }

        return collect($data);
    }

    /**
     * 获取数据库主键信息
     * 
     * @param string|null $db 数据库名
     * @param string|null $tb 表名
     * @return \think\Collection|Collection 主键信息集合
     */
    public function getDatabasePrimaryKeys($db = null, $tb = null): \think\Collection|Collection
    {
        $databases = Arr::where(config('database.connections', []), function ($value) {
            $supports = ['mysql'];

            return in_array(strtolower(Arr::get($value, 'driver')), $supports);
        });

        $data = [];

        try {
            foreach ($databases as $connectName => $value) {
                if ($db && $db != $value['database']) continue;

                $sql = sprintf('SELECT * FROM information_schema.columns WHERE table_schema = "%s"',
                    $value['database']);

                if ($tb) {
                    $p = Arr::get($value, 'prefix');

                    $sql .= " AND TABLE_NAME = '{$p}{$tb}'";
                }

                $collection = $this->getCollection($connectName, $sql, $value);

                $data[$value['database']] = $collection->groupBy('TABLE_NAME')->map(function ($v) {
                    return collect($v)
                        ->keyBy('COLUMN_NAME')
                        ->where('COLUMN_KEY', 'PRI')
                        ->whereNotIn('COLUMN_NAME', ['created_at', 'updated_at', 'deleted_at'])
                        ->map(fn($v) => $v['COLUMN_NAME'])
                        ->values()
                        ->first();
                });
            }
        } catch (Throwable) {
        }

        return collect($data);
    }

    /**
     * 生成代码
     * 
     * 根据记录ID和需要生成的项生成相应的代码文件
     * 
     * @param int $id 记录ID
     * @param array $needs 需要生成的项
     * @return string 生成结果信息
     * @throws Throwable
     */
    public function generate(int $id, array $needs = []): string
    {
        $record = AdminCodeGenerator::find($id);
        $model = AdminCodeGenerator::find($id);
        $needs = collect(filled($needs) ? $needs : $record->needs);

        $successMessage = fn($type, $path) => "<b class='text-success'>{$type} generated successfully!</b><br>{$path}<br><br>";

        $paths = [];
        $message = '';
        try {
            // 语言
            if ($needs->contains('need_lang')) {
                $path = TranslateGenerator::make($record)->generate();
                foreach ($path as $value) {
                    $message .= $successMessage('Translate', $value);
                    $paths[] = $value;
                }
            }

            // Model
            if ($needs->contains('need_model')) {
                $path = ModelGenerator::make($model)->generate();

                $message .= $successMessage('Model', $path);
                $paths[] = $path;
            }

            // Controller
            if ($needs->contains('need_controller')) {
                $path = ControllerGenerator::make($record)->generate();

                $message .= $successMessage('controller', $path);
                $paths[] = $path;
            }

            // Service
            if ($needs->contains('need_service')) {
                $path = ServiceGenerator::make($record)->generate();

                $message .= $successMessage('Service', $path);
                $paths[] = $path;
            }

            // Migration
            $migratePath = '';
            if ($needs->contains('need_database_migration')) {
                $path = MigrationGenerator::make($record)->generate();

                $message .= $successMessage('Migration', $path);
                $migratePath = str_replace(base_path(), '', $path);
                $paths[] = $path;
            }

            // 创建数据库表
            if ($needs->contains('need_create_table')) {
                if (DB::schema()->hasTable($record->getAttribute('table_name'))) {
                    abort(400, "Table [{$record->getAttribute('table_name')}] already exists!");
                }

                if (!$migratePath) {
                    $migratePath = $record->getAttribute('save_path')['directory'];
                    $migratePath = $migratePath === 'app' ? 'database/migrations' : $migratePath . '/database/migrations';
                }
                var_dump($migratePath);
                $output = runCommand('migrate',['--path' => $migratePath])['output'];
                $message .= $successMessage('Table', $output);
            }

            // Route
            $path = RouteGenerator::handle($record->getAttribute('menu_info'));
            $message .= $successMessage('Route', $path);

        } catch (Throwable $e) {
            if (count($paths) > 0) {
                (new \Illuminate\Filesystem\Filesystem)->delete($paths);
            }

            RouteGenerator::refresh();
            admin_abort($e->getMessage());
        }

        return $message;
    }

    /**
     * 预览代码
     * 
     * 根据记录ID预览将要生成的代码
     * 
     * @param int $id 记录ID
     * @return array 包含各类代码的数组
     * @throws Exception
     */
    public function preview(int $id): array
    {
        $record = AdminCodeGenerator::find($id);

        try {
            // Model
            $model = ModelGenerator::make($record)->preview();
            // Migration
            $migration = MigrationGenerator::make($record)->preview();
            // Controller
            $controller = ControllerGenerator::make($record)->preview();
            // Service
            $service = ServiceGenerator::make($record)->preview();
        } catch (Exception $e) {
            admin_abort($e->getMessage());
        }

        return compact('model', 'migration', 'controller', 'service');
    }

    /**
     * @param int|string $connectName
     * @param string $sql
     * @param mixed $value
     * @return Collection|\think\Collection
     */
    public function getCollection(int|string $connectName, string $sql, mixed $value): Collection|\think\Collection
    {
        $tmp = DB::connection($connectName)->select($sql);

        $collection = collect($tmp)->map(function ($v) use ($value) {
            if (!$p = Arr::get($value, 'prefix')) {
                return (array)$v;
            }
            $v = (array)$v;

            $v['TABLE_NAME'] = Str::replaceFirst($p, '', $v['TABLE_NAME']);

            return $v;
        });
        return $collection;
    }
}