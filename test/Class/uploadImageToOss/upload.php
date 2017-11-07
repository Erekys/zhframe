<?php
ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL ^ E_NOTICE);

class uploadImageToOss {

    private $oss;

    private $uploadConfig;

    public function __construct() {
        require_once 'lib/ossAli.class.php';
        $this->oss = new Oss_Ali();
    }

    public function startUpload() {
        try {
            $this->uploadConfig = $this->loadUploadConfig();
            $this->traversalPath($this->uploadConfig['imagePath']);
        } catch (ErrorException $error) {
            echo "错误:" . $error->getMessage();
        }
    }

    private function loadUploadConfig() {
        require_once 'config.php';
        $config['imagePath'] = IMAGE_PATH;
        $config['imageFinishPath'] = IMAGE_PATH_FINISH;
        $config['mapFile'] = MAP_FILE;
        $config['errorLog'] = ERROR_LOG;
        $config['uploadOssPath'] = UPLOAD_OSS_PATH ? UPLOAD_OSS_PATH : 'yp/';
        if (strrchr($config['uploadOssPath'], '/') != '/') {
            $config['uploadOssPath'] .= "/";
        }
        $config['filterFileList'] = $filterFile ? $filterFile : array(
            '.',
            '..',
            '.DS_Store'
        );
        $config['allowExt'] = $allowExt ? $allowExt : array(
            'jpg',
            'png'
        );
        $config['imageFindDep'] = IMAGE_FIND_DEP ? IMAGE_FIND_DEP : 5;
        if (empty($config['imagePath'])) {
            throw new ErrorException('未设置上传图片路径');
        }
        if (empty($config['mapFile'])) {
            throw new ErrorException('未设置映射保存文件');
        }
        if (! is_dir($config['imagePath'])) {
            throw new ErrorException('上传图片路径必须为文件夹');
        }
        return $config;
    }

    private function traversalPath($uploadPath, $dep = 1) {
        if ($dep >= $this->uploadConfig['imageFindDep']) {
            return;
        }
        $dep ++;
        if (false != ($handle = opendir($uploadPath . '/'))) {
            while (false !== ($file = readdir($handle))) {
                if (! in_array($file, $this->uploadConfig['filterFileList'])) {
                    if (is_dir($uploadPath . "/" . $file)) {
                        $this->traversalPath($uploadPath . "/" . $file, $dep);
                    } else {
                        $this->uploadFile($uploadPath . "/" . $file);
                    }
                }
            }
            closedir($handle);
        } else {
            throw new ErrorException("图片路径:{$uploadPath}打开失败");
        }
    }

    private function uploadFile($uploadFile) {
        if (! file_exists($uploadFile)) {
            $this->writeStringToFile('File:' . $uploadFile . " => Not exists", $this->uploadConfig['errorLog']);
            return false;
        }
        $extension = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
        $allowExt = array_change_key_case($this->uploadConfig['allowExt'], CASE_LOWER);
        if (! in_array($extension, $allowExt)) {
            $this->writeStringToFile('File:' . $uploadFile . " => Not in allow extension list", $this->uploadConfig['errorLog']);
            return false;
        }
        $rand = md5(time() . mt_rand(1111, 9999));
        $uploadName = $this->uploadConfig['uploadOssPath'] . date('Ym') . '/' . date('d') . '/' . $rand . '.' . $extension;
        $uploadKey = str_replace('/', '_', $this->uploadConfig['uploadOssPath']) . date('Ym') . '_' . date('d') . '_' . $rand . '.' . $extension;
        $response = $this->oss->upload_file_by_file('ypimg', $uploadName, $uploadFile);
        if ($response->status == 200) {
            $this->writeStringToFile($uploadFile . "=" . $uploadKey, $this->uploadConfig['mapFile']);
            $fileName = pathinfo($uploadFile, PATHINFO_BASENAME);
            $newFile = str_replace($this->uploadConfig['imagePath'], $this->uploadConfig['imageFinishPath'], $uploadFile);
            $newPath = str_replace($fileName, '', $newFile);
            $this->CreateServerFolder($newPath);
            if (! rename($uploadFile, $newFile)) {
                $this->writeStringToFile('File:' . $uploadFile . " => Upload success but move file fail", $this->uploadConfig['errorLog']);
            }
            return true;
        } else {
            $this->writeStringToFile('File:' . $uploadFile . " => Upload fail", $this->uploadConfig['errorLog']);
            return false;
        }
    }

    private function writeStringToFile($string, $path) {
        $string .= "\r\n";
        if (! $fp = @fopen($path, "a+")) {
            exit("文件打开失败");
        }
        // 加锁写入数据到文件尾部
        flock($fp, LOCK_EX);
        fwrite($fp, $string);
        // 解锁关闭文件
        flock($fp, LOCK_UN);
        fclose($fp);
        return true;
    }

    private function GetParentFolder($folderPath) {
        $sPattern = "-[/\\\\][^/\\\\]+[/\\\\]?$-";
        return preg_replace($sPattern, '', $folderPath);
    }

    private function CreateServerFolder($folderPath, $lastFolder = null) {
        $sParent = $this->GetParentFolder($folderPath);
        $sErrorMsg = '';
        
        // 去掉路径中的双斜线，否则在特定的系统上会造成mkdir失败
        while (strpos($folderPath, '//') !== false) {
            // $folderPath = strtr($folderPath, '//', '/');
            $folderPath = str_replace('//', '/', $folderPath);
        }
        
        // 检查上级目录是否存在，如果不存在则主动创建
        if (! file_exists($sParent)) {
            // 避免在无法创建根目录时发生无限循环
            if (! is_null($lastFolder) && $lastFolder === $sParent) {
                // return "Can't create $folderPath directory" ;
                return false;
            }
            
            $sErrorMsg = self::CreateServerFolder($sParent, $folderPath);
            if (! $sErrorMsg) {
                return false;
            }
        }
        
        if (! file_exists($folderPath)) {
            // 关闭错误报告
            error_reporting(0);
            $php_errormsg = '';
            // 开启错误跟踪以便记录错误
            ini_set('track_errors', '1');
            
            $permissions = 0777;
            $oldumask = umask(0);
            $result = mkdir($folderPath, $permissions);
            umask($oldumask);
            
            // 充值系统设置
            ini_restore('track_errors');
            ini_restore('error_reporting');
            
            return $result;
        } else {
            return true;
        }
    }
}

$uploadModel = new uploadImageToOss();
$uploadModel->startUpload();
