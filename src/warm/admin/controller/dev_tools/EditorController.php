<?php

namespace warm\admin\controller\dev_tools;

use Illuminate\Support\Arr;
use support\Response;
use warm\admin\controller\AdminController;
use warm\admin\renderer\RendererMap;

/**
 * AMIS编辑器控制器
 * 
 * 该控制器负责将AMIS的JSON结构转换为PHP代码表示，
 * 用于代码生成和可视化编辑功能。
 */
class EditorController extends AdminController
{
    /**
     * 处理编辑器请求入口
     * 
     * 接收前端传入的AMIS schema JSON结构，
     * 调用parse方法将其转换为PHP代码表示，
     * 然后返回给前端显示。
     * 
     * @return Response 响应对象，包含转换后的PHP代码
     */
    public function index(): Response
    {
        // 获取请求中的schema参数并解析为PHP代码
        $schema = $this->parse(request()->input('schema'));

        // 返回成功响应，包含解析后的代码
        return $this->response()->success(compact('schema'));
    }

    /**
     * 递归解析JSON结构并转换为PHP代码表示
     * 
     * 此方法负责将AMIS的JSON结构转换为可执行的PHP代码。
     * 它会根据元素是否包含type字段判断是组件还是普通数组，
     * 然后分别进行处理。
     * 
     * @param array $json 需要解析的JSON结构数据
     * @param int $level 当前缩进级别，用于格式化输出代码
     * @return string 解析后的PHP代码字符串
     */
    public function parse(array $json, int $level = 1): string
    {
        $code    = '';
        $map     = RendererMap::$map;
        $mapKeys = array_keys($map);
        $space   = "\n" . str_repeat("\t", $level);

        // 判断是否为AMIS组件（含有type字段）
        if ($json['type'] ?? null) {
            // 处理组件类型
            if (in_array($json['type'], $mapKeys)) {
                // 如果是已知组件类型，则使用对应的渲染器类
                $className = str_replace('warm\\admin\\renderer\\', '', $map[$json['type']]);
                $code      .= $space . sprintf('amis()->%s()', $className);
            } else {
                // 如果是未知组件类型，则直接使用type名称
                $code .= $space . sprintf('amis(\'%s\')', $json['type']);
            }

            // 处理组件属性
            foreach ($json as $key => $value) {
                // 跳过type字段，因为已经处理过了
                if ($key == 'type') {
                    continue;
                }
                
                // 如果属性值是数组，则递归处理
                if (is_array($value)) {
                    $code .= sprintf('->%s(%s)', $key, $this->parse($value, $level + 1));
                    continue;
                }
                
                // 处理标量值属性
                $code .= sprintf('->%s(\'%s\')', $key, $this->escape($value));
            }
        } else {
            // 处理普通数组结构，将其转换为PHP数组表示
            $code = '[';
            foreach ($json as $key => $value) {
                // 处理标量值
                if (!is_array($value)) {
                    $code .= $space . sprintf('\'%s\' => \'%s\',', $key, $this->escape($value));
                    continue;
                }

                // 处理索引数组（列表）
                if (Arr::isList($json)) {
                    $code .= $space . sprintf('%s,', $this->parse($value, $level + 1));
                    continue;
                }

                // 处理关联数组
                $code .= $space . sprintf('\'%s\' => %s,', $key, $this->parse($value, $level + 1));
            }
            // 结束数组定义，移除最后的制表符并加上右括号
            $code .= substr($space, 0, -1) . ']';
        }

        // 清理空数组表示，将[\n]替换为[]
        $code = preg_replace("/\[\n\t*]/", "[]", $code);

        // 清理多余的换行符
        return preg_replace("/\n\t*\n/", "\n", $code);
    }

    /**
     * 转义单引号
     * 
     * 在生成PHP代码时，需要对字符串中的单引号进行转义，
     * 以避免破坏生成的代码结构。
     * 
     * @param string $string 需要转义的字符串
     * @return string 转义后的字符串
     */
    public function escape(string $string): string
    {
        return str_replace("'", "\'", $string);
    }
}