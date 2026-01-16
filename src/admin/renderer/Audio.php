<?php
namespace warm\admin\renderer;
/**
 * Audio
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/audio
 */
class Audio extends BaseRenderer
{
    public string $type = 'audio';

    /**
     * 外层 Dom 的类名
     *
     * @param string $value
     * @return self
     */
    public function className(string $value = ''): self
    {
        return $this->set('className', $value);
    }

    /**
     * 是否是内联模式
     *
     * @param bool $value
     * @return self
     */
    public function inline(bool $value = true): self
    {
        return $this->set('inline', $value);
    }

    /**
     * 音频地址
     *
     * @param string $value
     * @return self
     */
    public function src(string $value = ''): self
    {
        return $this->set('src', $value);
    }

    /**
     * 是否循环播放
     *
     * @param bool $value
     * @return self
     */
    public function loop(bool $value = true): self
    {
        return $this->set('loop', $value);
    }

    /**
     * 是否自动播放
     *
     * @param bool $value
     * @return self
     */
    public function autoPlay(bool $value = true): self
    {
        return $this->set('autoPlay', $value);
    }

    /**
     * 可配置音频播放倍速如：`[1.0, 1.5, 2.0]`
     *
     * @param array $value
     * @return self
     */
    public function rates(array $value = []): self
    {
        return $this->set('rates', $value);
    }

    /**
     * 内部模块定制化
     *
     * @param array $value
     * @return self
     */
    public function controls(array $value = []): self
    {
        return $this->set('controls', $value);
    }
}
