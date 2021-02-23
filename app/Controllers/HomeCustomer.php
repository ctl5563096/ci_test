<?php declare(strict_types=1);


namespace App\Controllers;


use App\Common\RestController;

;

use App\Models\HomeCustomerModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class HomeCustomer
 * @package App\Controllers
 * @property $homeCustomerModel HomeCustomerModel;
 */
class HomeCustomer extends RestController
{
    protected $homeCustomerModel;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        $this->homeCustomerModel = new HomeCustomerModel();
        parent::initController($request, $response, $logger);
    }

    /**
     * Notes: 新增住房客户
     *
     * Author: chentulin
     * DateTime: 2021/2/5 15:28
     * E-MAIL: <chentulinys@163.com>
     */
    public function add()
    {
        $data = $this->request->getJSON(true);
        if (!$data) {
            $this->respondApi(['code' => 10009, '无法获取参数']);
        }
        $id = $this->homeCustomerModel->insertHomeCustomer($data);
        if (!is_int($id)) {
            return $this->respondApi($id);
        }
        return $this->respondApi(['id' => $id]);
    }

    /**
     * Notes: 获取租客列表
     *
     * Author: chentulin
     * DateTime: 2021/2/6 11:05
     * E-MAIL: <chentulinys@163.com>
     */
    public function getList()
    {
        $params = $this->request->getGet();
        if (!isset($params['page']) || !isset($params['pageSize'])) {
            $params['page']     = 1;
            $params['pageSize'] = 15;
        }
        return $this->respondApi($this->homeCustomerModel->getCustomerList($params));
    }

    /**
     * Notes: 获取详情
     *
     * Author: chentulin
     * DateTime: 2021/2/22 19:41
     * E-MAIL: <chentulinys@163.com>
     */
    public function detail()
    {
        $customerId = (int)$this->request->getPostGet('id');
        if (!$customerId) {
            $this->respondApi(['code' => 10009, '无法获取参数']);
        }
        $info = $this->homeCustomerModel->getInfoByCustomerId($customerId);
        return $this->respondApi($info);
    }

    /**
     * Notes: 更新租客详情
     *
     * Author: chentulin
     * DateTime: 2021/2/22 20:15
     * E-MAIL: <chentulinys@163.com>
     */
    public function updateInfo()
    {
        $data = $this->request->getJSON(true);
        if (isset($data['id'])) {
            $this->respondApi(['code' => 10009, '无法获取参数id']);
        }
        $res = $this->homeCustomerModel->updateInfo($data);
        if (!$res) {
            return $this->respondApi(['code' => 10010, '更新失败']);
        } else {
            return $this->respondApi([]);
        }
    }
}