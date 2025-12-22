<?php if(!defined('PHPOK_SET')){die('<h3>Error...</h3>');}?><html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<Meta http-equiv="Expires" Content="Wed, 26 Feb 1997 08:21:57 GMT">
<Meta http-equiv="Pragma" Content="No-cach">
<title>企业网站后台管理</title>
<script type="text/javascript" src="admin/tpl/images/global.js"></script>
<link rel="stylesheet" type="text/css" href="admin/tpl/images/right.css" />
</head>
<body>
<style type="text/css">
input
{
	font:normal 12px "Tahoma","Arial","serif","sans-serif";
	height:23px;
}
span
{
	font:normal 12px "Tahoma","Arial","serif","sans-serif";
	position: static
}

a,a:visited
{
	font:normal 12px "Tahoma","Arial","serif","sans-serif";
	color: #000000;
	text-decoration: none
}

a:hover
{
	color: #FF0000;
	text-decoration:none;
}
</style>
</head>
<body>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<form action="admin.php?act=loginok" method="post" target="_top">
<table cellpadding="1" cellspacing="0" border="0" align="center" style="border:solid 1px #D4D4D4;">
<tr>
	<td>
		<table style="width:350px;" border="0" cellspacing="0" cellpadding="0" align="center">
		<tr>
			<td height="25px" style="background:#4455aa;color:white;" align="center">Sino Gacma 台管理登录</td>
		</tr>
		</table>
		<table style="width:350px;" border="0" cellspacing="0" cellpadding="0" align="center">
		<tr>
			<td width="30%" align="right" height="25">用户名：</td>
			<td align="left"><input name="username" type="text" style="width:130px;" /></td>
		</tr>
		<tr>
			<td align="right" height="25">密　码：</font></td>
			<td align="left"><input name="password" type="password" style="width:130px;" /></td>
		</tr>
		<?php if(function_exists("imagecreate") && $isCheckCode){?>
		<tr>
			<td valign="middle" class="forumRow" align="right" height="32"><b>验证码：</b></font></td>
			<td valign="middle" class="forumRow" align="left">
				<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td style="width:60px;"><input name="chk" type="text" style="width:50px;" onkeyup="pressCaptcha(this)" /></td>
					<td id="updatecode"><img src="admin.php?act=chkcode" border="0" align="absmiddle" style="cursor:pointer;" onclick="qgupdatecode()"></td>
				</tr>
				</table>
			</td>
		</tr>
		<?php } ?>
		<tr>
			<td colspan="2" align="center">
				<input type="submit" name="submit" value="登 录" />
				<input type="button" value="返回首页" onclick="window.location='index.php'" />
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
</form>
<script language="JavaScript">
function pressCaptcha(obj)
{
	obj.value = obj.value.toUpperCase();
}
function qgupdatecode()
{
	var rand = Math.random();
	$('updatecode').innerHTML = '<img src="admin.php?act=chkcode&rand=' + rand + '" border="0" align="absmiddle" style="cursor:pointer;" onclick="qgupdatecode()">';
}
</script>
<?php QG_C_TEMPLATE::p("foot","","0");?>