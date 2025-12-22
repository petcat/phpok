<?php if(!defined('PHPOK_SET')){die('<h3>Error...</h3>');}?><!-- 会员注册页模板 -->
<?php QG_C_TEMPLATE::p("inc.head","","0");?>
<div align="center">
<div id="body">
<div class="table">

<div id="left_1">
	<div class="global_sub">广告</div>
	<div class="border_no_top">
		<div align="center" style="padding-top:3px;padding-bottom:3px;">
<script type="text/javascript"><!--
google_ad_client = "pub-3996640094115586";
google_ad_width = 160;
google_ad_height = 600;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
		</div>
	</div>
</div>

<div id="middle_1"><div class="space_between"></div></div>

<div id="right_1">
	<div class="global_sub">会员注册</div>
	<div class="border_no_top">
		<div style="display:none"><form action="<?php echo $siteurl;?>register.php?act=regok" method="post" onSubmit="return regcheck();"></div>
		<div style="padding:10px;line-height:150%;">
			<div>会员账号：<input type="text" class="userlogin" name="username" id="username" align="absmiddle"></div>
			<div class="space_between"><img src="templates/zh/default/images/blank.gif" border="0" width="1" height="1"></div>
			<div>会员密码：<input type="password" class="userlogin" name="password" id="password" align="absmiddle"></div>
			<div class="space_between"><img src="templates/zh/default/images/blank.gif" border="0" width="1" height="1"></div>
			<div>验证密码：<input type="password" class="userlogin" name="checkpass" id="checkpass" align="absmiddle"></div>
			<div class="space_between"><img src="templates/zh/default/images/blank.gif" border="0" width="1" height="1"></div>
			<div>会员邮箱：<input type="text" class="userlogin" name="email" id="email" align="absmiddle"></div>
			<div class="space_between"><img src="templates/zh/default/images/blank.gif" border="0" width="1" height="1"></div>
			<div>
				<input type="image" src="templates/zh/default/images/register.gif" style="border:0px;" id="register_user">
			</div>
		</div>
		<div style="display:none"></form></div>
	</div>
</div>

<div style="clear:both;"></div>

</div>
</div>
</div>

<script type="text/javascript">
function regcheck()
{
	var username = $("username").value;
	var password = $("password").value;
	var chkpass = $("checkpass").value;
	var email = $("email").value;
	if(username == "")
	{
		alert("会员账号为空,请填写");
		$("username").focus();
		return false;
	}
	if(password == "" || chkpass == "")
	{
		alert("会员密码为空！");
		if(password == "")
		{
			$("password").focus();
		}
		else
		{
			$("checkpass").focus();
		}
		return false;
	}
	if(password != chkpass)
	{
		alert("两次密码不一致！");
		$("password").value = "";
		$("checkpass").value = "";
		$("password").focus();
		return false;
	}
	if(email == "")
	{
		alert("邮箱为空！");
		$("email").focus();
		return false;
	}
	$("loginsubject").disabled=true;
}
</script>
<?php QG_C_TEMPLATE::p("inc.foot","","0");?>