<?php if(!defined('PHPOK_SET')){die('<h3>Error...</h3>');}?><?php QG_C_TEMPLATE::p("global_head","","0");?>
<?php QG_C_TEMPLATE::p("right.css","","0");?>
<?php QG_C_TEMPLATE::p("right.head","","0");?>

<?php if($bookcount && $bookcount>0){?>
<table width="100%">
<tr>
	<td class="qg_notice" style="text-align:left;">您当前有 <?php echo $bookcount;?> 条未审核的留言信息，请及时审核</td>
</tr>
</table>
<?php unset($bookcount);?>
<?php } ?>
<?php if($ordercount && $ordercount>0){?>
<table width="100%">
<tr>
	<td class="qg_notice" style="text-align:left;">您当前有 <?php echo $ordercount;?> 条未查阅的订单，请您及时检查</td>
</tr>
</table>
<?php unset($ordercount);?>
<?php } ?>
<style type="text/css">
.qg_index td
{
	height:28px;
}
</style>
<div class="qg_index">
<table width='100%' cellspacing='0' cellpadding='0'>
<tr>
	<td style='padding-left:20px;height:28px;'><b>服务器参数信息</b></td>
</tr>
</table>

<table width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td style='width:21%;' align='right'>&nbsp;PHP版本：</td>
	<td align='left'><?php echo PHP_VERSION;?></td>
</tr>
<tr>
	<td align="right">&nbsp;操作系统：</td>
	<td align="left"><?php echo php_uname();?></td>
</tr>
<tr>
	<td align="right">&nbsp;全局变量：</td>
	<td align="left"><?php if(get_cfg_var("register_globals")){?><span style="font-weight:bold;color:green">ON</span><?php }else{ ?><span style="font-weight:bold;color:red">OFF</span><?php } ?></td>
</tr>
<tr>
	<td align="right">&nbsp;上传文件：</td>
	<td align="left"><?php if(get_cfg_var("file_uploads")){?><span style="font-weight:bold;color:green">允许，最大<?php echo get_cfg_var("upload_max_filesize");?></span><?php }else{ ?><span style="font-weight:bold;color:red">不允许上传附件</span><?php } ?></td>
</tr>
<tr>
	<td align="right">&nbsp;脚本超时：</td>
	<td align="left"><span style='font-weight:bold;color:red'><?php echo get_cfg_var("max_execution_time");?></span> 秒</td>
</tr>
<tr>
	<td align="right">&nbsp;系统时间：</td>
	<td align="left"><?php echo date("Y年m月d日 H时i分s秒");?></td>
</tr>
<tr>
	<td align="right">&nbsp;调节后的时间：</td>
	<td align="left"><?php echo date("Y年m月d日 H时i分s秒",$system_time);?></td>
</tr>
<tr>
	<td align="right">&nbsp;</td>
	<td align="left"></td>
</tr>
<tr>
	<td align="right">&nbsp;</td>
	<td align="left"></td>
</tr>
</table>
</div>
<?php QG_C_TEMPLATE::p("foot","","0");?>