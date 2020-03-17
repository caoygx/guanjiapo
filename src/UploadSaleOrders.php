<?PHP
namespace guanjiapo;
class UploadSaleorders {
    function get_config(){
        $config['appkey'] = '68943923115886070418838901844741';
        $config['shopkey'] = '924adfd3-523f-4ef8-92c0-285dd394cfe0';
        $config['sign_key'] = 'lezitiancheng';
        $config['token'] = 'aoff7pvcJ7ycMa7lyTnUwCVmPey4Ylksv0Pq3PXh';
        $config['apiurl'] = 'http://ca.mygjp.com:8002/api';
        return $config;
    }

    public function execute($token){
        $config = (new Config())->getConfig();
        $config['token'] = $token;
        $request = $this->GetRequest($config);
        $str = '';
        foreach($request as $k=>$v){
            $str .= $k.'='.urlencode($v).'&';
        }
        echo '<br/>'.'<br/>'.'最终post的body数据：'.'<br/>';
        print_r($str);
        $str = trim($str,'&');
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-type:application/x-www-form-urlencoded',
                'content' => $str,
                'timeout' => 15 * 60 // 超时时间（单位:s）
            )
        );
        $context = stream_context_create($options);
        $output = file_get_contents($config['apiurl'], false, $context); //发送post请求
        echo '<br/>'.'<br/>'.'接口返回：'.'<br/>';
        print_r($output);
        $ret_json=json_decode($output,true,512,JSON_BIGINT_AS_STRING);
        //print_r($ret_json);
    }

    public function GetRequest($config = '') {
        date_default_timezone_set('PRC');
        $timenow = date('Y-m-d H:i:s',time());
        //添加系统参数
        echo '<br/>'.'获取系统参数：'.'<br/>';
        $post_data['method'] = 'zyx.selfbuiltmall.uploadsaleorders';//需要调取的接口名称
        $post_data['appkey'] = $config['appkey'];
        $post_data['token'] = $config['token'];//auto_code
        $post_data['timestamp'] = date('Y-m-d H:i:s',time());
        print_r($post_data);
        //添加业务参数
        echo '<br/>'.'<br/>'.'获取业务参数：'.'<br/>';
        $post_data['shopkey'] = $config['shopkey'];
        $post_data['orders'] = $this->GetOrdersParam($timenow);
        print_r($post_data['shopkey']);
        echo '<br/>';
        print_r($post_data['orders']);
        //获取签名
        echo '<br/>'.'<br/>'.'获取签名：';
        $post_data['sign'] =  $this->GetSign($config['sign_key'],$post_data);
        return $post_data;
    }


    function GetSign($sign_key,$post_data = ''){
        ksort($post_data);
        $sign_arr = array();
        foreach($post_data as $k=>$v){
            $sign_arr[] = $k.$v;
        }
        $sign_str = implode('',$sign_arr).$sign_key;
        $sign_str = $this->characet($sign_str);
        echo '<br/>'.'sign加密前字符串：'.'<br/>';
        print_r($sign_str);
        $sign_str = md5($sign_str);
        echo '<br/>'.'sign结果（MD5）：'.'<br/>';
        print_r($sign_str);
        return $sign_str;
    }

    //utf-8编码
    public function characet($data){
        if( !empty($data) ){
            $fileType = mb_detect_encoding($data , array('UTF-8','GBK','LATIN1','BIG5')) ;
            if( $fileType != 'UTF-8'){
                $data = mb_convert_encoding($data ,'utf-8' , $fileType);
            }
        }
        return $data;
    }

    public function GetOrdersParam($timenow = '') {
        $list = array();
        $list['BuyerMessage'] = "订单测试";
        $list['EShopBuyer']['CustomerEmail'] = 'zzz@grasp.com';
        $list['EShopBuyer']['CustomerReceiver'] = 'zzz';
        $list['EShopBuyer']['CustomerReceiverAddress'] = "天府软件园D2";
        $list['EShopBuyer']['CustomerReceiverCity'] = '成都市';
        $list['EShopBuyer']['CustomerReceiverCountry'] = '中国';
        $list['EShopBuyer']['CustomerReceiverDistrict'] = '高新区';
        $list['EShopBuyer']['CustomerReceiverMobile'] = '12345678910';
        $list['EShopBuyer']['CustomerReceiverPhone'] = '028-1234567';
        $list['EShopBuyer']['CustomerReceiverProvince'] = '四川省';
        $list['EShopBuyer']['CustomerReceiverZipcode'] = '';
        $list['EShopBuyer']['CustomerShopAccount'] = 'zzz';
        $list['InvoiceTitle'] = '发票抬头';
        $list['SellerMemo'] = '卖家备注';
        $list['TradeCreateTime'] = $timenow;
        $list['TradeFinishTime'] = $timenow;
        $list['TradePaiedTime'] = $timenow;
        $list['TradeModifiedTime'] = $timenow;
        $list['TradeStatus'] = '2';
        $list['TradeTotal'] = '600';
        $list['TradeType'] = '0';
        $list['TradeId'] = 'DD-20180131-001';
        $list['Total'] = '320';
        $list['PreferentialTotal'] = '280';
        $list['InvoiceCode'] = '123456789';
        $list['MallSendType'] = '0';
        $list['orderdetails'][0]['ProductName'] = '测试商品';
        $list['orderdetails'][0]['PtypeId'] = '110890-9001';
        $list['orderdetails'][0]['Qty'] = '1';
        $list['orderdetails'][0]['Price'] = '70';
        $list['orderdetails'][0]['SkuId'] = '110890-9528';
        $list['orderdetails'][0]['TradeOriginalPrice'] = '100';
        $list['orderdetails'][0]['PreferentialTotal'] = '30';
        $list['orderdetails'][0]['PlatformPropertiesName'] = '属性1';
        $list['orderdetails'][1]['ProductName'] = '测试商品';
        $list['orderdetails'][1]['PtypeId'] = '110890-9001';
        $list['orderdetails'][1]['Qty'] = '5';
        $list['orderdetails'][1]['Price'] = '50';
        $list['orderdetails'][1]['SkuId'] = '110890-9528';
        $list['orderdetails'][1]['TradeOriginalPrice'] = '100';
        $list['orderdetails'][1]['PreferentialTotal'] = '250';
        $list['orderdetails'][1]['PlatformPropertiesName'] = '属性2';
        $arr[] = $list;
        $str =  json_encode($arr,JSON_UNESCAPED_SLASHES);
        return $str;
    }
}



?>