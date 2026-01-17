<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\trait\FormItem;
use warm\admin\renderer\trait\OnEvent;

/**
 * Transfer
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/transfer
 */
class Transfer extends BaseRenderer
{
    use FormItem;
    use OnEvent;

    public string $type = 'transfer';

    /**
     * [选项组](./options#%E9%9D%99%E6%80%81%E9%80%89%E9%A1%B9%E7%BB%84-options)
     *
     * @param array $value
     * @return self
     */
    public function options(array $value = []): static
    {
        return $this->set('options', $value);
    }

    /**
     * [动态选项组](./options#%E5%8A%A8%E6%80%81%E9%80%89%E9%A1%B9%E7%BB%84-source)
     *
     * @param mixed $value
     * @return self
     */
    public function source(mixed $value = null): static
    {
        return $this->set('source', $value);
    }

    /**
     * [拼接符](./options#%E6%8B%BC%E6%8E%A5%E7%AC%A6-delimiter)
     *
     * @param string $value
     * @return self
     */
    public function delimeter(string $value = 'false'): static
    {
        return $this->set('delimeter', $value);
    }

    /**
     * [拼接值](./options#%E6%8B%BC%E6%8E%A5%E5%80%BC-joinvalues)
     *
     * @param bool $value
     * @return self
     */
    public function joinValues(bool $value = true): static
    {
        return $this->set('joinValues', $value);
    }

    /**
     * [提取值](./options#%E6%8F%90%E5%8F%96%E5%A4%9A%E9%80%89%E5%80%BC-extractvalue)
     *
     * @param bool $value
     * @return self
     */
    public function extractValue(bool $value = true): static
    {
        return $this->set('extractValue', $value);
    }

    /**
     * 如果想通过接口检索，可以设置这个 api。
     *
     * @param mixed $value
     * @return self
     */
    public function searchApi(mixed $value = null): static
    {
        return $this->set('searchApi', $value);
    }

    /**
     * 结果面板跟随模式，目前只支持`list`、`table`、`tree`（tree 目前只支持非延时加载的`tree`）
     *
     * @param bool $value
     * @return self
     */
    public function resultListModeFollowSelect(bool $value = true): static
    {
        return $this->set('resultListModeFollowSelect', $value);
    }

    /**
     * 是否显示统计数据
     *
     * @param bool $value
     * @return self
     */
    public function statistics(bool $value = true): static
    {
        return $this->set('statistics', $value);
    }

    /**
     * 左侧的标题文字
     *
     * @param string $value
     * @return self
     */
    public function selectTitle(string $value = '请选择'): static
    {
        return $this->set('selectTitle', $value);
    }

    /**
     * 右侧结果的标题文字
     *
     * @param string $value
     * @return self
     */
    public function resultTitle(string $value = '当前选择'): static
    {
        return $this->set('resultTitle', $value);
    }

    /**
     * 结果可以进行拖拽排序（结果列表为树时，不支持排序）
     *
     * @param bool $value
     * @return self
     */
    public function sortable(bool $value = true): static
    {
        return $this->set('sortable', $value);
    }

    /**
     * 可选：`list`、`table`、`tree`、`chained`、`associated`。分别为：列表形式、表格形式、树形选择形式、级联选择形式，关联选择形式（与级联选择的区别在于，级联是无限极，而关联只有一级，关联左边可以是个 tree）。
     *
     * @param string $value
     * @return self
     */
    public function selectMode(string $value = 'list'): static
    {
        return $this->set('selectMode', $value);
    }

    /**
     * 如果不设置将采用 `selectMode` 的值，可以单独配置，参考 `selectMode`，决定搜索结果的展示形式。
     *
     * @param string $value
     * @return self
     */
    public function searchResultMode(string $value = ''): static
    {
        return $this->set('searchResultMode', $value);
    }

    /**
     * 左侧列表搜索功能，当设置为  true  时表示可以通过输入部分内容检索出选项项。
     *
     * @param bool $value
     * @return self
     */
    public function searchable(bool $value = true): static
    {
        return $this->set('searchable', $value);
    }

    /**
     * 左侧列表搜索框提示
     *
     * @param string $value
     * @return self
     */
    public function searchPlaceholder(string $value = ''): static
    {
        return $this->set('searchPlaceholder', $value);
    }

    /**
     * 当展示形式为 `table` 可以用来配置展示哪些列，跟 table 中的 columns 配置相似，只是只有展示功能。
     *
     * @param array $value
     * @return self
     */
    public function columns(array $value = []): static
    {
        return $this->set('columns', $value);
    }

    /**
     * 当展示形式为 `associated` 时用来配置左边的选项集。
     *
     * @param array $value
     * @return self
     */
    public function leftOptions(array $value = []): static
    {
        return $this->set('leftOptions', $value);
    }

    /**
     * 当展示形式为 `associated` 时用来配置左边的选择形式，支持 `list` 或者 `tree`。默认为 `list`。
     *
     * @param string $value
     * @return self
     */
    public function leftMode(string $value = ''): static
    {
        return $this->set('leftMode', $value);
    }

    /**
     * 当展示形式为 `associated` 时用来配置右边的选择形式，可选：`list`、`table`、`tree`、`chained`。
     *
     * @param string $value
     * @return self
     */
    public function rightMode(string $value = ''): static
    {
        return $this->set('rightMode', $value);
    }

    /**
     * 结果（右则）列表的检索功能，当设置为 true 时，可以通过输入检索模糊匹配检索内容（目前树的延时加载不支持结果搜索功能）
     *
     * @param bool $value
     * @return self
     */
    public function resultSearchable(bool $value = true): static
    {
        return $this->set('resultSearchable', $value);
    }

    /**
     * 右侧列表搜索框提示
     *
     * @param string $value
     * @return self
     */
    public function resultSearchPlaceholder(string $value = ''): static
    {
        return $this->set('resultSearchPlaceholder', $value);
    }

    /**
     * 
     *
     * @param mixed $value
     * @return self
     */
    public function menuTpl(mixed $value = null): static
    {
        return $this->set('menuTpl', $value);
    }

    /**
     * 
     *
     * @param mixed $value
     * @return self
     */
    public function valueTpl(mixed $value = null): static
    {
        return $this->set('valueTpl', $value);
    }

    /**
     * 每个选项的高度，用于虚拟渲染
     *
     * @param int|float $value
     * @return self
     */
    public function itemHeight(int|float $value = 38): static
    {
        return $this->set('itemHeight', $value);
    }

    /**
     * 在选项数量超过多少时开启虚拟渲染
     *
     * @param int|float $value
     * @return self
     */
    public function virtualThreshold(int|float $value = 100): static
    {
        return $this->set('virtualThreshold', $value);
    }

    /**
     * 分页配置
     *
     * @param array $value
     * @return self
     */
    public function pagination(array $value = []): static
    {
        return $this->set('pagination', $value);
    }
}
