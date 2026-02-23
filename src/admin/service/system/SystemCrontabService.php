<?php

namespace warm\admin\service\system;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Builder;
use warm\admin\model\system\SystemCrontab;
use warm\admin\service\AdminService;
use warm\common\service\task\CrontabExpressionService;
use warm\common\service\task\TaskExecutorService;
use warm\common\service\task\TaskValidationService;
use Webman\Channel\Client;

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
     * Crontab表达式服务
     *
     * @var CrontabExpressionService|null
     */
    protected ?CrontabExpressionService $expressionService = null;

    /**
     * 任务执行服务
     *
     * @var TaskExecutorService|null
     */
    protected ?TaskExecutorService $executorService = null;

    /**
     * 任务验证服务
     *
     * @var TaskValidationService|null
     */
    protected ?TaskValidationService $validationService = null;

    /**
     * 获取Crontab表达式服务实例
     *
     * @return CrontabExpressionService
     */
    protected function getExpressionService(): CrontabExpressionService
    {
        if ($this->expressionService === null) {
            $this->expressionService = CrontabExpressionService::make();
        }
        return $this->expressionService;
    }

    /**
     * 获取任务执行服务实例
     *
     * @return TaskExecutorService
     */
    protected function getExecutorService(): TaskExecutorService
    {
        if ($this->executorService === null) {
            $this->executorService = TaskExecutorService::make();
        }
        return $this->executorService;
    }

    /**
     * 获取任务验证服务实例
     *
     * @return TaskValidationService
     */
    protected function getValidationService(): TaskValidationService
    {
        if ($this->validationService === null) {
            $this->validationService = TaskValidationService::make();
        }
        return $this->validationService;
    }

    /**
     * 存储任务
     *
     * @param array $data 存储的数据
     * @return bool 是否存储成功
     * @throws Exception
     */
    public function store(array $data): bool
    {
        $data = $this->getValidationService()->processTaskData($data, $this->request ?? null);
        unset($data['parameter']['']);
        return parent::store($data);
    }

    /**
     * 更新任务
     *
     * @param mixed $primaryKey 主键值
     * @param array $data 更新的数据
     * @return bool 是否更新成功
     * @throws Exception
     */
    public function update(mixed $primaryKey, array $data): bool
    {
        $data = $this->getValidationService()->processTaskData($data, $this->request ?? null);
        unset($data['parameter']['']);
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
     * 运行任务
     *
     * @param int $id 任务ID
     * @param array|null $task 任务信息
     * @param bool $forceQueue 是否强制使用队列执行
     * @return bool 是否运行成功（异步执行时立即返回 true）
     *
     * Author:sym
     * Date:2024/7/2 下午3:29
     * Company:极智科技
     * @throws GuzzleException
     */
    public function run(int $id, array $task = null, bool $forceQueue = false): bool
    {
        return $this->getExecutorService()->run($id, $task, $forceQueue);
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
        return $this->getExpressionService()->crontabExpressionToText($executionPeriod, $expression);
    }

    public function saved(mixed $model, bool $isEdit = false): void
    {
        Client::connect();
        Client::publish('crontab',null);
    }

    public function deleted(string $ids): void
    {
        Client::connect();
        Client::publish('crontab',null);
    }
}