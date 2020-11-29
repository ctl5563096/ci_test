<?php declare(strict_types=1);


namespace App\Models;

use App\Common\BaseModel;

class UserModel extends BaseModel
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
        'user_name', 'password', 'is_use', 'is_black', 'email', 'token','role','avatar'
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
     * Notes: 获取所有用户，包括黑名单，未被启用
     *
     * Author: chentulin
     * DateTime: 2020/7/16 16:45
     * E-MAIL: <chentulinys@163.com>
     * @param array $data
     * @return array
     */
    public function getList(array $data)
    {
        $query = $this->connect->table('admin_user as u');
        $query->select('u.id,u.user_name,u.password,u.is_use,u.is_black,u.email,u.created_at,u.token,b.role_name,if(u.sex = 1 ,"男" ,"女") as sex,role,sex,avatar')
            ->limit((int)$data['pageSize'], ((int)$data['page'] - 1) * (int)$data['pageSize'])
            ->join('role as b', 'u.role = b.id', 'left');
        // 关键字搜索
        if ($data['keywords']) {
            $query->like('user_name', trim($data['keywords']));
            $query->orLike('email', trim($data['keywords']));
        }
        // 权限搜索
        if ($data['role']) {
            $query->where('u.role', (int)$data['role']);
        }
        // 开始日期搜索
        if ($data['start_time']) {
            $query->where('u.created_at >=', $data['start_time']);
        }
        // 结束日期搜索
        if ($data['end_time']) {
            $query->where('u.created_at <', $data['end_time']);
        }
        $queryCount = clone $query;
        $list       = $query->get()->getResultArray();
        $count      = $queryCount->countAll();
        return ['count' => $count, 'list' => $list];
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
        $query = $this->connect->table('admin_user as u');
        $query->select('u.*,r.role_name as role_name');
        $query->join('role as r', 'u.role = r.id');
        $query->where('u.id', $id);
        $res = $query->get()->getResultArray();
        if (!empty($res)) {
            return current($res);
        }
        return ['code' => 10006, '获取用户信息失败', ''];
    }

    /**
     * Notes: 修改管理员信息
     *
     * Author: chentulin
     * DateTime: 2020/7/22 16:44
     * E-MAIL: <chentulinys@163.com>
     * @param $data
     * @return array|bool
     * @throws \ReflectionException
     */
    public function updateUserInfo($data)
    {
        $updateData = [
            'user_name' => $data['username'],
            'password'  => $data['password'],
            'email'     => $data['email'],
            'is_use'    => $data['is_enable'] === true ? 1 : 0,
            'is_black'  => $data['is_black'] === true ? 1 : 0,
            'sex'       => (int)$data['sex'],
        ];
        $userModel  = new self();
        $res        = $userModel->update((int)$data['id'], $updateData);
        if (!$res) {
            return ['code' => 10007, '更新用户信息失败', current($this->db->error())];
        }
        return ['res' => $res];
    }

    /**
     * Notes: 更新管理员状态
     *
     * @param int $id
     * @param string $type
     * @param int $status
     * @return array
     * @throws \ReflectionException
     * @author: chentulin
     * Date: 2020/9/7
     * Time: 11:03
     */
    public function changeStatus(int $id, string $type, int $status): array
    {
        $updateData[$type] = $status;
        $userModel         = new self();
        $res               = $userModel->update((int)$id, $updateData);
        if ($res) {
            return ['msg' => '更新成功'];
        }
    }

    /**
     * Notes:
     * @param $id
     * @param $data
     * @return array
     * @throws \ReflectionException
     * @author: chentulin
     * Date: 2020/9/16
     * Time: 11:10
     */
    public function updateAdminInfo(int $id, array $data)
    {
        $updateData = [
            'user_name' => $data['user_name'],
            'password'  => $data['password'],
            'email'     => $data['email'],
            'sex'       => (int)$data['sex'],
            'token'     => $data['token'],
            'role'      => (int)$data['role']
        ];
        $userModel  = new self();
        $res        = $userModel->update($id, $updateData);
        if (!$res) {
            return ['code' => 10007, '更新用户信息失败', current($this->db->error())];
        }
        return ['res' => $res];
    }

    /**
     * Notes: 更新某些用户信息
     *
     * @param int $id
     * @param array $data
     * @return bool
     * @throws \ReflectionException
     * @author: chentulin
     * Date: 2020/9/21
     * Time: 14:48
     */
    public function updateInfoForUserId(int $id ,array $data): bool
    {
        $userModel  = new self();
        $res        = $userModel->update($id, $data);
        if (!$res) {
            return false;
        }
        return true;
    }

    /**
     * Notes: 获取正常的用户信息
     *
     * @return array
     * @author: chentulin
     * Date: 2020/9/28
     * Time: 10:12
     */
    public function normalUserList(): array
    {
        $query = $this->connect->table('admin_user');
        $query->select('id,user_name');
        $query->where('is_use',1);
        $query->where('is_black',0);

        $queryCount = clone $query;
        $list       = $query->get()->getResultArray();
        $count      = $queryCount->countAll();
        return ['count' => $count, 'list' => $list];
    }
}