<?php
namespace warm\admin\renderer;
/**
 * Crud
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/crud
 */
class Crud extends BaseRenderer
{
    public string $type = 'crud';

    /**
     * `"table" 、 "cards" 或者 "list"`
     *
     * @param string $value
     * @return self
     */
    public function mode(string $value = 'table'): self
    {
        return $this->set('mode', $value);
    }

    /**
     * 可设置成空，当设置成空时，没有标题栏
     *
     * @param string $value
     * @return self
     */
    public function title(string $value = ''): self
    {
        return $this->set('title', $value);
    }

    /**
     * 表格外层 Dom 的类名
     *
     * @param string $value
     * @return self
     */
    public function className(string $value = ''): self
    {
        return $this->set('className', $value);
    }

    /**
     * CRUD 用来获取列表数据的 api。
     *
     * @param mixed $value
     * @return self
     */
    public function api(mixed $value = null): self
    {
        return $this->set('api', $value);
    }

    /**
     * 当行数据中有 defer 属性时，用此接口进一步加载内容
     *
     * @param mixed $value
     * @return self
     */
    public function deferApi(mixed $value = null): self
    {
        return $this->set('deferApi', $value);
    }

    /**
     * 是否一次性加载所有数据（前端分页）
     *
     * @param bool $value
     * @return self
     */
    public function loadDataOnce(bool $value = true): self
    {
        return $this->set('loadDataOnce', $value);
    }

    /**
     * 在开启 loadDataOnce 时，filter 时是否去重新请求 api
     *
     * @param bool $value
     * @return self
     */
    public function loadDataOnceFetchOnFilter(bool $value = true): self
    {
        return $this->set('loadDataOnceFetchOnFilter', $value);
    }

    /**
     * 数据映射接口返回某字段的值，不设置会默认使用接口返回的`${items}`或者`${rows}`，也可以设置成上层数据源的内容
     *
     * @param string $value
     * @return self
     */
    public function source(string $value = ''): self
    {
        return $this->set('source', $value);
    }

    /**
     * 设置过滤器，当该表单提交后，会把数据带给当前 `mode` 刷新列表。
     *
     * @param mixed $value
     * @return self
     */
    public function filter(mixed $value = null): self
    {
        return $this->set('filter', $value);
    }

    /**
     * `false`
     *
     * @param mixed $value
     * @return self
     */
    public function filterTogglable(mixed $value = null): self
    {
        return $this->set('filterTogglable', $value);
    }

    /**
     * 设置过滤器默认是否可见。
     *
     * @param bool $value
     * @return self
     */
    public function filterDefaultVisible(bool $value = true): self
    {
        return $this->set('filterDefaultVisible', $value);
    }

    /**
     * 是否初始化的时候拉取数据, 只针对有 filter 的情况, 没有 filter 初始都会拉取数据
     *
     * @param bool $value
     * @return self
     */
    public function initFetch(bool $value = true): self
    {
        return $this->set('initFetch', $value);
    }

    /**
     * 刷新时间(最低 1000)
     *
     * @param int|bool $value
     * @return self
     */
    public function interval(int|bool $value = 3000): self
    {
        return $this->set('interval', $value);
    }

    /**
     * 配置刷新时是否隐藏加载动画
     *
     * @param bool $value
     * @return self
     */
    public function silentPolling(bool $value = true): self
    {
        return $this->set('silentPolling', $value);
    }

    /**
     * 通过[表达式](../../docs/concepts/expression)来配置停止刷新的条件
     *
     * @param string $value
     * @return self
     */
    public function stopAutoRefreshWhen(string $value = ''): self
    {
        return $this->set('stopAutoRefreshWhen', $value);
    }

    /**
     * 当有弹框时关闭自动刷新，关闭弹框又恢复
     *
     * @param bool $value
     * @return self
     */
    public function stopAutoRefreshWhenModalIsOpen(bool $value = true): self
    {
        return $this->set('stopAutoRefreshWhenModalIsOpen', $value);
    }

    /**
     * 是否将过滤条件的参数同步到地址栏
     *
     * @param bool $value
     * @return self
     */
    public function syncLocation(bool $value = true): self
    {
        return $this->set('syncLocation', $value);
    }

    /**
     * 是否可通过拖拽排序
     *
     * @param bool $value
     * @return self
     */
    public function draggable(bool $value = true): self
    {
        return $this->set('draggable', $value);
    }

    /**
     * 是否可以调整列宽度
     *
     * @param bool $value
     * @return self
     */
    public function resizable(bool $value = true): self
    {
        return $this->set('resizable', $value);
    }

    /**
     * 用[表达式](../../docs/concepts/expression)来配置是否可拖拽排序
     *
     * @param bool $value
     * @return self
     */
    public function itemDraggableOn(bool $value = true): self
    {
        return $this->set('itemDraggableOn', $value);
    }

    /**
     * 保存排序的 api。
     *
     * @param mixed $value
     * @return self
     */
    public function saveOrderApi(mixed $value = null): self
    {
        return $this->set('saveOrderApi', $value);
    }

    /**
     * 快速编辑后用来批量保存的 API。
     *
     * @param mixed $value
     * @return self
     */
    public function quickSaveApi(mixed $value = null): self
    {
        return $this->set('quickSaveApi', $value);
    }

    /**
     * 快速编辑配置成及时保存时使用的 API。
     *
     * @param mixed $value
     * @return self
     */
    public function quickSaveItemApi(mixed $value = null): self
    {
        return $this->set('quickSaveItemApi', $value);
    }

    /**
     * 批量操作列表，配置后，表格可进行选中操作。
     *
     * @param mixed $value
     * @return self
     */
    public function bulkActions(mixed $value = null): self
    {
        return $this->set('bulkActions', $value);
    }

    /**
     * 覆盖消息提示，如果不指定，将采用 api 返回的 message
     *
     * @param array $value
     * @return self
     */
    public function messages(array $value = []): self
    {
        return $this->set('messages', $value);
    }

    /**
     * 设置 ID 字段名。
     *
     * @param string $value
     * @return self
     */
    public function primaryField(string $value = 'id'): self
    {
        return $this->set('primaryField', $value);
    }

    /**
     * 设置一页显示多少条数据。
     *
     * @param int|bool $value
     * @return self
     */
    public function perPage(int|bool $value = 10): self
    {
        return $this->set('perPage', $value);
    }

    /**
     * 默认排序字段，这个是传给后端，需要后端接口实现
     *
     * @param string $value
     * @return self
     */
    public function orderBy(string $value = ''): self
    {
        return $this->set('orderBy', $value);
    }

    /**
     * 
     *
     * @param mixed $value
     * @return self
     */
    public function orderDir(mixed $value = null): self
    {
        return $this->set('orderDir', $value);
    }

    /**
     * 设置默认 filter 默认参数，会在查询的时候一起发给后端
     *
     * @param array $value
     * @return self
     */
    public function defaultParams(array $value = []): self
    {
        return $this->set('defaultParams', $value);
    }

    /**
     * 设置分页页码字段名。
     *
     * @param string $value
     * @return self
     */
    public function pageField(string $value = 'page'): self
    {
        return $this->set('pageField', $value);
    }

    /**
     * 设置分页一页显示的多少条数据的字段名。注意：最好与 defaultParams 一起使用，请看下面例子。
     *
     * @param string $value
     * @return self
     */
    public function perPageField(string $value = 'perPage'): self
    {
        return $this->set('perPageField', $value);
    }

    /**
     * 设置数据返回中用来表示数据总量的字段名
     *
     * @param string $value
     * @return self
     */
    public function totalField(string $value = 'total'): self
    {
        return $this->set('totalField', $value);
    }

    /**
     * 分页方向字段名可能是 forward 或者 backward
     *
     * @param string $value
     * @return self
     */
    public function pageDirectionField(string $value = 'pageDir'): self
    {
        return $this->set('pageDirectionField', $value);
    }

    /**
     * 设置一页显示多少条数据下拉框可选条数。
     *
     * @param array $value
     * @return self
     */
    public function perPageAvailable(array $value = []): self
    {
        return $this->set('perPageAvailable', $value);
    }

    /**
     * 设置用来确定位置的字段名，设置后新的顺序将被赋值到该字段中。
     *
     * @param string $value
     * @return self
     */
    public function orderField(string $value = ''): self
    {
        return $this->set('orderField', $value);
    }

    /**
     * 隐藏顶部快速保存提示
     *
     * @param bool $value
     * @return self
     */
    public function hideQuickSaveBtn(bool $value = true): self
    {
        return $this->set('hideQuickSaveBtn', $value);
    }

    /**
     * 当切分页的时候，是否自动跳顶部。
     *
     * @param bool $value
     * @return self
     */
    public function autoJumpToTopOnPagerChange(bool $value = true): self
    {
        return $this->set('autoJumpToTopOnPagerChange', $value);
    }

    /**
     * 将返回数据同步到过滤器上。
     *
     * @param bool $value
     * @return self
     */
    public function syncResponse2Query(bool $value = true): self
    {
        return $this->set('syncResponse2Query', $value);
    }

    /**
     * 保留条目选择，默认分页、搜索后，用户选择条目会被清空，开启此选项后会保留用户选择，可以实现跨页面批量操作。
     *
     * @param bool $value
     * @return self
     */
    public function keepItemSelectionOnPageChange(bool $value = true): self
    {
        return $this->set('keepItemSelectionOnPageChange', $value);
    }

    /**
     * 单条描述模板，`keepItemSelectionOnPageChange`设置为`true`后会把所有已选择条目列出来，此选项可以用来定制条目展示文案。
     *
     * @param string $value
     * @return self
     */
    public function labelTpl(string $value = ''): self
    {
        return $this->set('labelTpl', $value);
    }

    /**
     * 和`keepItemSelectionOnPageChange`搭配使用，限制最大勾选数。
     *
     * @param int|bool $value
     * @return self
     */
    public function maxKeepItemSelectionLength(int|bool $value = true): self
    {
        return $this->set('maxKeepItemSelectionLength', $value);
    }

    /**
     * 可单独使用限制当前页的最大勾选数，也可以和`keepItemSelectionOnPageChange`搭配使用达到和 maxKeepItemSelectionLength 一样的效果。
     *
     * @param int|bool $value
     * @return self
     */
    public function maxItemSelectionLength(int|bool $value = true): self
    {
        return $this->set('maxItemSelectionLength', $value);
    }

    /**
     * 顶部工具栏配置
     *
     * @param array $value
     * @return self
     */
    public function headerToolbar(array $value = []): self
    {
        return $this->set('headerToolbar', $value);
    }

    /**
     * 底部工具栏配置
     *
     * @param array $value
     * @return self
     */
    public function footerToolbar(array $value = []): self
    {
        return $this->set('footerToolbar', $value);
    }

    /**
     * 是否总是显示分页
     *
     * @param bool $value
     * @return self
     */
    public function alwaysShowPagination(bool $value = true): self
    {
        return $this->set('alwaysShowPagination', $value);
    }

    /**
     * 是否固定表头(table 下)
     *
     * @param bool $value
     * @return self
     */
    public function affixHeader(bool $value = true): self
    {
        return $this->set('affixHeader', $value);
    }

    /**
     * 是否固定表格底部工具栏
     *
     * @param bool $value
     * @return self
     */
    public function affixFooter(bool $value = true): self
    {
        return $this->set('affixFooter', $value);
    }

    /**
     * 
     *
     * @param mixed $value
     * @return self
     */
    public function autoGenerateFilter(mixed $value = null): self
    {
        return $this->set('autoGenerateFilter', $value);
    }

    /**
     * 单条数据 ajax 操作后是否重置页码为第一页
     *
     * @param bool $value
     * @return self
     */
    public function resetPageAfterAjaxItemAction(bool $value = true): self
    {
        return $this->set('resetPageAfterAjaxItemAction', $value);
    }

    /**
     * 内容区域自适应高度
     *
     * @param mixed $value
     * @return self
     */
    public function autoFillHeight(mixed $value = null): self
    {
        return $this->set('autoFillHeight', $value);
    }

    /**
     * 指定是否可以自动获取上层的数据并映射到表格行数据上，如果列也配置了该属性，则列的优先级更高
     *
     * @param bool $value
     * @return self
     */
    public function canAccessSuperData(bool $value = true): self
    {
        return $this->set('canAccessSuperData', $value);
    }

    /**
     * 自定义匹配函数, 当开启`loadDataOnce`时，会基于该函数计算的匹配结果进行过滤，主要用于处理列字段类型较为复杂或者字段值格式和后端返回不一致的场景
     *
     * @param string $value
     * @return self
     */
    public function matchFunc(string $value = '[`CRUDMatchFunc`]匹配函数)'): self
    {
        return $this->set('matchFunc', $value);
    }

    /**
     * 是否开启 Query 信息转换，开启后将会对 url 中的 Query 进行转换，默认开启，默认仅转化布尔值
     *
     * @param mixed $value
     * @return self
     */
    public function parsePrimitiveQuery(mixed $value = true): self
    {
        return $this->set('parsePrimitiveQuery', $value);
    }
}
