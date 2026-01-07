<?php

namespace warm\admin\support\code_generator;

use support\Model;

/**
 * 翻译文件生成器
 * 
 * 用于生成多语言翻译文件
 * 继承自BaseGenerator，提供翻译文件特定的生成逻辑
 */
class TranslateGenerator extends BaseGenerator
{
    /**
     * 创建静态实例
     * 
     * @param Model $model 模型实例
     * @return static 静态实例
     */
    public static function make(Model $model): static
    {
        return new self($model);
    }

    /**
     * 生成翻译文件
     * 
     * @return array 生成的文件路径数组
     */
    public function generate(): array
    {
        blank($this->model->columns) && abort(400, 'Table fields can\'t be empty');
        // 遍历字段生成翻译文件
        $en = [];
        $zh = [];
        foreach ($this->model->columns as $field) {
            $zh[$field['name']] = empty($field['comment']) ? $field['name'] : $field['comment'];
            $en[$field['name']] = mb_convert_case(str_replace('_', ' ', $field['name']), MB_CASE_TITLE, 'UTF-8');
        }
        $result[] = $this->generateFile($this->model->table_name, 'en', $en);
        $result[] = $this->generateFile($this->model->table_name, 'zh_CN', $zh);
        return $result;
    }

    /**
     * 生成单个翻译文件
     * 
     * @param string $tableName 表名
     * @param string $languageCode 语言代码
     * @param array $data 翻译数据
     * @return string 生成的文件路径
     */
    public function generateFile(string $tableName, string $languageCode, array $data): string
    {
        $dirPath = base_path('resource/translations/' . $languageCode);
        if (!is_dir($dirPath)) {
            mkdir($dirPath, 0755, true);
        }

        $fileName = $dirPath . '/' . $tableName . '.php';
        if (file_exists($fileName)) {
            abort(400, 'The language file already exists');
        }
        $content = "<?php\n\nreturn [\n";
        foreach ($data as $key => $val) {
            $content .= "\t" . "'{$key}' => '{$val}'," . PHP_EOL;
        }
        $content .= "];\n";
        file_put_contents($fileName, $content);
        return $fileName;
    }

    /**
     * 删除翻译文件
     * 
     * @param string $tableName 表名
     * @return void
     */
    public function del(string $tableName)
    {
        $languageCodes = ['en', 'zh_CN'];
        foreach ($languageCodes as $val) {
            unlink(base_path('resource/translations/' . $val . '/' . $tableName . '.php'));
        }

    }
}