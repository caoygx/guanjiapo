<?php
namespace guanjiapo;
class Config{
    function getConfig(){
        $config['auth_code'] = 'yA7b3q04EUssKGisMBQRr6x2NmFG5OQa';
        $config['api_link'] = 'http://ca.mygjp.com:8002/api';
        $config['companyName'] = 'TestMall';
        $config['userName'] = 'test';
        $config['userpass'] = 'grasp@101';
        $config['appkey'] = '68943923115886070418838901844741';
        $config['app_secret'] = 'ONxYDyNaCoyTzsp83JoQ3YYuMPHxk3j7';
        $config['sign_key'] = 'lezitiancheng';
        $config['get_token_url'] = 'http://ca.mygjp.com:8002/api/token';
        $config['redirect_url'] =  'http://'.$_SERVER['HTTP_HOST'].'/GetToken.php';
        $config['get_auth_code_url'] = 'http://ca.mygjp.com:666/account/login?appkey='.$config['appkey'].'&redirect_url='.$config['redirect_url'].'&keyword=test';
        $config['get_auth_code_api_url'] = 'http://ca.mygjp.com:8002/api/login';//线上 http://apigateway.wsgjp.com.cn/api/login
        $config['get_token_api_url'] = 'http://ca.mygjp.com:8002/api/token ';//线上 http://apigateway.wsgjp.com.cn/api/token

        $config['shopkey'] = '924adfd3-523f-4ef8-92c0-285dd394cfe0';
        $config['apiurl'] = 'http://ca.mygjp.com:8002/api';

        return $config;
    }
}
