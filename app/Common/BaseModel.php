<?php declare(strict_types=1);


namespace App\Common;


use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;

/**
 * 模型积累
 *
 * Class BaseModel
 * @package App\Common
 */
class BaseModel extends Model
{
    public $connect;
    public $paramsType = [];
    public function __construct(ConnectionInterface &$db = null, ValidationInterface $validation = null)
    {
        $this->connect = \Config\Database::connect();
        parent::__construct($db, $validation);
    }

    /**
     * Notes: 强转参数
     *
     * Author: chentulin
     * DateTime: 2021/1/29 10:21
     * E-MAIL: <chentulinys@163.com>
     * @param array $params
     */
    public function transformationType(array &$params)
    {
        foreach ($this->paramsType as $key => $value){
            switch ($value){
                case 'int':
                    $params[$key] = (int)$params[$key];
                    break;
                case 'string':
                    $params[$key] = (string)$params[$key];
                    break;
                case 'date':
                    $pregStr = '/^\d{4}[\-](0?[1-9]|1[012])[\-](0?[1-9]|[12][0-9]|3[01])(\s+(0?[0-9]|[12][0-3])\:(0?[0-9]|[1-5][1-9])\:(0?[0-9]|[1-5][1-9]))?$/';
                    $res = preg_match($pregStr,$params[$key]);
                    // 给一个默认值
                    if (!$res){
                        $params[$key] = date('Y-m-d H:i:s');
                    }
                    break;
            }
        }
    }
}