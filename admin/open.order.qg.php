<?php
#[查看商品信息及联系人信息]
$id = intval($id);
if(!$id)
{
	Error("操作不正确！");
}
$rs = $DB->qgGetOne("SELECT * FROM ".$prefix."order WHERE id='".$id."'");
$rs["postdate"] = date("Y 年 m 月 d 日 H 时 i 分 s 秒",$rs["postdate"]);
$TPL->p("open.order.qg");
exit;
?>