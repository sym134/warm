<?php

namespace warm\admin\renderer;


class InputPasswordControl extends BaseRenderer
{
    public function __construct()
    {
        $this->set('type', 'input-password');
    }

    public function revealPassword($value = true)
    {
        $this->set('revealPassword', $value);
    }

    public function name($value)
    {
        $this->set('name', $value);
    }

    public function lable($value)
    {
        $this->set('lable', $value);
    }
}