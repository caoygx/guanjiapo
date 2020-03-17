<?php
namespace guanjiapo;

class Aes {
    public $iv = "0000000000000000";//密钥偏移量IV，可自定义

    public $encryptKey = "自定义16位长度key";//AESkey，可自定义

    //加密
    public function encrypt($encryptStr) {
        $localIV = $this->iv;
        $encryptKey = $this->encryptKey;
        $encryptStr = trim($encryptStr);
        //Open module
        $module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, $localIV);

        //print "module = $module <br/>" ;

        mcrypt_generic_init($module, $encryptKey, $localIV);

        //Padding
        $block = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $pad = $block - (strlen($encryptStr) % $block); //Compute how many characters need to pad
        $encryptStr .= str_repeat(chr($pad), $pad); // After pad, the str length must be equal to block or its integer multiples
        //encrypt
        $encrypted = mcrypt_generic($module, $encryptStr);
        //Close
        mcrypt_generic_deinit($module);
        mcrypt_module_close($module);
        return base64_encode($encrypted);
    }

    //解密
    public function decrypt($encryptStr) {
        $localIV = $this->iv;
        $encryptKey = $this->encryptKey;

        //Open module
        $module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, $localIV);

        //print "module = $module <br/>" ;

        mcrypt_generic_init($module, $encryptKey, $localIV);

        $encryptedData = base64_decode($encryptStr);
        $encryptedData = mdecrypt_generic($module, $encryptedData);

        return $encryptedData;
    }
}