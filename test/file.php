<?php
/**
 * 文件类。
 * 
 * 
 */
class File {

    const PATH = '/Users/lisuxiang/Desktop/test/data';
    const SUFFIX = '.json';

    public static function write_file($path, $data, $mode = 'wb') {
        if ( ! $fp = @fopen($path, $mode)) {
            return FALSE;
        }

        flock($fp, LOCK_EX);
        fwrite($fp, $data);
        flock($fp, LOCK_UN);
        fclose($fp);

        return TRUE;
    }
    /**
     * 获取存放主目录
     *
     */
    public function get_data_path($subPath) {
        $today = date('Ymd', time());
        $path = self::PATH . '/' . $today . '/' . $subPath . '/' ;
        return $path;
    }

    /**
     * 根据文件名md5后生成子目录
     *
     */
    public function gen_sub_path($file) {
        $pathinfo = array();
        $filename = basename($file, self::SUFFIX);
        return substr(md5($filename), 0, 2);
    }

    /**
     * 存放原始数据
     *
     */
    public function putSourceFile($filename, $directory, $data, $mode = 'wb') {
        $flag = false;
        if ( ! is_dir($directory)) {
            $flag = mkdir($directory, 0777, true);
            if ( ! $flag) {
                $this->writeLog('create Directory failed: '.$directory, 'put_data');
                return false;
            }
        }

        $file = rtrim($directory, '/') . '/' . $filename;
        $flag = $this->write_file($file, $data, $mode);
        if ( ! $flag) {
            echo 'Write file contents failed: '. $file;
            return false;
        }

        return true;
    }
}

?>
