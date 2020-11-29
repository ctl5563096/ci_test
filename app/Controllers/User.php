<?php declare(strict_types=1);


namespace App\Controllers;


use App\Common\RestController;
use App\Models\UserModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * 用户控制器 对用户进行注册添加删除以及验证
 *
 * Class User
 * @package App\Controllers
 * @property UserModel $modelObj
 */
class User extends RestController
{
    protected $modelName = 'App\Models\UserModel';

    protected $modelObj;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        $this->modelObj = new UserModel();
        parent::initController($request, $response, $logger);
    }

    /**
     * Notes: 注册用户
     *
     * Author: chentulin
     * DateTime: 2020/7/16 15:19
     * E-MAIL: <chentulinys@163.com>
     * @throws \ReflectionException
     */
    public function register()
    {
        $id = $this->modelObj->register($this->request->getJSON(true));
        // 如果不是整数就返回错误信息
        if (!is_int($id)) {
            return $this->respondApi($id);
        }
        return $this->respondApi(['id' => $id]);
    }

    /**
     * Notes: 获取用户列表
     *
     * Author: chentulin
     * DateTime: 2020/7/16 15:20
     * E-MAIL: <chentulinys@163.com>
     */
    public function userList()
    {
        return $this->respondApi($this->modelObj->getList($this->request->getGet()));
    }

    /**
     * Notes: 获取管理员信息
     *
     * Author: chentulin
     * DateTime: 2020/7/21 11:39
     * E-MAIL: <chentulinys@163.com>
     */
    public function getUserInfo()
    {
        return $this->respondApi($this->modelObj->getUserInfo((int)$this->request->getPostGet('id')));
    }

    /**
     * Notes: 修改管理员信息
     *
     * Author: chentulin
     * DateTime: 2020/7/22 16:03
     * E-MAIL: <chentulinys@163.com>
     * @throws \ReflectionException
     */
    public function updateUserInfo()
    {
        return $this->respondApi($this->modelObj->updateUserInfo($this->request->getJSON(true)));
    }

    /**
     * Notes: 改变用户状态 如:启用
     *
     * @throws \ReflectionException
     * @author: chentulin
     * Date: 2020/9/7
     * Time: 10:54
     */
    public function changeStatus()
    {
        $id     = (int)$this->request->getGet('id');
        $type   = (string)$this->request->getGet('type');
        $status = (int)$this->request->getGet('status');
        if (!$id || !$type) {
            return $this->respondApi(['code' => 10009, '无法获取参数']);
        }
        return $this->respondApi($this->modelObj->changeStatus($id, $type, $status));
    }

    /**
     * Notes: 更新管理员信息
     *
     * @throws \ReflectionException
     * @author: chentulin
     * Date: 2020/9/16
     * Time: 11:08
     */
    public function updateAdminInfo()
    {
        $data = $this->request->getJSON(true);
        if (!$data['id']) {
            return $this->respondApi(['code' => 10009, '无法获取参数']);
        }
        return $this->respondApi($this->modelObj->updateAdminInfo((int)$data['id'], $data));
    }

    /**
     * Notes: 获取负责人
     *
     * @author: chentulin
     * Date: 2020/9/28
     * Time: 10:15
     */
    public function normalUserList()
    {
        return $this->respondApi($this->modelObj->normalUserList());
    }
}