<?php

namespace warm\admin\renderer\expand;

trait OnEvent
{
    public function onEvent(array $event)
    {
        $this->set('onEvent.', $event);
    }
}