<?php if(!defined('PHPOK_SET')){die('<h3>Error...</h3>');}?><?php QG_C_TEMPLATE::p("global_head","","0");?>
<?php QG_C_TEMPLATE::p("right.css","","0");?>
<?php QG_C_TEMPLATE::p("right.head","","0");?>

<!-- 系统信息配置 -->
<table width="100%">
<tr>
	<td class="qg_notice" style="text-align:left;">
		<a href="admin.php?file=index">系统首页</a>
		&raquo; 系统配置 &nbsp; 〔注意不要使用单引号、双引号及美元符号〕
	</td>
</tr>
</table>

<form name="qgform" id="qgform" method="post" action="admin.php?file=system&act=setok" onsubmit="return SystemSet()">
<div class="table" onmouseover="this.className='table table1'" onmouseout="this.className='table'">
<table width="100%">
<tr>
	<td class="left">网站名称：<span style="color:red;">*</span></td>
	<td class="right">
		<input type="text" name="sitename" id="sitename" class="long_input" value="<?php echo $rs[sitename];?>">
		<span class="clue_on">[填写站点的名称]</span>
	</td>
</tr>
</table>
</div>

<div class="table" onmouseover="this.className='table table1'" onmouseout="this.className='table'">
<table width="100%">
<tr>
	<td class="left">关键字：</td>
	<td class="right">
		<input type="text" name="keywords" id="keywords" class="long_input" value="<?php echo $rs[keywords];?>">
		<span class="clue_on">[多个关键字用英文逗号隔开]</span>
	</td>
</tr>
</table>
</div>

<div class="table" onmouseover="this.className='table table1'" onmouseout="this.className='table'">
<table width="100%">
<tr>
	<td class="left">网站描述：</td>
	<td class="right">
		<input type="text" name="description" id="description" class="long_input" value="<?php echo $rs[description];?>">
		<span class="clue_on">[简单描述一下站点的信息]</span>
	</td>
</tr>
</table>
</div>
<div class="table" onmouseover="this.className='table table1'" onmouseout="this.className='table'">
<table width="100%">
<tr>
	<td class="left">缓存时间：</td>
	<td class="right">
		<input type="text" name="mintime" id="mintime" class="short_input" value="<?php echo $rs[mintime];?>">
		－
		<input type="text" name="maxtime" id="maxtime" class="short_input" value="<?php echo $rs[maxtime];?>">
		<span class="clue_on">[填写缓存时间的范围，左边是最小值，右边是最大值，不缓存请都设为0，单位是秒]</span>
	</td>
</tr>
</table>
</div>
<div class="table">
<table width="100%">
<tr>
	<td class="left">&nbsp;</td>
	<td class="right clue_on">1小时等于3600秒，IP流量小于3000时建议不要使用缓存，启用缓存时建议最小缓存时间不要小于3600秒</td>
</tr>
</table>
</div>
<div class="table" onmouseover="this.className='table table1'" onmouseout="this.className='table'">
<table width="100%">
<tr>
	<td class="left">时间较正：</td>
	<td class="right">
		<input type="text" name="timerevise" id="timerevise" class="short_input" value="<?php echo $rs[timerevise];?>">
		<span class="clue_on">[如果服务器时间与客户端时间不一致，请添加误差，支持负值，单位是分钟]</span>
	</td>
</tr>
</table>
</div>
<div class="table" onmouseover="this.className='table table1'" onmouseout="this.className='table'">
<table width="100%">
<tr>
	<td class="left">时区：</td>
	<td class="right">
		<input type="text" name="timezone" id="timezone" class="short_input" value="<?php echo $rs[timezone];?>">
		<span class="clue_on">[填写时区，北京使用 +8 区，支持负值]</span>
	</td>
</tr>
</table>
</div>

<div class="table" onmouseover="this.className='table table1'" onmouseout="this.className='table'">
<table width="100%">
<tr>
	<td class="left">管理员邮箱：</td>
	<td class="right">
		<input type="text" name="adminemail" id="adminemail" value="<?php echo $rs[adminemail];?>">
		<span class="clue_on">[请填写管理员的邮箱以便接收通知]</span>
	</td>
</tr>
</table>
</div>

<table width="100%">
<tr>
	<td class="qg_notice" style="text-align:left;">
		以下是设置 SMTP发送邮件环境设置 &nbsp;〔只有正确设置了SMTP设置，系统才能正常发送通知信息给客户〕
	</td>
</tr>
</table>
<div class="table" onmouseover="this.className='table table1'" onmouseout="this.className='table'">
<table width="100%">
<tr>
	<td class="left">编码：</td>
	<td class="right">
		<select name="mailtype" id="mailtype">
		<option value="gbk"<?php if($rs[mailtype] == "gbk"){?> selected<?php } ?>>GBK编码</option>
		<option value="utf8"<?php if($rs[mailtype] == "utf8"){?> selected<?php } ?>>UTF-8编码</option>
		</select>
		<span class="clue_on">[国内服务器一般使用GBK编码，请注意选择]</span>
	</td>
</tr>
</table>
</div>

<div class="table" onmouseover="this.className='table table1'" onmouseout="this.className='table'">
<table width="100%">
<tr>
	<td class="left">SMTP服务器：</td>
	<td class="right">
		<input type="text" name="mailhost" id="mailhost" value="<?php echo $rs[mailhost];?>">
		<span class="clue_on">[设置SMTP的服务器，如：smtp.188.com]</span>
	</td>
</tr>
</table>
</div>

<div class="table" onmouseover="this.className='table table1'" onmouseout="this.className='table'">
<table width="100%">
<tr>
	<td class="left">端口：</td>
	<td class="right">
		<input type="text" name="mailport" id="mailport" value="<?php echo $rs[mailport];?>" class="short_input">
		<span class="clue_on">[邮箱服务器的端口，默认是25]</span>
	</td>
</tr>
</table>
</div>

<div class="table" onmouseover="this.className='table table1'" onmouseout="this.className='table'">
<table width="100%">
<tr>
	<td class="left">账号：</td>
	<td class="right">
		<input type="text" name="mailuser" id="mailuser" value="<?php echo $rs[mailuser];?>">
	</td>
</tr>
</table>
</div>

<div class="table" onmouseover="this.className='table table1'" onmouseout="this.className='table'">
<table width="100%">
<tr>
	<td class="left">密码：</td>
	<td class="right">
		<input type="password" name="mailpass" id="mailpass" value="<?php echo $rs[mailpass];?>">
	</td>
</tr>
</table>
</div>

<div class="table">
<table width="100%">
<tr>
	<td class="left">&nbsp;</td>
	<td class="right clue_on">国内大部分邮件服务器均要求账号和密码才能发送，因此请仔细填写，不熟悉可咨询相关服务商</td>
</tr>
</table>
</div>

<div class="table" onmouseover="this.className='table table1'" onmouseout="this.className='table'">
<table width="100%">
<tr>
	<td class="left">邮箱：</td>
	<td class="right">
		<input type="text" name="mailqg" id="mailqg" value="<?php echo $rs[mailqg];?>">
		<span class="clue_on">[填写完整的邮箱，如：qinggan@188.com]</span>
	</td>
</tr>
</table>
</div>
<div class="table">
<table width="100%">
<tr>
	<td class="left">&nbsp;</td>
	<td class="right clue_on">如果未设置管理员邮箱，则该邮箱将同时作为接收回复的邮箱</td>
</tr>
</table>
</div>

<table width="100%">
<tr>
	<td class="qg_notice" style="text-align:left;">
		下面是设置： 缩略（水印）图环境，只有正确设置才能保证缩略图、水印图的有效
	</td>
</tr>
</table>
<div class="table" onmouseover="this.className='table table1'" onmouseout="this.className='table'">
<table width="100%">
<tr>
	<td class="left">GD功能</td>
	<td class="right">
		<input type="radio" name="isgd" id="isgd_1" value="1"<?php if($rs[isgd] && $rs[isgd] != 0){?> checked<?php } ?>>启用
		<input type="radio" name="isgd" id="isgd_0" value="0"<?php if(!$rs[isgd] || $rs[isgd] == 0){?> checked<?php } ?>>禁用
		<span class="clue_on">[如果您的PHP环境不支持GD库功能，请禁用GD功能]</span>
	</td>
</tr>
</table>
</div>

<div class="table" onmouseover="this.className='table table1'" onmouseout="this.className='table'">
<table width="100%">
<tr>
	<td class="left">水印图片：</td>
	<td class="right">
		<input type="text" name="gdpic" id="gdpic" value="<?php echo $rs[gdpic];?>">
		<span class="clue_on">[请填写基于网站根目录下的相对图片路径，如：<?php echo "images";;?>/qinggan.jpg 仅支持jpg格式]</span>
	</td>
</tr>
</table>
</div>

<div class="table" onmouseover="this.className='table table1'" onmouseout="this.className='table'">
<table width="100%">
<tr>
	<td class="left">图片位置：</td>
	<td class="right">
		<select name="gdposition" id="gdposition">
		<option value="1"<?php if($rs[gdposition] == 1){?> selected<?php } ?>>左上</option>
		<option value="2"<?php if($rs[gdposition] == 2){?> selected<?php } ?>>右上</option>
		<option value="3"<?php if($rs[gdposition] == 3){?> selected<?php } ?>>中间</option>
		<option value="4"<?php if($rs[gdposition] == 4){?> selected<?php } ?>>左下</option>
		<option value="5"<?php if($rs[gdposition] == 5){?> selected<?php } ?>>右下</option>
		</select>
		<span class="clue_on">[请选择要附加的水印图片的位置，支持五个常用位置]</span>
	</td>
</tr>
</table>
</div>

<div class="table" onmouseover="this.className='table table1'" onmouseout="this.className='table'">
<table width="100%">
<tr>
	<td class="left">缩略图类型：</td>
	<td class="right">
		<select name="thumbtype" id="thumbtype">
		<option value="0"<?php if($rs[thumbtype] == 0 || !$rs[thumbtype]){?> selected<?php } ?>>缩放型缩略图</option>
		<option value="1"<?php if($rs[thumbtype] == 1){?> selected<?php } ?>>裁剪式缩略图</option>
		</select>
		<span class="clue_on">[PHPOK官方推荐使用裁剪式的缩略图，可以保证图片不会变型]</span>
	</td>
</tr>
</table>
</div>

<div class="table" onmouseover="this.className='table table1'" onmouseout="this.className='table'">
<table width="100%">
<tr>
	<td class="left">缩略图：</td>
	<td class="right">
		<input type="text" name="thumbwidth" id="thumbwidth" value="<?php echo $rs[thumbwidth];?>" class="short_input">
		×
		<input type="text" name="thumbheight" id="thumbheight" value="<?php echo $rs[thumbheight];?>" class="short_input">
		<span class="clue_on">[设置缩略图的宽和高，建议设置其比例为 4:3 PHPOK官方推荐您使用 160×120 ]</span>
	</td>
</tr>
</table>
</div>

<div class="table" onmouseover="this.className='table table1'" onmouseout="this.className='table'">
<table width="100%">
<tr>
	<td class="left">水印图：</td>
	<td class="right">
		<input type="text" name="markwidth" id="markwidth" value="<?php echo $rs[markwidth];?>" class="short_input">
		×
		<input type="text" name="markheight" id="markheight" value="<?php echo $rs[markheight];?>" class="short_input">
		<span class="clue_on">[设置水印图的宽高，若设置为 0 使用系统原图的宽高值]</span>
	</td>
</tr>
</table>
</div>

<div class="table">
<table width="100%">
<tr>
	<td class="left">&nbsp;</td>
	<td class="right"><input type="submit" id="qgbutton" class="mybutton_01" value="设 置"></td>
</tr>
</table>
</div>

</form>
<script type="text/javascript">
function SystemSet()
{
	var idtrue = $("isgd_1").checked;
	var idfalse = $("isgd_0").checked;
	if(idtrue)
	{
		//检测内容是否有正常
		var gdpic = $("gdpic").value;
		if(!gdpic)
		{
			alert("水印图片不能为空！PHPOK2.0不再支持文字水印！");
			return false;
		}
	}
	//更新
	$("qgbutton").disabled = true;
}
</script>

<?php QG_C_TEMPLATE::p("foot","","0");?>