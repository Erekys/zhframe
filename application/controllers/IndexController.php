<?php
/**
 * Created by PhpStorm.
 * User: kali
 * Date: 2017/10/19
 * Time: 11:27
 */
class IndexController extends Controller{
    public function __construct($controller, $action)
    {
        parent::__construct($controller, $action);
    }

public function  index(){
}
public function test(){
    echo 'xxxx';
}
}