<?php


namespace App\Controllers;


use App\Common\Jwt;
use App\Common\RedisClient;
use App\Common\RestController;
use App\Models\RuleModel;
use App\Models\UserModel;
use Config\Services;
use GatewayClient\Gateway;

class Login extends RestController
{
    protected $modelName = 'App\Models\UserModel';

    /**
     * Notes: 登录获取token接口
     *
     * Author: chentulin
     * DateTime: 2021/3/5 15:34
     * E-MAIL: <chentulinys@163.com>
     * @return mixed
     */
    public function login()
    {
        // 去查询是否有验证码了,如果有就不需要重复发送
        $param = $this->request->getJSON();
        $model = new UserModel();
        $arr   = $model->where(['user_name' => $param->username])->find();
        $ruleModel = new RuleModel();
        if (isset($param->password) && $param->password === current($arr)['password']) {
            $payload = [
                'iss' => current($arr)['user_name'],
                'iat' => time(),  //签发时间
                'exp' => md5((string)time() . current($arr)['user_name']),  //过期时间存在redis里面
                'nbf' => time() + 0,  //生效时间，在此之前是无效的
                'jti' => md5(uniqid('JWT') . time()),
                'sub' => current($arr)['id'],
            ];
            // 生成jwt
            $token = Jwt::getToken($payload);
            // 把权限存到redis里面
            $authStr = json_encode($ruleModel->getAllAuth((int)current($arr)['id']));
            $key     = (int)current($arr)['id'] . '_' . current($arr)['user_name'];
            $cache   = Services::cache();
            $cache->save($key, $authStr, 7200);
            RedisClient::getInstance()->setex('ci_' . $payload['exp'], 7200, 'token_expire_time');
            $phone_num = current($arr)['phone_num'];
            $key = 'login_code_' . $phone_num;
            if (!RedisClient::getInstance()->exists($key) && (int)RedisClient::getInstance()->hget($key,$phone_num) !== 0) {
                return $this->respondApi(['status' => 423, 'phone_num' => current($arr)['phone_num'], 'msg' => '请验证手机号码']);
            }
            // 返回用户信息以方便存储到vuex里面去
            return $this->respondApi(['token' => $token, 'userInfo' => json_encode(current($arr))]);
        }else{
            return $this->respondApi(['code' => 201, 'msg' => '登录失败,请检查账号密码是否正确！']);
        }

    }

    /**
     * Notes: 登出
     *
     * Author: chentulin
     * DateTime: 2021/3/5 15:35
     * E-MAIL: <chentulinys@163.com>
     */
    public function logout()
    {
        $token  = $this->request->getHeader('Authorization');
        $token  = explode(' ', $token)[2];
        $res    = Jwt::delToken($token);
        $userId = Jwt::getUserIdByToken($token);
        // 这边也删除websocket连接的键
        RedisClient::getInstance()->hdel('websocket', [(string)$userId]);
        if (!$res) {
            return $this->respondApi(['code' => 400, 'msg' => '退出登陆失败,联系管理员']);
        }
        return $this->respondApi([]);
    }
}
