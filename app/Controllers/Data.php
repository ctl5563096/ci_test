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
    public function updateHouseData()
    {
        $data = $this->request->getJSON(true);
        if (isset($data['id'])){
            $this->respondApi(['code' => 10009, '无法获取参数id']);
        }
        $res = $this->houseModel->updateInfo($data);
        if (!$res){
            return $this->respondApi(['code' => 10010, '更新失败']);
        }else{
            return $this->respondApi([]);
        }
    }

    /**
     * Notes: 根据houseId获取房屋详细信息
     *
     * Author: chentulin
     * DateTime: 2021/1/29 15:31
     * E-MAIL: <chentulinys@163.com>
     */
    public function getInfoById()
    {
        $houseId = (int)$this->request->getPostGet('id');
        if (!$houseId){
            $this->respondApi(['code' => 10009, '无法获取参数']);
        }
        $info = $this->houseModel->getInfoByHouseId($houseId);
        return $this->respondApi($info);
    }
}