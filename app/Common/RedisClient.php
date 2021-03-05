<?php declare(strict_types=1);


namespace App\Common;

use Config\Cache;
use Predis\Client;

/**
 * 获取predis客户端已获取更多操作
 *
 * Class RedisClient
 * @package App\Common
 */
class RedisClient
{
    private static $instance = null;

    //构造器私有化:禁止从类外部实例化
    private function __construct()
    {
    }

    //克隆方法私有化:禁止从外部克隆对象
    private function __clone()
    {
    }

    //因为用静态属性返回类实例,而只能在静态方法使用静态属性
    //所以必须创建一个静态方法来生成当前类的唯一实例
    public static function getInstance()
    {
        //检测当前类属性$instance是否已经保存了当前类的实例
        if (self::$instance == null) {
            $redisConfig = (new Cache())->redis;
            //如果没有,则创建当前类的实例
            self::$instance = new Client($redisConfig);
        }
        //如果已经有了当前类实例,就直接返回,不要重复创建类实例
        return self::$instance;
    }

}