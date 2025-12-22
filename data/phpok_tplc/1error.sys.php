<?php if(!defined('PHPOK_SET')){die('<h3>Error...</h3>');}?><!-- 错误有情提示文件 -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>友情提示</title>
<meta name="keywords" content="<?php echo $system[keywords];?>,qinggan,phpok">
<meta name="description" content="<?php echo $system[description];?> - PHPOK.COM">
<link rel="stylesheet" type="text/css" href="templates/zh/default/images/default.css" />
<script type="text/javascript" src="templates/zh/default/images/default.js"></script>
</head>
<body style="background:#FFFFFF;">
<div align="center">
	<div>&nbsp;</div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
	<div class="error">
		<div><?php echo $error_msg;?></div>
		<?php if($error_url){?>
		<div class="space_between"><img src="templates/zh/default/images/blank.gif" border="0" width="1" height="1"></div>
		<div><a href="<?php echo $error_url;?>">如果您的网站无法在 <span style="color:red;font-weight:bold;"><?php echo $error_time;?></span> 后跳转，请点这里</a></div>
		<?php } ?>
	</div>
</div>
<?php if($error_url && $error_time && $error_time != 0){?>
<script type="text/javascript">
var href="<?php echo $error_url;?>";
timeset("<?php echo $error_time;?>","tourl('"+href+"')");
</script>
<?php } ?>
</body>
</html>