<?php

namespace warm\admin\service\system;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Builder;
use warm\admin\model\system\SystemCrontab;
use warm\admin\service\AdminService;

/**
 * 定时任务服务类
 *
 * 提供定时任务管理功能，包括任务存储、更新、执行等
 *
 * @method SystemCrontab getModel() 获取模型实例
 * @method SystemCrontab|\Illuminate\Database\Query\Builder query() 获取查询构造器
 */
class SystemCrontabService extends AdminService
{
    /**
     * 模型类名
     *
     * @var string
     */
    protected string $modelName = SystemCrontab::class;

    /**
     * 存储任务
     *
     * @param array $data 存储的数据
     * @return bool 是否存储成功
     * @throws \Exception
     */
    public function store($data): bool
    {
        $data['rule'] = $this->generateCrontabExpression($data['execution_cycle'], $data['second'], $data['minute'], $data['hour'], $data['day'], '*', $data['week']);
        $data['created_by'] = $this->request->user->id;
        $this->validateTask($data['task_type'], $data['target']);
        return parent::store($data);
    }

    /**
     * 更新任务
     *
     * @param mixed $primaryKey 主键值
     * @param array $data 更新的数据
     * @return bool 是否更新成功
     */
    public function update($primaryKey, $data): bool
    {
        $data['rule'] = $this->generateCrontabExpression($data['execution_cycle'], $data['second'], $data['minute'], $data['hour'], $data['day'], '*', $data['week']);
        $data['created_by'] = $this->request->user->id;
        $this->validateTask($data['task_type'], $data['target']);
        return parent::update($primaryKey, $data);
    }

    /**
     * 列表查询处理
     *
     * @return Builder 查询构造器
     */
    public function listQuery(): Builder
    {
        return parent::listQuery();
    }

    /**
     * 验证任务
     *
     * @param string $task_type 任务类型
     * @param string $target 任务目标
     * @return void
     * @throws \Exception Author:sym
     * Date:2024/7/2 下午3:28
     * Company:极智科技
     */
    private function validateTask(string $task_type, string $target): void
    {
        if ((int)$task_type === 3) {
            if (!str_contains($target, ':')) {
                throw new \Exception('类任务格式错误');
            }
            [$class, $fun] = explode(':', $target);
            if (!class_exists($class)) {
                throw new \Exception('类任务不存在:' . $class);
            }
            if (!method_exists($class, $fun)) {
                throw new \Exception('类任务:' . $class . ',方法:' . $fun . ',未找到');
            }
        }
    }

    /**
     * 生成Crontab表达式
     *
     * @param string $executionPeriod 执行周期
     * @param string $second 秒
     * @param string $minute 分钟
     * @param string $hour 小时
     * @param string $dayOfMonth 日期
     * @param string $month 月份
     * @param string $dayOfWeek 星期
     * @return string Crontab表达式
     *
     * Author:sym
     * Date:2024/7/4 下午6:34
     * Company:极智科技
     */
    private function generateCrontabExpression($executionPeriod, $second = '*', $minute = '*', $hour = '*', $dayOfMonth = '*', $month = '*', $dayOfWeek = '*'): string
    {
        // 设置默认值
        $second = ($second !== null && $second !== '') ? $second : '*';
        $minute = ($minute !== null && $minute !== '') ? $minute : '*';
        $hour = ($hour !== null && $hour !== '') ? $hour : '*';
        $dayOfMonth = ($dayOfMonth !== null && $dayOfMonth !== '') ? $dayOfMonth : '*';
        $month = ($month !== null && $month !== '') ? $month : '*';
        $dayOfWeek = ($dayOfWeek !== null && $dayOfWeek !== '') ? $dayOfWeek : '*';

        switch ($executionPeriod) {
            case 'day':
                // 每天执行
                $minute = ($minute !== '*') ? $minute : '0';
                $hour = ($hour !== '*') ? $hour : '0';
                $dayOfMonth = '*';
                $month = '*';
                $dayOfWeek = '*';
                break;
            case 'day-n':
                // 每 N 天执行
                $minute = ($minute !== '*') ? $minute : '0';
                $hour = ($hour !== '*') ? $hour : '0';
                $dayOfMonth = "*/$dayOfMonth";
                $month = '*';
                $dayOfWeek = '*';
                break;
            case 'hour':
                // 每小时执行
                $minute = ($minute !== '*') ? $minute : '0';
                $hour = '*';
                $dayOfMonth = '*';
                $month = '*';
                $dayOfWeek = '*';
                break;
            case 'hour-n':
                // 每 N 小时执行
                $minute = ($minute !== '*') ? $minute : '0';
                $hour = "*/$hour";
                $dayOfMonth = '*';
                $month = '*';
                $dayOfWeek = '*';
                break;
            case 'minute-n':
                // 每 N 分钟执行
                $minute = "*/$minute";
                $hour = '*';
                $dayOfMonth = '*';
                $month = '*';
                $dayOfWeek = '*';
                break;
            case 'week':
                // 每周执行
                $minute = ($minute !== '*') ? $minute : '0';
                $hour = ($hour !== '*') ? $hour : '0';
                $dayOfMonth = '*';
                $month = '*';
                $dayOfWeek = ($dayOfWeek !== '*') ? $dayOfWeek : '0';
                break;
            case 'month':
                // 每月执行
                $minute = ($minute !== '*') ? $minute : '0';
                $hour = ($hour !== '*') ? $hour : '0';
                $dayOfMonth = ($dayOfMonth !== '*') ? $dayOfMonth : '1';
                $month = '*';
                $dayOfWeek = '*';
                break;
            case 'second-n':
                $second = ($second !== '*') ? '*/' . $second : '0';
                $minute = "*";
                $hour = '*';
                $dayOfMonth = '*';
                $month = '*';
                $dayOfWeek = '*';
                break;
            default:
                return "Invalid execution period.";
        }

        // 组合成 crontab 表达式
        return "$second $minute $hour $dayOfMonth $month $dayOfWeek";
    }

    /**
     * crontab表达式到文本
     *
     * @param string $executionPeriod 执行周期
     * @param string $expression 表达式
     * @return string 文本描述
     *
     * Author:sym
     * Date:2024/7/4 下午6:34
     * Company:极智科技
     */
    public function crontabExpressionToText(string $executionPeriod, string $expression): string
    {
        $parts = explode(' ', $expression);

        if (count($parts) != 6) {
            return "Invalid crontab expression.";
        }

        [$second, $minute, $hour, $dayOfMonth, $month, $dayOfWeek] = $parts;

        // 定义一个用于返回的文本数组
        $text = [];

        // 处理不同的执行周期
        switch ($executionPeriod) {
            case 'second-n':
                return $this->convertPeriod($second, '秒', 'second-n');
            case 'minute-n':
                return $this->convertPeriod($minute, '分钟', 'minute-n');
            case 'hour-n':
                return $this->convertPeriod($hour, '小时', 'hour-n');
            case 'day-n':
                return $this->convertPeriod($dayOfMonth, '天', 'day-n');
            case 'day':
                $text[] = "每天";
                break;
            case 'hour':
                $text[] = "每小时";
                break;
            case 'week':
                $text[] = "每周";
                break;
            case 'month':
                $text[] = "每月";
                break;
            default:
                return "Invalid execution period.";
        }

        // 处理周几
        if ($dayOfWeek !== '*') {
            $days = ['日', '一', '二', '三', '四', '五', '六'];
            $text[] = "周" . $days[$dayOfWeek];
        }

        // 处理每月的哪一天
        if ($dayOfMonth !== '*' && $executionPeriod !== 'day-n') {
            $text[] = "每月" . $dayOfMonth . "日";
        }

        // 处理月份
        if ($month !== '*') {
            $text[] = $month . "月";
        }

        // 处理小时和分钟
        if ($hour !== '*') {
            $text[] = sprintf("%02d", $hour) . "时";
        }

        if ($minute !== '*') {
            $text[] = sprintf("%02d", $minute) . "分";
        }

        // 处理秒
        if ($second !== '*' && $executionPeriod !== 'second-n') {
            $text[] .= sprintf("%02d", $second) . "秒";
        }

        // 生成最终的文本描述
        $finalText = implode(' ', array_filter($text));

        // 优化输出
        if ($executionPeriod == 'hour' && strpos($finalText, "00时") !== false) {
            $finalText = str_replace("00时", "", $finalText);
            $finalText = "每小时第 " . trim($finalText) . " 执行一次";
        } else {
            $finalText = $finalText . " 执行一次";
        }

        return $finalText;
    }

    /**
     * 辅助函数，用于处理 'n' 周期
     *
     * @param string $part 部分表达式
     * @param string $unit 单位
     * @param string $periodType 周期类型
     * @return string 文本描述
     *
     * Author:sym
     * Date:2024/7/4 下午6:33
     * Company:极智科技
     */
    private function convertPeriod($part, $unit, $periodType): string
    {
        if (preg_match('/^\*\/(\d+)$/', $part, $matches)) {
            return "每隔 " . $matches[1] . " " . $unit . "执行一次";
        } else {
            return "Invalid expression for " . $periodType . ".";
        }
    }

    /**
     * 运行任务
     *
     * @param int $id 任务ID
     * @return bool 是否运行成功
     *
     * Author:sym
     * Date:2024/7/2 下午3:29
     * Company:极智科技
     */
    public function run(int $id): bool
    {
        // 获取任务信息
        $info = $this->getModel()->find($id);
        
        // 检查任务是否存在
        if (!$info) {
            return false;
        }
        
        // 初始化日志数据
        $logData = [
            'crontab_id' => $info->id,
            'target' => $info->target,
            'parameter' => $info->parameter,
            'exception_info' => '',
            'execution_status' => 2 // 默认失败状态
        ];

        try {
            switch ($info->task_type) {
                case 1:
                    // URL任务GET
                    return $this->executeHttpGetTask($info, $logData);
                    
                case 2:
                    // URL任务POST
                    return $this->executeHttpPostTask($info, $logData);
                    
                case 3:
                    // 类任务
                    return $this->executeClassTask($info, $logData);
                    
                default:
                    $logData['exception_info'] = '未知的任务类型: ' . $info->task_type;
                    SystemCrontabLogService::make()->store($logData);
                    return false;
            }
        } catch (\Exception $e) {
            $logData['exception_info'] = $e->getMessage();
            SystemCrontabLogService::make()->store($logData);
            return false;
        }
    }
    
    /**
     * 执行HTTP GET任务
     *
     * @param SystemCrontab $info 任务信息
     * @param array $logData 日志数据
     * @return bool 是否执行成功
     */
    private function executeHttpGetTask(SystemCrontab $info, array &$logData): bool
    {
        $httpClient = new Client([
            'timeout' => 5,
            'verify' => false,
        ]);
        
        try {
            $response = $httpClient->request('GET', $info->target, [
                'form_params' => $info->parameter,
            ]);
            
            $logData['execution_status'] = $response->getStatusCode() === 200 ? 1 : 2;
            SystemCrontabLogService::make()->store($logData);
            return $logData['execution_status'] === 1;
        } catch (GuzzleException $e) {
            $logData['exception_info'] = $e->getMessage();
            SystemCrontabLogService::make()->store($logData);
            return false;
        }
    }
    
    /**
     * 执行HTTP POST任务
     *
     * @param SystemCrontab $info 任务信息
     * @param array $logData 日志数据
     * @return bool 是否执行成功
     */
    private function executeHttpPostTask(SystemCrontab $info, array &$logData): bool
    {
        $httpClient = new Client([
            'timeout' => 5,
            'verify' => false,
        ]);
        
        try {
            $response = $httpClient->request('POST', $info->target, [
                'form_params' => $info->parameter,
            ]);
            
            $logData['execution_status'] = $response->getStatusCode() === 200 ? 1 : 2;
            SystemCrontabLogService::make()->store($logData);
            return $logData['execution_status'] === 1;
        } catch (GuzzleException $e) {
            $logData['exception_info'] = $e->getMessage();
            SystemCrontabLogService::make()->store($logData);
            return false;
        }
    }
    
    /**
     * 执行类任务
     *
     * @param SystemCrontab $info 任务信息
     * @param array $logData 日志数据
     * @return bool 是否执行成功
     */
    private function executeClassTask(SystemCrontab $info, array &$logData): bool
    {
        if (!str_contains($info->target, ':')) {
            $logData['exception_info'] = '类任务格式错误';
            SystemCrontabLogService::make()->store($logData);
            return false;
        }
        
        [$className, $methodName] = explode(':', $info->target);
        
        if (!class_exists($className)) {
            $logData['exception_info'] = '类任务不存在:' . $className;
            SystemCrontabLogService::make()->store($logData);
            return false;
        }
        
        if (!method_exists($className, $methodName)) {
            $logData['exception_info'] = '类任务:' . $className . ',方法:' . $methodName . ',未找到';
            SystemCrontabLogService::make()->store($logData);
            return false;
        }
        
        try {
            $class = new $className;
            $result = $class->$methodName($info->parameter);
            
            $logData['execution_status'] = 1;
            $logData['exception_info'] = is_string($result) ? $result : json_encode($result, JSON_UNESCAPED_UNICODE);
            SystemCrontabLogService::make()->store($logData);
            return true;
        } catch (\Exception $e) {
            $logData['exception_info'] = '执行类任务时发生错误: ' . $e->getMessage();
            SystemCrontabLogService::make()->store($logData);
            return false;
        }
    }
}