<?php declare(strict_types=1);


namespace App\Common;


use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Psr\Log\LoggerInterface;

class RestController extends ResourceController
{
    protected $format = 'json';

    public $verbs = [
        'get'    => 'index',
        'post'   => 'create',
        'put'    => 'update',
        'delete' => 'delete',
    ];

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
    }

    public function respondApi(array $data)
    {
        // 判断$data是否为正常的请求返回的数据
        if (array_key_exists('code', $data)) {
            return $this->respond($data);
        }
        $data = [
            'code' => 200,
            'msg'  => '请求成功',
            'data' => $data,
        ];
        return $this->respond($data);
    }
}