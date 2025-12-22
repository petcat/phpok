<?php if(!defined('PHPOK_SET')){die('<h3>Error...</h3>');}?><!-- 留言本 -->
<?php QG_C_TEMPLATE::p("inc.head","","0");?>
<div align="center">
<div id="body">
<div class="table">

<script type="text/javascript">
function qgcheckaddbook()
{
	var username = $("qgusername").value;
	var email = $("email").value;
	if(!username)
	{
		alert("Username is empty");
		$("qgusername").focus();
		return false;
	}
	if(!email)
	{
		alert("email is empty");
		$("email").focus();
		return false;
	}
	var str_reg=/^\w+((-\w+)|(\.\w+))*\@{1}\w+\.{1}\w{2,4}(\.{0,1}\w{2}){0,1}/ig;
	if (email.search(str_reg) == -1)
	{
		alert("email is not right");
		$("email").value = "";
		$("email").focus();
		return false;
	}
	var subject = $("subject").value;
	if(!subject)
	{
		alert("subject is empty");
		$("subject").focus();
		return false;
	}
	var content = $("content").value;
	if(!content)
	{
		alert("Message is empty");
		return false;
	}
	$("qgsubmit").disabled = true;
	return true;
}
</script>

<div id="left_2">
	<?php $_i=0;$book_list=(is_array($book_list))?$book_list:array();foreach($book_list AS  $key=>$value){$_i++; ?>
	<div class="global_sub"><?php echo $value[subject];?> <span style="font-size:11px;">(<?php echo $value[postdate];?>)</span></div>
	<div class="border_no_top">
		<div style="padding:3px;"><?php echo $value[content];?></div>
		<?php if($value[reply]){?>
		<div style="padding:5px;">
			<div style="border:1px #D2DFE6 dashed;padding:5px;"><span style="color:red;">Administrator:</span><?php echo $value[reply];?></div>
		</div>
		<?php } ?>
		<div align="right"><a href="#addbook" style="color:darkred;">POST</a>&nbsp; &nbsp;</div>
	</div>
	<div class="space_between"><div class="space_between"></div></div>
	<?php } ?>

	<div align="right"><?php echo $pagelist;?></div>

	<!-- 发布新留言 -->
	<a name="addbook"></a>
	<div class="global_sub">Post </div>
	<div class="border_no_top">
		<div>
		<table width="99%">
		<form method="post" action="book.php?act=addok" onsubmit="return qgcheckaddbook()">
		<?php if($_SESSION["qg_sys_user"]){?>
		<input type="hidden" name="qgusername" id="qgusername" value="<?php echo $_SESSION[qg_sys_user][user];?>">
		<input type="hidden" name="email" id="email" value="<?php echo $_SESSION[qg_sys_user][email];?>">
		<tr>
			<td width="10%" align="right"><span style="color:red;">*</span> Name: </td>
			<td><?php echo $_SESSION[qg_sys_user][user];?></td>
		</tr>
		<?php }else{ ?>
		<tr>
			<td width="10%" align="right"><span style="color:red;">*</span> Name: </td>
			<td><input type="text" name="qgusername" id="qgusername" /></td>
		</tr>
		<tr>
			<td align="right"><span style="color:red;">*</span> Email: </td>
			<td><input type="text" name="email" id="email" /></td>
		</tr>
		<?php } ?>
		<tr>
			<td align="right"><span style="color:red;">*</span> Subject: </td>
			<td><input type="text" name="subject" id="subject" style="width:300px;" /></td>
		</tr>
		<tr>
			<td align="right"><span style="color:red;">*</span> Message: </td>
			<td><textarea name="content" id="content" style="width:90%;height:100px;"></textarea></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input type="submit" value="Submit" class="button" /></td>
		</tr>
		</form>
		</table>
		</div>
	</div>

</div>

<div id="middle_2"><div class="space_between"></div></div>

<div id="right_2">
	<div><?php QG_C_TEMPLATE::p("inc.login","","0");?></div>
	<div class="space_between"><div class="space_between"></div></div>

	<div class="global_sub">Google AD</div>
	<div class="border_no_top">
		<div align="center" style="padding-top:3px;padding-bottom:3px;"><?php echo QGMOD_AD(8);?></div>
	</div>
</div>

<div style="clear:both;"></div>

</div>
</div>
</div>

<?php QG_C_TEMPLATE::p("inc.foot","","0");?>