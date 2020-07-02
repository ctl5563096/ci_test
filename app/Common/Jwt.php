<?php declare(strict_types=1);


namespace App\Common;

use PHPUnit\Framework\Constraint\IsFalse;

/**
 * 实现jwt
 *
 * Class Jwt
 * @package App\Common
 */
class Jwt
{
    private static $header = [
        'alg' => 'HS256', //生成signature的算法
        'typ' => 'JWT'    //类型
    ];

    private static $key = 'backend';

    /**
     *
     * 获取token
     * @param array $payload
     */
    public static function getToken(array $payload)
    {
        if (isset($payload)) {
            $base64header  = self::base64UrlEncode(json_encode(self::$header, JSON_UNESCAPED_UNICODE));
            $base64payload = self::base64UrlEncode(json_encode($payload, JSON_UNESCAPED_UNICODE));
            return $base64header . '.' . $base64payload . '.' . self::signature($base64header . '.' . $base64payload, self::$key, self::$header['alg']);
        }
        return false;
    }


    public static function verifyToken($Token)
    {
        $tokens = explode('.', $Token);
        if (count($tokens) != 3)
            return false;

        [$base64header, $base64payload, $sign] = $tokens;
        $base64decodeheader = json_decode(self::base64UrlDecode($base64header), true);
        if (empty($base64decodeheader['alg']))
            return false;

        //签名验证
        if (self::signature($base64header . '.' . $base64payload, self::$key, $base64decodeheader['alg']) !== $sign)
            return false;

        $payload = json_decode(self::base64UrlDecode($base64payload), true);

        //签发时间大于当前服务器时间验证失败
        if (isset($payload['iat']) && $payload['iat'] > time())
            return false;

        //过期时间小宇当前服务器时间验证失败
        if (isset($payload['exp']) && $payload['exp'] < time())
            return false;

        //该nbf时间之前不接收处理该Token
        if (isset($payload['nbf']) && $payload['nbf'] > time())
            return false;
        return $payload;
    }

    private static function base64UrlEncode(string $input)
    {
        return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
    }


    private static function base64UrlDecode(string $input)
    {
        $remainder = strlen($input) % 4;
        if ($remainder) {
            $addlen = 4 - $remainder;
            $input  .= str_repeat('=', $addlen);
        }
        return base64_decode(strtr($input, '-_', '+/'));
    }


    private static function signature(string $input, string $key, $alg = 'HS256')
    {
        $alg_config = [
            'HS256' => 'sha256',
        ];
        return self::base64UrlEncode(hash_hmac($alg_config[$alg], $input, $key, true));
    }
}