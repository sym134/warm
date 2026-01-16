<?php

namespace warm\support\facade;

class Test2
{
    private string $name='null';

    public function set($name): static
    {
        $this->name=$name;
        return $this;
    }
    
    public function get()
    {
        return $this->name;
    }

}