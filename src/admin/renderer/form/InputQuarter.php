<?php

declare(strict_types=1);

namespace warm\admin\renderer\form;

use warm\admin\renderer\trait\OnEvent;

/**
 * InputQuarter 季度
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/form/input-quarter
 */
class InputQuarter extends InputDate
{
    use OnEvent;
    public string $type = 'input-quarter';
}
