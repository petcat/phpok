<?php
#[预览图片信息]
$id = intval($id);
if(!$id)
{
	Error("参数传递错误！");
}
$rs = $DB->qgGetOne("SELECT * FROM ".$prefix."upfiles WHERE id='".$id."'");
$ext = substr($rs["filename"],-3,3);
$ext = strtolower($ext);
if($ext && @strpos("jpg,gif,png",$ext) !== false)
{
	$ispicture = true;
}
else
{
	$ispicture = false;
}
if(!file_exists($rs["folder"].$rs["filename"]))
{
	Error("文件不存在！");
}
$qgfilename = $rs["folder"].$rs["filename"];
$thumb = $rs["thumbfile"] ? $rs["folder"].$rs["thumbfile"] : false;
$mark = $rs["markfile"] ? $rs["folder"].$rs["markfile"] : false;
$TPL->p("open.viewpic.qg");
?>