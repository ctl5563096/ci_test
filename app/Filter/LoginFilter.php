<?php

namespace App\Filter;

use App\Common\Jwt;
use App\Common\RedisClient;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

/**
 * 接口调用过滤器 主要负责权限验证
 *
 * Class LoginFilter
 * @package App\Filter
 */
class LoginFilter implements FilterInterface
{
    use \CodeIgniter\API\ResponseTrait;

    public function before(RequestInterface $request)
    {
        $methods = ['get', 'post', 'delete', 'put'];
        $allAuth = [
            'User\getUserInfo',
            'User\updateUserInfo',
            'System\getParameterInit',
            'System\getIndexCarousel',
            'Login\logout'
        ];
        // 从Service类里面提取响应实例 由于在过滤器Response还未被实例化
        $this->response = Services::response();
        // 规避options请求带来的token验证导致的401
        if (in_array($request->getMethod(), $methods)) {
            $token    = $request->getHeader('Authorization');
            $tokenArr = explode(' ', $token);;
            if (count($tokenArr) !== 3) {
                // 响应 401 状态码
                return $this->response->setStatusCode(400, 'LOSE TOKEN')->send();
            }
            // 验证token
            $res = Jwt::verifyToken($tokenArr[2]);
            if ($res['res'] === false) {
                return $this->response->setStatusCode(403, 'TOKEN AUTH FAIL')->send();
            }
            $router = Services::router();
            // 验证权限 跳过部分全局接口：比如获取个人信息接口
            $controllerStr = $router->controllerName();
            $controller    = explode("\\", $controllerStr)[3];
            $method        = $router->methodName();
            $authUrl       = $controller . '\\' . $method;
            $cache         = Services::cache();
            $authStr       = $cache->get($res['res']['sub'] . '_' . $res['res']['iss']);
            $authArr = json_decode($authStr);
            // 跳过部分接口
            if (!in_array($authUrl, $allAuth)) {
                // 使用token去查询用户 防止token被不同用户调用
                if ((int)$res['res']['sub'] !== 1 && !in_array($authUrl, $authArr)) {
                    return $this->response->setStatusCode(401, 'USER NOT AUTH')->send();
                }
            }
            // 这里返回的redis单例去进行更多的操作 去刷新权限在redis中的存活时间
            $client = RedisClient::getInstance();
            $client->expire('ci_' . $authStr, 7200);
//            $cache->save('userAuthId',$res['res']['sub']);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response)
    {
        // TODO: Implement after() method.
//        $cache      = Services::cache();
//        $cache->delete('userAuthId');
    }
}