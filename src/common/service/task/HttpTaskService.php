<?php

namespace warm\common\service\task;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Throwable;
use Workerman\Http\Client as WorkermanHttpClient;
use warm\common\service\BaseService;
use warm\admin\service\system\SystemCrontabLogService;

/**
 * HTTP任务服务类
 * 
 * 提供HTTP GET和POST任务的执行功能
 * 
 * @author sym
 * @date 2024/7/2
 */
class HttpTaskService extends BaseService
{
    /**
     * 执行HTTP GET任务
     *
     * @param array $task 任务信息
     * @param array $logData 日志数据
     * @return bool 是否执行成功
     */
    public function executeHttpGetTask(array $task, array &$logData): bool
    {
        var_dump('是否开启现成线程');
        var_dump(isCoroutineEnabled());
        // 优先使用 Workerman\Http\Client (支持协程非阻塞)
        if (isCoroutineEnabled()) {
            try {
                $options = [
                    'timeout' => config('crontab.http_timeout', 30),
                    'ssl' => [
                        'verify_peer' => config('crontab.verify_ssl', true),
                        'verify_peer_name' => config('crontab.verify_ssl', true),
                    ],
                ];
                $client = new WorkermanHttpClient($options);
                $url = $task['target'];
                if (!empty($task['parameter'])) {
                    $url .= (!str_contains($url, '?') ? '?' : '&') . http_build_query($task['parameter']);
                }
                $response = $client->get($url);
                $logData['execution_status'] = $response->getStatusCode() === 200 ? 1 : 2;
                SystemCrontabLogService::make()->store($logData);
                return $logData['execution_status'] === 1;
            } catch (Throwable $e) {
                $logData['exception_info'] = [
                    'message' => 'Workerman HTTP Client Error: ' . $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ];
                SystemCrontabLogService::make()->store($logData);
                return false;
            }
        }

        $httpClient = new Client([
            'timeout' => config('crontab.http_timeout', 30),
            'verify' => config('crontab.verify_ssl', true),
        ]);
        
        try {
            // GET 请求使用 query 参数，而不是 form_params
            $response = $httpClient->request('GET', $task['target'], [
                'query' => $task['parameter'] ?? [],
            ]);

            $logData['execution_status'] = $response->getStatusCode() === 200 ? 1 : 2;
            SystemCrontabLogService::make()->store($logData);
            return $logData['execution_status'] === 1;
        } catch (GuzzleException $e) {
            // 记录完整的异常信息
            $logData['exception_info'] = [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ];
            SystemCrontabLogService::make()->store($logData);
            return false;
        }
    }
    
    /**
     * 执行HTTP POST任务
     *
     * @param array $task 任务信息
     * @param array $logData 日志数据
     * @return bool 是否执行成功
     */
    public function executeHttpPostTask(array $task, array &$logData): bool
    {
        // 优先使用 Workerman\Http\Client (支持协程非阻塞)
        if (isCoroutineEnabled()) {
            try {
                $options = [
                    'timeout' => config('crontab.http_timeout', 30),
                    'ssl' => [
                        'verify_peer' => config('crontab.verify_ssl', true),
                        'verify_peer_name' => config('crontab.verify_ssl', true),
                    ],
                ];
                $client = new WorkermanHttpClient($options);
                $data = $task['parameter'];
                // 如果是 JSON 字符串，需要设置 Header
                $headers = [];
                if (is_string($data) && (str_starts_with(trim($data), '{') || str_starts_with(trim($data), '['))) {
                     $headers = ['Content-Type' => 'application/json'];
                }
                
                // Workerman Http Client 的 post 方法第二个参数是 data
                $response = $client->post($task['target'], $data, $headers);
                
                $logData['execution_status'] = $response->getStatusCode() === 200 ? 1 : 2;
                SystemCrontabLogService::make()->store($logData);
                return $logData['execution_status'] === 1;
            } catch (Throwable $e) {
                $logData['exception_info'] = [
                    'message' => 'Workerman HTTP Client Error: ' . $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ];
                SystemCrontabLogService::make()->store($logData);
                return false;
            }
        }

        $httpClient = new Client([
            'timeout' => config('crontab.http_timeout', 30),
            'verify' => config('crontab.verify_ssl', true),
        ]);
        
        try {
            // POST 请求根据参数类型选择 form_params 或 json
            $options = [];
            if (!empty($task['parameter'])) {
                // 如果参数是数组，使用 form_params；如果是 JSON 字符串，使用 json
                if (is_array($task['parameter'])) {
                    $options['form_params'] = $task['parameter'];
                } else {
                    $options['json'] = $task['parameter'];
                }
            }
            
            $response = $httpClient->request('POST', $task['target'], $options);
            
            $logData['execution_status'] = $response->getStatusCode() === 200 ? 1 : 2;
            SystemCrontabLogService::make()->store($logData);
            return $logData['execution_status'] === 1;
        } catch (GuzzleException $e) {
            // 记录完整的异常信息
            $logData['exception_info'] = [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ];
            SystemCrontabLogService::make()->store($logData);
            return false;
        }
    }
}