<?php

namespace warm\admin\middleware;

use warm\admin\model\AdminPlugin;
use warm\admin\plugin\PluginService;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

/**
 * 检查插件启用状态中间件
 *
 * 负责验证请求的插件是否已启用，确保只有已启用的插件才能被访问。
 * 使用共享缓存提高性能和一致性。
 */
class CheckPluginEnabled implements MiddlewareInterface
{
    /**
     * 处理插件启用状态检查的中间件方法
     *
     * 在请求处理前进行插件启用状态检查：
     * 1. 从请求中获取插件名称
     * 2. 检查插件是否已启用（优先使用缓存）
     * 3. 如果插件未启用，返回404响应
     * 4. 如果插件已启用，继续处理请求
     *
     * @param Request $request HTTP请求对象
     * @param callable $handler 请求处理回调函数
     * @return Response HTTP响应对象
     */
    public function process(Request $request, callable $handler): Response
    {
        $pluginService = new PluginService();

        // 如果无法确定插件名称，则继续处理请求
        if (!$request->plugin) {
            return $handler($request);
        }

        // 检查插件是否已启用
        if (!$pluginService->isPluginEnabled($request->plugin)) {
            if ($request->isPjax() || $request->acceptJson()) {
                return new Response(404, [
                    'Content-Type' => 'application/json'], json_encode(['msg' => translator('::admin.disabled_plugin'),
                ], JSON_UNESCAPED_UNICODE));
            }
            // 插件未启用，返回404响应
            return response(translator('::admin.disabled_plugin'), 404);
        }

        // 插件已启用，继续处理请求
        return $handler($request);
    }

    /**
     * 清除插件状态缓存
     *
     * @param string|null $pluginName 插件名称，如果为null则清除所有插件状态缓存
     * @return void
     */
    public static function clearCache(?string $pluginName = null): void
    {
        $pluginService = new PluginService();
        $pluginService->clearPluginCache($pluginName);
    }
}
