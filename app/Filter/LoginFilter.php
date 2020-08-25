<?php

namespace App\Filter;

use App\Common\Jwt;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Predis\Client;

class LoginFilter implements FilterInterface
{
    use \CodeIgniter\API\ResponseTrait;

    public function before(RequestInterface $request)
    {
        $methods = ['get', 'post', 'delete', 'put'];
        // 从Service类里面提取响应实例 由于在过滤器Response还未被实例化
        $this->response = Services::response();
        // 规避options请求带来的token验证导致的401
        if (in_array($request->getMethod(), $methods)) {
            $token    = $request->getHeader('Authorization');
            $tokenArr = explode(' ', $token);;
            if (count($tokenArr) !== 3) {
                // 响应 401 状态码
                return $this->response->setStatusCode(400,'LOSE TOKEN')->send();
            }
            // 验证token
            $res = Jwt::verifyToken($tokenArr[2]);
            if ($res['res'] === false) {
                return $this->response->setStatusCode(403,'TOKEN AUTH FAIL')->send();
            }
            $router = Services::router();
            // 验证权限
            $controllerStr = $router->controllerName();
            $controller = explode("\\", $controllerStr)[3];
            $method     = $router->methodName();
            $autUrl     = $controller.'\\'.$method;
            $cache      = Services::cache();
            $authStr    = $cache->get($res['res']['sub'] . '_' . $res['res']['iss']);
            $authArr    = json_decode($authStr);
            if ((int)$res['res']['sub'] !== 1 && !in_array($autUrl,$authArr)){
                return $this->response->setStatusCode(401,'USER NOT  AUTH')->send();
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response)
    {
        // TODO: Implement after() method.
    }
}