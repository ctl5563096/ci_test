<?php declare(strict_types=1);


namespace App\Common;

/**
 * 发送短信
 *
 * Class sendSms
 * @package App\Common
 */
class SendSms
{
    /**
     * Notes: 发送测试号码
     *
     * Author: chentulin
     * DateTime: 2021/3/9 23:25
     * E-MAIL: <chentulinys@163.com>
     * @param $to
     * @param $data
     * @param $tempId
     * @return bool
     */
    public function sendTemplateSMS($to, $data)
    {
        //主帐号
        $accountSid = '8a216da86b8863a1016ba230f8f60f72';
        //主帐号Token
        $accountToken = '38aceab8c1a844939b6fe27b5dc37024';
        //应用Id
        $appId = '8a216da86b8863a1016ba230f9550f79';
        //请求地址，格式如下，不需要写https://
        $serverIP = 'app.cloopen.com';
        //请求端口
        $serverPort = '8883';
        //REST版本号
        $softVersion = '2013-12-26';
        // 初始化REST SDK
        $rest = new REST($serverIP, $serverPort, $softVersion);
        $rest->setAccount($accountSid, $accountToken);
        $rest->setAppId($appId);

        $result = $rest->sendTemplateSMS($to, $data, 1);
        if ($result == NULL) {
            return false;
        }
        if ($result->statusCode != 0) {
            return false;
        } else {
            return true;
        }
    }
}