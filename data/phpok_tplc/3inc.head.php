<?php if(!defined('PHPOK_SET')){die('<h3>Error...</h3>');}?><!-- 公共头部页信息 -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $sitetitle;?></title>
<meta name="keywords" content="<?php echo $system[keywords];?> /">
<meta name="description" content="<?php echo $system[description];?> /">
<link rel="stylesheet" type="text/css" href="templates/zh/crystal_blue/images/crystal_blue.css" />
<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
<script type="text/javascript" src="templates/zh/crystal_blue/images/crystal_blue.js"></script>
</head>
<body>
<div id="header">
	<div class="header">
	<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td width="310px" height="66px"><img src="templates/zh/crystal_blue/images/logo.jpg" border="0"></td>
		<td width="10px" align="right">&nbsp;</td>
		<td>&nbsp;</td>
		<td width="300px" align="center"><a href="home.php?langsign=zh"><img src="templates/zh/crystal_blue/images/cn.gif" /> 中文</a>&nbsp;&nbsp;<a href="home.php?langsign=en"><img src="templates/zh/crystal_blue/images/en.gif" /> English</a></td>
	</tr>
	</table>
	<div id="menu">
	<ul>
		<?php $_i=0;$qgnav=(is_array($qgnav))?$qgnav:array();foreach($qgnav AS  $key=>$value){$_i++; ?>
		<li><a href="<?php echo $value[url];?>" <?php if($key == 0){?>id="menu_home"<?php } ?> <?php if($value[target]){?>target="_blank"<?php } ?> style="<?php echo $value[css];?>"><span><?php echo $value[name];?></span></a></li>
		<?php } ?>
		<li id="search">
			<span style="display:none;"><form method="post" action="search.php?act=searchlink"><input type="hidden" name="stype" value=""></span>
			<input type="text" id="searchkey" name="keywords" value="<?php echo $keywords;?>" /><input class="submit" type="submit" name="subjectsearch" value="搜索" />
			<span style="display:none;"></form></span>
		</li>
	</ul>
	</div>
	</div>
</div>
<div align="center">
<div id="top">
	<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td width="10px"><img src="templates/zh/crystal_blue/images/topnav_left.jpg" border="0"></td>
		<td style="background:url(templates/zh/crystal_blue/images/topnav_bg.jpg) repeat-x" align="left">
			&#8251;&nbsp;
			<a href="index.php"><?php echo $system[sitename];?></a>
			&raquo;
			<a href="home.php">网站首页</a>
			<?php $_i=0;$lead_menu=(is_array($lead_menu))?$lead_menu:array();foreach($lead_menu AS  $key=>$value){$_i++; ?>
			&raquo; <a href="<?php echo $value[url];?>"><?php echo $value[name];?></a>
			<?php } ?>
		</td>
		<td width="10px"><img src="templates/zh/crystal_blue/images/topnav_right.jpg" border="0"></td>
	</tr>
	</table>
</div>
</div>