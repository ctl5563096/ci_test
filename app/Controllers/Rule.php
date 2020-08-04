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
     */
    public function addRule()
    {
        var_dump(1111);die();
        return $this->respondApi($this->modelObj->addRule($this->request->getJSON(true)));
    }
}