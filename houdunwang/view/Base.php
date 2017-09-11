<?php
//主页模板加载类所在的空间
namespace houdunwang\view;
//页面加载类
class Base{
//    用于接收数据库数据
    private $data=[];
//    用于存储模板文件所在的路径
    private $file;
//    数据库数据 获得方法
    public function with($var){
//        将传入的数据库数据存到存储数据的属性中
        $this->data=$var;
//        返回一个地址,如果不反回的话链式操作会报错
        return $this;
    }
//    获得模板文件所在的完整路径方法
    public function make(){
//        获得需要显示的模板文件所在的完整路径存到属性中
        $this->file =  "../app/".MODEL."/view/".strtolower (CONTROLLER)."/".ACTION . '.php';
//        返回一个地址,如果不反回的话链式操作会报错
        return $this;
    }
//    输出一个对象的时候执行的方法
//    因为模板加载文件和数据库数据获得方法分开写了
    public function __toString()
    {
//        将获得的数据库数据数组键名作为变量,用于调用变量输出相应的数据到页面
//        键值作为变量的值
        extract($this->data);
//        加载模板
        include $this->file;
//        语法规定,必须要返回一个空字符串,否则会报错
        return '';
    }
}

?>