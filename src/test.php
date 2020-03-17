<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/3/16
 * Time: 15:06
 */

include "Auth.php";
include "UploadSaleOrders.php";
include "UploadProducts.php";
$auth = new Auth();
$authcode = $auth->getAuth();
$token = $auth->getToken($authcode);
/*$api = new UploadSaleorders();
$api->execute($token);*/

$prodcut = new UploadProducts();
$prodcut->execute($token);