<?php

namespace warm\admin\support\code_generator;

use Illuminate\Support\Str;

/**
 * 服务类代码生成器
 * 
 * 用于生成Admin服务类代码
 * 继承自BaseGenerator，提供服务类特定的生成逻辑
 */
class ServiceGenerator extends BaseGenerator
{
    /**
     * 生成服务类文件
     * 
     * @return bool|string 生成的文件路径
     */
    public function generate(): bool|string
    {
        return $this->writeFile($this->model->service_name, 'Service');
    }

    /**
     * 预览服务类代码
     * 
     * @return string 生成的服务类代码
     */
    public function preview(): string
    {
        return $this->assembly();
    }

    /**
     * 组装服务类代码
     * 
     * @return string 完整的服务类代码
     */
    public function assembly(): string
    {
        $name           = $this->model->service_name;
        $class          = Str::of($name)->explode('/')->last();
        $modelClass     = str_replace('/', '\\', $this->model->model_name);
        $modelClassName = Str::of($modelClass)->explode('\\')->last();

        $content = '<?php' . PHP_EOL . PHP_EOL;
        $content .= 'namespace ' . $this->getNamespace($name) . ';' . PHP_EOL . PHP_EOL;
        $content .= "use {$modelClass};" . PHP_EOL;
        $content .= 'use warm\admin\service\AdminService;' . PHP_EOL . PHP_EOL;
        $content .= '/**' . PHP_EOL;
        $content .= ' * ' . $this->model->title . PHP_EOL;
        $content .= ' *' . PHP_EOL;
        $content .= " * @method {$modelClassName} getModel()" . PHP_EOL;
        $content .= " * @method {$modelClassName}|\Illuminate\Database\Query\Builder query()" . PHP_EOL;
        $content .= ' */' . PHP_EOL;
        $content .= "class {$class} extends AdminService" . PHP_EOL;
        $content .= '{' . PHP_EOL;
        $content .= "\tprotected string \$modelName = {$modelClassName}::class;" . PHP_EOL;

        $filter = FilterGenerator::make($this->model)->renderQuery();

        if ($filter) {
            $content .= $filter;
        }

        $content .= '}';

        return $content;
    }
}