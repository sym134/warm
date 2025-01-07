<?php

namespace warm\support\code_generator;

use function Termwind\parse;

/**
 * 翻译生成
 * TranslateGenerator
 * warm\support\code_generator
 *
 * Author:sym
 * Date:2024/12/2 22:48
 * Company:极智科技
 */
class TranslateGenerator extends BaseGenerator
{
    public static function make($model): static
    {
        return new self($model);
    }

    public function generate(): array
    {
        blank($this->model->columns) && abort(400, 'Table fields can\'t be empty');
        // 遍历字段生成翻译文件
        $en = [];
        $zh = [];
        foreach ($this->model->columns as $field) {
            $zh[$field['name']] = empty($field['comment']) ? $field['name'] : $field['comment'];
            $en[$field['name']] = str_replace('_', ' ', $field['name']);
        }
        $result[] = $this->generateFile($this->model->table_name, 'en', $en);
        $result[] = $this->generateFile($this->model->table_name, 'zh_CN', $zh);
        return $result;
    }

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

    public function del(string $tableName)
    {
        $languageCodes = ['en', 'zh_CN'];
        foreach ($languageCodes as $val) {
            unlink(base_path('resource/translations/' . $val . '/' . $tableName . '.php'));
        }

    }
}
