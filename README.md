
<p align="center">
	<strong>管家婆php sdk</strong>    
</p>


## 使用方法

1. 使用`composer`安装库：

```
composer require rrbrr/guanjiapo
```

2. 使用方法
```
        $config    = [];
        $config['appkey']                = '68943923115886070418838901844741';
        $config['app_secret']            = 'ONxYDyNaCoyTzsp83JoQ3YYuMPHxk3j7';
        $config['sign_key']              = 'lezitiancheng';
        $config['auth_code']             = 'yA7b3q04EUssKGisMBQRr6x2NmFG5OQa';
        $config['api_link']              = 'http://ca.mygjp.com:8002/api';
        $config['companyName']           = 'TestMall';
        $config['userName']              = '进品测试';
        $config['userpass']              = 'grasp@101';
        $config['get_token_url']         = 'http://ca.mygjp.com:8002/api/token';
        $config['redirect_url']          = 'http://' . $_SERVER['HTTP_HOST'] . '/GetToken.php';
        $config['get_auth_code_url']     = 'http://ca.mygjp.com:666/account/login?appkey=' . $config['appkey'] . '&redirect_url=' . $config['redirect_url'] . '&keyword=test';
        $config['get_auth_code_api_url'] = 'http://ca.mygjp.com:8002/api/login';//线上 http://apigateway.wsgjp.com.cn/api/login
        $config['get_token_api_url']     = 'http://ca.mygjp.com:8002/api/token ';//线上 http://apigateway.wsgjp.com.cn/api/token
        $config['shopkey']               = '74d51f5d-e6ea-4dea-b213-bf33b965592d';
        $config['apiurl']                = 'http://ca.mygjp.com:8002/api';

        $this->sdk = new Sdk($config);
        $param = [];// 这个变量里存放的是将要上传的数据，具体数据字段内容参见管家婆文档
        $r = $this->sdk->uploadProduct($param);
```





