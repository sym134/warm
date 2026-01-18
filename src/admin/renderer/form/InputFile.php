<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\trait\FormItem;
use warm\admin\renderer\trait\OnEvent;

/**
 * InputFile 文件上传
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/input-file
 */
class InputFile extends BaseRenderer
{
    use FormItem;
    use OnEvent;

    public string $type = 'input-file';

    /**
     * 上传文件接口
     *
     * @param mixed $value
     * @return self
     */
    public function receiver(mixed $value = null): static
    {
        return $this->set('receiver', $value);
    }

    /**
     * 默认只支持纯文本，要支持其他类型，请配置此属性为文件后缀`.xxx`
     *
     * @param string $value
     * @return self
     */
    public function accept(string $value = 'text/plain'): static
    {
        return $this->set('accept', $value);
    }

    /**
     * 用于控制 input[type=file] 标签的 capture 属性，在移动端可控制输入来源
     *
     * @param string $value
     * @return self
     */
    public function capture(string $value = 'undefined'): static
    {
        return $this->set('capture', $value);
    }

    /**
     * 将文件以`base64`的形式，赋值给当前组件
     *
     * @param bool $value
     * @return self
     */
    public function asBase64(bool $value = true): static
    {
        return $this->set('asBase64', $value);
    }

    /**
     * 将文件以二进制的形式，赋值给当前组件
     *
     * @param bool $value
     * @return self
     */
    public function asBlob(bool $value = true): static
    {
        return $this->set('asBlob', $value);
    }

    /**
     * 默认没有限制，当设置后，文件大小大于此值将不允许上传。单位为`B`
     *
     * @param int|float $value
     * @return self
     */
    public function maxSize(int|float $value = 0): static
    {
        return $this->set('maxSize', $value);
    }

    /**
     * 默认没有限制，当设置后，一次只允许上传指定数量文件。
     *
     * @param int|float $value
     * @return self
     */
    public function maxLength(int|float $value = 0): static
    {
        return $this->set('maxLength', $value);
    }

    /**
     * 是否多选。
     *
     * @param bool $value
     * @return self
     */
    public function multiple(bool $value = true): static
    {
        return $this->set('multiple', $value);
    }

    /**
     * 是否为拖拽上传
     *
     * @param bool $value
     * @return self
     */
    public function drag(bool $value = true): static
    {
        return $this->set('drag', $value);
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
     * [拼接符](./options#%E6%8B%BC%E6%8E%A5%E7%AC%A6-delimiter)
     *
     * @param string $value
     * @return self
     */
    public function delimiter(string $value = ','): static
    {
        return $this->set('delimiter', $value);
    }

    /**
     * 否选择完就自动开始上传
     *
     * @param bool $value
     * @return self
     */
    public function autoUpload(bool $value = true): static
    {
        return $this->set('autoUpload', $value);
    }

    /**
     * 隐藏上传按钮
     *
     * @param bool $value
     * @return self
     */
    public function hideUploadButton(bool $value = true): static
    {
        return $this->set('hideUploadButton', $value);
    }

    /**
     * 上传状态文案
     *
     * @param array $value
     * @return self
     */
    public function stateTextMap(array $value = []): static
    {
        return $this->set('stateTextMap', $value);
    }

    /**
     * 如果你不想自己存储，则可以忽略此属性。
     *
     * @param string $value
     * @return self
     */
    public function fileField(string $value = 'file'): static
    {
        return $this->set('fileField', $value);
    }

    /**
     * 接口返回哪个字段用来标识文件名
     *
     * @param string $value
     * @return self
     */
    public function nameField(string $value = 'name'): static
    {
        return $this->set('nameField', $value);
    }

    /**
     * 文件的值用那个字段来标识。
     *
     * @param string $value
     * @return self
     */
    public function valueField(string $value = 'value'): static
    {
        return $this->set('valueField', $value);
    }

    /**
     * 文件下载地址的字段名。
     *
     * @param string $value
     * @return self
     */
    public function urlField(string $value = 'url'): static
    {
        return $this->set('urlField', $value);
    }

    /**
     * 上传按钮的文字
     *
     * @param string $value
     * @return self
     */
    public function btnLabel(string $value = ''): static
    {
        return $this->set('btnLabel', $value);
    }

    /**
     * 默认显示文件路径的时候会支持直接下载，可以支持加前缀如：`http://xx.dom/filename=` ，如果不希望这样，可以把当前配置项设置为 `false`。
     *
     * @param mixed $value
     * @return self
     */
    public function downloadUrl(mixed $value = null): static
    {
        return $this->set('downloadUrl', $value);
    }

    /**
     * amis 所在服务器，限制了文件上传大小不得超出 10M，所以 amis 在用户选择大文件的时候，自动会改成分块上传模式。
     *
     * @param mixed $value
     * @return self
     */
    public function useChunk(mixed $value = null): static
    {
        return $this->set('useChunk', $value);
    }

    /**
     * 分块大小
     *
     * @param int|float $value
     * @return self
     */
    public function chunkSize(int|float $value = 0): static
    {
        return $this->set('chunkSize', $value);
    }

    /**
     * startChunkApi
     *
     * @param mixed $value
     * @return self
     */
    public function startChunkApi(mixed $value = null): static
    {
        return $this->set('startChunkApi', $value);
    }

    /**
     * chunkApi
     *
     * @param mixed $value
     * @return self
     */
    public function chunkApi(mixed $value = null): static
    {
        return $this->set('chunkApi', $value);
    }

    /**
     * finishChunkApi
     *
     * @param mixed $value
     * @return self
     */
    public function finishChunkApi(mixed $value = null): static
    {
        return $this->set('finishChunkApi', $value);
    }

    /**
     * 分块上传时并行个数
     *
     * @param int|float $value
     * @return self
     */
    public function concurrency(int|float $value = 0): static
    {
        return $this->set('concurrency', $value);
    }

    /**
     * 文档内容
     *
     * @param string $value
     * @return self
     */
    public function documentation(string $value = ''): static
    {
        return $this->set('documentation', $value);
    }

    /**
     * 文档链接
     *
     * @param string $value
     * @return self
     */
    public function documentLink(string $value = ''): static
    {
        return $this->set('documentLink', $value);
    }

    /**
     * 初表单反显时是否执行
     *
     * @param bool $value
     * @return self
     */
    public function initAutoFill(bool $value = true): static
    {
        return $this->set('initAutoFill', $value);
    }

    /**
     * 校验格式失败后的提示信息 可以用{{}}获取内部变量值，如{{accept}}
     *
     * @param string $value
     * @return self
     */
    public function invalidTypeMessage(string $value = ''): static
    {
        return $this->set('invalidTypeMessage', $value);
    }

    /**
     * 校验文件大小失败时显示的文字信息 可以用{{}}获取内部变量值，如{{maxSize}}
     *
     * @param string $value
     * @return self
     */
    public function invalidSizeMessage(string $value = ''): static
    {
        return $this->set('invalidSizeMessage', $value);
    }
}
