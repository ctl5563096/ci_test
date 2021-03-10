<?php declare(strict_types=1);


namespace App\Controllers;


use App\Common\RedisClient;
use App\Common\REST;
use App\Common\RestController;
use App\Common\SendSms;
use App\Models\CarouselModel;
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
 * @property CarouselModel $carouselModel
 */
class System extends RestController
{
    protected $parameterModel;
    protected $carouselModel;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        $this->parameterModel = new ParameterModel();
        $this->carouselModel  = new CarouselModel();
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
        $other['is_enabled'] = 2;
        // 晚点考虑存到redis里面去
        $list = $this->parameterModel->getList(1, 1, '', -1, true, true, $other);
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
        $other  = [];
        $params = $this->request->getGet();
        if (!isset($params['page']) || !isset($params['pageSize'])) {
            $params['page']     = 1;
            $params['pageSize'] = 15;
        }
        $keywords = '';
        if ($params['keywords']) {
            $keywords = $params['keywords'];
        }
        $is_enabled = false;
        if ($params['is_enabled']) {
            $is_enabled          = true;
            $other['is_enabled'] = $params['is_enabled'];
        }
        $list = $this->parameterModel->getList((int)$params['page'], (int)$params['pageSize'], $keywords, 1, false, $is_enabled, $other);
        return $this->respondApi($list);
    }

    /**
     * Notes: 获取详情
     *
     * Author: chentulin
     * DateTime: 2021/2/2 19:52
     * E-MAIL: <chentulinys@163.com>
     */
    public function getParameterDetail()
    {
        $params = $this->request->getGet();
        if (!isset($params['id'])) {
            $this->respondApi(['code' => 10009, '无法获取参数']);
        }
        $info              = $this->parameterModel->getInfoById((int)$params['id']);
        $info['para_code'] = $info['code'];
        unset($info['code']);
        return $this->respondApi($info);
    }

    /**
     * Notes: 修改参数
     *
     * Author: chentulin
     * DateTime: 2021/2/3 11:40
     * E-MAIL: <chentulinys@163.com>
     */
    public function editParameter()
    {
        $data = $this->request->getJSON(true);
        if (isset($data['id'])) {
            $this->respondApi(['code' => 10009, '无法获取参数id']);
        }
        $res = $this->parameterModel->updateInfo($data);
        return $this->respondApi($res);
    }

    /**
     * Notes: 新增参数
     *
     * Author: chentulin
     * DateTime: 2021/2/3 14:36
     * E-MAIL: <chentulinys@163.com>
     * @throws \ReflectionException
     */
    public function addParameter()
    {
        $res = $this->parameterModel->addRecord($this->request->getJSON(true));
        if (!is_int($res)) {
            return $this->respondApi($res);
        }
        return $this->respondApi(['id' => $res]);
    }

    /**
     * Notes:
     * Author: chentulin
     * DateTime: 2021/2/5 17:28
     * E-MAIL: <chentulinys@163.com>
     * @return array|void
     */
    public function deleteRecord()
    {
        $params = $this->request->getGet();
        if (!isset($params['id'])) {
            $this->respondApi(['code' => 10009, '无法获取参数']);
        }
        $info = $this->parameterModel->delete((int)$params['id']);
        if (!$info) {
            return $info = ['code' => 10012, '删除失败'];
        } else {
            $info = [];
        }
        return $this->respondApi($info);
    }

    /**
     * Notes: 获取所有的轮播图
     *
     * Author: chentulin
     * DateTime: 2021/3/1 16:32
     * E-MAIL: <chentulinys@163.com>
     */
    public function getCarousel()
    {
        $params = $this->request->getGet();
        if (!isset($params['page']) || !isset($params['pageSize'])) {
            $params['page']     = 1;
            $params['pageSize'] = 15;
        }
        $list = $this->carouselModel->getList($params);
        return $this->respondApi($list);
    }

    /**
     * Notes: 通过Id获取轮播图详情
     *
     * Author: chentulin
     * DateTime: 2021/3/2 17:14
     * E-MAIL: <chentulinys@163.com>
     */
    public function getInfoCarouselById()
    {
        $params = $this->request->getGet();
        if (!isset($params['id'])) {
            $this->respondApi(['code' => 10009, '无法获取参数']);
        }
        $list = $this->carouselModel->getInfoCarouselById((int)$params['id']);
        return $this->respondApi($list);
    }

    /**
     * Notes: 更新轮播图信息
     *
     * Author: chentulin
     * DateTime: 2021/3/3 10:29
     * E-MAIL: <chentulinys@163.com>
     * @throws \ReflectionException
     */
    public function updateCarousel()
    {
        $data = $this->request->getJSON(true);
        if (!isset($data['id'])) {
            $this->respondApi(['code' => 10009, '无法获取参数id']);
        }
        $id = $data['id'];
        unset($data['id']);
        $res = $this->carouselModel->updateInfo((int)$id,$data);
        return $this->respondApi($res);
    }

    /**
     * Notes:
     * Author: chentulin
     * DateTime: 2021/3/3 14:53
     * E-MAIL: <chentulinys@163.com>
     * @throws \ReflectionException
     */
    public function addCarousel()
    {
        $data = $this->request->getJSON(true);
        $res = $this->carouselModel->addRecord($data);
        return $this->respondApi($res);
    }

    /**
     * Notes: 获取所有的启用的轮播图
     *
     * Author: chentulin
     * DateTime: 2021/3/3 15:31
     * E-MAIL: <chentulinys@163.com>
     */
    public function getIndexCarousel()
    {
        $list = $this->carouselModel->getEnableCarousel();
        return $this->respondApi($list);
    }

    /**
     * Notes: 获取验证码
     *
     * Author: chentulin
     * DateTime: 2021/3/9 23:15
     * E-MAIL: <chentulinys@163.com>
     */
    public function sendSmsCode()
    {
        $params = $this->request->getJSON(true);
        if (!isset($params['phone_num'])) {
            $this->respondApi(['code' => 10009, '无法获取手机号码,请联系管理员']);
        }
        // 开发环境防止多次调用短信sdk 就需要把这段代码打开
        RedisClient::getInstance()->hset('login_code_' . $params['phone_num'] ,$params['phone_num'] ,'123456');
        RedisClient::getInstance()->expire('login_code_' . $params['phone_num'],300);
        return $this->respondApi(['smsStatus' => 200, 'msg'=>'发送验证码成功']);
        $res = (new SendSms())->sendTemplateSMS($params['phone_num'],['123456',5]);
        if (!$res){
            return $this->respondApi(['smsStatus' => 500, 'msg'=>'发送验证码失败']);
        }else{
            // 存进去redis
            RedisClient::getInstance()->hset('login_code_' . $params['phone_num'] ,$params['phone_num'] ,'123456');
            RedisClient::getInstance()->expire('login_code_' . $params['phone_num'],300);
            return $this->respondApi(['smsStatus' => 200, 'msg'=>'发送验证码成功']);
        }
    }
}