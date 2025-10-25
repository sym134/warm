<?php

namespace warm\common\trait;

/**
 * 日期时间格式化特性
 * 
 * 为模型提供统一的日期时间格式化功能
 * 
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait DatetimeFormatterTrait
{
    /**
     * 序列化日期时间
     * 
     * 将日期时间对象格式化为字符串
     * 
     * @param \DateTimeInterface $date 日期时间对象
     * @return string 格式化后的日期时间字符串
     */
    protected function serializeDate(\DateTimeInterface $date): string
    {
        return $date->format($this->getDateFormat());
    }
}