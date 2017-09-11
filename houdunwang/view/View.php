<?php
//模板加载跳板所在的空间
namespace houdunwang\view;
//模板加载方法跳板
class View{
//    不管普通调用还是静态调用都可以
//    普通调用的方法不存在的时候执行
    public function __call($name, $arguments)
    {
//        调用模板加载类,$arguments是传递的数据库数据
//        $name是调用在本类不存在的方法名
        return self::parseAction($name,$arguments);
    }
//    静态调用的方法不存在的时候执行
    public static function __callStatic($name, $arguments)
    {
//        调用模板加载类,$arguments是传递的数据库数据
//        $name是调用在本类不存在的方法名
        return self::parseAction($name,$arguments);
    }
//    实例化调用模板加载方法的方法
    private static function parseAction($name,$arguments){
//        实例化base类,主页模板类
//        name是类里面的方法
//        arguments是数据库的数据
        return call_user_func_array([new Base(),$name],$arguments);
    }
}

?>