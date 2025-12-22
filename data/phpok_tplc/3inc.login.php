<?php if(!defined('PHPOK_SET')){die('<h3>Error...</h3>');}?><!-- 会员登录及登录入的个人面板 -->
<?php if(USER_STATUS){?>
<div class="global_sub">会员面板</div>
<div class="border_no_top">
	<table>
	<tr>
		<td width="40px" align="right" height="21px"><img src="templates/zh/crystal_blue/images/user_profile.gif"></td>
		<td><a href="my.php?file=usercp">个人信息</a></td>
	</tr>
	<tr>
		<td align="right" height="21px"><img src="templates/zh/crystal_blue/images/user_order.gif"></td>
		<td><a href="my.php?file=order">我的订单</a></td>
	</tr>
	<tr>
		<td align="right" height="21px"><img src="templates/zh/crystal_blue/images/user_feedback.gif"></td>
		<td><a href="my.php?file=feedback">信息反馈</a></td>
	</tr>
	<tr>
		<td align="right" height="21px"><img src="templates/zh/crystal_blue/images/user_logout.gif"></td>
		<td><a href="login.php?act=logout">退出</a></td>
	</tr>
	</table>
</div>
<?php }else{ ?>
<div class="global_sub">会员登录</div>
<div class="border_no_top">
	<div align="center">
	<table>
	<form action="login.php?act=loginok" method="post" onSubmit="return logincheck();">
	<tr>
		<td width="40px" align="right" height="30px">账号：</td>
		<td><input type="text" name="username" id="username" class="userlogin_out" onmouseover="this.className='userlogin_over'" onmouseout="this.className='userlogin_out'" /></td>
	</tr>
	<tr>
		<td align="right" height="30px">密码：</td>
		<td><input type="password" name="password" id="password" class="userlogin_out" onmouseover="this.className='userlogin_over'" onmouseout="this.className='userlogin_out'" /></td>
	</tr>
	<tr>
		<td colspan="2" align="center"><input type="image" src="templates/zh/crystal_blue/images/login.gif" style="border:0px;" />
			<a href="register.php"><img src="templates/zh/crystal_blue/images/register.gif" border="0"></a></td>
	</tr>
	</form>
	</table>
	</div>
</div>

<script type="text/javascript">
function logincheck()
{
	var username = $("username").value;
	var password = $("password").value;
	if(username == "")
	{
		alert("会员账号为空,请填写");
		$("username").focus();
		return false;
	}
	if(password == "")
	{
		alert("会员密码为空！");
		$("password").focus();
		return false;
	}
	$("loginsubject").disabled=true;
}
</script>
<?php } ?>