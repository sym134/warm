<?php

namespace warm\admin\support\code_generator;

use Illuminate\Support\Arr;
use support\Db;
use warm\admin\Admin;
use warm\admin\model\AdminCodeGenerator;
use warm\admin\support\code_generator\BaseGenerator;
use warm\admin\trait\MakeTrait;

class GenCodeClear
{
    use MakeTrait;

    protected string $module = '';

    public function handle($data): void
    {
        $records = $this->getRecord($data['id']);
        $selected = explode(',', $data['selected']);

        if (in_array('translate_en', $selected)) {
            @unlink($records['translate_en']);
        }

        if (in_array('translate_zh_CN', $selected)) {
            @unlink($records['translate_zh_CN']);
        }

        if (in_array('controller', $selected)) {
            @unlink($records['controller']);
        }

        if (in_array('model', $selected)) {
            @unlink($records['model']);
        }

        if (in_array('service', $selected)) {
            @unlink($records['service']);
        }

        if (in_array('migration', $selected)) {
            $arr = Arr::wrap($records['migration']);

            array_map(fn($path) => @unlink($path), $arr);
        }

        if (in_array('table', $selected)) {
            Db::Schema()->dropIfExists($records['table']);
        }

        if (in_array('menu', $selected)) {
            Admin::adminMenuModel()::where('id', $records['menu_id'])->delete();
        }
    }

    public function getRecord($id): array
    {
        $record = AdminCodeGenerator::find($id);

        $controllerPath = BaseGenerator::guessClassFileName(str_replace('/', '\\', $record->controller_name));
        $modelPath = BaseGenerator::guessClassFileName(str_replace('/', '\\', $record->model_name));
        $servicePath = BaseGenerator::guessClassFileName(str_replace('/', '\\', $record->service_name));
        $tableName = $record->table_name;
        $migrationPath = $this->getMigrationFileName($tableName, $record->model_name);
        $menuRecord = $this->getMenu($record->menu_info);
        $translateENPath = base_path('resource/translations/en/' . $record->table_name . '.php');
        $translateZHPath = base_path('resource/translations/zh_CN/' . $record->table_name . '.php');

        $checkFile = function ($path) {
            if (is_array($path)) {
                return $path;
            }

            return file_exists($path) ? $path : '';
        };

        $content = [
            'controller' => $checkFile($controllerPath),
            'model'      => $checkFile($modelPath),
            'service'    => $checkFile($servicePath),
            'migration'  => $checkFile($migrationPath),
            'translate_en'  => $checkFile($translateENPath),
            'translate_zh_CN'  => $checkFile($translateZHPath),
            'table'      => Db::Schema()->hasTable($tableName) ? $tableName : '',
        ];

        if ($menuRecord) {
            $content['menu'] = sprintf('[%s] %s(%s)', $menuRecord->id, $menuRecord->title, $menuRecord->url);
            $content['menu_id'] = $menuRecord->id;
        }

        return $content;
    }

    protected function getMigrationFileName($tableName, $model_name): array|bool|string
    {
        $tableName = 'create_' . $tableName . '_table';

        $migrationPath = BaseGenerator::guessClassFileName($model_name);
        if ($this->module) {
            $migrationPath = str_replace('/Models/', '/database/migrations/', $migrationPath);
        } else {
            $migrationPath = str_replace('/Models/', '/../database/migrations/', $migrationPath);
        }
        $migrationPath = dirname($migrationPath);

        if (!is_dir($migrationPath)) {
            return '';
        }
        $files = scandir($migrationPath);

        $files = array_filter($files, fn($file) => str_contains($file, $tableName));

        if (count($files) > 1) {
            return array_map(fn($i) => realpath($migrationPath . '/' . $i), $files);
        }

        if (count($files) == 0) {
            return '';
        }

        $files = array_values($files);

        return realpath($migrationPath . '/' . $files[0]);
    }

    protected function getMenu($menuInfo)
    {
        $where = [
            'title'     => $menuInfo['title'],
            'parent_id' => $menuInfo['parent_id'],
            'url'       => '/' . ltrim($menuInfo['route'], '/'),
        ];

        // webman 待取消
        // if ($this->module) {
        //     $menuModel = config(Admin::module()->getLowerName($this->module) . '.admin.models.admin_menu');
        //
        //     if (class_exists($menuModel)) {
        //         return $menuModel::query()->where($where)->first();
        //     }
        // }

        return Admin::adminMenuModel()::query()->where($where)->first();
    }
}
