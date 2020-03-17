<?PHP
namespace guanjiapo;
class UploadProducts {

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
        $post_data['method'] = 'zyx.selfbuiltmall.uploadproducts';//需要调取的接口名称
        $post_data['appkey'] = $config['appkey'];
        $post_data['token'] = $config['token'];//auto_code
        $post_data['timestamp'] = date('Y-m-d H:i:s',time());
        print_r($post_data);
        //添加业务参数
        echo '<br/>'.'<br/>'.'获取业务参数：'.'<br/>';
        $post_data['shopkey'] = $config['shopkey'];
        $post_data['products'] = $this->GetParam($timenow);
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

    public function GetParam($timenow = '') {
        $list = array();
        $list['productname'] = "uzipm";
        $list['numid'] = '12300'; //商品数字ID（请与订单明细中ptypeid保持一致）
        $list['outerid'] = 'SP_A'; //商品商家编码
        $list['picurl'] = "http://img.selfbuiltmall.com/producta.jpg"; //商品主图片地址
        $list['price'] = "3.00";
        $list['stockstatus'] = 1; //商品在售状态(1-在售;2-库中)

        //商品sku列表
        $skus = [];
        $skus['numid'] ="12300"; //商品数字ID
        $skus['skuid'] ="123123"; //Sku数字id（请与订单明细中skuid保持一致）
        $skus['outerskuid'] ="SP_A_红色_24码"; //Sku的商家编码
        $skus['properties'] ="1:11;2:22"; //sku的销售属性组合字符串,格式为p1:v1;p2:v2
        $skus['propertiesname'] ="红色_24码"; //sku的销售属性中文字符
        $skus['qty'] ="100"; //sku商品数量
        $skus['price'] ="23.00"; //sku商品价格
        $skus['barcode'] ="100000001231"; //商品的条形码

        $list['skus'][] = $skus;

        $arr[] = $list;
        $str =  json_encode($arr,JSON_UNESCAPED_SLASHES);
        return $str;
    }
}



?>