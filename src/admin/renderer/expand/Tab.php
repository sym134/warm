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
}