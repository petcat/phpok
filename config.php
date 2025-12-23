<?php
#[数据库信息 - 使用SQLite]
$dbHost = "";
$dbUser = "";
$dbPass = "";
$dbData = $_ENV['DB_NAME'] ?? "/workspace/data/database.db";  // 使用SQLite数据库文件

#[数据表前缀]
$prefix = "sino_";

#[是否启用调试]
$viewbug = 0;

#[是否启用伪静态页功能，使用为true，不使用为false]
$urlRewrite = false;

#[后台是否启用验证码功能，使用为true，不使用为false]
$isCheckCode = true;
?>