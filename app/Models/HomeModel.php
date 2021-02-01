<?php declare(strict_types=1);


namespace App\Models;


use App\Common\BaseModel;

/**
 * 房间模型层
 *
 * Class HomeModel
 * @package App\Models
 */
class HomeModel extends BaseModel
{
    // 表的主键 必须要有以保证模型的正常工作
    protected $primaryKey = 'id';
    // 表名
    protected $table = 'home';
    // 模型的返回类型
    protected $returnType = 'array';
    // 开启安全删除 一般使用了deleted_at字段作为删除字段 暂时不使用
    //protected $useSoftDeletes = true;
    // 数据库的时间格式
    protected $dateFormat = 'datetime';
    // 更新或者插入时候 允许插入或者更新的字段
    protected $allowedFields = [
        'home_num', 'floor', 'customer_id', 'charger', 'size', 'type', 'money', 'is_del', 'is_all', 'is_pay', 'in_time', 'out_time',
    ];
    // 传参类型强转
    public $paramsType = [
        'home_num'    => 'string',
        'floor'       => 'int',
        'customer_id' => 'int',
        'charger'     => 'int',
        'size'        => 'int',
        'type'        => 'int',
        'money'       => 'int',
        'is_del'      => 'int',
        'is_all'      => 'int',
        'is_pay'      => 'int',
        'in_time'     => 'date',
        'out_time'    => 'date',
    ];
    // 验证规则
    protected $validationRules = [
        'home_num' => 'required',
        'floor'    => 'required',
        'size'     => 'required',
        'charger'  => 'required',
        'money'    => 'required',
        'type'     => 'required',
        'is_all'   => 'required',
    ];

    // 验证返回的错误信息
    protected $validationMessages = [
        'house_name' => [
            'required' => '房间号不能为空',
        ],
        'floor'      => [
            'required' => '楼层不能为空',
        ],
        'size'       => [
            'required' => '使用面积不能为空',
        ],
        'charger'    => [
            'required' => '负责人不能为空',
        ],
        'type'       => [
            'required' => '类型不能为空',
        ],
        'is_all'     => [
            'required' => '是否租出不能为空',
        ],
    ];

    // 是否跳过验证数据
    protected $skipValidation = false;

    /**
     * Notes: 获取房间信息
     *
     * Author: chentulin
     * DateTime: 2021/2/1 16:02
     * E-MAIL: <chentulinys@163.com>
     * @param int $id
     * @return array
     */
    public function getHomeListByHomeId(int $id):array
    {

    }
}