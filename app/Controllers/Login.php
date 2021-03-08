<?php


namespace App\Controllers;


use App\Common\Jwt;
use App\Common\RestController;
use App\Models\RuleModel;
use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;
use Config\Services;

class Login extends RestController
{
    protected $modelName = 'App\Models\UserModel';

    public function login()
    {
        $param     = $this->request->getJSON();
        $model     = new UserModel();
        $arr       = $model->where(['user_name' => $param->username])->find();
        $ruleModel = new RuleModel();
        if (isset($param->password) && $param->password === current($arr)['password']) {
            $payload = [
                'iss' => current($arr)['user_name'],
                'iat' => time(),  //签发时间
                'exp' => time() + 7200,  //过期时间
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
            // 返回用户信息以方便存储到vuex里面去
            return $this->respondApi(['token' => $token, 'userInfo' => json_encode(current($arr))]);
        }
        return  $this->respondApi(['code' => 201, 'msg' => '登录失败,请检查账号密码是否正确！']);
    }
}
