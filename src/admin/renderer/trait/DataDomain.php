<?php

namespace warm\admin\renderer\trait;

/**
 * 数据域
 */
trait DataDomain
{
    /**
     *  数据
     *
     * @param array $data
     * @return $this
     */
    public function Data(array $data=[]):static
    {
        return $this->set('data', $data);
    }
}