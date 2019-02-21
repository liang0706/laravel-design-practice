<?php
/**
 * 用户登录记录日志,我们可以选择文件或者数据库
 */

//日志接口
interface Log {
    public function write();
}

//文件记录
class FileLog implements Log {
    public function write() {
        echo 'file log write ...' . PHP_EOL;
    }
}

//数据库记录日志
class DatabaseLog implements Log {
    public function write() {
        echo 'database log write ...' . PHP_EOL;
    }
}

//用户
class User {
    protected $log;

    public function __construct(DatabaseLog $log) {
        $this->log = $log;
    }

    public function login() {
        echo 'login success ...' . PHP_EOL;
        $this->log->write();
    }
}

function make($concrete) {
    $reflector      = new ReflectionClass($concrete);
    $constructor    = $reflector->getConstructor();
    if(is_null($constructor)) {
        return $reflector->newInstance();
    } else {
        $parameters = $constructor->getParameters();
        $dependencies = getDependencies($parameters);
        return $reflector->newInstanceArgs($dependencies);
    }
}

function getDependencies($parameters) {
    $dependencies = [];
    foreach($parameters as $parameter) {
        $dependencies[] = make($parameter->getClass()->name);
    }

    return $dependencies;
}

$user = make(User::class);
$user->login();
