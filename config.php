<?php
#[数据库信息]
$dbHost = "localhost";
$dbUser = "sinogacm_sino";
$dbPass = "fCbe4B146772EE15";
$dbData = "sinogacm_sino";

#[数据表前缀]
$prefix = "sino_";

#[是否启用调试]
$viewbug = 0;

#[是否启用伪静态页功能，使用为true，不使用为false]
$urlRewrite = false;

#[后台是否启用验证码功能，使用为true，不使用为false]
$isCheckCode = true;

# 防止直接访问
if (!defined('PHPOK_SET')) {
    header('HTTP/1.1 403 Forbidden');
    exit('Access denied');
}
?>