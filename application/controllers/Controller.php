<?php
/**
 * Created by PhpStorm.
 * User: kali
 * Date: 2017/10/19
 * Time: 14:52
 */
class Controller extends Controller{
    public function __construct($controller, $action)
    {
        parent::__construct($controller, $action);
    }

public index(){
echo 123;
}
}