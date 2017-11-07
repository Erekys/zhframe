<?php
/**
 * Created by PhpStorm.
 * User: kali
 * Date: 2017/11/2
 * Time: 14:24
 */
namespace zhframe\Log;
use zhframe\Log\config;
class Log{
    protected $path;
    protected $message;

    function __construct()
    {
        $conf = config::get('option',log);
        $this->path = $conf['path'];
    }
    function log(,$message,$file){
        if(!is_dir($this->path.date('YmdH'))){
            mkdir($this->path.date('YmdH'),'0777',true);
        }
        return file_put_contents($this->path.date('YmdH').'/'.$file.'.log',date('Y-m-d H:i:s').json_encode($message).PHP_EOL,FILE_APPEND);
    }
    }

}