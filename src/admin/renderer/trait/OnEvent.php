<?php

namespace warm\admin\renderer\trait;

trait OnEvent
{
    /**
     * 添加事件
     *
     * @param string|array $event
     * @return $this
     */
    public function onEvent(string|array $event = []): static
    {
        return $this->set('onEvent', $event);
    }
}