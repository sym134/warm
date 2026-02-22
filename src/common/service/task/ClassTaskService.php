<?php

namespace warm\common\service\task;

use Exception;
use ReflectionMethod;
use warm\common\service\BaseService;
use warm\admin\service\system\SystemCrontabLogService;

/**
 * 类任务服务类
 * 
 * 提供类任务的执行和验证功能
 * 
 * @author sym
 * @date 2024/7/2
 */
class ClassTaskService extends BaseService
{
    /**
     * 验证任务
     *
     * @param string $task_type 任务类型
     * @param string $target 任务目标
     * @return void
     * @throws Exception
     */
    public function validateTask(string $task_type, string $target): void
    {
        if ((int)$task_type === 3) {
            if (!str_contains($target, ':')) {
                throw new Exception('类任务格式错误');
            }
            [$class, $fun] = explode(':', $target);
            if (!class_exists($class)) {
                throw new Exception('类任务不存在:' . $class);
            }
            if (!method_exists($class, $fun)) {
                throw new Exception('类任务:' . $class . ',方法:' . $fun . ',未找到');
            }
        }
    }

    /**
     * 执行类任务
     *
     * @param array $task 任务信息
     * @param array $logData 日志数据
     * @return bool 是否执行成功
     */
    public function executeClassTask(array $task, array &$logData): bool
    {
        if (!str_contains($task['target'], ':')) {
            $logData['exception_info'] = '类任务格式错误';
            SystemCrontabLogService::make()->store($logData);
            return false;
        }
        
        [$className, $methodName] = explode(':', $task['target'], 2);
        
        if (!class_exists($className)) {
            $logData['exception_info']['message'] = '类任务不存在:' . $className;
            SystemCrontabLogService::make()->store($logData);
            return false;
        }
        
        if (!method_exists($className, $methodName)) {
            $logData['exception_info']['message'] = '类任务:' . $className . ',方法:' . $methodName . ',未找到';
            SystemCrontabLogService::make()->store($logData);
            return false;
        }
        
        try {
            $class = new $className;
            
            // 根据方法签名决定如何传递参数
            $reflection = new ReflectionMethod($className, $methodName);
            $parameters = $reflection->getParameters();
            
            if (empty($parameters)) {
                // 方法无参数
                $result = $class->$methodName();
            } elseif (count($parameters) === 1 && $parameters[0]->isArray()) {
                // 方法接受数组参数
                $result = $class->$methodName($task['parameter'] ?? []);
            } else {
                // 其他情况，直接传递参数
                $result = $class->$methodName($task['parameter']);
            }
            
            $logData['execution_status'] = 1;
            $logData['exception_info'] = is_string($result) ? $result : json_encode($result, JSON_UNESCAPED_UNICODE);
            SystemCrontabLogService::make()->store($logData);
            return true;
        } catch (Exception $e) {
            // 记录完整的异常信息
            $logData['exception_info'] = [
                'message' => '执行类任务时发生错误: ' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ];
            SystemCrontabLogService::make()->store($logData);
            return false;
        }
    }
}