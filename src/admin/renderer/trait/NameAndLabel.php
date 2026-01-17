<?php

namespace warm\admin\renderer\trait;

trait NameAndLabel
{
    /**
     * 字段名，指定该表单项提交时的 key
     *
     * @param string $value
     * @return self
     */
    public function name(string $value = ''): static
    {
        return $this->set('name', $value);
    }

    /**
     * 表单项标签
     *
     * @param mixed $value
     * @return self
     */
    public function label(mixed $value = null): static
    {
        return $this->set('label', $value);
    }
}