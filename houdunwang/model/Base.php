<?php
//数据库操作类所在的空间
namespace houdunwang\model;
//数据库操作类
class  Base
{
//    数据库pdo链接属性,用于存到属性中,防止多次链接数据库
//    因为每次调用类方法的时候都会初始化,所以变量必须是静态的
    private static $pdo   = null;
//    数据库的表名,因为使用的地方较多,所以存成属性,本类能全局使用
    private        $table;
//    数据库操作的条件,因为使用的地方较多,所以存成属性,本类能全局使用
    private        $where;
//    数据库操作成功后获得的数据,因为使用的地方较多,所以存成属性,本类能全局使用
    public         $data;
//    数据库操作的输出数据的条件,需要输出哪些数据或者还是全部,因为使用的地方较多,所以存成属性,本类能全局使用
    private        $field = '';
//    构造方法
    public function __construct($table)
    {
//        判断是否为null
//        初始化为null,执行一次后就不为null,防止多次加载数据库
        if (is_null(self::$pdo)) {
//            调用数据库链接方法
            $this->connect();
        }
//        获得需要操作的表名
//        因为表名带空间名,所以需要截取
        $this->table = strtolower(ltrim(strrchr($table, '\\'), '\\'));
    }
//    数据了链接方法
    private function connect()
    {
        try {
//            链接的数据库,主机,数据库名
            $dsn       = c('database.base') . ":host=" . c('database.host') . ";dbname=" . c('database.dbname');
//            数据库账号
            $user      = c('database.user');
//            数据库密码
            $password  = c('database.password');
//            链接数据库
            self::$pdo = new \PDO($dsn, $user, $password);
//            设置异常信息抛出
            self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
//            设置编码,防止乱码
            self::$pdo->query('set names utf8');
        }
        catch (\PDOException $err) {
//            错误信息抛出
            throw new \Exception($err->getMessage());
        }
    }
//    以主键查找数据库单条数据
    public function find($id)
    {
//        获得表的主键名方法
        $pk = $this->setPk();
//        组合where条件的方法,返回值是一个完整的条件
        $this->where("{$pk}={$id}");
//        判断是否需要查看单条数据的所有数据
//        如果需要查看测试的时候需要调用field方法,在调用find方法
        $field = $this->field ? $this->field : '*';
//        组合完整的查找数据的语句
        $sql   = "select {$field} from {$this->table} {$this->where}";
//        执行查找
//        返回值是查找到的结果
        $res = $this->select($sql);
//        判断查找的数据是否为空
        if (!empty($res)) {
//            如果为空,那么把二维数据转为一维数据,存到属性中,外部调用toArray方法的时候返回出去
            $this->data = current($res);
        }
//        返回一个地址,为了链式操作
        return $this;
    }
//    排序方法,$tj是要一哪个字段排序
//    sort是要升序还是降序,默认不传是升序
    public function orderBy($tj, $sort = '')
    {
        //        判断是否需要查看数据的所有数据
        //        如果需要查看测试的时候需要调用field方法,在调用orderBy方法
        $field = $this->field ?: '*';
//        判断是否有需要排序的条件,如果没有条件,那么不能进行排序操作
        if (!empty($tj)) {
//            判断是升序还是降序
//            如果成立,那么说明是升序
            if (empty($sort)) {
//                组合升序的查询语句
//                field是查看哪个字段,table是查看的表$tj是以哪个字段排序
                $sql = "select {$field} from {$this->table} order by {$tj}";
            }
            else {
//                如果不成立,说明是升序
                $sql = "select {$field} from {$this->table} order by {$tj} {$sort}";
            }
//            当有排序的字段的时候执行查找方法
//            将查到的数据返回出去
            return $this->select($sql);
        }
//        如果没有条件,那么返回一个空数组
        return [];
    }
//    统计数据库有多少条数据
    public function count()
    {
//        组合统计的sql语句
        $sql  = "select count(*) as num from {$this->table} {$this->where}";
//        执行有结果查找方法
        $data = $this->select($sql);
//        返回查找到有多少条
        return $data[0]['num'];
    }
//    数据库写入方法
    public function insert($arr)
    {
//        写入的字段
        $key    = '';
//        写入的数据
        $values = '';
//        循环重组需要写入的字段，数据
        foreach ($arr as $k => $v) {
//            获得需要写入的字段
            $key .= $k . ',';
//            判断需要写入的数据是否是整型
            if (is_int($v)) {
//                如果是整型，那么就不加引号
                $values .= $v . ',';
            }
            else {
//                否则是字符串的话那么就加引号
                $values .= "'$v'" . ',';
            }
        }
//        因为组合后的字段最后有个，号所以要去掉，号
        $key    = rtrim($key, ',');
//        组合后的数据后面有个，去掉逗号
        $values = rtrim($values, ',');
//        组合sql语句
        $sql    = "insert into {$this->table} ({$key}) values ({$values})";
//        执行没有返回的查询方法
        return $this->exec($sql);
    }
//    修改数据库数据的方法
    public function update(array $arr)
    {
//        必须要加条件才能修改
//        否则会把数据库里的所有数据都会修改
        if (empty($this->where))
//            如果没有条件，那么终止代码的运行
            return false;
//        组合要修改的数据
        $date = '';
//        传入的数据是数组，所以循环组合每一条数据
        foreach ($arr as $k => $v) {
//            判断是否是整型
            if (is_int($v)) {
//                如果是整型，那么不需要加引号
                $date .= "{$k}={$v}" . ',';
            }
            else {
//                如果是字符串，那么需要加引号
                $date .= "{$k}='{$v}'" . ',';
            }
        }
//        因为组合后的字符串最后有个，号所以要去掉逗号
        $date = rtrim($date, ',');
//        组合sql修改语句
        $sql  = "update {$this->table} set {$date} {$this->where}";
//        执行没有结果的sql语句
        return $this->exec($sql);
    }
//    删除数据库数据方法
    public function destroy($id = '')
    {
//        如果没有条件或者没有需要删除的数据的主键条件，那么不能删除
        if (empty($this->where) || empty($id)) {
//            判断有没有删除的条件
            if (empty($this->where)) {
//                如果没有条件，那么以传入的主键来作为条件删除
                $pk          = $this->setPk();
//                组合条件
                $this->where = "where {$pk}={$id}";
            }
//            组合需要删除的sql语句
            $sql = "delete from {$this->table} {$this->where}";
//            执行没有结果集的查询，并且返回成功或失败结果
            return $this->exec($sql);
        }
        else {
//            如果又传主键id又给where条件，条件冲突，不能查询
            return false;
        }
    }
//    无结果执行sql语句的方法
    private function exec($sql)
    {
        try {
//            执行sql语句
            $res = self::$pdo->exec($sql);
//            判断是否是添加数据，如果是添加数据的话，那么返回当前添加的自增id
            if ($lastId = self::$pdo->lastInsertId()) {
                return $lastId;
            }
//            返回成功与失败
            return $res;
        }
        catch (\PDOException $err) {
//            错误消息抛出
            throw new \Exception($err->getMessage());
        }
    }
//    获得数据库里面的所有数据方法
    public function getAll()
    {
//        判断是看哪个字段，如果没有，默认全部
        $field = $this->field ?: '*';
//        组合查询语句
        $sql   = "select {$field} from {$this->table} {$this->where}";
//        接收查找到的结果
        $res   = $this->select($sql);
//        判断结果是否为空
        if (!empty($res)) {
//            不为空，那么存到属性中，调用toArray方法就能返回出去结果
            $this->data = $res;
        }
//        返回一个地址，链式操作
        return $this;
    }
//    获得需要查找哪个字段的数据
    public function field($field = '')
    {
//        存到属性中，组合sql语句的时候使用
        $this->field = $field;
//        返回当前的地址，用于链式操作
        return $this;
    }
//    外部获得执行完语句后获得结果的方法
    public function toArray()
    {
//        如果数据不为空
        if ($this->data) {
//            那么就返回数据
            return $this->data;
        }
//        否则返回一个空数据
        return [];
    }
//    条件方法
    public function where($where = '')
    {
//        组合条件，存到属性中，组合mql语句的时候调用
        $this->where = isset($where) ? "where {$where}" : '';
//        返回当前的地址，链式操作
        return $this;
    }
//    获得每个表主键的方法
    private function setPk()
    {
//        查看表结构的sql语句
        $sql  = 'desc ' . $this->table;
//        执行sql语句
        $data = $this->select($sql);
//        定义一个变量，用于接收主键名
        $pk   = '';
//        循环查找判断哪个是主键
        foreach ($data as $v) {
//            如果满足这个条件，那么说明是主键
            if ($v['Key'] == 'PRI') {
//                将主键的名字保存到变量中
                $pk = $v['Field'];
//                查询到了就停止循环
                break;
            }
        }
//        返回查找到的主键名字
        return $pk;
    }
//    有结果的执行查询方法
    public function select($sql)
    {
        try {
//            执行查询
            $res = self::$pdo->query($sql);
//            获得查询的结果，返回出去
            return $res->fetchAll(\PDO::FETCH_ASSOC);
        }
        catch (\PDOException $err) {
//            错误消息抛出
            throw new \Exception($err->getMessage());
        }

    }
}

?>