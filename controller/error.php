<?php
if(!$userValidator->isLogin()){
    header("Location: /logout"); 
    return;
}	
/**
 * 載入頁面
 */
require 'view/header/default.php'; 
require 'view/body/error.php';
require 'view/footer/default.php';
