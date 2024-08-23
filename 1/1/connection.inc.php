<?php
session_start();
$con=mysqli_connect("localhost","root","","cloth");
define('SERVER_PATH',$_SERVER['DOCUMENT_ROOT'].'/1/1/');
define('SITE_PATH','http://localhost/1/1/');

define('INSTAMOJO_REDIRECT',SITE_PATH.'payment_complete.php');

define('PRODUCT_IMAGE_SERVER_PATH',SERVER_PATH.'media/product/');
define('PRODUCT_IMAGE_SITE_PATH',SITE_PATH.'media/product/');

define('PRODUCT_MULTIPLE_IMAGE_SERVER_PATH',SERVER_PATH.'media/product_images/');
define('PRODUCT_MULTIPLE_IMAGE_SITE_PATH',SITE_PATH.'media/product_images/');

define('BANNER_SERVER_PATH',SERVER_PATH.'media/banner/');
define('BANNER_SITE_PATH',SITE_PATH.'media/banner/');

define('INSTAMOJO_KEY','key');
define('INSTAMOJO_TOKEN','token');



define('SMTP_EMAIL','email@gmail.com');
define('SMTP_PASSWORD','password');



define('SMS_KEY','sms_key');

?>