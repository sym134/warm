<?php

namespace warm\admin\service;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use RegexIterator;
use warm\admin\model\AdminRelationship;
use warm\admin\support\cores\Database;
use warm\common\model\BaseModel;

/**
 * 管理关联关系服务类
 * 
 * 提供模型关联关系管理功能，包括关联关系验证、模型扫描等
 * 
 * @method AdminRelationship getModel() 获取模型实例
 * @method AdminRelationship|Builder query() 获取查询构造器
 */
class AdminRelationshipService extends AdminService
{
    /**
     * 模型类名
     * 
     * @var string
     */
    protected string $modelName = AdminRelationship::class;

    /**
     * 缓存键名
     * 
     * @var string
     */
    public string $cacheKey = 'admin_relationships';

    /**
     * 获取列表数据
     * 
     * @return array 列表数据
     */
    public function list(): array
    {
        $list = parent::list();

        collect($list['items'])->transform(function ($item) {
            $item->setAttribute('preview_code', $item->getPreviewCode());
        });

        return $list;
    }

    /**
     * 获取所有关联关系
     * 
     * @return mixed 关联关系数据
     */
    public function getAll(): mixed
    {
        return cache()->rememberForever($this->cacheKey, function () {
            return self::query()->get();
        });
    }

    /**
     * 保存前处理
     * 
     * 验证关联关系是否已存在
     * 
     * @param array $data 保存的数据
     * @param string $primaryKey 主键值
     * @return void
     */
    public function saving(array &$data, string $primaryKey = ''): void
    {
        $exists = self::query()
            ->where('model', $data['model'])
            ->where('title', $data['title'])
            ->when($primaryKey, fn($q) => $q->where('id', '<>', $primaryKey))
            ->exists();

        admin_abort_if($exists, translator('admin.relationships.rel_name_exists'));

        $methodExists = method_exists($data['model'], $data['title']);

        admin_abort_if($methodExists, translator('admin.relationships.rel_name_exists'));
    }

    /**
     * 保存后处理
     * 
     * 清除关联关系缓存
     * 
     * @param mixed $model 保存的模型实例
     * @param bool $isEdit 是否为编辑操作
     * @return void
     */
    public function saved(mixed $model, bool $isEdit = false): void
    {
        cache()->forget($this->cacheKey);
    }

    /**
     * 删除后处理
     * 
     * 清除关联关系缓存
     * 
     * @param string $ids 删除的ID列表
     * @return void
     */
    public function deleted(string $ids): void
    {
        cache()->forget($this->cacheKey);
    }

    /**
     * 获取所有模型
     * 
     * 扫描项目中的所有模型类，包括通过Composer自动加载的模型和应用目录下的模型，
     * 并筛选出继承自BaseModel且对应数据表存在的模型类
     * 
     * @return array 返回包含所有有效模型信息和数据表列表的数组
     *               - tables: 数据库中所有数据表名称数组
     *               - models: 符合条件的模型类信息数组，每个元素包含:
     *                   - label: 模型类名（不包含命名空间）
     *                   - table: 模型对应的数据表名
     *                   - value: 模型类的完整命名空间路径
     * @throws Exception 当admin/model目录不存在时抛出异常
     */
    public function allModels(): array
    {
        // 检查模型目录是否存在
        if (!file_exists(app_path('admin/model'))) {
            throw new Exception('Please create a model in the app/admin/model directory');
        }
        
        // 递归遍历app/admin/model目录下的所有PHP文件并加载
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(app_path('admin/model'))); // todo 目前只有app，没有插件的model
        $phpFiles = new RegexIterator($iterator, '/^.+\.php$/i', RegexIterator::GET_MATCH);

        // 加载找到的所有PHP文件
        foreach ($phpFiles as $phpFile) {
            $filePath = $phpFile[0];
            require_once $filePath;
        }

        // 过滤出app\admin\model命名空间下的所有已声明类
        $modelDirClass = collect(get_declared_classes())
            ->filter(fn($i) => Str::startsWith($i, 'app\\admin\\model'))
            ->toArray();

        // 获取Composer自动加载器中的类映射
        $composer = require base_path('/vendor/autoload.php');
        $classMap = $composer->getClassMap();
        // 获取数据库中所有数据表
        $tables = Database::getTables();

        // 收集并处理所有模型类：
        // 1. 从Composer类映射中筛选包含'model\'的类
        // 2. 确保类可以被加载
        // 3. 筛选出继承自BaseModel的类
        // 4. 合并app/admin/model目录下定义的模型类
        // 5. 去重并筛选出对应数据表存在的模型
        // 6. 构造返回格式：包含模型标签、表名和类路径
        $models = collect($classMap)
            ->keys()
            ->filter(fn($item) => str_contains($item, 'model\\'))
            ->filter(fn($item) => @class_exists($item))
            ->filter(fn($item) => (new ReflectionClass($item))->isSubclassOf(BaseModel::class))
            ->merge($modelDirClass)
            ->unique()
            ->filter(fn($item) => in_array((new $item)->getTable(), $tables))
            ->values()
            ->map(fn($item) => [
                'label' => Str::of($item)->explode('\\')->pop(),
                'table' => (new $item)->getTable(),
                'value' => $item,
            ]);

        // 返回数据表列表和模型信息
        return compact('tables', 'models');
    }

    /**
     * 生成模型文件
     * 
     * @param string $table 数据表名
     * @return void
     */
    public function generateModel(string $table): void
    {
        $className = Str::of($table)->studly()->singular()->value();

        $template = <<<PHP
<?php

namespace warm\model;

class $className extends Model
{
    protected \$table = '$table';
}
PHP;

        $path = app_path("model/$className.php");

        admin_abort_if(file_exists($path), translator('admin.relationships.model_exists'));

        app('files')->put($path, $template);
    }
}