<?php declare(strict_types=1);


namespace App\Controllers;


use App\Common\RestController;
use App\Models\ParameterModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * 系统类控制器
 *
 * Class System
 * @package App\Controllers
 * @property ParameterModel $parameterModel
 */
class System extends RestController
{
    protected $parameterModel;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        $this->parameterModel = new ParameterModel();
        parent::initController($request, $response, $logger);
    }

    /**
     * Notes: 系统初始化时获取参数
     *
     * Author: chentulin
     * DateTime: 2021/2/1 16:55
     * E-MAIL: <chentulinys@163.com>
     */
    public function getParameterInit()
    {
        $list = $this->parameterModel->getList(1,1,'',-1,true,true);
        return $this->respondApi($list);
    }

    /**
     * Notes: 获取
     *
     * Author: chentulin
     * DateTime: 2021/2/1 17:17
     * E-MAIL: <chentulinys@163.com>
     * @return mixed
     */
    public function getParameterList()
    {
        $params = $this->request->getGet();
        if (!isset($params['page']) || !isset($params['pageSize'])) {
            $params['page']     = 1;
            $params['pageSize'] = 15;
        }
        $keywords = '';
        if (!$params['keywords']){
            $keywords = $params['keywords'];
        }
        $list = $this->parameterModel->getList($params['page'],$params['pageSize'],$keywords,-1);
        return $this->respondApi($list);
    }
}