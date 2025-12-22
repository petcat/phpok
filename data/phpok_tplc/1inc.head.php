<?php if(!defined('PHPOK_SET')){die('<h3>Error...</h3>');}?><!-- 公共头部页信息 -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $sitetitle;?></title>
<meta name="keywords" content="<?php echo $system[keywords];?>,qinggan,phpok">
<meta name="description" content="<?php echo $system[description];?> - PHPOK.COM">
<link rel="stylesheet" type="text/css" href="templates/zh/default/images/default.css" />
<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
<script type="text/javascript" src="templates/zh/default/images/default.js"></script>
</head>
<body>
<div id="headertop">
<div align="center" style="background:(templates/zh/default/images/title_bg.gif) repeat-x;">
<table width="969px">
<tr>
	<td width="88px"><img src="templates/zh/default/images/title_logo.gif"></td>
	<td width="10px" align="right">&nbsp;</td>
	<td width="500px" align="left">
		<div id="notice_1">
			<div id="notice_2">
				<?php $notice_list = QGMOD_NOTICE();?>
				<?php $_i=0;$notice_list=(is_array($notice_list))?$notice_list:array();foreach($notice_list AS  $key=>$value){$_i++; ?>
				<div><a href="<?php echo $value[url];?>"<?php echo $value[target];?>><?php echo $value[subject];?></a><em>(<?php echo $value[postdate];?>)</em></div>
				<?php } ?>
			</div>
			<div id="notice_3"></div>
		</div>
	</td>
	<td>&nbsp;</td>
	<script type="text/javascript">
	marquee("notice_1","notice_2","notice_3");
	</script>
	<td align="right" width="351px">
		<table cellpadding="0" cellspacing="0">
		<tr>
			<td width="3"><img src="templates/zh/default/images/blank.gif" width="0" height="0"></td>
			<td width="2"><img src="templates/zh/default/images/h_m_l.gif"></td>
			<td background="templates/zh/default/images/h_m_b.gif">&nbsp;<a href="index.php">网站首页</a>&nbsp;</td>
			<td><img src="templates/zh/default/images/h_m_r.gif"></td>

			<td width="3"><img src="templates/zh/default/images/blank.gif" width="0" height="0"></td>
			<td width="2"><img src="templates/zh/default/images/h_m_l.gif"></td>
			<td background="templates/zh/default/images/h_m_b.gif">&nbsp;<a href="admin.php" target="_blank">管理</a>&nbsp;</td>
			<td><img src="templates/zh/default/images/h_m_r.gif"></td>

			<td width="3"><img src="templates/zh/default/images/blank.gif" width="0" height="0"></td>
			<td width="2"><img src="templates/zh/default/images/h_m_l.gif"></td>
			<td background="templates/zh/default/images/h_m_b.gif">&nbsp;<a href="special.php?id=2">联系我们</a>&nbsp;</td>
			<td><img src="templates/zh/default/images/h_m_r.gif"></td>

			<td width="3"><img src="templates/zh/default/images/blank.gif" width="0" height="0"></td>
			<td width="2"><img src="templates/zh/default/images/h_m_l.gif"></td>
			<td background="templates/zh/default/images/h_m_b.gif">&nbsp;<a href="book.php">留言本</a>&nbsp;</td>
			<td><img src="templates/zh/default/images/h_m_r.gif"></td>
		</tr>
		</table>
	</td>
</tr>
</table>
</div>
</div>

<div align="center">
<div id="top_we_are">
<table width="100%" cellpadding="0" cellspacing="0">
<tr>
	<td width="10"><img src="templates/zh/default/images/headertop_left.gif"></td>
	<td align="left" background="templates/zh/default/images/headertop_bg.gif">
		<table width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td align="left"><img src="templates/zh/default/images/headertop_img.gif"></td>
			<td align="right"><?php echo SelectLangTpl();?></td>
		</tr>
		</table>
	</td>
	<td width="10"><img src="templates/zh/default/images/headertop_right.gif"></td>
</tr>
</table>
</div>
</div>

<div align="center">
<div id="top">
<table width="100%" cellpadding="0" cellspacing="0">
<tr>
	<td width="20px">&nbsp;</td>
	<td width="162px" height="72px" valign="middle"><img src="templates/zh/default/images/logo.gif" border="0"></td>
	<td valign="bottom" class="tabs" align="left">
	<ul>
		<?php $_i=0;$qgnav=(is_array($qgnav))?$qgnav:array();foreach($qgnav AS  $key=>$value){$_i++; ?>
		<li><a href="<?php echo $value[url];?>"<?php if($value[target]){?> target="_blank"<?php } ?> style="<?php echo $value[css];?>"><span><?php echo $value[name];?></span></a></li>
		<?php } ?>
	</ul>
	</td>
</tr>
</table>

<div class="lead">
&nbsp;&#8251;&nbsp;
<a href="index.php"><?php echo $system[sitename];?></a>
&raquo;
<a href="home.php">网站首页</a>
<?php $_i=0;$lead_menu=(is_array($lead_menu))?$lead_menu:array();foreach($lead_menu AS  $key=>$value){$_i++; ?>
&raquo; <a href="<?php echo $value[url];?>"><?php echo $value[name];?></a>
<?php } ?>
</div>

</div>
</div>