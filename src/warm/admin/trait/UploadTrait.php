<?php

namespace warm\admin\trait;

use Illuminate\Support\Str;
use support\Response;
use Throwable;
use warm\admin\Admin;
use warm\admin\model\system\File;
use warm\admin\service\system\FileService;
use warm\common\service\StorageService;
use warm\framework\support\facade\Storage;

trait UploadTrait
{
    /**
     * 图片上传路径
     *
     * @return string
     */
    public function uploadImagePath(): string
    {
        return admin_url('upload_image');
    }

    public function uploadImage(): Response
    {
        return $this->upload('image');
    }

    /**
     * 文件上传路径
     *
     * @return string
     */
    public function uploadFilePath(): string
    {
        return admin_url('upload_file');
    }

    public function uploadFile(): Response
    {
        return $this->upload();
    }

    /**
     * 富文本编辑器上传路径
     *
     * @param bool $needPrefix
     *
     * @return string
     */
    public function uploadRichPath(bool $needPrefix = false): string
    {
        return admin_url('upload_rich', $needPrefix);
    }

    public function uploadRich(): Response
    {
        $fromWangEditor = false;
        $file = request()->file('file');

        if (!$file) {
            $fromWangEditor = true;
            $file = request()->file('wangeditor-uploaded-image');
            if (!$file) {
                $file = request()->file('wangeditor-uploaded-video');
            }
        }

        if (!$file) {
            return $this->response()->additional(['errno' => 1])->fail(admin_trans('admin.upload_file_error'));
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

    protected function upload(): Response
    {
        $file = request()->file('file');
        if (!$file) {
            return $this->response()->fail(admin_trans('admin.upload_file_error'));
        }

        try {
            $file_info = StorageService::upload($file);
            $fileId = File::baseQuery()->insertGetId([
                'origin_name' => $file_info['origin_name'],
                'storage_mode' => $file_info['adapter'],
                'new_name' => $file_info['file_name'] . '.' . $file_info['extension'],
                'mime_type' => $file_info['mime_type'],
                'hash' => md5_file($file),
                'file_type' => $file_info['type'],
                'storage_path' => $file_info['path'],
                'file_size' => bcdiv($file_info['size'], 1024),
                'size_byte' => $file_info['size'],
                'url' => $file_info['url'],
                'created_by' => 1,
            ]);
            return $this->response()->success(['value' => $file_info['url'], 'id' => $fileId]);
        } catch (Throwable $e) {
            return $this->response()->fail($e->getMessage());
        }
    }

    public function chunkUploadStart(): Response
    {
        $uploadId = Str::uuid();

        cache()->put($uploadId, [], 600);

        appw('filesystem')->makeDirectory(base_path('public/chunk/' . $uploadId));

        return $this->response()->success(compact('uploadId'));
    }

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
            appw('filesystem')->makeDirectory($dir);
        }

        for ($i = 0; $i < count($partList); $i++) {
            $partNumber = $partList[$i]['partNumber'];
            $eTag = $partList[$i]['eTag'];

            $partPath = 'chunk/' . $uploadId . '/' . $partNumber;

            $partETag = md5(Storage::read($partPath));

            if ($eTag != $partETag) {
                return $this->response()->fail('分片上传失败');
            }

            file_put_contents($fullPath, Storage::read($partPath), FILE_APPEND);
        }

        clearstatcache();

        $value = admin_resource_full_path($path);

        appw('files')->deleteDirectory(base_path('public/chunk/' . $uploadId));

        return $this->response()->success(['value' => $value], '上传成功');
    }
}
