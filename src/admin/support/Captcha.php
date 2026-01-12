<?php

namespace warm\admin\support;

/**
 * 验证码生成类
 * 
 * 该类用于生成图形验证码，包含以下功能：
 * 1. 创建指定尺寸的验证码图片
 * 2. 生成随机验证码字符串
 * 3. 添加干扰元素防止机器识别
 * 4. 输出Base64编码的PNG图片
 * 
 * 验证码图片使用Facon-2.ttf字体文件进行渲染。
 */
class Captcha
{
    /**
     * 验证码图片宽度
     * 
     * @var mixed
     */
    private mixed $width;
    
    /**
     * 验证码图片高度
     * 
     * @var mixed
     */
    private mixed $height;
    
    /**
     * 验证码字符数量
     * 
     * @var mixed
     */
    private mixed $codeNum;
    
    /**
     * 生成的验证码字符串
     * 
     * @var string
     */
    private string $code;
    
    /**
     * 图片资源句柄
     * 
     * @var resource
     */
    private $im;
    
    /**
     * 字体文件路径
     * 
     * @var string
     */
    private string $font;

    /**
     * 构造函数
     * 
     * 初始化验证码生成器的参数：
     * 1. 设置图片尺寸（默认100x40）
     * 2. 设置验证码字符数量（默认4个字符）
     * 3. 设置字体文件路径
     * 
     * @param int $width 验证码图片宽度，默认100像素
     * @param int $height 验证码图片高度，默认40像素
     * @param int $codeNum 验证码字符数量，默认4个字符
     */
    public function __construct(int $width = 100, int $height = 40, int $codeNum = 4)
    {
        $this->width = $width;
        $this->height = $height;
        $this->codeNum = $codeNum;
        $this->font = __DIR__ . '/Facon-2.ttf';
    }

    /**
     * 显示验证码图片
     * 
     * 按顺序执行以下操作生成验证码图片：
     * 1. 创建图片资源
     * 2. 添加干扰元素
     * 3. 设置验证码字符
     * 4. 输出Base64编码的PNG图片
     * 
     * @return string Base64编码的PNG图片数据
     */
    public function showImg(): string
    {
        //创建图片
        $this->createImg();
        //设置干扰元素
        $this->setDisturb();
        //设置验证码
        $this->setCaptcha();
        //输出图片
        return $this->outputImg();
    }

    /**
     * 获取验证码字符串
     * 
     * 返回生成的验证码字符串，用于验证用户输入
     * 
     * @return string 验证码字符串
     */
    public function getCaptcha(): string
    {
        return $this->code;
    }

    /**
     * 创建图片资源
     * 
     * 创建指定尺寸的真彩色图片，并填充白色背景
     * 
     * @return void
     */
    private function createImg(): void
    {
        // 创建真彩色图片资源
        $this->im = imagecreatetruecolor($this->width, $this->height);
        // 分配白色背景色
        $bgColor = imagecolorallocate($this->im, 255, 255, 255);
        // 填充背景色
        imagefill($this->im, 0, 0, $bgColor);
    }

    /**
     * 设置干扰元素
     * 
     * 在图片上添加干扰字符，防止机器自动识别验证码
     * 
     * @return void
     */
    private function setDisturb(): void
    {
        // 干扰字符可选字符集
        $codeSet = '2345678abcdefhijkmnpqrstuvwxyz';
        // 添加10组干扰元素
        for ($i = 0; $i < 10; $i++) {
            //杂点颜色
            $noiseColor = imagecolorallocate($this->im, mt_rand(150, 180), mt_rand(150, 180), mt_rand(150, 180));
            // 每组添加5个干扰字符
            for ($j = 0; $j < 5; $j++) {
                // 添加干扰字符
                imagettftext($this->im,
                    6,                           // 字体大小
                    mt_rand(-30, 30),           // 随机角度
                    mt_rand(-10, $this->width), // 随机X坐标
                    mt_rand(-10, $this->height),// 随机Y坐标
                    $noiseColor,                // 颜色
                    $this->font,                // 字体文件
                    $codeSet[mt_rand(0, 29)]    // 随机字符
                );
            }
        }
    }

    /**
     * 创建验证码字符串
     * 
     * 从指定字符集中随机生成指定数量的字符组成验证码
     * 
     * @return void
     */
    private function createCode(): void
    {
        // 验证码可选字符集（去除了容易混淆的字符如0、O、1、l等）
        $str = "23456789abcdefghijkmnpqrstuvwxyzABCDEFGHIJKMNPQRSTUVWXYZ";

        // 生成指定数量的随机字符
        for ($i = 0; $i < $this->codeNum; $i++) {
            $this->code .= $str[rand(0, strlen($str) - 1)];
        }
    }

    /**
     * 设置验证码字符
     * 
     * 在图片上绘制验证码字符：
     * 1. 生成验证码字符串
     * 2. 计算每个字符的位置和颜色
     * 3. 使用TTF字体绘制字符
     * 
     * @return void
     */
    private function setCaptcha(): void
    {
        // 生成验证码字符串
        $this->createCode();

        // 绘制每个验证码字符
        for ($i = 0; $i < $this->codeNum; $i++) {
            // 为每个字符分配随机颜色
            $color = imagecolorallocate($this->im, rand(0, 150), rand(0, 150), rand(0, 150));
            // 计算字符X坐标位置
            $x = floor($this->width / $this->codeNum) * $i + 3;
            // 随机Y坐标位置
            $y = rand(16, $this->height - 5);
            // 使用TTF字体绘制字符（字体大小16，随机角度）
            imagettftext($this->im, 16, rand(-30, 30), $x, $y, $color, $this->font, $this->code[$i]);
        }
    }

    /**
     * 输出图片
     * 
     * 将图片资源转换为Base64编码的PNG数据：
     * 1. 将图片保存到临时文件
     * 2. 读取临时文件内容
     * 3. 编码为Base64格式
     * 4. 删除临时文件
     * 5. 返回Base64数据URI
     * 
     * @return string Base64编码的PNG图片数据URI
     */
    private function outputImg(): string
    {
        // 创建临时文件路径
        $tempPath = tempnam(sys_get_temp_dir(), 'temp');
        $base64 = '';
        // 将图片资源输出到临时文件
        imagepng($this->im, $tempPath);
        // 读取临时文件内容
        if ($fp = fopen($tempPath, "rb", 0)) {
            $tempFile = fread($fp, filesize($tempPath));
            fclose($fp);

            // 编码为Base64
            $base64 = base64_encode($tempFile);
            // 删除临时文件
            @unlink($tempPath);
        }

        // 返回Base64数据URI
        return 'data:image/png;base64,' . $base64;
    }

}