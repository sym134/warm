<?php

namespace warm\support\apis;

interface AdminApiInterface
{
    /**
     * 接口处理逻辑
     *
     * @return mixed
     */
    public function handle(): mixed;

    /**
     * 接口参数设置 (表单结构)
     *
     * @return mixed
     */
    public function argsSchema(): mixed;
}
