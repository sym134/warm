<?php

namespace warm\admin\renderer\trait;

trait OnEvent
{
    /**
     * 添加事件
     *
     * @param array $event
     * @return $this
     */
    public function onEvent(array $event = []): static
    {
        return $this->set('onEvent.', $event);
    }
}