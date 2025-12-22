<?php if(!defined('PHPOK_SET')){die('<h3>Error...</h3>');}?><?php QG_C_TEMPLATE::p("global_head","","0");?>
<?php QG_C_TEMPLATE::p("right.css","","0");?>
<?php QG_C_TEMPLATE::p("right.head","","0");?>

<!-- 图片管理 -->
<table width="100%">
<tr>
	<td class="qg_notice" style="text-align:left;">
		<a href="admin.php?file=index">系统首页</a> &raquo;
		<a href="admin.php?file=index.set&act=list">图片轮播管理</a> &raquo;
		当前动作：在线编辑图片播放器信息 &nbsp; 最多支持九张图片
	</td>
</tr>
</table>

<form name="qgform" action="admin.php?file=index.img&act=setok&id=<?php echo $id;?>" method="post">
<div class="table">
<table width="100%">
<tr>
	<td class="left">说明：</td>
	<td class="right clue_on">图片轮播涉及到大量图片路径及网站因为无法更换JS相关信息，故请使用带http://的图片及网址</td>
</tr>
</table>
</div>
<?php $_i=0;$qgarray=(is_array($qgarray))?$qgarray:array();foreach($qgarray AS  $key=>$value){$_i++; ?>
<?php $img_file = $imgarray[$value];;?>
<?php $url_file = $urlarray[$value];;?>
<div class="table">
<table width="100%">
<tr>
	<td class="left">图片：<?php echo $value;?></td>
	<td class="right">
		<input type="text" name="img_<?php echo $value;?>" id="img_<?php echo $value;?>" value="<?php echo $img_file;?>" class="long_input">
		<input type="button" value="选择图片" onclick="to_openpic('img_<?php echo $value;?>')">
	</td>
</tr>
</table>
</div>
<div class="table">
<table width="100%">
<tr>
	<td class="left">网址：<?php echo $value;?></td>
	<td class="right"><input type="text" name="url_<?php echo $value;?>" id="url_<?php echo $value;?>" value="<?php echo $url_file;?>" class="long_input"></td>
</tr>
</table>
</div>
<hr size="1" color="#1685E9">
<?php } ?>
<div class="table">
<table width="100%">
<tr>
	<td class="left"></td>
	<td class="right"><input type="submit" value="提 交"></td>
</tr>
</table>
</div>
<script type="text/javascript">
function to_openpic(id)
{
	var siteurl = "admin.php?file=open.index.img&act=list&id="+id;
	qg_open(siteurl,"500");
}
</script>