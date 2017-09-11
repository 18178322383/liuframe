<?php
//Erytry类所在的空间
namespace app\home\controller;
//继承的controller类所在的空间
use houdunwang\core\Controller;
//调用模板类的时候,模板类所在的空间
use houdunwang\view\View;
//调用数据库链接类的时候,数据库类所在的位置
use system\model\Article;
//测试类
class Entry extends Controller {
//    模板加载,数据库链接测试方法
    public function index(){
//        测试以主键查找一条数据
//        Article::find(1)->toArray();
//        测试以主键查看一条数据,并且输出需要的数据
//        $data = Article::field('name,age')->find(1)->toArray();
//        获得数据库表里面的所有数据
//        $data = Article::field()->getAll()->toArray();
//        删除数据库数据测试
//        $data = Article::where('sid>13')->destroy();
//        $date=['name'=>'刘冬鹏','sex'=>'男'];
//        数据库修改数据方法
//        $data = Article::where('sid=7')->update($date);
        $date = [
            'name'=>'张三',
            'age'=>20,
            'sex'=>'女'
        ];
//        数据库写入数据测试
//        $data = Article::insert($date);
//        获得数据库总数据测试
//        $data = Article::count();
//        获得排序后的数据测试
        $data = Article::field("sid")->orderBy('sid');
        dd($data);
//        主页的数据
        $test='houdunwang';
//        默认加载的主页跳板类
//        with是获取数据的方法
//        make是获得模板文件所在的目录结构
        return View::with(compact('test'))->make();
    }
//    友情提示模板加载测试方法
    public function add(){
//        调用继承的controller里面的有钱提示模板,并且输出友情提示
        $this->setRedirect()->message('添加成功');
    }
}

?>