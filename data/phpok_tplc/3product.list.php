<?php if(!defined('PHPOK_SET')){die('<h3>Error...</h3>');}?><!-- 文章列表页模板 -->
<?php QG_C_TEMPLATE::p("inc.head","","0");?>
<div align="center">
<div id="body">
<div class="table">

<div id="left_1">
	<div><?php QG_C_TEMPLATE::p("inc.login","","0");?></div>
	<div class="space_between"><div class="space_between"></div></div>

	<div class="global_sub">当前分类 &nbsp;<?php if($thiscate[parentid]){?><a href="list.php?id=<?php echo $thiscate[parentid];?>">上级分类</a><?php } ?></div>
	<div class="border_no_top">
		<?php $_i=0;$left_catelist=(is_array($left_catelist))?$left_catelist:array();foreach($left_catelist AS  $key=>$value){$_i++; ?>
		<div style="padding-left:20px;"><a href="list.php?id=<?php echo $value[id];?>" style="<?php echo $value[style];?><?php if($value[id] == $id){?>;color:red;<?php } ?>"><?php echo $value[catename];?></a></div>
		<?php } ?>
	</div>
	<div class="space_between"><div class="space_between"></div></div>

	<div class="global_sub">其它</div>
	<div class="border_no_top">
		<div align="center" style="padding-top:3px;padding-bottom:3px;"><?php echo QGMOD_AD(6);?></div>
	</div>

</div>

<div id="middle_1"><div class="space_between"></div></div>

<div id="right_1">
	<div class="global_sub"><?php echo $thiscate[catename];?></div>
	<div class="border_no_top">
		<?php $_i=0;$msglist=(is_array($msglist))?$msglist:array();foreach($msglist AS  $key=>$value){$_i++; ?>
		<div class="list_out" onmouseover="this.className='list_over'" onmouseout="this.className='list_out'">
			<table cellpadding="0" cellspacing="0">
			<tr>
				<td width="120px" align="center">
					<?php if($value[thumbfile] && file_exists($value[folder].$value[thumbfile])){?>
					<a href="msg.php?id=<?php echo $value[id];?>" title="<?php echo $value[subject];?>"><img src="<?php echo $value[folder];?><?php echo $value[thumbfile];?>" border="0" width="100px" height="100px" style="border:1px #D2DFE6 solid;"></a>
					<?php }else{ ?>
					<a href="msg.php?id=<?php echo $value[id];?>" title="<?php echo $value[subject];?>"><img src="templates/zh/crystal_blue/images/nopicture.gif" border="0" width="100px" height="100px" style="border:1px #D2DFE6 solid;"></a>
					<?php } ?>
				</td>
				<td valign="top" width="600px">
					<div class="wtr">名称：<a href="msg.php?id=<?php echo $value[id];?>" title="<?php echo $value[subject];?>" style="<?php echo $value[style];?>"><?php echo $value[cut_subject];?></a></div>
					<div class="dtr">分类：<a href="list.php?id=<?php echo $value[cateid];?>" title="<?php echo $value[catename];?>"><?php echo $value[catename];?></a></div>
					<div class="wtr">规格：<?php echo $value[ext_standard];?></div>
					<div class="dtr">编号：<?php echo $value[ext_number];?></div>
					<div class="pro_dtr"><?php echo $value[ext_docket];?></div>
					<div class="pro_tr" align="right">
						<a href="msg.php?id=<?php echo $value[id];?>"><img src="templates/zh/crystal_blue/images/product_msg.gif" border="0"></a>
						<a href="buy.php?id=<?php echo $value[id];?>"><img src="templates/zh/crystal_blue/images/product_buy.gif" border="0"></a>
					</div>
				</td>
			</tr>
			</table>
		</div>
		<?php } ?>
	</div>
	<div align="right"><?php echo $pagelist;?></div>
	</div>

</div>
<div style="clear:both;"></div>

</div>
</div>
</div>
<?php QG_C_TEMPLATE::p("inc.foot","","0");?>