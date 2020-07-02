<?php

namespace App\Filter;

use App\Common\Jwt;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class LoginFilter implements FilterInterface
{
    use \CodeIgniter\API\ResponseTrait;

    public function before(RequestInterface $request)
    {
        $token = $request->getHeader('Authorization');
        $tokenArr = explode(' ',$token);
        // 从Service类里面提取响应实例 由于在过滤器Response还未被实例化
        $this->response = Services::response();
        if(count($tokenArr) !== 3){
            // 响应 201 状态码
            return $this->failUnauthorized('Lose Auth token' ,10001,'access fail');
        }
        // 验证token
        Jwt::verifyToken($tokenArr[2]);
    }

    public function after(RequestInterface $request, ResponseInterface $response)
    {
        // TODO: Implement after() method.
    }
}