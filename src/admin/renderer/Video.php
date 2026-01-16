<?php
namespace warm\admin\renderer;
/**
 * Video
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/video
 */
class Video extends BaseRenderer
{
    public string $type = 'video';

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
     * 视频地址
     *
     * @param string $value
     * @return self
     */
    public function src(string $value = ''): self
    {
        return $this->set('src', $value);
    }

    /**
     * 是否为直播，视频为直播时需要添加上，支持`flv`和`hls`格式
     *
     * @param bool $value
     * @return self
     */
    public function isLive(bool $value = true): self
    {
        return $this->set('isLive', $value);
    }

    /**
     * 指定直播视频格式
     *
     * @param string $value
     * @return self
     */
    public function videoType(string $value = ''): self
    {
        return $this->set('videoType', $value);
    }

    /**
     * 视频封面地址
     *
     * @param string $value
     * @return self
     */
    public function poster(string $value = ''): self
    {
        return $this->set('poster', $value);
    }

    /**
     * 是否静音
     *
     * @param bool $value
     * @return self
     */
    public function muted(bool $value = true): self
    {
        return $this->set('muted', $value);
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
     * 倍数，格式为`[1.0, 1.5, 2.0]`
     *
     * @param array $value
     * @return self
     */
    public function rates(array $value = []): self
    {
        return $this->set('rates', $value);
    }

    /**
     * key 是时刻信息，value 可以可以为空，可有设置为图片地址，请看上方示例
     *
     * @param array $value
     * @return self
     */
    public function frames(array $value = []): self
    {
        return $this->set('frames', $value);
    }

    /**
     * 点击帧的时候默认是跳转到对应的时刻，如果想提前 3 秒钟，可以设置这个值为 3
     *
     * @param bool $value
     * @return self
     */
    public function jumpBufferDuration(bool $value = true): self
    {
        return $this->set('jumpBufferDuration', $value);
    }

    /**
     * 到了下一帧默认是接着播放，配置这个会自动停止
     *
     * @param bool $value
     * @return self
     */
    public function stopOnNextFrame(bool $value = true): self
    {
        return $this->set('stopOnNextFrame', $value);
    }
}
