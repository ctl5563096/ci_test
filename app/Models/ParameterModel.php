<?php declare(strict_types=1);


namespace App\Models;


use App\Common\BaseModel;
use mysqli_sql_exception;

/**
 * 参数模型
 *
 * Class ParameterModel
 * @package App\Models
 */
class ParameterModel extends BaseModel
{
    // 表的主键 必须要有以保证模型的正常工作
    protected $primaryKey = 'id';
    // 表名
    protected $table = 'parameter';
    // 模型的返回类型
    protected $returnType = 'array';
    // 开启安全删除 一般使用了deleted_at字段作为删除字段 暂时不使用
    //protected $useSoftDeletes = true;
    // 数据库的时间格式
    protected $dateFormat = 'datetime';
    // 更新或者插入时候 允许插入或者更新的字段
    protected $allowedFields = [
        'name', 'code', 'para_name', 'para_value', 'is_enabled', 'weight', 'add_by', 'update_by', 'update_time',
    ];
    // 传参类型强转
    public $paramsType = [
        'name'        => 'string',
        'code'        => 'string',
        'para_name'   => 'string',
        'para_value'  => 'int',
        'is_enabled'  => 'int',
        'weight'      => 'int',
        'add_by'      => 'string',
        'update_by'   => 'string',
        'update_time' => 'date',
    ];
    // 验证规则
    protected $validationRules = [
        'name'       => 'required',
        'code'       => 'required',
        'para_name'  => 'required',
        'para_value' => 'required',
        'is_enabled' => 'required',
    ];
    // 验证返回的错误信息
    protected $validationMessages = [
        'name'       => [
            'required' => '名称不能为空',
        ],
        'code'       => [
            'required' => '编码不能为空',
        ],
        'para_name'  => [
            'required' => '参数名',
        ],
        'para_value' => [
            'required' => '参数值',
        ],
        'is_enabled' => [
            'required' => '是否启用',
        ],
    ];

    /**
     * Notes: 获取参数列表
     *
     * Author: chentulin
     * DateTime: 2021/2/1 16:57
     * E-MAIL: <chentulinys@163.com>
     * @param int $page
     * @param int $pageSize
     * @param string $keywords
     * @param int $isPage
     * @param bool $sort
     * @param bool $is_enabled
     * @return array
     */
    public function getList(int $page, int $pageSize, string $keywords, $isPage = -1,$sort = false,$is_enabled = false): array
    {
        $query = $this->connect->table($this->table);
        $query->select('name,code,is_enabled,para_name,para_value');
        if ($is_enabled){
            $query->where('is_enabled', 2);
        }
//        if ($keywords){
//
//        }
        $count = 0;
        if ($isPage !== -1) {
            $query->limit((int)$pageSize, ((int)$page - 1) * (int)$pageSize);
            $queryCount = clone $query;
            $count      = $queryCount->countAll();
        }
        $list = $query->get(null,0,false)->getResultArray();
        if (!$list){
            return ['code' => 90001 ,'msg' => 'sql错误','sql' => $this->getSql($query)];
        }
        if ($isPage !== -1) {
            $res = [
                'count' => $count,
                'list'  => $list,
            ];
        } else {
            if ($sort){
                $list = array_column($list,null,'code');
            }
            $res = [
                'list' => $list,
            ];
        }
        return $res;
    }
}