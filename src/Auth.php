<?php
namespace guanjiapo;
class Auth
{

    private $config=[];
    function __construct($config)
    {
        $this->config = $config;
    }

    function getAuth()
    {
        $config = $this->config;
        date_default_timezone_set('PRC');
        $params                = [];
        $params['CompanyName'] = $config['companyName'] ;
        $params['UserId']      = $config['userName'];
        $params["Password"]    = $config['userpass'];
        $params['TimeStamp'] = date('Y-m-d H:i:s');

        $eas             = new Aes();
        $iv              = mb_substr(trim($config['app_secret']), 5, 16);//获取偏移量
        $eas->iv         = $iv;
        $eas->encryptKey = trim($config['app_secret']);//转换密码
//        $params = array('TimeStamp'=>date('Y-m-d H:i:s',time()),'GrantType'=>'auth_token','AuthParam'=>trim($code));
        $p      = $eas->encrypt(trim(json_encode($params, JSON_UNESCAPED_SLASHES)));
        $params = array('appkey' => trim($config['appkey']), 'p' => $p, 'signkey' => trim($config['sign_key']));
        $sign   = $this->sha256(json_encode($params, JSON_UNESCAPED_SLASHES));
        //var_dump($sign);exit;
        $sign           = urlencode($sign);
        $post_data['p'] = urlencode($p);;//签名
        $post_data['sign']   = $sign;
        $post_data['appkey'] = $config['appkey'];//key

        $str = '';
        foreach ($post_data as $k => $v) {
            $str .= $k . '=' . $v . '&';
        }
        $str = trim($str, '&');

        $options  = array(
            'http' => array(
                'method'  => 'POST',
                'header'  => 'Content-type:application/x-www-form-urlencoded',
                'content' => $str,
                'timeout' => 15 * 60 // 超时时间（单位:s）
            ));
        $context  = stream_context_create($options);
        $output   = file_get_contents($config['get_auth_code_api_url'], false, $context); //发送post请求
        $ret_json = json_decode($output, true, 512, JSON_BIGINT_AS_STRING);

        $requestid = $ret_json['requestid']; //获取请求id
        //echo 'requestid=' . $requestid . '<br>';

        return $ret_json['response']['authcode'];

    }

    function getToken($authCode)
    {
        $params              = [];
        $params["GrantType"] = "auth_token";
        //$params['TimeStamp'] = "2019-05-05 10:59:26";
        $params['TimeStamp'] = date('Y-m-d H:i:s', time());
        $params['AuthParam'] = $authCode;


        $config = $this->config;
        date_default_timezone_set('PRC');
        $eas             = new aes();
        $iv              = mb_substr(trim($config['app_secret']), 5, 16);//获取偏移量
        $eas->iv         = $iv;
        $eas->encryptKey = trim($config['app_secret']);//转换密码
//        $params = array('TimeStamp'=>date('Y-m-d H:i:s',time()),'GrantType'=>'auth_token','AuthParam'=>trim($code));
        $p      = $eas->encrypt(trim(json_encode($params, JSON_UNESCAPED_SLASHES)));
        $params = array('appkey' => trim($config['appkey']), 'p' => $p, 'signkey' => trim($config['sign_key']));
        $sign   = $this->sha256(json_encode($params, JSON_UNESCAPED_SLASHES));
        //var_dump($sign);exit;
        $sign           = urlencode($sign);
        $post_data['p'] = urlencode($p);;//签名
        $post_data['sign']   = $sign;
        $post_data['appkey'] = $config['appkey'];//key

        $str = '';
        foreach ($post_data as $k => $v) {
            $str .= $k . '=' . $v . '&';
        }
        $str = trim($str, '&');

        $options = array(
            'http' => array(
                'method'  => 'POST',
                'header'  => 'Content-type:application/x-www-form-urlencoded',
                'content' => $str,
                'timeout' => 15 * 60 // 超时时间（单位:s）
            ));
        $context = stream_context_create($options);
        $output  = file_get_contents($config['get_token_api_url'], false, $context); //发送post请求

        $ret_json = json_decode($output, true, 512, JSON_BIGINT_AS_STRING);

        $tokenStr = $ret_json['response']['response'];
        $tokenStr = $eas->decrypt($tokenStr);
//        $tokenStr = trim($tokenStr);
        preg_match_all("/({.*})/is", $tokenStr, $matches);
        $tokenStr = $matches[1][0];
        $tokenArr = json_decode($tokenStr, true);
        /* $tokenArr内容
         ["appkey"]=>
          string(32) "68943923115886070418838901844741"
          ["auth_token"]=>
          string(40) "4L80PN6P93huPMXPMXkxLoj8RdnpbvDPX6v0Tayo"
          ["profileid"]=>
          string(18) "154275374803480431"
          ["employee_id"]=>
          string(17) "76029669893116591"
          ["expires_in"]=>
          float(863999.995996)
          ["refresh_token"]=>
          string(40) "2sbrlN0DTw0GLTGDph9j2EreBtswvHSEq5RLASSs"
          ["re_expires_in"]=>
          float(15551999.986987)
          ["timestamp"]=>
          string(18) "2020/3/14 23:36:33"
         */

        //var_dump($tokenArr);
        $requestid = $ret_json['requestid']; //获取请求id
        //echo 'requestid=' . $requestid . '<br>';

        return $tokenArr['auth_token'];


    }

    //sha256算法
    function sha256($data, $rawOutput = false)
    {
        if (!is_scalar($data)) {
            return false;
        }
        $data      = (string)$data;
        $rawOutput = !!$rawOutput;
        return hash('sha256', $data, $rawOutput);
    }
}