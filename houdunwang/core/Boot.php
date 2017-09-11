<?php
//Boot类所在的空间
namespace houdunwang\core;
//初始化类
class Boot{
//    初始化方法
    public static function run(){
//        报错提示页面方法
        self::handler();
//        初始化方法,基本设置
        self::inti();
//        根据地址栏参数默认加载的方法
//        变量分配
        self::appRun();
    }
//    报错提示页面
    private static function handler(){
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $whoops->register();
    }
//    初始化设置
    private static function inti(){
//        设置编码
//        防止页面乱码
        header('Content-type:text/html;charset=utf8');
//        设置时区
//        防止时区错误或者没设置的时候报错或者时间不正确
        date_default_timezone_set('PRC');
//        开启session
//        判断session_id是否存在,如果存在那么就不会重复开启session
        session_id()||session_start();
    }
//    初始化完成后需要执行的方法
//    方法调用页面加载数据库链接测试类
    private static function appRun(){
//        判断地址栏是否有参数,如果没有参数会走else体,加载默认的模板
        if(isset($_GET['s'])){
//            将地址栏的参数用/分割成数组,数组第一个值是模块,参数二是要调用的类名,参数三是调用类里面的方法
            $info=explode('/',$_GET['s']);
//            设置三个常量,用于模板的加载,拼接路径的时候使用
//            模板文件所在的目录跟地址栏的参数对应
//            模块
            define('MODEL',$info[0]);
//            控制器
            define('CONTROLLER',$info[1]);
//            类方法,对应的文件名
            define('ACTION',$info[2]);
//            拼接出下个调用的类所在的空间位置
            $class = 'app\\'.$info[0].'\controller\\'.ucfirst($info[1]);
//            类里面的方法
            $action=$info[2];
        }else{
//            如果地址栏没有参数,默认需要加载的类
            $class = 'app\home\controller\Entry';
//            默认模块
//            用于加载模板文件
            define('MODEL','home');
//            默认模板文件所在的目录
            define('CONTROLLER','entry');
//            要加载的文件名
            define('ACTION','index');
//            默认加载的方法
            $action = 'index';
        }
//        实例化类,调用里面的index方法
//        因为tostring方法需要输出对象的时候执行,所以必须echo
        echo call_user_func_array([new $class,$action],[]);
    }
}
?>