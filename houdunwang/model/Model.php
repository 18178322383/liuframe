<?php
//数据库跳板模型所在的空间
namespace houdunwang\model;
//数据库链接跳板
class Model{
//    静态调用或者非静态调用都可以
//    如果非静态数据库的方法不存在的时候执行的方法
    public function __call($name, $arguments)
    {
//        调用实例化数据库操作的方法
//        $name是本类中不存在的方法,arguments是参数
        return self::parseAction($name,$arguments);
    }
//    如果是静态调用数据库方法的时候执行的方法
    public static function __callStatic($name, $arguments)
    {
//        调用实例化数据库操作的方法
//        $name是本类中不存在的方法,arguments是参数
        return self::parseAction($name,$arguments);
    }
//    跳板实例化数据库方法
    private static function parseAction($name,$arguments){
//        获得上一次实例化的类,对应的是数据库的表名
        $table = get_called_class();
//        实例化数据库操作类,name是方法,arguments是传入的参数
        return call_user_func_array([new Base($table),$name],$arguments);
    }
}

?>