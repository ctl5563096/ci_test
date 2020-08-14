<?php declare(strict_types=1);

namespace App\Controllers;

use App\Common\RestController;
use App\Models\RuleModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class Rule
 * 权限控制以及菜单模块
 *
 * @package App\Controllers
 * @property RuleModel $modelObj
 */
class Rule extends RestController
{
    protected $modelName = 'App\Models\RuleModel';

    protected $modelObj;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        $this->modelObj = new RuleModel();
        parent::initController($request, $response, $logger);
    }

    /**
     * Notes: 添加权限
     *
     * Author: chentulin
     * DateTime: 2020/8/4 19:38
     * E-MAIL: <chentulinys@163.com>
     * @throws \ReflectionException
     */
    public function addRule()
    {
        $id = $this->modelObj->addRule($this->request->getJSON(true));
        // 如果不是整数就返回错误信息
        if (!is_int($id)) {
            return $this->respondApi($id);
        }
        return $this->respondApi(['id' => $id]);
    }

    /**
     * Notes: 根据用户获取权限菜单
     *
     * Author: chentulin
     * DateTime: 2020/8/5 16:53
     * E-MAIL: <chentulinys@163.com>}
     */
    public function getMenu()
    {
        // 暂时未做权限 区分
        $res = $this->modelObj->getMenuByRole();
        return $this->respondApi($res);
    }

    /**
     * Notes: 获取所有的权限
     *
     * @author: chentulin
     * Date: 2020/8/10
     * Time: 17:11
     */
    public function getRule()
    {
        // 获取所有的权限
        $res = $this->modelObj->getAllRule();
        return $this->respondApi($res);
    }

    /**
     * Notes: 获取所有角色
     *
     * @author: chentulin
     * Date: 2020/8/11
     * Time: 16:30
     */
    public function getRole()
    {
        // 获取所有的权限
        $res = $this->modelObj->getAllRole();
        return $this->respondApi($res);
    }

    /**
     * Notes: 根据角色获取用户权限
     *
     * @author: chentulin
     * Date: 2020/8/11
     * Time: 17:42
     */
    public function getRuleByRoleId()
    {
        $id = $this->request->getGet('id');
        if (!isset($id)){
            return $this->respondApi([
                'code' => 20002, 'msg' => '获取权限失败,请检查是否选中角色，否则请联系管理员', ''
            ]);
        }
        return $this->respondApi($this->modelObj->getRuleByRoleId((int)$id));
    }

    /**
     * Notes: 更改角色权限
     *
     * @author: chentulin
     * Date: 2020/8/13
     * Time: 18:05
     */
    public function changeRoleByRule()
    {
        // 获取参数
        $params = $this->request->getJSON(true);
        if (isset($params['id'])){
            return $this->respondApi([
                'code' => 20003, 'msg' => '请确认选中角色', ''
            ]);
        }
        return $this->respondApi($this->modelObj->changeRoleByRule($params));
    }
}