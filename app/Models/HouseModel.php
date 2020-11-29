<?php declare(strict_types=1);


namespace App\Models;


use App\Common\BaseModel;

/**
 * 房屋模型
 *
 * Class HouseModel
 * @package App\Models
 */
class HouseModel extends BaseModel
{
    // 表的主键 必须要有以保证模型的正常工作
    protected $primaryKey = 'id';
    // 表名
    protected $table = 'house';
    // 模型的返回类型
    protected $returnType = 'array';
    // 开启安全删除 一般使用了deleted_at字段作为删除字段 暂时不使用
    //protected $useSoftDeletes = true;
    // 数据库的时间格式
    protected $dateFormat = 'datetime';
    // 更新或者插入时候 允许插入或者更新的字段
    protected $allowedFields = [
        'house_name', 'address', 'master', 'size', 'is_del', 'charger', 'is_all', 'type', 'create_time', 'room_num'
    ];
    // 验证规则
    protected $validationRules = [
        'house_name'  => 'required',
        'address'     => 'required',
        'master'      => 'required',
        'size'        => 'required',
        'charger'     => 'required',
        'create_time' => 'required',
        'room_num'    => 'required',
    ];
    // 验证返回的错误信息
    protected $validationMessages = [
        'house_name'  => [
            'required' => '房间名不能为空',
        ],
        'address'     => [
            'required' => '地址不能为空',
        ],
        'master'      => [
            'required' => '法人不能为空',
        ],
        'size'        => [
            'required' => '面积不能为空',
        ],
        'charger'     => [
            'required' => '负责人不能为空',
        ],
        'create_time' => [
            'required' => '建造时间不能为空',
        ],
        'room_num'    => [
            'required' => '房间数',
        ]
    ];

    // 是否跳过验证数据
    protected $skipValidation = false;

    /**
     * Notes: 插入房间信息
     *
     * @param array $params
     * @return array|int
     * @throws \ReflectionException
     * @author: chentulin
     * Date: 2020/9/22
     * Time: 10:14
     */
    public function insertHouse(array $params)
    {
        $userId = $this->insert($params, true);
        if ($userId === false) return ['code' => 70001, '注册房屋信息失败', current($this->errors())];
        else return (int)$userId;
    }

    /**
     * Notes: 获取房屋列表
     *
     * @param array $params
     * @return array
     * @author: chentulin
     * Date: 2020/9/22
     * Time: 10:40
     */
    public function getHouseList(array $params)
    {
        $query = $this->connect->table('house as a');
        $query->select('a.*,b.user_name as name')
            ->join('admin_user b', 'a.charger = b.id', 'left')
            ->limit((int)$params['pageSize'], ((int)$params['page'] - 1) * (int)$params['pageSize']);
        // 关键字搜索
        if ($params['keywords']) {
            $query->like('a.house_name', trim($params['keywords']));
            $query->orLike('a.address', trim($params['keywords']));
            $query->orLike('a.master', trim($params['keywords']));
        }
        // 负责人搜索
        if ($params['charger']) {
            $query->whereIn('charger', $params['charger']);
        }
        // 时间搜索
        if ($params['date_type'] && ($params['start_time'] || $params['end_time'])) {
            $time_type = '';
            switch ($params['date_type']) {
                case 1 :
                    $time_type = 'a.add_time';
                    break;
                case 2 :
                    $time_type = 'a.create_time';
                    break;
            }
            // 开始日期搜索
            if ($params['start_time']) {
                $query->where($time_type . ' >=', $params['start_time']);
            }
            // 结束日期搜索
            if ($params['end_time']) {
                $query->where($time_type . ' <', $params['end_time']);
            }
        }
        $queryCount = clone $query;
        $list       = $query->get()->getResultArray();
        $count      = $queryCount->countAll();
        return ['count' => $count, 'list' => $list];
    }
}