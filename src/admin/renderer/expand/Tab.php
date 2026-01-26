<?php

namespace warm\admin\renderer\expand;

use warm\admin\renderer\BaseRenderer;

class Tab extends BaseRenderer
{
    public function title(string $value = ''): static
    {
        return $this->set('title', $value);
    }
    public function tab(mixed $value): static
    {
        return $this->set('tab', $value);
    }

    /**
     * 设置hash
     * 
     * @param string $string
     * @return Tab
     */
    public function hash(string $string): static
    {
        return $this->set('hash', $string);
    }
}