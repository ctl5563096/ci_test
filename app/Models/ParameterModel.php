<?php declare(strict_types=1);


namespace App\Models;


use App\Common\BaseModel;
use mysqli_sql_exception;
use phpDocumentor\Reflection\Types\False_;

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
     * @param array $other
     * @return array
     */
    public function getList(int $page, int $pageSize, string $keywords, $isPage = -1, $sort = false, $is_enabled = false, $other = []): array
    {
        $query = $this->connect->table($this->table);
        $query->select('id,name,code,is_enabled,para_name,para_value');
        if ($is_enabled) {
            $query->where('is_enabled', (int)$other['is_enabled']);
        }
        // 关键字搜索
        if ($keywords) {
            $query->groupStart();
            $query->like('code', $keywords);
            $query->orLike('name', $keywords);
            $query->orLike('para_name', $keywords);
            $query->groupEnd();
        }
        $count = 0;
        if ($isPage !== -1) {
            $query->limit((int)$pageSize, ((int)$page - 1) * (int)$pageSize);
            $queryCount = clone $query;
            $count      = $queryCount->countAll();
        }
        $query->orderBy('add_time', 'DESC');
        $list = $query->get(null, 0, false)->getResultArray();
        if ($list === false) {
            return ['code' => 90001, 'msg' => 'sql错误', 'sql' => $this->getSql($query)];
        }
        if ($isPage !== -1) {
            $res = [
                'count' => $count,
                'list'  => $list,
            ];
        } else {
            $newArr = [];
            if ($sort) {
                foreach ($list as $key => $item) {
                    $newArr[$item['code']][] = $item;
                }
            }
            $res = [
                'list' => $newArr,
            ];
        }
        return $res;
    }

    /**
     * Notes:
     * Author: chentulin
     * DateTime: 2021/2/2 19:55
     * E-MAIL: <chentulinys@163.com>
     * @param int $id
     * @return array
     */
    public function getInfoById(int $id): array
    {
        $query = $this->connect->table($this->table);
        $query->select('*');
        $query->where('id', $id);
        $info = $query->get()->getRowArray();
        if ($info === false) {
            return ['code' => 90001, 'msg' => 'sql错误', 'sql' => $this->getSql($query)];
        }
        return $info;
    }

    /**
     * Notes: 更新参数信息
     *
     * Author: chentulin
     * DateTime: 2021/2/3 11:41
     * E-MAIL: <chentulinys@163.com>
     * @param array $data
     * @return array
     */
    public function updateInfo(array $data): array
    {
        $id = $data['id'];
        unset($data['id']);
        $data['code'] = $data['para_code'];
        unset($data['para_code']);
        $data['update_time'] = date('Y-m-d H:i:s');
        $this->transformationType($data);
        $query = $this->connect->table($this->table);
        $query->where('id', $id);
        $res = $query->update($data);
        if (!$res) {
            return ['code' => 90002, 'msg' => '更新失败', 'realMsg' => '更新失败'];
        } else {
            return [];
        }
    }

    /**
     * Notes: 新增
     *
     * Author: chentulin
     * DateTime: 2021/2/3 15:21
     * E-MAIL: <chentulinys@163.com>
     * @param array $data
     * @throws \ReflectionException
     */
    public function addRecord(array $data)
    {
        $data['code'] = $data['para_code'];
        unset($data['para_code']);
        $this->transformationType($data);
        $parameterId = $this->insert($data,true);
        if ($parameterId === false) return ['code' => 90003, 'msg' => '插入参数失败', 'realMsg' => current($this->errors())];
        else return (int)$parameterId;
    }
}