<?php

namespace warm\admin\plugin;

use Exception;
use Illuminate\Contracts\Queue\Monitor;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use ReflectionClass;
use warm\admin\Admin;
use warm\admin\model\AdminPlugin;
use warm\admin\renderer\Form;
use warm\admin\service\AdminService;
use warm\admin\support\Helper;
use warm\exception\AdminException;
use warm\support\Cache;
use ZipArchive;

/**
 * 插件服务类
 *
 * 提供插件管理相关功能，包括插件创建、启用/禁用、配置读取等
 */
class PluginService extends AdminService
{
    /**
     * 模型类名
     *
     * @var string
     */
    protected string $modelName = AdminPlugin::class;

    /**
     * 插件属性配置
     *
     * @var array
     */
    protected array $property = [];

    /**
     * 插件路径
     *
     * @var string
     */
    protected string $path = '';
    protected ?string $key = null;

    /**
     * 通过当前类获取插件名称
     *
     * @return string|null 插件名称，如果无法确定则返回null
     */
    public function getName(): ?string
    {
        // 如果已经缓存过插件名称，直接返回
        if (!is_null($this->key)) {
            return $this->key;
        }

        // 对于composer包，插件实际位于项目根目录的plugin目录下
        // 从类文件的实际路径中提取插件名称
        $reflection = new ReflectionClass($this);
        $filePath = $reflection->getFileName();

        // 检查文件路径是否包含插件目录
        if (str_contains($filePath, 'plugin' . DIRECTORY_SEPARATOR)) {
            // 提取插件目录后的第一个目录名作为插件名
            $pattern = '/' . preg_quote('plugin' . DIRECTORY_SEPARATOR, '/') . '([^' . preg_quote(DIRECTORY_SEPARATOR, '/') . ']+)/';
            if (preg_match($pattern, $filePath, $matches)) {
                return $this->key = $matches[1];
            }
        }

        return $this->key = null;
    }

    /**
     * 创建插件
     *
     * 执行插件创建命令并保存插件信息到数据库
     *
     * @param array $data 插件数据，必须包含name字段
     * @return bool 是否创建成功
     */
    public function store(array $data): bool
    {
        if (strtolower($data['key']) === 'app') {
            $this->setError('禁止使用app目录');
            return false;
        }
        // 判断数据库是否存在
        if (!is_null($this->modelName::query()->where('key', $data['key'])->first())) {
            $this->setError('插件已存在');
            return false;
        }
        Helper::pauseFileMonitor();
        [$state, $msg] = runCommand('warm-plugin:create ' . $data['key'] . ' ' . $data['name']);
        if ($state) {
            Helper::resumeFileMonitor();
            Helper::reloadWebman();
            $this->configApp($data['key']);
            return parent::store($data);
        }
        $this->setError($msg);
        return false;
    }

    /**
     * 获取扩展包路径
     *
     * @param string|null $path 相对路径
     * @return string 完整路径
     * @throws Exception 当配置的路径不是目录时抛出异常
     */
    public function path(string $path = null): string
    {
        if (!$this->path) {
            $this->path = Admin::warmConfig('app.plugin.dir');
            if (!is_dir($this->path)) {
                throw new Exception("The $this->path is not a directory.");
            }
        }

        $path = ltrim($path, '/');

        return $path ? $this->path . '/' . $path : $this->path;
    }

    /**
     * 排序处理
     *
     * 重写排序方法，按ID倒序排列
     *
     * @param mixed $query 查询构造器
     * @return void
     */
    public function sortable(mixed $query): void
    {
        $query->orderByDesc('id');
    }

    /**
     * 修改插件配置文件中的指定键值
     *
     * @param string $pluginName 插件名称
     * @param string $key 配置键名，支持点号分隔的多维数组路径，如 'AAA.BBB.CCC'
     * @param mixed $value 配置值
     * @return bool 是否修改成功
     */
    public function updatePluginConfig(string $pluginName, string $key, mixed $value): bool
    {
        $configPath = base_path('plugin/' . $pluginName . '/config/app.php');
        if (!file_exists($configPath)) {
            return false;
        }

        // 检查文件是否可写
        if (!is_writable($configPath)) {
            return false;
        }

        // 读取配置文件内容
        $content = file_get_contents($configPath);
        if ($content === false) {
            return false;
        }

        // 根据值的类型生成对应的PHP表示
        if (is_bool($value)) {
            $valueStr = $value ? 'true' : 'false';
        } elseif (is_string($value)) {
            $valueStr = "'" . addslashes($value) . "'";
        } elseif (is_array($value)) {
            $valueStr = var_export($value, true);
        } elseif (is_null($value)) {
            $valueStr = 'null';
        } else {
            $valueStr = (string)$value;
        }

        // 处理点号分隔的多维数组路径
        if (str_contains($key, '.')) {
            // 对于多维数组路径，如 'AAA.BBB.CCC'，我们需要特殊处理
            $keys = explode('.', $key);
            $mainKey = $keys[0];

            // 检查主键是否存在
            if (!str_contains($content, "'$mainKey' => [")) {
                // 主键不存在，添加主键结构
                $pattern = "/(return\s*\[)(.*)/s";
                $mainArray = "\n    '$mainKey' => [],";
                $replacement = "return [$mainArray\$2";
                $content = preg_replace($pattern, $replacement, $content);
            }

            // 构建嵌套键的正则表达式
            $pattern = "/(\s*'" . preg_quote($mainKey) . "'\s*=>\s*\[)([^\]]*?)(\],)/s";

            if (preg_match($pattern, $content, $matches)) {
                $arrayContent = $matches[2];

                // 构建嵌套键路径
                $nestedKeys = array_slice($keys, 1);

                // 检查嵌套键是否已存在
                if (str_contains($arrayContent, "'$nestedKeys[0]'")) {
                    // 嵌套键存在，更新值
                    $nestedPattern = "/(\s*'" . preg_quote($nestedKeys[0]) . "'\s*=>\s*[^,\]]*)(.*?)([,\]])/s";
                    $nestedReplacement = "$1 => " . $valueStr . "$3";
                    $arrayContent = preg_replace($nestedPattern, $nestedReplacement, $arrayContent);
                } else {
                    // 嵌套键不存在，添加新键
                    $newNested = "\n        '" . implode("' => [\n            '", $nestedKeys) . "' => " . $valueStr . ",";
                    $arrayContent = $newNested . $arrayContent;
                }

                $replacement = "$1" . $arrayContent . "$3";
                $content = preg_replace($pattern, $replacement, $content);
            }
        } else {
            // 处理简单键值对
            $pattern = "/(\s*'" . preg_quote($key) . "'\s*=>\s*)([^,\n]*)([,\n])/";
            if (preg_match($pattern, $content)) {
                $replacement = "$1" . $valueStr . "$3";
            } else {
                // 如果键不存在，则添加新的键值对
                $pattern = "/(return\s*\[)(.*)/s";
                $replacement = "return [\n    '" . $key . "' => " . $valueStr . ",\$2";
            }
            $content = preg_replace($pattern, $replacement, $content);
        }

        // 写入修改后的内容
        $result = file_put_contents($configPath, $content);
        return $result !== false;
    }

    /**
     * 启用/禁用插件
     *
     * 切换插件的启用状态
     *
     * @param array $data 包含插件ID和当前启用状态的数据
     * @return int 更新影响的行数
     */
    public function enable(array $data): int
    {
        $plugin = $this->modelName::query()->where('id', $data['id'])->first();
        if (!$plugin) {
            $this->setError('插件不存在');
            return 0;
        }

        $isEnabled = $data['enabled'] ? 1 : 0;

        // 先尝试更新配置文件
        $configUpdated = $this->updatePluginConfig($plugin->key, 'enable', $isEnabled === 1);

        if ($configUpdated) {
            // 配置文件更新成功后，再更新数据库
            $result = $this->modelName::query()->where('id', $data['id'])->update(['is_enabled' => $isEnabled]);
            
            // 清除插件缓存以确保状态一致性
            $this->clearPluginCache($plugin->key);
            
            return $result;
        } else {
            // 如果配置文件更新失败，保持数据库状态不变
            $this->setError('配置文件更新失败，可能没有写入权限或文件不存在');
            return 0;
        }
    }

    /**
     * 插件配置文件
     *
     * 获取指定插件的配置信息
     *
     * @param string $name 插件名称
     * @return array|null 插件配置数组或null
     *
     * Author:sym
     * Date:2024/6/18 上午11:07
     * Company:极智科技
     */
    public function configApp(string $name): array|null
    {
        return config('plugin.' . $name . '.app');
    }

    /**
     * 获取已启用的插件列表
     *
     * @return array|Collection 已启用的插件集合
     */
    public function getPlugins(): array|Collection
    {
        return $this->modelName::query()->where('is_enabled', 1)->get();
    }

    /**
     * 卸载插件
     *
     * 执行插件卸载命令并删除插件目录
     *
     * @param int $id 插件ID
     * @return bool 是否卸载成功
     */
    public function uninstall(int $id): bool
    {
        $version = '';
        $data = $this->modelName::query()->where('id', $id)->first();
        if (!$data) {
            $this->setError('插件不存在');
            return false;
        }
        
        $pluginName = $data['key'];
        
        // 获得插件路径
        clearstatcache();
        $path = get_realpath(base_path() . "/plugin/$data->key");
        if (!$path || !is_dir($path)) {
            // 即使目录不存在，也要清除缓存并删除数据库记录
            $this->clearPluginCache($pluginName);
            $this->modelName::query()->where('key', $data['key'])->delete();
            return true;
        }

        // 执行uninstall卸载
        $install_class = "\\plugin\\$data->key\\api\\Install";
        if (class_exists($install_class) && method_exists($install_class, 'uninstall')) {
            call_user_func([$install_class, 'uninstall'], $version);
        }

        // 删除目录
        clearstatcache();
        if (is_dir($path)) {
            $monitor_support_pause = method_exists(Monitor::class, 'pause');
            if ($monitor_support_pause) {
                Helper::pauseFileMonitor();
            }
            try {
                $this->removeDir($path);
            } finally {
                if ($monitor_support_pause) {
                    Helper::resumeFileMonitor();
                }
            }
        }
        clearstatcache();

        Helper::reloadWebman();

        // 从数据库中删除插件记录
        $this->modelName::query()->where('key', $data['key'])->delete();
        
        // 清除插件缓存
        $this->clearPluginCache($pluginName);

        return true;
    }

    /**
     * 递归删除目录及其内容
     *
     * @param string $src 目录路径
     * @return void 是否删除成功
     */
    private function removeDir(string $src): void
    {
        $dir = opendir($src);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                $full = $src . '/' . $file;
                if (is_dir($full)) {
                    $this->removeDir($full);
                } else {
                    unlink($full);
                }
            }
        }
        closedir($dir);
        rmdir($src);
    }

    /**
     * 本地安装插件
     *
     * 将上传的压缩包解压到{主项目}/plugin/目录下，并验证是否为合规插件
     *
     * @param mixed $file 上传的文件对象
     * @return bool 是否安装成功
     * @throws AdminException
     */
    public function localInstall(mixed $file): bool
    {
        // 检查文件是否存在且有效
        if (!$file || !$file->isValid()) {
            $this->setError('上传文件不存在或无效');
            return false;
        }

        $pluginDir = base_path('plugin');

        // 检查plugin目录是否存在，不存在则创建
        if (!is_dir($pluginDir)) {
            if (!mkdir($pluginDir, 0755, true)) {
                $this->setError('无法创建插件目录');
                return false;
            }
        }

        // 检查是否安装了zip扩展
        if (!extension_loaded('zip')) {
            $this->setError('服务器未安装zip扩展，无法解压文件');
            return false;
        }

        // 创建临时目录用于解压
        $tempDir = $pluginDir . DIRECTORY_SEPARATOR . 'temp_' . time();
        if (!mkdir($tempDir, 0755, true)) {
            $this->setError('无法创建临时目录');
            return false;
        }

        // 获取临时文件路径
        $zipFile = $file->getRealPath();

        $has_zip_archive = class_exists(ZipArchive::class, false);
        if (!$has_zip_archive) {
            $cmd = $this->getUnzipCmd($zipFile, $pluginDir);
            if (!$cmd) {
                throw new AdminException('请给php安装zip模块或者给系统安装unzip命令');
            }
            if (!function_exists('proc_open')) {
                throw new AdminException('请解除proc_open函数的禁用或者给php安装zip模块');
            }
        }

        Helper::pauseFileMonitor();

        try {
            // 解压文件
            $zip = new ZipArchive();
            if ($zip->open($zipFile) !== true) {
                $this->setError('无法打开压缩文件');
                $this->removeDir($tempDir);
                return false;
            }

            // 解压到临时目录
            if (!$zip->extractTo($tempDir)) {
                $this->setError('解压文件失败');
                $zip->close();
                $this->removeDir($tempDir);
                return false;
            }

            $zip->close();

            // 检查解压后的内容
            $files = scandir($tempDir);
            $files = array_diff($files, ['.', '..']);

            // 查找插件根目录
            $pluginRoot = $tempDir;
            if (count($files) == 1 && is_dir($tempDir . DIRECTORY_SEPARATOR . $files[0])) {
                $pluginRoot = $tempDir . DIRECTORY_SEPARATOR . $files[0];
            }

            // 验证是否为合规插件
            if (!$this->isValidPlugin($pluginRoot)) {
                $this->setError('插件不合规，缺少必要的文件或配置');
                $this->removeDir($tempDir);
                return false;
            }

            // 获取插件名称
            $pluginName = basename($pluginRoot);

            // 检查数据库中是否已存在同名插件
            if (!is_null($this->modelName::query()->where('key', $pluginName)->first())) {
                $this->setError('已存在该插件，请先卸载后再安装');
                $this->removeDir($tempDir);
                return false;
            }

            // 检查插件是否已存在
            $targetPluginDir = $pluginDir . DIRECTORY_SEPARATOR . $pluginName;
            if (is_dir($targetPluginDir)) {
                $this->setError('插件目录已存在，请先删除已安装插件后再次安装');
                $this->removeDir($tempDir);
                return false;
            }

            // 移动插件到目标目录
            if (!rename($tempDir, $targetPluginDir)) {
                $this->setError('无法移动插件到目标目录');
                $this->removeDir($tempDir);
                return false;
            }

            // 获取插件配置信息
            $config = include $targetPluginDir . DIRECTORY_SEPARATOR . 'config/app.php';

            // 在数据库中记录插件信息
            $pluginData = [
                'key' => $pluginName,
                'is_enabled' => 0, // 默认不启用
                'options' => $config['options'] ?? []
            ];

            $result = $this->modelName::query()->create($pluginData);
            if (!$result) {
                $this->setError('插件信息记录失败');
                // 如果数据库记录失败，回滚已复制的插件目录
                $this->removeDir($targetPluginDir);
                return false;
            }
        }finally {
            Helper::resumeFileMonitor();
        }

        Helper::reloadWebman();
        return true;
    }

    /**
     * 获取系统支持的解压命令
     * @param $zip_file
     * @param $extract_to
     * @return mixed|string|null
     */
    protected function getUnzipCmd($zip_file, $extract_to): mixed
    {
        if ($cmd = $this->findCmd('unzip')) {
            $cmd = "$cmd -o -qq $zip_file -d $extract_to";
        } else if ($cmd = $this->findCmd('7z')) {
            $cmd = "$cmd x -bb0 -y $zip_file -o$extract_to";
        } else if ($cmd = $this->findCmd('7zz')) {
            $cmd = "$cmd x -bb0 -y $zip_file -o$extract_to";
        }
        return $cmd;
    }

    /**
     * 查找系统命令
     * @param string $name
     * @param string|null $default
     * @param array $extraDirs
     * @return mixed|string|null
     */
    protected function findCmd(string $name, ?string $default = null, array $extraDirs = []): mixed
    {
        if (ini_get('open_basedir')) {
            $searchPath = array_merge(explode(PATH_SEPARATOR, ini_get('open_basedir')), $extraDirs);
            $dirs = [];
            foreach ($searchPath as $path) {
                if (@is_dir($path)) {
                    $dirs[] = $path;
                } else {
                    if (basename($path) == $name && @is_executable($path)) {
                        return $path;
                    }
                }
            }
        } else {
            $dirs = array_merge(
                explode(PATH_SEPARATOR, getenv('PATH') ?: getenv('Path')),
                $extraDirs
            );
        }

        $suffixes = [''];
        if ('\\' === DIRECTORY_SEPARATOR) {
            $pathExt = getenv('PATHEXT');
            $suffixes = array_merge($pathExt ? explode(PATH_SEPARATOR, $pathExt) : ['.exe', '.bat', '.cmd', '.com'], $suffixes);
        }
        foreach ($suffixes as $suffix) {
            foreach ($dirs as $dir) {
                if (@is_file($file = $dir . DIRECTORY_SEPARATOR . $name . $suffix) && ('\\' === DIRECTORY_SEPARATOR || @is_executable($file))) {
                    return $file;
                }
            }
        }

        return $default;
    }

    /**
     * 验证是否为合规插件
     *
     * @param string $pluginPath 插件路径
     * @return bool 是否为合规插件
     */
    private function isValidPlugin(string $pluginPath): bool
    {
        // 检查插件目录是否存在
        if (!is_dir($pluginPath)) {
            return false;
        }

        // 检查必要的文件是否存在
        $requiredFiles = [
            'config/app.php',
        ];

        foreach ($requiredFiles as $file) {
            if (!file_exists($pluginPath . DIRECTORY_SEPARATOR . $file)) {
                return false;
            }
        }

        // 检查配置文件是否包含必要的配置项
        $configFile = $pluginPath . DIRECTORY_SEPARATOR . 'config/app.php';
        if (file_exists($configFile)) {
            $config = include $configFile;
            if (!is_array($config) || !isset($config['key'])) {
                return false;
            }
        } else {
            return false;
        }

        return true;
    }

    /**
     * 从数据库获取插件信息
     *
     * 根据插件名称从数据库中获取插件信息
     *
     * @param int $id 插件名称
     * @return AdminPlugin|null 插件模型实例或null
     */
    public function getPluginById(int $id): ?AdminPlugin
    {
        return $this->modelName::query()->where('id', $id)->first();
    }

    protected function baseSettingForm(): Form
    {
        return amis()->Form()
            ->panelClassName('border-0')
            ->affixFooter()
            ->title()
            ->data(['plugin' => $this->getName()])
            ->initApi([
                'url' => admin_url('dev_tools/plugin/get_config'),
                'method' => 'POST',
                'data' => [
                    'plugin' => $this->getName(),
                ],
            ])
            ->actions([amis('submit')->label(translator('admin.save'))->level('primary')])
            ->api('post:' . admin_url('dev_tools/plugin/save_config'));
    }

    public function settingForm()
    {
        return null;
    }

    public function saveConfig(array $config): void
    {
        Admin::config()->set($this->getConfigKey(), $config);
    }

    /**
     * 配置key.
     *
     * @return string|string[]
     */
    protected function getConfigKey(): array|string
    {
        return 'plugin.' . $this->getName() . '.config';
    }

    public function config(?string $key = null, $default = null)
    {
        $config = Admin::config()->get($this->getConfigKey());

        if (is_null($key)) {
            return $config;
        }

        return Arr::get($config, $key, $default);
    }

    /**
     * 通过插件名称获取插件服务类实例
     *
     * @param string $pluginName 插件名称
     * @return object|null 插件服务类实例，如果不存在则返回null
     */
    public function getPluginServiceByName(string $pluginName): ?object
    {
        // 构造插件服务类的完整类名
        $serviceClassName = 'plugin\\' . $pluginName . '\\app\\service\\' . ucfirst($pluginName) . 'Service';

        // 检查类是否存在
        if (class_exists($serviceClassName)) {
            // 如果类存在，则创建并返回实例
            return new $serviceClassName();
        }

        // 类不存在，返回null
        return null;
    }

    /**
     * 从请求路径中提取插件名称
     *
     * 通过解析请求路径获取插件名称，用于中间件等场景
     *
     * @param string $path 请求路径
     * @return string|null 插件名称，如果无法确定则返回null
     */
    public function getPluginNameFromPath(string $path): ?string
    {
        // 匹配 /插件名称/... 的路径格式
        if (preg_match('/^\/([^\/]+)/', $path, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * 清除插件状态缓存
     *
     * 当插件启用状态变更时，清除对应的缓存以保证数据一致性
     *
     * @param string|null $pluginName 插件名称，如果为null则清除所有插件状态缓存
     * @return void
     */
    public function clearPluginCache(?string $pluginName = null): void
    {
        if ($pluginName) {
            // 清除指定插件的状态缓存
            $cacheKey = "plugin_enabled_status_" . md5($pluginName);
            Cache::flush($cacheKey);
        } else {
            // 清除所有插件状态缓存
            // 注意：在生产环境中，这种方法可能不够精确
            // 更好的做法是维护一个插件列表来逐一清除
        }
    }

    /**
     * 检查插件是否已启用
     * 
     * 使用共享缓存机制减少数据库查询次数，提高性能和一致性。
     * 
     * @param string $pluginName 插件名称
     * @return bool 插件是否已启用
     */
    public function isPluginEnabled(string $pluginName): bool
    {
        // 构造缓存键（避免使用非法字符）
        $cacheKey = "plugin_enabled_status_" . md5($pluginName);
        
        // 尝试从缓存中获取
        $isEnabled = Cache::get($cacheKey, null);
        
        // 缓存未命中
        if ($isEnabled === null) {
            // 查询数据库
            $plugin = $this->modelName::query()->where('key', $pluginName)->first();
            
            // 检查插件是否存在且已启用
            $isEnabled = $plugin && $plugin->is_enabled;
            
            // 将结果存入缓存
            Cache::put($cacheKey, $isEnabled, 60);
        }
        
        return $isEnabled;
    }
}