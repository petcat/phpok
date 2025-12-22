<?php if(!defined('PHPOK_SET')){die('<h3>Error...</h3>');}?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $site_title;?> -- Www.PhpOk.Com</title>
<!-- 弹出窗口默认页 -->
<script type="text/javascript" src="admin/tpl/images/global.js"></script>
<style type="text/css">
body
{
	padding:0px;
	margin:0px;
}
input
{
	height:23px;
	line-height:20px;
	vertical-align: middle;
	background-color: #ECE9D8;
	border:1px #D4D4D4 solid;
}
</style>
<script type="text/javascript">
function get_value()
{
	if(window.frames["main_frame"].ok())
	{
		window.close();
		return true;
	}
}
</script>
</head>
<body>
<div id="my_body">
<iframe src="<?php echo $incfile;?>" name="main_frame" id="main_frame" width="100%" height="<?php echo $iframe_height;?>px" frameborder="0" marginheight="0" marginwidth="0" border="0" noresize></iframe>
<div align="right" style="padding-top:2px;padding-right:2px;">
	<input type="button" value="确 定" onclick="get_value();">
	<input type="button" value="关闭窗口" onclick="window.close();">
</div>
</div>
</body>
</html>