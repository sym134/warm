<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\trait\FormItem;

/**
 * InputKvs 键值对数组输入框
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/input-kvs
 */
class InputKvs extends BaseRenderer
{
    use FormItem;

    public string $type = 'input-kvs';

    public function addButtonText(string $value): InputKvs
    {
        return $this->set('addButtonText', $value);
    }

    /**
     * 其中 keyItem 可以用来修改 key 值控件，比如可以改成下拉框
     *
     * @param array $value
     * @return InputKvs
     */
    public function keyItem(array $value=[]): InputKvs
    {
        return $this->set('keyItem', $value);
    }

    /**
     * valueItems 可以进一步嵌套，比如里面又嵌一个 input-kvs 实现深层结构编辑
     *
     * @param array $value
     * @return InputKvs
     */
    public function valueItems(array $value=[]): InputKvs
    {
        return $this->set('valueItems', $value);
    }

}
