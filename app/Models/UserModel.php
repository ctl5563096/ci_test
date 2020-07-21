<?php declare(strict_types=1);


namespace App\Models;

use CodeIgniter\Config\Services;
use CodeIgniter\Model;

class UserModel extends Model
{
    // 表的主键 必须要有以保证模型的正常工作
    protected $primaryKey = 'id';
    // 表名
    protected $table = 'admin_user';
    // 模型的返回类型
    protected $returnType = 'array';
    // 开启安全删除 一般使用了deleted_at字段作为删除字段 暂时不使用
    //protected $useSoftDeletes = true;
    // 数据库的时间格式
    protected $dateFormat = 'datetime';
    // 更新或者插入时候 允许插入或者更新的字段
    protected $allowedFields = [
        'user_name', 'password', 'is_use', 'is_black', 'email', 'token',
    ];
    // 验证规则
    protected $validationRules = [
        'user_name' => 'required',
        'email'     => 'required|valid_email',
        'password'  => 'required',
    ];
    // 验证返回的错误信息
    protected $validationMessages = [
        'user_name' => [
            'required' => '用户名不能为空',
        ],
        'email'     => [
            'required'    => '邮箱不能为空',
            'valid_email' => '邮箱格式不对',
        ],
        'password'  => [
            'required' => '密码不能为空',
        ],
    ];

    // 是否跳过验证数据
    protected $skipValidation = false;


    /**
     * Notes: 获取已被启用用户列表
     *
     * Author: chentulin
     * DateTime: 2020/7/16 16:45
     * E-MAIL: <chentulinys@163.com>
     */
    public function getList()
    {
        return $this->where('is_use', 1)->where('is_black', 0)->findAll();
    }

    /**
     * Notes: 注册用户
     *
     * Author: chentulin
     * DateTime: 2020/7/16 17:50
     * E-MAIL: <chentulinys@163.com>
     * @param array $data
     * @return int | array
     * @throws \ReflectionException
     */
    public function register(array $data)
    {
        $userId = $this->insert($data, true);
        if ($userId === false) return ['code' => 10005, '注册用户失败', current($this->errors())];
        else return (int)$userId;
    }

    /**
     * Notes: 获取管理员个人信息
     *
     * Author: chentulin
     * DateTime: 2020/7/21 14:23
     * E-MAIL: <chentulinys@163.com>
     * @param int $id
     * @return array|object
     */
    public function getUserInfo(int $id)
    {
        $res = $this->find($id);
        if (!empty($res)){
            return $res;
        }
        return ['code' => 10006, '获取用户信息失败', ''];
    }
}