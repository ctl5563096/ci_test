<?php declare(strict_types=1);

namespace App\Controllers;

use App\Common\RestController;
use App\Models\HouseModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use ReflectionException;

/**
 * 房间数据以及分析数据
 *
 * Class Data
 * @package App\Controllers
 * @property HouseModel $houseModel
 */
class Data extends RestController
{
    protected $houseModel;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        $this->houseModel = new HouseModel();
        parent::initController($request, $response, $logger);
    }

    /**
     * Notes: 添加房间信息
     *
     * @throws ReflectionException
     * @author: chentulin
     * Date: 2020/9/21
     * Time: 23:44
     */
    public function insertHouse()
    {
        $id = $this->houseModel->insertHouse($this->request->getJSON(true));
        if (!is_int($id)) {
            return $this->respondApi($id);
        }
        return $this->respondApi(['id' => $id]);
    }

    /**
     * Notes: 获取房间列表
     *
     * @author: chentulin
     * Date: 2020/9/22
     * Time: 9:00
     */
    public function getHouseList()
    {
        $params = $this->request->getGet();
        if (!isset($params['page']) || !isset($params['pageSize'])) {
            $params['page']     = 1;
            $params['pageSize'] = 15;
        }
        return $this->respondApi($this->houseModel->getHouseList($params));
    }

    /**
     * Notes: 修改房屋信息
     *
     * @author: chentulin
     * Date: 2020/9/28
     * Time: 15:32
     */
    public function changeHouseData()
    {

    }
}