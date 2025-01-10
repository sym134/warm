<?php

namespace warm\admin\support;

use Illuminate\Support\Traits\Conditionable;

class Pipeline extends \Illuminate\Pipeline\Pipeline
{
    use Conditionable;

    /**
     * @param $passable
     *
     * @return self
     */
    public static function handle($passable): Pipeline
    {
        return appw(self::class)->send($passable);
    }
}
