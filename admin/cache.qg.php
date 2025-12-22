<?php
#[缓存文件管理]
if($sysAct == "delete")
{
	#[删除选定的文件]
	$delfolder = SafeHtml(rawurldecode($delfolder));
	if(!$delfolder)
	{
		Error("操作非法！","admin.php?file=cache&act=list");
	}
	$FS->qgDelete("data/".$delfolder);
	if($delfolder == "phpok_tplc")
	{
		$msg = "网站模板编译缓存信息已清空...";
	}
	elseif($delfolder == "admin_tplc")
	{
		$msg = "后台编辑缓存已清空...";
	}
	elseif($delfolder == "session")
	{
		$msg = "SESSION缓存已清空，系统有可能会要求您重新登录...";
	}
	else
	{
		$msg = "缓存已清空...";
	}
	Error($msg,"admin.php?file=cache&act=list");
}
else
{
	Foot("cache.qg");
}
?>