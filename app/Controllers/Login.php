<?php


namespace App\Controllers;


use App\Common\Jwt;
use App\Common\RestController;
use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;

class Login extends RestController
{
    protected $modelName = 'App\Models\UserModel';

    public function login()
    {
        $param = $this->request->getJSON();
        $model = new UserModel();
        $arr   = $model->where(['user_name' => $param->username])->find();
        if (isset($param->password) && $param->password === current($arr)['password']) {
            $payload = [
                'iss' => current($arr)['user_name'],
                'iat' => time(),  //签发时间
                'exp' => time() + 7200,  //过期时间
                'nbf' => time() + 0,  //生效时间，在此之前是无效的
                'jti' => md5(uniqid('JWT') . time()),
                'sub' => current($arr)['id']
            ];
            // 生成jwt
            $token = Jwt::getToken($payload);
            return $this->respondApi(['token' => $token]);
        }
        return $this->failForbidden('连接被拒绝，请确认账号密码输入正确', 10001, 'access fail');
    }
}