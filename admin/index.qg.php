<?php
$index_notice = array();
#[检查是否有留言信息]
$bookcount = $DB->qgCount("SELECT id FROM ".$prefix."book WHERE ifcheck=0 AND language='".$language."'");

#[检查是否有新的定单]
$ordercount = $DB->qgCount("SELECT id FROM ".$prefix."order WHERE status=0");

Foot("index.qg");
?>