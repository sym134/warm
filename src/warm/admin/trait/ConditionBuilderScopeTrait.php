<?php

namespace warm\admin\trait;

/**
 * 条件组合查询作用域Trait
 * 
 * 提供基于条件构建器的查询功能，支持复杂的条件组合查询
 * 可以处理各种操作符的条件判断，如等于、不等于、模糊匹配等
 * 
 * @property self|\Illuminate\Database\Query\Builder withConditionBuilder()
 */
trait ConditionBuilderScopeTrait
{
    /** @var bool 是否隐藏表名 */
    protected bool $conditionBuilderHideTable = false;

    /**
     * 条件构建器查询作用域
     * 
     * @param \Illuminate\Database\Query\Builder $query 查询构建器实例
     * @param bool $hideTable 是否隐藏表名
     * @return \Illuminate\Database\Query\Builder 查询构建器实例
     */
    public function scopeWithConditionBuilder($query, $hideTable = false)
    {
        $filter = request()->input('filter_condition_builder');

        $this->conditionBuilderHideTable = $hideTable;

        if (blank($filter)) {
            return $query;
        }

        try {
            return $query->where(fn($q) => $this->buildConditionBuilderQuery($q, $filter));
        } catch (\Throwable $e) {
            admin_abort('ConditionBuilder Parser Error!');
        }
    }

    /**
     * 构建条件组合查询
     *
     * @param \Illuminate\Database\Query\Builder $query 查询构建器实例
     * @param array $filter 过滤条件数组
     * @return void
     */
    protected function buildConditionBuilderQuery($query, $filter)
    {
        $or = $filter['conjunction'];

        if (method_exists($this, 'qualifyColumn') && !$this->conditionBuilderHideTable) {
            $qualifyColumn = fn($field) => $this->qualifyColumn($field);
        } else {
            $qualifyColumn = fn($field) => $field;
        }

        foreach ($filter['children'] as $item) {
            $field = data_get($item, 'left.field');
            $op    = data_get($item, 'op');
            $value = data_get($item, 'right');

            // 条件组
            if (data_get($item, 'children')) {
                $fn = $or == 'or' ? 'orWhere' : 'where';

                $query->{$fn}(fn($q) => $this->buildConditionBuilderQuery($q, $item));
            }

            // 过滤异常值
            if (blank($field) || blank($op)) {
                continue;
            }

            // 拼接当前表名
            $field = $qualifyColumn($field);

            // 组合查询条件
            switch ($op) {
                case 'equal': // 等于
                case 'select_equals': // 选项 - 等于
                    $query->where($field, '=', $value, $or);
                    break;
                case 'not_equal': // 不等于
                case 'select_not_equals': // 选项 - 不等于
                    $query->where($field, '!=', $value, $or);
                    break;
                case 'is_empty': // 为空
                    $query->whereNull($field, $or);
                    break;
                case 'is_not_empty': // 不为空
                    $query->whereNotNull($field, $or);
                    break;
                case 'like': // 模糊匹配
                    $query->where($field, 'like', "%{$value}%", $or);
                    break;
                case 'not_like': // 不匹配
                    $query->where($field, 'not like', "%{$value}%", $or);
                    break;
                case 'starts_with': // 匹配开头
                    $query->where($field, 'like', "{$value}%", $or);
                    break;
                case 'ends_with': // 匹配结尾
                    $query->where($field, 'like', "%{$value}", $or);
                    break;
                case 'less': // 小于
                    $query->where($field, '<', $value, $or);
                    break;
                case 'less_or_equal': // 小于等于
                    $query->where($field, '<=', $value, $or);
                    break;
                case 'greater': // 大于
                    $query->where($field, '>', $value, $or);
                    break;
                case 'greater_or_equal': // 大于等于
                    $query->where($field, '>=', $value, $or);
                    break;
                case 'between': // 属于范围
                    $query->whereBetween($field, $value, $or);
                    break;
                case 'not_between': // 不属于范围
                    $query->whereNotBetween($field, $value, $or);
                    break;
                case 'select_any_in': // 选项 - 包含
                    $query->whereIn($field, $value, $or);
                    break;
                case 'select_not_any_in': // 选项 - 不包含
                    $query->whereNotIn($field, $value, $or);
                    break;
                default:
                    // 自定义条件
                    if (method_exists($this, 'extraConditionBuilderQuery')) {
                        $this->extraConditionBuilderQuery($query, $item, $or);
                    }
                    break;
            }
        }
    }
}