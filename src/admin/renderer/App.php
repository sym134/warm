<?php
namespace warm\admin\renderer;

use warm\admin\renderer\trait\DataDomain;
use warm\admin\renderer\trait\OnEvent;

/**
 * App
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/app
 */
class App extends BaseRenderer
{
    use OnEvent;
    use DataDomain;

    public string $type = 'app';


}
