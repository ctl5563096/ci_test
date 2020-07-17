<?php namespace App\Controllers;

use CodeIgniter\HTTP\Message;

/**
 * 测试控制器
 *
 * Class Home
 * @package App\Controllers
 */
class Home extends BaseController
{
    use \CodeIgniter\API\ResponseTrait;

    public function index()
    {
        return $this->respond([
            'status' => 200,
            'msg'    => '请求成功1',
            'data'   => '',
        ], 200, 'success');
    }

    public function notFound()
    {
        $methods = ['get', 'post', 'delete', 'put'];
        if (in_array($this->request->getMethod(), $methods)) {
            return $this->respond(json_encode(['', 10002, 'api not found']), 404, 'api not found');
        }
        return $this->respond([], 200, 'option request');
    }
}
