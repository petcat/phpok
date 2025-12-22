<?php if(!defined('PHPOK_SET')){die('<h3>Error...</h3>');}?><!-- 图片列表页模板 -->
<?php QG_C_TEMPLATE::p("inc.head","","0");?>
<div align="center">
<div id="body">
<div class="table">

<div id="left_2">
	<div class="global_sub"><?php echo $thiscate[catename];?></div>
	<div class="border_no_top">
		<div style="padding:1px">
			<table>
			<tr>
				<?php $_i=0;$msglist=(is_array($msglist))?$msglist:array();foreach($msglist AS  $key=>$value){$_i++; ?>
				<td style="width:150px;height:150px;margin:1px;border:1px #D4D4D4 solid;text-align:center;"><a href="msg.php?id=<?php echo $value[id];?>" title="<?php echo $value[subject];?>"><img src="<?php echo $value[folder];?><?php echo $value[thumbfile];?>" border="0" width="100px" height="100px" alt="<?php echo $value[subject];?>"></a></td>
				<?php if($_i%5===0){echo '</tr><tr>';}?>
				<?php } ?>
			</tr>
			</table>
		</div>
	</div>
	<div align="right"><?php echo $pagelist;?></div>
</div>

<div id="middle_2"><div class="space_between"></div></div>

<div id="right_2">
	<div><?php QG_C_TEMPLATE::p("inc.login","","0");?></div>
	<div class="space_between"><div class="space_between"></div></div>
	<div class="global_sub">当前分类 &nbsp;<?php if($thiscate[parentid]){?><a href="list.php?id=<?php echo $thiscate[parentid];?>">上级分类</a><?php } ?></div>
	<div class="border_no_top">
		<?php $_i=0;$left_catelist=(is_array($left_catelist))?$left_catelist:array();foreach($left_catelist AS  $key=>$value){$_i++; ?>
		<div style="padding-left:20px;"><a href="list.php?id=<?php echo $value[id];?>" style="<?php echo $value[style];?><?php if($value[id] == $id){?>;color:red;<?php } ?>"><?php echo $value[catename];?></a></div>
		<?php } ?>
	</div>
	<div class="space_between"><div class="space_between"></div></div>
	<div class="global_sub">广告</div>
	<div class="border_no_top">
		<div align="center" style="padding-top:3px;padding-bottom:3px;"><?php echo QGMOD_AD(7);?></div>
	</div>
</div>
<div style="clear:both;"></div>

</div>
</div>
</div>
<?php QG_C_TEMPLATE::p("inc.foot","","0");?>