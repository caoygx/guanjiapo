<?php
namespace guanjiapo;
class Sdk{
    private $config=[];
    private $token="";
    function __construct($config)
    {
        $this->config = $config;
        $auth = new Auth($config);
        $authcode = $auth->getAuth();
        $this->token = $auth->getToken($authcode);
    }

    public function uploadProduct($param){
        $post_data['method'] = 'zyx.selfbuiltmall.uploadproducts';//需要调取的接口名称
        $post_data['products'] = $param;
        return $this->upload($post_data);
    }

    public function uploadOrder($param){
        $post_data['method'] = 'zyx.selfbuiltmall.uploadsaleorders';//需要调取的接口名称
        $post_data['orders'] = $param;
        return $this->upload($post_data);
    }

    public function queryOrder($param){
        $post_data['method'] = 'beefun.selfbuiltmall.querysaleorder';//需要调取的接口名称
        if(!empty($param)) $post_data = array_merge($post_data,$param);
        return $this->upload($post_data);
    }


    function upload($post_data,$format=''){
        $post_data['appkey'] = $this->config['appkey'];
        $post_data['token'] = $this->token;//auto_code
        $post_data['timestamp'] = date('Y-m-d H:i:s',time());

        //print_r($post_data);
        //echo '<br/>'.'<br/>'.'获取业务参数：'.'<br/>';

        $post_data['shopkey'] = $this->config['shopkey'];

        //print_r($post_data['shopkey']);
        //echo '<br/>';
        //print_r($post_data['orders']);
        //echo '<br/>'.'<br/>'.'获取签名：';

        $post_data['sign'] =  $this->GetSign($this->config['sign_key'],$post_data);
        $request = $post_data;

        $str = '';
        foreach($request as $k=>$v){
            $str .= $k.'='.urlencode($v).'&';
        }

        //echo '<br/>'.'<br/>'.'最终post的body数据：'.'<br/>';
        //print_r($str);

        $str = trim($str,'&');

        if(empty($format)) $format = "application/x-www-form-urlencoded";
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-type:'.$format,
                'content' => $str,
                'timeout' => 15 * 60 // 超时时间（单位:s）
            )
        );
        $context = stream_context_create($options);
        $output = file_get_contents($this->config['apiurl'], false, $context); //发送post请求

        //echo '<br/>'.'<br/>'.'接口返回：'.'<br/>';
        //print_r($output);

        $ret_json=json_decode($output,true,512,JSON_BIGINT_AS_STRING);
        //print_r($ret_json);
        return $ret_json;
    }

    function GetSign($sign_key,$post_data = ''){
        ksort($post_data);
        $sign_arr = array();
        foreach($post_data as $k=>$v){
            $sign_arr[] = $k.$v;
        }
        $sign_str = implode('',$sign_arr).$sign_key;
        $sign_str = $this->characet($sign_str);
        //echo '<br/>'.'sign加密前字符串：'.'<br/>';
        //print_r($sign_str);
        $sign_str = md5($sign_str);
        //echo '<br/>'.'sign结果（MD5）：'.'<br/>';
        //print_r($sign_str);
        return $sign_str;
    }

    public function characet($data){
        if( !empty($data) ){
            $fileType = mb_detect_encoding($data , array('UTF-8','GBK','LATIN1','BIG5')) ;
            if( $fileType != 'UTF-8'){
                $data = mb_convert_encoding($data ,'utf-8' , $fileType);
            }
        }
        return $data;
    }


}