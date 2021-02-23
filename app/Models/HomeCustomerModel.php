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
    protected $table = 'customer_home';
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
        'real_name'   => 'string',
        'openid'      => 'string',
        'phone_num'   => 'string',
        'id_card'     => 'string',
        'add_type'    => 'int',
        'sex'         => 'int',
        'update_by'   => 'string',
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
            'required' => 'phone_num',
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
        if (!$data['id_card']) {
            return ['code' => 70001, 'msg' => '新增租客用户失败', 'realMsg' => '身份证号码不能为空'];
        }
        if (!$data['phone_num']) {
            return ['code' => 70001, 'msg' => '新增租客用户失败', 'realMsg' => '手机号码不能为空'];
        }
        // 身份证号码验证
        $preg = '/^[1-9]\d{5}(18|19|20|(3\d))\d{2}((0[1-9])|(1[0-2]))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$/';
        // 手机号码正则
        $phoneNumPreg = '/^1((34[0-8]\d{7})|((3[0-3|5-9])|(4[5-7|9])|(5[0-3|5-9])|(66)|(7[2-3|5-8])|(8[0-9])|(9[1|8|9]))\d{8})$/';
        if (!preg_match($preg, $data['id_card'])) {
            return ['code' => 70001, 'msg' => '身份证号码有误,请确认之后重新输入', 'realMsg' => '身份证号码有误,请确认之后重新输入'];
        }
        if (!preg_match($phoneNumPreg, $data['phone_num'])) {
            return ['code' => 70001, 'msg' => '手机号码有误,请确认之后重新输入', 'realMsg' => '手机号码有误,请确认之后重新输入'];
        }
        $customerId = $this->insert($data, true);
        if ($customerId === false) return ['code' => 70001, 'msg' => '新增租客用户失败', 'realMsg' => current($this->errors())];
        else return (int)$customerId;
    }

    /**
     * Notes: 获取列表
     *
     * Author: chentulin
     * DateTime: 2021/2/6 11:19
     * E-MAIL: <chentulinys@163.com>
     * @param array $params
     * @return array
     */
    public function getCustomerList(array $params)
    {
        $query = $this->connect->table('customer_home as a');
        $query->select('a.id,a.real_name ,case when sex = 1 then "男" when sex = 2 then "女" else "保密" end as sex, a.openid, a.phone_num ,a.id_card ,a.add_time ,b.para_name as add_type', false);
        $query->join('ci_parameter as b', ' a.add_type = b.para_value and code = "customerOrigin"', 'left');
        // 关键字搜索
        if ($params['keywords']) {
            $query->like('a.real_name', trim($params['keywords']));
            $query->orLike('a.id_card', trim($params['keywords']));
            $query->orLike('a.phone_num', trim($params['keywords']));
        }
        // 新增来源搜索
        if ($params['origin']) {
            $query->where('a.add_type', (int)$params['origin']);
        }
        $query->limit((int)$params['pageSize'], ((int)$params['page'] - 1) * (int)$params['pageSize']);
        $queryCount = clone $query;
        $list       = $query->get()->getResultArray();
        if ($list === false) {
            return ['code' => 90001, 'msg' => 'sql错误', 'sql' => $this->getSql($query)];
        }
        $count = $queryCount->countAll();
        return ['count' => $count, 'list' => $list];
    }

    /**
     * Notes: 获取用户详情
     *
     * Author: chentulin
     * DateTime: 2021/2/22 19:43
     * E-MAIL: <chentulinys@163.com>
     * @param int $id
     */
    public function getInfoByCustomerId(int $id): array
    {
        $query = $this->connect->table('customer_home as a');
        $query->select('a.*,b.para_name as add_type');
        $query->join('ci_parameter as b', ' a.add_type = b.para_value and code = "customerOrigin"', 'left');
        $query->where('a.id', $id);
        $query->limit(1);
        $res = $query->get()->getResultArray();
        if ($res) {
            return current($res);
        } else {
            return ['code' => 10010, '获取数据失败'];
        }
    }

    /**
     * Notes: 更新租客用户信息
     *
     * Author: chentulin
     * DateTime: 2021/2/22 20:20
     * E-MAIL: <chentulinys@163.com>
     * @param array $info
     */
    public function updateInfo(array $info): bool
    {
        $info['update_time'] = date('Y-m-d H:i:s');
        $this->transformationType($info);
        $id = $info['id'];
        unset($info['id']);
        unset($info['add_type']);
        $query = $this->connect->table($this->table);
        $query->where('id', $id);
        $res = $query->update($info);
        if (!$res) return false;
        return true;
    }
}