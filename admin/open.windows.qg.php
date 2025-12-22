<?php
#[弹出窗口总控制台]
$incfile = rawurldecode($_GET["incfile"]);
$site_title = "欢迎进入弹窗页";
$C_tpl->set_file("open.windows");
$C_tpl->n();
$C_tpl->p("open.windows");
?>