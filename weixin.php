<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/10
 * Time: 16:54
 */

namespace Home\Controller;


class ActTestController extends CommonController
{
    /**
     * 获取微信公众账号授权code
     * 本方法采用snsapi_useinfo
     */
    const APPID = 'wxc348cdb75ec65183';
    const AppSecret = 'cd6a99e8c30f4682851849ebc26b9ab9';
    function sendWxopenId(){
        $appid = self::APPID;
        $redirect_uri = urlencode('http://'.$_SERVER['HTTP_HOST'].'/index.php/ActTest/getWxOpenId');
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appid}&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect";
        header("Location:{$url}");
    }

    function getWxOpenId(){
        $code = $_GET['code'];
        //获取access_token：
        $secret = self::AppSecret;
        $appid = self::APPID;
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appid}&secret={$secret}&code={$code}&grant_type=authorization_code";
        $data_access_token = $this->sendcurl($url) ;

//        $refresh_token = $data_access_token->refresh_token;
//        //缓存refresh_token
//        $url1 = "https://api.weixin.qq.com/sns/oauth2/refresh_token?appid={$appid}&grant_type=refresh_token&refresh_token={$refresh_token}";
//        $ch = curl_init($url1);
//        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
//        curl_setopt($ch,CURLOPT_HEADER,0);
//        var_dump(json_decode(curl_exec($ch)));
//        curl_close($url1);

        $access_token = $data_access_token->access_token ;
        $openid = $data_access_token->openid ;
        $url2= "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$openid}&lang=zh_CN";
        //data object
        $data = $this->sendcurl($url2);
        return $data;
    }

    public function sendcurl($url){
        $ch = curl_init($url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_HEADER,0);
        $data = curl_exec($ch);
        curl_close($ch);
        return (json_decode($data));
    }
    
}