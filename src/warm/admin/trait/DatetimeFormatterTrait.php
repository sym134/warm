<?php

namespace warm\admin\trait;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait DatetimeFormatterTrait
{
    protected function serializeDate(\DateTimeInterface $date): string
    {
        return $date->format($this->getDateFormat());
    }
}
