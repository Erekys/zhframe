<?php
/**
 * Created by PhpStorm.
 * User: kali
 * Date: 2017/10/14
 * Time: 13:42
 */
/**
 * 控制器基类
 */
namespace zhframe\controller;
use zhframe\Request\Request();
use zhframe\validate\validate();
class Controller
{
    protected $_controller;
    protected $_action;
    protected $_view;
    // 构造函数，初始化属性，并实例化对应模型
    function __construct($controller, $action)
    {
        $this->_controller = $controller;
        $this->_action = $action;
        $this->_view = new View($controller, $action);
    }
    // 分配变量
    function assign($name, $value)
    {
        $this->_view->assign($name, $value);
    }
    // 渲染视图
    function __destruct()
    {
        $this->_view->render();
    }
}