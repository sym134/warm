<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\trait\OnEvent;
use warm\admin\renderer\trait\FormItem;

/**
 * InputTable 表格输入框
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/input-table
 */
class InputTable extends BaseRenderer
{
    use OnEvent;
    use FormItem;

    public string $type = 'input-table';

    /**
     * `false`
     *
     * @param mixed $value
     * @return self
     */
    public function addable(mixed $value = null): static
    {
        return $this->set('addable', $value);
    }

    /**
     * `false`
     *
     * @param mixed $value
     * @return self
     */
    public function copyable(mixed $value = null): static
    {
        return $this->set('copyable', $value);
    }

    /**
     * 控制复制时的数据映射，不配置时复制整行数据
     *
     * @param mixed $value
     * @return self
     */
    public function copyData(mixed $value = null): static
    {
        return $this->set('copyData', $value);
    }

    /**
     * `false`
     *
     * @param mixed $value
     * @return self
     */
    public function childrenAddable(mixed $value = null): static
    {
        return $this->set('childrenAddable', $value);
    }

    /**
     * `false`
     *
     * @param mixed $value
     * @return self
     */
    public function editable(mixed $value = null): static
    {
        return $this->set('editable', $value);
    }

    /**
     * `false`
     *
     * @param mixed $value
     * @return self
     */
    public function removable(mixed $value = null): static
    {
        return $this->set('removable', $value);
    }

    /**
     * 是否显示表格操作栏添加按钮，前提是要开启可新增功能
     *
     * @param bool $value
     * @return self
     */
    public function showTableAddBtn(bool $value = true): static
    {
        return $this->set('showTableAddBtn', $value);
    }

    /**
     * 是否显示表格下方添加按，前提是要开启可新增功能
     *
     * @param bool $value
     * @return self
     */
    public function showFooterAddBtn(bool $value = true): static
    {
        return $this->set('showFooterAddBtn', $value);
    }

    /**
     * 新增时提交的 API
     *
     * @param mixed $value
     * @return self
     */
    public function addApi(mixed $value = null): static
    {
        return $this->set('addApi', $value);
    }

    /**
     * 底部新增按钮配置
     *
     * @param mixed $value
     * @return self
     */
    public function footerAddBtn(mixed $value = null): static
    {
        return $this->set('footerAddBtn', $value);
    }

    /**
     * 修改时提交的 API
     *
     * @param mixed $value
     * @return self
     */
    public function updateApi(mixed $value = null): static
    {
        return $this->set('updateApi', $value);
    }

    /**
     * 删除时提交的 API
     *
     * @param mixed $value
     * @return self
     */
    public function deleteApi(mixed $value = null): static
    {
        return $this->set('deleteApi', $value);
    }

    /**
     * 新增时初始数据，支持数据映射
     *
     * @param mixed $value
     * @return self
     */
    public function scaffold(mixed $value = null): static
    {
        return $this->set('scaffold', $value);
    }

    /**
     * 增加按钮名称
     *
     * @param string $value
     * @return self
     */
    public function addBtnLabel(string $value = ''): static
    {
        return $this->set('addBtnLabel', $value);
    }

    /**
     * 增加按钮图标
     *
     * @param string $value
     * @return self
     */
    public function addBtnIcon(string $value = 'plus'): static
    {
        return $this->set('addBtnIcon', $value);
    }

    /**
     * 子级增加按钮名称
     *
     * @param string $value
     * @return self
     */
    public function subAddBtnLabel(string $value = ''): static
    {
        return $this->set('subAddBtnLabel', $value);
    }

    /**
     * 子级增加按钮图标
     *
     * @param string $value
     * @return self
     */
    public function subAddBtnIcon(string $value = 'sub-plus'): static
    {
        return $this->set('subAddBtnIcon', $value);
    }

    /**
     * 复制按钮文字
     *
     * @param string $value
     * @return self
     */
    public function copyBtnLabel(string $value = ''): static
    {
        return $this->set('copyBtnLabel', $value);
    }

    /**
     * 复制按钮图标
     *
     * @param string $value
     * @return self
     */
    public function copyBtnIcon(string $value = 'copy'): static
    {
        return $this->set('copyBtnIcon', $value);
    }

    /**
     * 编辑按钮名称
     *
     * @param string $value
     * @return self
     */
    public function editBtnLabel(string $value = ''): static
    {
        return $this->set('editBtnLabel', $value);
    }

    /**
     * 编辑按钮图标
     *
     * @param string $value
     * @return self
     */
    public function editBtnIcon(string $value = 'pencil'): static
    {
        return $this->set('editBtnIcon', $value);
    }

    /**
     * 删除按钮名称
     *
     * @param string $value
     * @return self
     */
    public function deleteBtnLabel(string $value = ''): static
    {
        return $this->set('deleteBtnLabel', $value);
    }

    /**
     * 删除按钮图标
     *
     * @param string $value
     * @return self
     */
    public function deleteBtnIcon(string $value = 'minus'): static
    {
        return $this->set('deleteBtnIcon', $value);
    }

    /**
     * 确认编辑按钮名称
     *
     * @param string $value
     * @return self
     */
    public function confirmBtnLabel(string $value = ''): static
    {
        return $this->set('confirmBtnLabel', $value);
    }

    /**
     * 确认编辑按钮图标
     *
     * @param string $value
     * @return self
     */
    public function confirmBtnIcon(string $value = 'check'): static
    {
        return $this->set('confirmBtnIcon', $value);
    }

    /**
     * 取消编辑按钮名称
     *
     * @param string $value
     * @return self
     */
    public function cancelBtnLabel(string $value = ''): static
    {
        return $this->set('cancelBtnLabel', $value);
    }

    /**
     * 取消编辑按钮图标
     *
     * @param string $value
     * @return self
     */
    public function cancelBtnIcon(string $value = 'times'): static
    {
        return $this->set('cancelBtnIcon', $value);
    }

    /**
     * 是否需要确认操作，可用来控制表格的操作交互
     *
     * @param bool $value
     * @return self
     */
    public function needConfirm(bool $value = true): static
    {
        return $this->set('needConfirm', $value);
    }

    /**
     * 是否可以访问父级数据，也就是表单中的同级数据，通常需要跟 strictMode 搭配使用
     *
     * @param bool $value
     * @return self
     */
    public function canAccessSuperData(bool $value = true): static
    {
        return $this->set('canAccessSuperData', $value);
    }

    /**
     * 为了性能，默认其他表单项项值变化不会让当前表格更新，有时候为了同步获取其他表单项字段，需要开启这个。
     *
     * @param bool $value
     * @return self
     */
    public function strictMode(bool $value = true): static
    {
        return $this->set('strictMode', $value);
    }

    /**
     * 最小行数, `2.4.1`版本后支持变量
     *
     * @param int|float $value
     * @return self
     */
    public function minLength(int|float $value = 0): static
    {
        return $this->set('minLength', $value);
    }

    /**
     * 最大行数, `2.4.1`版本后支持变量
     *
     * @param int|float $value
     * @return self
     */
    public function maxLength(int|float $value = 0): static
    {
        return $this->set('maxLength', $value);
    }

    /**
     * 每页展示几行数据，如果不配置则不会显示分页器
     *
     * @param int|float $value
     * @return self
     */
    public function perPage(int|float $value = 0): static
    {
        return $this->set('perPage', $value);
    }

    /**
     * 列信息
     *
     * @param array $value
     * @return self
     */
    public function columns(array $value = []): static
    {
        return $this->set('columns', $value);
    }
}