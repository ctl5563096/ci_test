<?php declare(strict_types=1);


namespace App\Models;


use App\Common\BaseModel;

/**
 * 轮播图模型
 *
 * Class CarouselModel
 * @package App\Models
 */
class CarouselModel extends BaseModel
{
    // 表的主键 必须要有以保证模型的正常工作
    protected $primaryKey = 'id';
    // 表名
    protected $table = 'carousel';
    // 模型的返回类型
    protected $returnType = 'array';
    // 开启安全删除 一般使用了deleted_at字段作为删除字段 暂时不使用
    //protected $useSoftDeletes = true;
    // 数据库的时间格式
    protected $dateFormat = 'datetime';
    // 更新或者插入时候 允许插入或者更新的字段
    protected $allowedFields = [
        'name', 'content', 'is_enabled', 'image_url', 'add_by', 'update_by', 'update_time',
    ];
    // 传参类型强转
    public $paramsType = [
        'content'     => 'string',
        'is_enabled'  => 'int',
        'image_url'   => 'string',
        'add_by'      => 'string',
        'update_by'   => 'string',
        'update_time' => 'date',
        'name'        => 'string',
    ];
    // 验证规则
    protected $validationRules = [
        'name'       => 'required',
        'is_enabled' => 'required',
        'image_url'  => 'required',
    ];

    /**
     * Notes: 获取轮播图列表
     *
     * Author: chentulin
     * DateTime: 2021/3/1 17:50
     * E-MAIL: <chentulinys@163.com>
     * @param array $params
     * @return array
     */
    public function getList(array $params): array
    {
        $query = $this->connect->table($this->table);
        $query->select('*,if(is_enabled = 1,"未启用","已启用") as cn_enabled');
        $query->limit((int)$params['pageSize'], ((int)$params['page'] - 1) * (int)$params['pageSize']);
        $queryCount = clone $query;
        $count      = $queryCount->countAll();
        $list       = $query->get()->getResultArray();
        if ($list === false) {
            return ['code' => 90001, 'msg' => 'sql错误', 'sql' => $this->getSql($query)];
        }
        return [
            'count' => $count,
            'list'  => $list,
        ];
    }
}