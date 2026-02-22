<?php

namespace warm\common\service\task;

use Exception;
use warm\common\service\BaseService;

/**
 * 任务验证服务类
 * 
 * 提供任务数据的验证和处理功能
 * 
 * @author sym
 * @date 2024/7/2
 */
class TaskValidationService extends BaseService
{
    /**
     * Crontab表达式服务
     * 
     * @var CrontabExpressionService
     */
    protected CrontabExpressionService $expressionService;

    /**
     * 类任务服务
     * 
     * @var ClassTaskService
     */
    protected ClassTaskService $classTaskService;

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->expressionService = CrontabExpressionService::make();
        $this->classTaskService = ClassTaskService::make();
    }

    /**
     * 处理任务数据数组
     *
     * @param array $data 任务数据
     * @param object|null $request 请求对象（用于获取用户信息）
     * @return array 处理后的数据
     * @throws Exception
     */
    public function processTaskData(array $data, ?object $request = null): array
    {
        // 验证必填字段
        $requiredFields = ['execution_cycle', 'task_type', 'target'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || $data[$field] === '') {
                throw new Exception("字段 {$field} 是必填的");
            }
        }
        
        // 验证任务类型
        if (!in_array((int)$data['task_type'], [1, 2, 3])) {
            throw new Exception('无效的任务类型: ' . $data['task_type']);
        }
        
        // 验证执行周期
        $validPeriods = ['day', 'day-n', 'hour', 'hour-n', 'minute-n', 'week', 'month', 'second-n'];
        if (!in_array($data['execution_cycle'], $validPeriods)) {
            throw new Exception('无效的执行周期: ' . $data['execution_cycle']);
        }
        
        // 验证 URL 任务的目标格式
        if (in_array((int)$data['task_type'], [1, 2])) {
            if (!filter_var($data['target'], FILTER_VALIDATE_URL)) {
                throw new Exception('URL 任务的目标必须是有效的 URL 地址');
            }
        }
        
        // 生成 Cron 表达式
        $data['rule'] = $this->expressionService->generateCrontabExpression(
            $data['execution_cycle'],
            $data['second'] ?? '*',
            $data['minute'] ?? '*',
            $data['hour'] ?? '*',
            $data['day'] ?? '*',
            '*',
            $data['week'] ?? '*'
        );
        
        // 设置创建者（如果存在 request 对象）
        if ($request && isset($request->user) && isset($request->user->id)) {
            $data['created_by'] = $request->user->id;
        }
        
        // 验证任务
        $this->classTaskService->validateTask($data['task_type'], $data['target']);
        
        return $data;
    }
}