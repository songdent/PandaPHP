<?php
/**
 * +----------------------------------------------------------------
 * + pandaphp [WE LOVE PANDA, WE LOVE PHP]
 * +----------------------------------------------------------------
 * + Copyright (c) 2015 http://www.pandaphp.com All rights reserved.
 * +----------------------------------------------------------------
 * + Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 * +----------------------------------------------------------------
 * + Author songdengtao <http://www.songdengtao.cn/>
 * +----------------------------------------------------------------
 */
namespace pandaphp\sysfile;

/**
 * 文件上传
 * @author songdengtao <http://www.songdengtao.cn>
 */
class Upload
{
    /**
     * 上传的根目录路径
     * @var string
     */
    protected $uploadPath = '';

    /**
     * 允许上传的文件MiMe类型
     * @var array
     */
    protected $mimes = [];

    /**
     * 允许上传的图片后缀
     * @var array
     */
    protected $exts = [];

    /**
     * 上传的文件大小限制 (0-不做限制)
     * @var int
     */
    protected $maxSize = 0;

    /**
     * 自动子目录保存文件
     * @var boolean
     */
    protected $autoSub = true;

    /**
     * 子目录名称创建方式
     * @var array [0]-函数名，[1]-参数
     */
    protected $nameSub = ['date', 'Y-m-d'];

    /**
     * 文件保存的路径
     * @var string
     */
    protected $savePath = '';

    /**
     * 文件保存的后缀，空表示使用原后缀
     * @var string
     */
    protected $saveExt = '';

    /**
     * 文件保存的名称
     * @var array 传文件命名规则，[0]-函数名，[1]-参数
     */
    protected $saveName = ['uniqid', ''];

    /**
     * 存在同名的是否覆盖
     * @var boolean
     */
    protected $overwrite = false;

    /**
     * 构造函数
     * @access public
     * @param  string $type 上传类型
     * @author songdengtao <http://www.songdengtao.cn>
     */
    public function __construct($type = 'image')
    {
        $this->uploadPath = \Pandaphp::get('uploadPath');
        $type             = strtolower($type);
        switch ($type) {
            case 'image':
                $this->_SetDefaultConfigOfUploadingImage();
                break;
            case 'media':
                $this->_SetDefaultConfigOfUploadingMedia();
                break;
            default:
                $this->_SetDefaultConfigOfUploadingFile();
                break;
        }
    }

    /**
     * 设置属性
     * @access public
     * @param  string $name
     * @param  mixed $value
     * @author songdengtao <http://www.songdengtao.cn>
     * @return Upload
     */
    public function set($name = '', $value)
    {
        if (isset($this->$name)) {
            $this->$name = $value;
        }
        return $this;
    }

    /**
     * 获取属性
     * @access public
     * @param  string $name
     * @author songdengtao <http://www.songdengtao.cn>
     * @return mixed
     */
    public function get($name)
    {
        return isset($this->$name) ? $this->$name : null;
    }

    /**
     * 设置图片上传的默认配置
     * @access public
     * @author songdengtao <http://www.songdengtao.cn>
     * @return Pandaphp
     */
    public function _SetDefaultConfigOfUploadingImage()
    {
        $this->mimes     = \Pandaphp::shell('Config::get', 'image_upload_mimes');
        $this->exts      = \Pandaphp::shell('Config::get', 'image_upload_exts');
        $this->maxSize   = \Pandaphp::shell('Config::get', 'image_upload_max_size');
        $this->autoSub   = \Pandaphp::shell('Config::get', 'image_upload_auto_sub');
        $this->nameSub   = \Pandaphp::shell('Config::get', 'image_upload_name_sub');
        $this->savePath  = $this->uploadPath . \Pandaphp::shell('Config::get', 'image_upload_save_dir') . '/';
        $this->saveName  = \Pandaphp::shell('Config::get', 'image_upload_save_name');
        $this->overwrite = \Pandaphp::shell('Config::get', 'image_upload_overwrite');
    }

    /**
     * 设置多媒体文件上传的默认配置
     * @access public
     * @author songdengtao <http://www.songdengtao.cn>
     * @return void
     */
    public function _SetDefaultConfigOfUploadingMedia()
    {

        $this->mimes     = \Pandaphp::shell('Config::get', 'media_upload_mimes');
        $this->exts      = \Pandaphp::shell('Config::get', 'media_upload_exts');
        $this->maxSize   = \Pandaphp::shell('Config::get', 'media_upload_max_size');
        $this->autoSub   = \Pandaphp::shell('Config::get', 'media_upload_auto_sub');
        $this->nameSub   = \Pandaphp::shell('Config::get', 'media_upload_name_sub');
        $this->savePath  = $this->uploadPath . \Pandaphp::shell('Config::get', 'media_upload_save_dir') . '/';
        $this->saveName  = \Pandaphp::shell('Config::get', 'media_upload_save_name');
        $this->overwrite = \Pandaphp::shell('Config::get', 'media_upload_overwrite');
    }

    /**
     * 设置非图片和多媒体文件上传的默认配置
     * @access public
     * @author songdengtao <http://www.songdengtao.cn>
     * @return void
     */
    public function _SetDefaultConfigOfUploadingFile()
    {
        $this->mimes     = \Pandaphp::shell('Config::get', 'file_upload_mimes');
        $this->exts      = \Pandaphp::shell('Config::get', 'file_upload_exts');
        $this->maxSize   = \Pandaphp::shell('Config::get', 'file_upload_max_size');
        $this->autoSub   = \Pandaphp::shell('Config::get', 'file_upload_auto_sub');
        $this->nameSub   = \Pandaphp::shell('Config::get', 'file_upload_name_sub');
        $this->savePath  = $this->uploadPath . \Pandaphp::shell('Config::get', 'file_upload_save_dir') . '/';
        $this->saveName  = \Pandaphp::shell('Config::get', 'file_upload_save_name');
        $this->overwrite = \Pandaphp::shell('Config::get', 'file_upload_overwrite');
    }

    /**
     * 上传单张文件
     * @access public
     * @param array $imageFile $_FILES[filename]
     * @author songdengtao <http://www.songdengtao.cn>
     * @return array
     */
    public function uploadOne(array $imageFile = [])
    {
        if (empty($imageFile)) {
            return ['stat' => 4, 'msg' => '请选择文件'];
        }

        $checkFileErrorRes = $this->_CheckFileError($imageFile['error']);
        if ($checkFileErrorRes['stat'] !== 0) {
            return $checkFileErrorRes;
        }

        //原文件名
        $fileName = strip_tags($imageFile['name']);
        //服务器上临时文件名
        $tempName = $imageFile['tmp_name'];
        //文件大小
        $fileSize = $imageFile['size'];

        //检查文件名
        if (!$fileName) {
            return ['stat' => 4, 'msg' => '请选择文件'];
        }

        // 检查上传的目录是否存在
        if (!@is_dir($this->savePath)) {
            return ['stat' => 9, 'msg' => '上传目录不存在'];
        }

        //检查目录写权限
        if (!@is_writable($this->savePath)) {
            return ['stat' => 10, 'msg' => '上传目录没有写权限'];
        }

        //检查是否已上传
        if (!@is_uploaded_file($tempName)) {
            return ['stat' => 8, 'msg' => '文件上传失败'];
        }

        //检查文件大小
        if ($this->maxSize !== 0 && $fileSize > $this->maxSize) {
            return ['stat' => 11, 'msg' => '上传文件大小超过系统限制'];
        }

        //获得文件扩展名
        $tempArr = explode(".", $fileName);
        $fileExt = strtolower(trim(array_pop($tempArr)));

        //检查扩展名
        if (!in_array($fileExt, $this->exts)) {
            return ['stat' => 12, 'msg' => '上传文件扩展名是不允许的扩展名:' . $fileExt];
        }

        // 检查mime
        $mime = \Pandaphp::shell('Image::getFileMimeType', $tempName);
        if (!in_array($mime, $this->mimes)) {
            return ['stat' => 13, 'msg' => '文件的MIME是不允许的MIME:' . $mime];
        }

        //创建文件夹
        if ($this->autoSub) { // 子目录保存
            $subDirName = date('Ymd');
            if (function_exists($this->nameSub[0])) {
                $subDirName = $this->nameSub[0]($this->nameSub[1]);
            }
            $dirpath = $this->savePath . $subDirName . '/';
        } else {
            $dirpath = $this->savePath;
        }

        if (!\Pandaphp::shell('Dir::mkdir', $dirpath, true)) {
            return ['stat' => 14, 'msg' => '创建文件保存目录和子目录失败，请检查权限或者手动创建'];
        }

        // 创建新的文件
        if ($this->saveExt) {
            $fileExt = $this->saveExt;
        }

        $newFileName = date("YmdHis") . '_' . rand(10000, 99999);
        if (function_exists($this->saveName[0])) {
            $newFileName = $this->saveName[0]($this->saveName[1]);
        }

        $newFile = $newFileName . '.' . $fileExt;
        // 如果重名
        if (file_exists($newFile) && !$this->overwrite) {
            $newFile = $newFileName . '_' . rand(10000, 99999) . $fileExt;
        }

        //移动文件
        $newFilePath = $dirpath . $newFile;
        if (move_uploaded_file($tempName, $newFilePath) === false) {
            return ['stat' => 8, 'msg' => '文件上传失败'];
        }
        @chmod($newFilePath, 0644);

        return [
            'stat' => 0,
            'msg'  => '文件上传成功',
            'data' => [
                'name' => $newFileName,
                'ext'  => $fileExt,
                'size' => $fileSize,
                'path' => $newFilePath,
                'src'  => str_replace(\Pandaphp::get('webroot'), '/', $newFilePath)
            ]
        ];

    }

    /**
     * 上传全部文件
     * @access public
     * @param array $imageFiles $_FILES[filename]
     * @author songdengtao <http://www.songdengtao.cn>
     * @return array
     */
    public function uploadAll(array $imageFiles = [])
    {
        return [];
    }

    /**
     * 检查上传的文件错误
     * @access public
     * @param integer $fileElementErrno 类似$_FILES['imgFile']['error']
     * @author songdengtao <http://www.songdengtao.cn>
     * @return array
     */
    private function _CheckFileError($fileElementErrno = 0)
    {
        $stat  = 0;
        $error = '';
        if (!empty($fileElementErrno)) {
            switch ($fileElementErrno) {
                case '1':
                    $stat  = 1;
                    $error = '超过php配置允许的大小';
                    break;
                case '2':
                    $stat  = 2;
                    $error = '超过表单中MAX_FILE_SIZE的大小';
                    break;
                case '3':
                    $stat  = 3;
                    $error = '文件只有部分被上传';
                    break;
                case '4':
                    $stat  = 4;
                    $error = '请选择文件';
                    break;
                case '5':
                    $stat  = 5;
                    $error = '上传文件大小为0';
                    break;
                case '6':
                    $stat  = 6;
                    $error = '找不到临时目录';
                    break;
                case '7':
                    $stat  = 7;
                    $error = '写文件到硬盘出错';
                    break;
                case '8':
                    $stat  = 8;
                    $error = '文件上传失败';
                    break;
                case '999':
                default:
                    $stat  = -1;
                    $error = '未知错误';
            }
        }

        return ['stat' => $stat, 'msg' => $error];
    }
}