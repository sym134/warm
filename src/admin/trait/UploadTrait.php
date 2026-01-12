<?php

namespace warm\admin\trait;

use warm\framework\filesystem\facade\Storage;
use Illuminate\Support\Str;
use support\Request;
use support\Response;
use Throwable;
use warm\admin\model\system\SystemFile;
use warm\common\service\StorageService;

/**
 * 上传Trait
 *
 * 提供文件上传功能，包括图片上传、文件上传、富文本编辑器上传等
 * 支持普通上传和分片上传两种方式
 */
trait UploadTrait
{
    /**
     * 图片上传路径
     *
     * @return string 图片上传的API路径
     */
    public function uploadImagePath(): string
    {
        return admin_url('upload_image');
    }

    /**
     * 图片上传处理
     *
     * @return Response 响应对象
     */
    public function uploadImage(Request $request): Response
    {
        try {
            return $this->response()->success($this->systemFileUpload($request->file('file')));
        }catch (Throwable $e){
            return $this->response()->fail($e->getMessage());
        }
    }

    /**
     * 文件上传路径
     *
     * @return string 文件上传的API路径
     */
    public function uploadFilePath(): string
    {
        return admin_url('upload_file');
    }

    /**
     * 文件上传处理
     *
     * @return Response 响应对象
     */
    public function uploadFile(Request $request): Response
    {
        try {
            return $this->response()->success($this->systemFileUpload($request->file('file')));
        }catch (Throwable $e){
            return $this->response()->fail($e->getMessage());
        }
    }

    /**
     * 富文本编辑器上传路径
     *
     * @param bool $needPrefix 是否需要添加前缀
     *
     * @return string 富文本编辑器上传的API路径
     */
    public function uploadRichPath(bool $needPrefix = false): string
    {
        return admin_url('upload_rich', $needPrefix);
    }

    /**
     * 富文本编辑器上传处理
     *
     * @return Response 响应对象
     */
    public function uploadRich(): Response
    {
        $fromWangEditor = false;
        $file = request()->file('file');

        if (!$file) {
            $fromWangEditor = true;
            $file = request()->file('wangeditor-uploaded-image');
            if (!$file) {
                $file = request()->file('wangeditor-uploaded-video');
                if (!$file) {
                    return $this->response()->additional(['errno' => 1])->fail(translator('admin.upload_file_error'));
                }
            }
        }

        try {
            $file_info = StorageService::upload($file, 'rich');
        } catch (Throwable $e) {
            return $this->response()->fail($e->getMessage());
        }

        $link = $file_info['url'];

        if ($fromWangEditor) {
            return $this->response()->additional(['errno' => 0])->success(['url' => $link]);
        }

        return $this->response()->additional(compact('link'))->success(compact('link'));
    }

    /**
     * 文件上传处理
     * @param Request $request
     * @return Response
     */
    protected function upload(Request $request): Response
    {
        $file = $request->file('file');
        if (!$file) {
            return $this->response()->fail(translator('admin.upload_file_error'));
        }

        try {
            return $this->response()->success($this->systemFileUpload($file));
        } catch (Throwable $e) {
            return $this->response()->fail($e->getMessage());
        }
    }

    public function systemFileUpload($file): array
    {
        $file_info = StorageService::upload($file);
        $fileId = SystemFile::baseQuery()->insertGetId([
            'origin_name' => $file_info['origin_name'],
            'storage_mode' => $file_info['adapter'],
            'new_name' => $file_info['file_name'],
            'mime_type' => $file_info['mime_type'],
            'hash' => md5_file($file),
            'file_type' => $file_info['type'],
            'storage_path' => $file_info['path'],
            'file_size' => bcdiv($file_info['size'], 1024),
            'size_byte' => $file_info['size'],
            'url' => $file_info['url'],
            'created_by' => 1,
        ]);
        return ['value' => $file_info['path'], 'id' => $fileId];
    }

    /**
     * 分片上传开始
     *
     * @return Response 响应对象
     */
    public function chunkUploadStart(): Response
    {
        $uploadId = Str::uuid();

        cache()->put($uploadId, [], 600);

        Storage::makeDirectory(base_path('public/chunk/' . $uploadId));

        return $this->response()->success(compact('uploadId'));
    }

    /**
     * 分片上传处理
     *
     * @return Response 响应对象
     */
    public function chunkUpload(): Response
    {
        $uploadId = request()->input('uploadId');
        $partNumber = request()->input('partNumber');
        $file = request()->file('file');

        $path = 'chunk/' . $uploadId;

        try {
            $file_info = StorageService::upload($file, $path, $partNumber);
            $eTag = md5($file_info['path']);
            return $this->response()->success(compact('eTag'));
        } catch (Throwable $e) {
            return $this->response()->fail($e->getMessage());
        }
    }

    /**
     * 分片上传完成处理
     *
     * @return Response 响应对象
     */
    public function chunkUploadFinish(): Response
    {
        $fileName = request()->file('file_name');
        $partList = request()->input('partList');
        $uploadId = request()->input('uploadId');
        $type = request()->input('t');

        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
        $path = $type . '/' . $uploadId . '.' . $ext;
        $fullPath = base_path('public/' . $path);

        $dir = dirname($fullPath);
        if (!is_dir($dir)) {
            Storage::makeDirectory($dir);
        }

        for ($i = 0; $i < count($partList); $i++) {
            $partNumber = $partList[$i]['partNumber'];
            $eTag = $partList[$i]['eTag'];

            $partPath = 'chunk/' . $uploadId . '/' . $partNumber;

            $partETag = md5(Storage::get($partPath));

            if ($eTag != $partETag) {
                return $this->response()->fail('分片上传失败');
            }

            file_put_contents($fullPath, Storage::get($partPath), FILE_APPEND);
        }

        clearstatcache();

        $value = admin_resource_full_path($path);

        Storage::deleteDirectory('chunk/' . $uploadId);

        return $this->response()->success(['value' => $value], '上传成功');
    }
}