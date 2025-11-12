<?php

namespace warm\admin\model;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Support\Str;
use warm\common\model\BaseModel;

/**
 * 管理关系模型类
 * 
 * 该模型用于存储和管理模型之间的关系配置，支持多种关系类型：
 * 1. 一对一关系 (HAS_ONE)
 * 2. 一对多关系 (HAS_MANY)
 * 3. 反向一对一/属于关系 (BELONGS_TO)
 * 4. 多对多关系 (BELONGS_TO_MANY)
 * 5. 远程一对一关系 (HAS_ONE_THROUGH)
 * 6. 远程一对多关系 (HAS_MANY_THROUGH)
 * 7. 一对一多态关系 (MORPH_ONE)
 * 8. 一对多多态关系 (MORPH_MANY)
 * 9. 多对多多态关系 (MORPH_TO_MANY)
 * 
 * 提供关系方法构建和预览代码生成功能。
 */
class AdminRelationship extends BaseModel
{
    use HasTimestamps;

    /**
     * 需要进行类型转换的字段
     * 
     * 将数据库中的JSON字符串自动转换为PHP数组
     * 
     * @var array
     */
    protected $casts = [
        'args'  => 'json',   // 关系参数
        'extra' => 'json',   // 额外信息
    ];

    /** @var string 一对一关系类型常量 */
    const TYPE_HAS_ONE = 'HAS_ONE';

    /** @var string 一对多关系类型常量 */
    const TYPE_HAS_MANY = 'HAS_MANY';

    /** @var string 一对多(反向)/属于关系类型常量 */
    const TYPE_BELONGS_TO = 'BELONGS_TO';

    /** @var string 多对多关系类型常量 */
    const TYPE_BELONGS_TO_MANY = 'BELONGS_TO_MANY';

    /** @var string 远程一对一关系类型常量 */
    const TYPE_HAS_ONE_THROUGH = 'HAS_ONE_THROUGH';

    /** @var string 远程一对多关系类型常量 */
    const TYPE_HAS_MANY_THROUGH = 'HAS_MANY_THROUGH';

    /** @var string 一对一(多态)关系类型常量 */
    const TYPE_MORPH_ONE = 'MORPH_ONE';

    /** @var string 一对多(多态)关系类型常量 */
    const TYPE_MORPH_MANY = 'MORPH_MANY';

    /** @var string 多对多(多态)关系类型常量 */
    const TYPE_MORPH_TO_MANY = 'MORPH_TO_MANY';

    /**
     * 关系类型与方法名的映射关系
     * 
     * @var array
     */
    const TYPE_MAP = [
        self::TYPE_HAS_ONE          => 'hasOne',
        self::TYPE_HAS_MANY         => 'hasMany',
        self::TYPE_BELONGS_TO       => 'belongsTo',
        self::TYPE_BELONGS_TO_MANY  => 'belongsToMany',
        self::TYPE_HAS_ONE_THROUGH  => 'hasOneThrough',
        self::TYPE_HAS_MANY_THROUGH => 'hasManyThrough',
        self::TYPE_MORPH_ONE        => 'morphOne',
        self::TYPE_MORPH_MANY       => 'morphMany',
        self::TYPE_MORPH_TO_MANY    => 'morphToMany',
    ];

    /**
     * 关系类型与中文标签的映射关系
     * 
     * @var array
     */
    const TYPE_LABEL_MAP = [
        self::TYPE_HAS_ONE          => '一对一',
        self::TYPE_HAS_MANY         => '一对多',
        self::TYPE_BELONGS_TO       => '一对多(反向)/属于',
        self::TYPE_BELONGS_TO_MANY  => '多对多',
        self::TYPE_HAS_ONE_THROUGH  => '远程一对一',
        self::TYPE_HAS_MANY_THROUGH => '远程一对多',
        self::TYPE_MORPH_ONE        => '一对一(多态)',
        self::TYPE_MORPH_MANY       => '一对多(多态)',
        self::TYPE_MORPH_TO_MANY    => '多对多(多态)',
    ];

    /**
     * 获取关系类型选项
     * 
     * 返回所有支持的关系类型及其对应的标签和方法名
     * 根据系统语言环境返回中文或英文标签
     * 
     * @return array 关系类型选项数组
     */
    public static function typeOptions()
    {
        return collect(self::TYPE_MAP)->map(function ($item, $index) {
            return [
                'label'  => config('translation.locale') == 'zh_CN' ? self::TYPE_LABEL_MAP[$index] : self::TYPE_MAP[$index],
                'method' => $item,
                'value'  => $index,
            ];
        })->values();
    }

    /**
     * 获取关系方法名访问器
     * 
     * 根据关系类型获取对应的方法名
     * 
     * @return Attribute 关系方法名属性访问器
     */
    public function method(): Attribute
    {
        return Attribute::get(fn() => self::TYPE_MAP[$this->type]);
    }

    /**
     * 构建关系参数
     * 
     * 根据模型类和关系方法，通过反射机制获取方法参数，
     * 并结合配置的参数值构建完整的参数列表
     * 
     * @return array 构建好的参数数组
     */
    public function buildArgs()
    {
        // 检查模型类是否存在
        if(!class_exists($this->model)) return [];

        // 通过反射获取模型类信息
        $reflection = new \ReflectionClass($this->model);
        // 获取关系方法的参数信息
        $params     = $reflection->getMethod($this->method)->getParameters();

        $args = [];

        // 遍历参数，构建参数数组
        foreach ($params as $item) {
            $_value = data_get($this->args, $item->getName());
            $args[] = [
                'name'  => $item->getName(),
                'value' => filled($_value) ? $_value : $item->getDefaultValue(),
            ];
        }

        return $args;
    }

    /**
     * 获取预览代码
     * 
     * 根据配置生成关系方法的预览代码
     * 用于在界面中展示将要生成的关系代码
     * 
     * @return string 预览代码
     */
    public function getPreviewCode()
    {
        // 从模型类名中提取类名
        $className = Str::of($this->model)->explode('\\')->pop();
        // 构建参数字符串
        $args      = collect($this->buildArgs())
            ->pluck('value')
            ->map(fn($item) => is_null($item) ? 'null' : (is_string($item) ? "'$item'" : $item))
            ->implode(', ');

        // 生成预览代码
        return <<<PHP
<?php

class $className extends Model
{
    public function $this->title() {
        return \$this->$this->method($args);
    }
}
PHP;
    }
}