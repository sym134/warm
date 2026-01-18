<?php

namespace warm\admin\controller;

use support\Response;
use warm\admin\Admin;
use warm\admin\renderer\Panel;
use warm\admin\renderer\Wrapper;

/**
 * 后台首页控制器类
 * 
 * 负责构建和展示后台管理系统的首页内容
 * 包括框架信息、图表展示、时钟等组件
 */
class HomeController extends AdminController
{
    /**
     * 首页展示
     * 
     * 构建并返回首页页面结构，包含各种信息展示组件
     * 
     * @return Response 首页响应
     */
    public function index(): Response
    {
        // 构建首页页面结构
        $page = $this->basePage()->css($this->css())->body([
            // 第一行布局：框架信息和饼图
            amis()->Grid()->columns([
                $this->frameworkInfo()->set('md', 5),
                amis()->Flex()->items([
                    $this->pieChart(),
                    $this->cube(),
                ]),
            ]),
            // 第二行布局：折线图和代码视图
            amis()->Grid()->columns([
                $this->lineChart()->set('md', 8),
                amis()->Flex()->className('h-full')->items([
                    $this->clock(),
                    $this->codeView(),
                ])->direction('column'),
            ]),
        ]);

        // 返回页面响应
        return $this->response()->success($page);
    }

    /**
     * 代码视图组件
     * 
     * 展示示例代码片段
     * 
     * @return Panel 代码视图面板
     */
    public function codeView(): Panel
    {
        return amis()->Panel()->className('h-full clear-card-mb rounded-md')->body([
            amis()->Markdown()->options(['html' => true, 'breaks' => true])->value(<<<MD
### __The beginning of everything__

```php
<?php

echo 'Hello World';
```
MD
            ),
        ])->id('code-view-panel')->set('animations', [
            'enter' => [
                'duration' => 0.5,
                'type'     => 'fadeInRight',
            ],
        ]);
    }

    /**
     * 时钟组件
     * 
     * 展示实时时间的时钟组件
     * 
     * @return Wrapper 时钟卡片
     */
    public function clock(): Wrapper
    {
        /** @noinspection all */
        $panel = amis()->Panel()->className('h-full bg-blingbling')->body([
            amis()->Tpl()->tpl('<div class="text-2xl font-bold mb-4">Clock</div>'),
            amis()->Custom()
                ->name('clock')
                ->html('<div id="clock" class="text-4xl"></div><div id="clock-date" class="mt-5"></div>')
                ->onMount(
                    <<<JS
const clock = document.getElementById('clock');
const tick = () => {
    clock.innerHTML = (new Date()).toLocaleTimeString();
    requestAnimationFrame(tick);
};
tick();

const clockDate = document.getElementById('clock-date');
clockDate.innerHTML = (new Date()).toLocaleDateString();
JS

                ),
        ]);

        return amis()->Wrapper()->size('none')->className('h-full mb-3')->id('clock-panel')->set('animations', [
            'enter' => [
                'duration' => 0.5,
                'type'     => 'fadeInRight',
            ],
        ])->body($panel);
    }

    /**
     * 框架信息组件
     * 
     * 展示框架相关信息和链接
     * 
     * @return Panel 框架信息卡片
     */
    public function frameworkInfo(): Panel
    {
        // 创建链接组件的辅助函数
        $link = function ($label, $link) {
            return amis()->Action()
                ->level('link')
                ->className('text-lg font-semibold')
                ->label($label)
                ->set('blank', true)
                ->actionType('url')
                ->link($link);
        };

        return amis()->Panel()->className('h-96')->body(
            amis()->Wrapper()->className('h-full')->body([
                amis()->Flex()
                    ->className('h-full')
                    ->direction('column')
                    ->justify('center')
                    ->alignItems('center')
                    ->items([
                        amis()->Image()->src(url(Admin::warmConfig('app.logo'))),
                        amis()->Wrapper()->className('text-3xl mt-9 font-bold')->body(Admin::warmConfig('app.name')),
                        amis()->Flex()->className('px-24 w-full mt-5')->justify('space-around')->items([
                            $link('GitHub', 'https://github.com/sym134/warm'),
                        ]),
                    ]),
            ])
        )->id('framework-info')->set('animations', [
            'enter' => [
                'duration' => 0.5,
                'type'     => 'zoomIn',
            ],
        ]);
    }

    /**
     * 饼图组件
     * 
     * 展示数据分布的饼图
     * 
     * @return Panel 饼图卡片
     */
    public function pieChart(): Panel
    {
        return amis()->Panel()->className('w-full h-96')->body([
            amis()->Chart()->height(350)->config([
                'backgroundColor' => '',
                'tooltip'         => ['trigger' => 'item'],
                'legend'          => ['bottom' => 0, 'left' => 'center'],
                'series'          => [
                    [
                        'name'              => 'Access From',
                        'type'              => 'pie',
                        'radius'            => ['40%', '70%'],
                        'avoidLabelOverlap' => false,
                        'itemStyle'         => ['borderRadius' => 10, 'borderColor' => '#fff', 'borderWidth' => 2],
                        'label'             => ['show' => false, 'position' => 'center'],
                        'emphasis'          => [
                            'label' => [
                                'show'       => true,
                                'fontSize'   => '40',
                                'fontWeight' => 'bold',
                            ],
                        ],
                        'labelLine'         => ['show' => false],
                        'data'              => [
                            ['value' => 1048, 'name' => 'Search Engine'],
                            ['value' => 735, 'name' => 'Direct'],
                            ['value' => 580, 'name' => 'Email'],
                            ['value' => 484, 'name' => 'Union Ads'],
                            ['value' => 300, 'name' => 'Video Ads'],
                        ],
                    ],
                ],
            ])
        ])->id('pie-chart-panel')->set('animations', [
            'enter' => [
                'duration' => 0.5,
                'type'     => 'zoomIn',
            ],
        ]);
    }

    /**
     * 折线图组件
     * 
     * 展示用户行为数据的折线图
     * 
     * @return Panel 折线图卡片
     */
    public function lineChart(): Panel
    {
        // 生成随机数组的辅助函数
        $randArr = function () {
            $_arr = [];
            for ($i = 0; $i < 7; $i++) {
                $_arr[] = rand(50, 200);
            }
            return $_arr;
        };

        // 生成两组随机数据
        $random1 = $randArr();
        $random2 = $randArr();

        // 构建折线图
        $chart = amis()->Chart()->height(380)->className('h-96')->config([
            'backgroundColor' => '',
            'title'           => ['text' => 'Users Behavior'],
            'tooltip'         => ['trigger' => 'axis'],
            'xAxis'           => [
                'type'        => 'category',
                'boundaryGap' => false,
                'data'        => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            ],
            'yAxis'           => ['type' => 'value'],
            'grid'            => ['left' => '7%', 'right' => '3%', 'top' => 60, 'bottom' => 30,],
            'legend'          => ['data' => ['Visits', 'Bounce Rate']],
            'series'          => [
                [
                    'name'      => 'Visits',
                    'data'      => $random1,
                    'type'      => 'line',
                    'areaStyle' => [],
                    'smooth'    => true,
                    'symbol'    => 'none',
                ],
                [
                    'name'      => 'Bounce Rate',
                    'data'      => $random2,
                    'type'      => 'line',
                    'areaStyle' => [],
                    'smooth'    => true,
                    'symbol'    => 'none',
                ],
            ],
        ]);

        return amis()->Panel()->className('clear-card-mb')->body($chart)->id('line-chart-panel')->set('animations', [
            'enter' => [
                'duration' => 0.5,
                'type'     => 'zoomIn',
            ],
        ]);
    }

    /**
     * 3D立方体组件
     * 
     * 展示一个旋转的3D立方体动画
     * 
     * @return Panel 立方体卡片
     */
    public function cube(): Panel
    {
        return amis()->Panel()->className('h-96 ml-4 w-8/12')->body(
            amis()->Html()->html(
                <<<HTML
<style>
    .cube-box{ height: 300px; display: flex; align-items: center; justify-content: center; }
  .cube { width: 100px; height: 100px; position: relative; transform-style: preserve-3d; animation: rotate 10s linear infinite; }
  .cube:after {
    content: '';
    width: 100%;
    height: 100%;
    box-shadow: 0 0 50px rgba(0, 0, 0, 0.2);
    position: absolute;
    transform-origin: bottom;
    transform-style: preserve-3d;
    transform: rotateX(90deg) translateY(50px) translateZ(-50px);
    background-color: rgba(0, 0, 0, 0.1);
  }
  .cube div {
    background-color: rgba(64, 158, 255, 0.7);
    position: absolute;
    width: 100%;
    height: 100%;
    border: 1px solid rgb(27, 99, 170);
    box-shadow: 0 0 60px rgba(64, 158, 255, 0.7);
  }
  .cube div:nth-child(1) { transform: translateZ(-50px); animation: shade 10s -5s linear infinite; }
  .cube div:nth-child(2) { transform: translateZ(50px) rotateY(180deg); animation: shade 10s linear infinite; }
  .cube div:nth-child(3) { transform-origin: right; transform: translateZ(50px) rotateY(270deg); animation: shade 10s -2.5s linear infinite; }
  .cube div:nth-child(4) { transform-origin: left; transform: translateZ(50px) rotateY(90deg); animation: shade 10s -7.5s linear infinite; }
  .cube div:nth-child(5) { transform-origin: bottom; transform: translateZ(50px) rotateX(90deg); background-color: rgba(0, 0, 0, 0.7); }
  .cube div:nth-child(6) { transform-origin: top; transform: translateZ(50px) rotateX(270deg); }

  @keyframes rotate {
    0% { transform: rotateX(-15deg) rotateY(0deg); }
    100% { transform: rotateX(-15deg) rotateY(360deg); }
  }
  @keyframes shade { 50% { background-color: rgba(0, 0, 0, 0.7); } }
</style>
<div class="cube-box">
    <div class="cube">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
    </div>
</div>
HTML
            )
        )->id('cube-panel')->set('animations', [
            'enter' => [
                'duration' => 0.5,
                'type'     => 'zoomIn',
            ],
        ]);
    }

    /**
     * 自定义CSS样式
     * 
     * 定义页面中使用的自定义CSS样式
     * 
     * @return array CSS样式数组
     */
    private function css(): array
    {
        return [
            '.clear-card-mb'                 => [
                'margin-bottom' => '0 !important',
            ],
            '.cxd-Image'                     => [
                'border' => '0',
            ],
            '.bg-blingbling'                 => [
                'color'             => '#fff',
                'background'        => 'linear-gradient(to bottom right, #00C9FF, #FD746C, #FF8235, #ffff1c, #92FE9D, #2C3E50, #a044ff, #e73827)',
                'background-repeat' => 'no-repeat',
                'background-size'   => '1000% 1000%',
                'animation'         => 'gradient 60s ease infinite',
            ],
            '@keyframes gradient'            => [
                '0%{background-position:0% 0%} 50%{background-position:100% 100%} 100%{background-position:0% 0%}',
            ],
            '.bg-blingbling .cxd-Card-title' => [
                'color' => '#fff',
            ],
        ];
    }
}