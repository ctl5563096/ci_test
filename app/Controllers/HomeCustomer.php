<?php declare(strict_types=1);


namespace App\Controllers;


use App\Common\RestController;;

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
        var_dump(111111111);die();
        $id = $this->homeCustomerModel->insertHomeCustomer($this->request->getJSON(true));
        if (!is_int($id)) {
            return $this->respondApi($id);
        }
        return $this->respondApi(['id' => $id]);
    }
}