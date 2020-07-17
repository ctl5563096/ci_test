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
        return $this->respondApi($this->modelObj->getList());
    }
}