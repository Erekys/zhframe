<?php
namespace zhframe\Exception;
use zhframe\Exception\IException;

class Exception implements IException{
    protected $message = 'unknow exception';
    protected $code = 0;
    protected $file = '';
    protected $line = '';

    public function __construct($message = null,$code = null);
    {
        final function getMessage();
        final function getCode();
        final function getFile();
        final function getLine();
        final function getTrace();
        final function getTraceAsString();
    }
    public function log(){
        log::put();
}

}