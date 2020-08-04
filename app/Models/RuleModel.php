<?php declare(strict_types=1);


namespace App\Models;


use CodeIgniter\Model;

class RuleModel extends Model
{
    // 表的主键 必须要有以保证模型的正常工作
    protected $primaryKey = 'id';
    // 表名
    protected $table = 'rule';
    // 模型的返回类型
    protected $returnType = 'array';
    // 开启安全删除 一般使用了deleted_at字段作为删除字段 暂时不使用
    //protected $useSoftDeletes = true;
    // 数据库的时间格式
    protected $dateFormat = 'datetime';
    // 更新或者插入时候 允许插入或者更新的字段
    protected $allowedFields = [
        'rule_name', 'pid', 'is_menu',
    ];
    // 验证规则
    protected $validationRules = [
        'rule_name' => 'required',
        'pid'       => 'required',
    ];
    // 验证返回的错误信息
    protected $validationMessages = [
        'rule_name' => [
            'required' => '权限名不能为空',
        ],
        'pid'       => [
            'required' => '父ID不能为空',
        ],
    ];

    // 是否跳过验证数据
    protected $skipValidation = false;

    /**
     * Notes: 添加权限
     *
     * Author: chentulin
     * DateTime: 2020/8/4 19:43
     * E-MAIL: <chentulinys@163.com>
     * @param array $data
     * @return void
     */
    public function addRule(array $data)
    {

    }
}