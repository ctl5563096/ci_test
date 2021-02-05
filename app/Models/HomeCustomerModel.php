<?php declare(strict_types=1);


namespace App\Models;

use App\Common\BaseModel;

/**
 * Class HomeCustomerModel
 * @package App\Models
 */
class HomeCustomerModel extends BaseModel
{
    // 表的主键 必须要有以保证模型的正常工作
    protected $primaryKey = 'id';
    // 表名
    protected $table = 'ci_customer_home';
    // 模型的返回类型
    protected $returnType = 'array';
    // 开启安全删除 一般使用了deleted_at字段作为删除字段 暂时不使用
    //protected $useSoftDeletes = true;
    // 数据库的时间格式
    protected $dateFormat = 'datetime';
    // 更新或者插入时候 允许插入或者更新的字段
    protected $allowedFields = [
        'openid', 'phone_num', 'id_card', 'front_card_url', 'back_card_url', 'update_by', 'update_time', 'real_name', 'sex', 'add_type',
    ];
    // 传参类型强转
    public $paramsType = [
        'openid'      => 'string',
        'phone_num'   => 'string',
        'id_card'     => 'string',
        'add_type'    => 'int',
        'sex'         => 'int',
        'update_by'   => 'int',
        'update_time' => 'date',
    ];
    // 验证规则
    protected $validationRules = [
        'phone_num' => 'required',
        'id_card'   => 'required',
        'real_name' => 'required',
        'sex'       => 'required',
        'add_type'  => 'required',
    ];
    // 验证返回的错误信息
    protected $validationMessages = [
        'id_card'   => [
            'required' => '身份证号码不能为空',
        ],
        'phone_num' => [
            'required' => '密码不能为空',
        ],
        'real_name' => [
            'required' => '姓名不能为空',
        ],
        'sex'       => [
            'required' => '性别不能为空',
        ],
        'add_type'  => [
            'required' => '新增来源不能为空',
        ],
    ];

    // 是否跳过验证数据
    protected $skipValidation = false;

    /**
     * Notes:
     * Author: chentulin
     * DateTime: 2021/2/5 15:22
     * E-MAIL: <chentulinys@163.com>
     * @param array $data
     * @return array|int
     * @throws \ReflectionException
     */
    public function insertHomeCustomer(array $data)
    {
        $this->transformationType($data);
        $customerId = $this->insert($data, true);
        if ($customerId === false) return ['code' => 70001, 'msg' => '新增租客用户失败', 'realMsg' => current($this->errors())];
        else return (int)$customerId;
    }
}