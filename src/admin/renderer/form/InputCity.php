<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\trait\FormItem;
use warm\admin\renderer\trait\OnEvent;

/**
 * InputCity 城市选择器
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/input-city
 */
class InputCity extends BaseRenderer
{
    use FormItem;
    use OnEvent;

    public string $type = 'input-city';

    /**
     * 允许选择城市
     *
     * @param bool $value
     * @return self
     */
    public function allowCity(bool $value = true): static
    {
        return $this->set('allowCity', $value);
    }

    /**
     * 允许选择区域
     *
     * @param bool $value
     * @return self
     */
    public function allowDistrict(bool $value = true): static
    {
        return $this->set('allowDistrict', $value);
    }

    /**
     * 是否出搜索框
     *
     * @param bool $value
     * @return self
     */
    public function searchable(bool $value = true): static
    {
        return $this->set('searchable', $value);
    }

    /**
     * 默认 `true` 是否抽取值，如果设置成 `false` 值格式会变成对象，包含 `code`、`province`、`city` 和 `district` 文字信息。
     *
     * @param bool $value
     * @return self
     */
    public function extractValue(bool $value = true): static
    {
        return $this->set('extractValue', $value);
    }
}
