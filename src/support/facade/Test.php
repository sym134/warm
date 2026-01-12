<?php

namespace warm\support\facade;

class Test extends Facade
{
    protected static function getFacadeClass(): string
    {
        return Test2::class;
    }
}