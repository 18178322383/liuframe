<?php
//友情提示页面类所在的空间
namespace houdunwang\core;
//友情提示类
class Controller{
//    将提示内容做成属性,类全局调用
    private $message;
//    把要跳转的地址做成属性,类里面全局能调用
    private $url;
    public function message($message){
//        加载提示页面,输出提示内容
        include 'view/message.php';
        exit;
    }
//    跳转地址方法
    public function setRedirect($url=''){
//        判断是否输入需要跳转的链接
        if(empty($url)){
//            如果没有数据链接,默认返回上一个页面
            $this->url="history.back()";
        }else{
//            否则跳转到指定的页面
            $this->url="location.href={$url}";
        }
        return $this;
    }
}
?>