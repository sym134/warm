<?php

namespace warm\common\service\task;

use warm\common\service\BaseService;

/**
 * Crontab表达式服务类
 * 
 * 提供Crontab表达式的生成、解析和格式化功能
 * 
 * @author sym
 * @date 2024/7/4
 */
class CrontabExpressionService extends BaseService
{
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
     */
    public function generateCrontabExpression(
        string $executionPeriod, 
        string $second = '*', 
        string $minute = '*', 
        string $hour = '*', 
        string $dayOfMonth = '*', 
        string $month = '*', 
        string $dayOfWeek = '*'
    ): string {
        // 设置默认值
        $second = ($second !== null && $second !== '') ? $second : '*';
        $minute = ($minute !== null && $minute !== '') ? $minute : '*';
        $hour = ($hour !== null && $hour !== '') ? $hour : '*';
        $dayOfMonth = ($dayOfMonth !== null && $dayOfMonth !== '') ? $dayOfMonth : '*';
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
                // 修复：当 second 为 '*' 时，应该使用默认值而不是 '0'
                if ($second === '*' || $second === '' || $second === null) {
                    $second = '*/1'; // 默认每秒执行
                } else {
                    $second = '*/' . $second;
                }
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
     * Crontab表达式到文本描述
     *
     * @param string $executionPeriod 执行周期
     * @param string $expression 表达式
     * @return string 文本描述
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
        if ($executionPeriod == 'hour' && str_contains($finalText, "00时")) {
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
     */
    private function convertPeriod(string $part, string $unit, string $periodType): string
    {
        if (preg_match('/^\*\/(\d+)$/', $part, $matches)) {
            return "每隔 " . $matches[1] . " " . $unit . "执行一次";
        } else {
            return "Invalid expression for " . $periodType . ".";
        }
    }
}