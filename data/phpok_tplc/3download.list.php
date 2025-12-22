<?php if(!defined('PHPOK_SET')){die('<h3>Error...</h3>');}?><!-- 下载列表页模板 -->
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

	<div class="global_sub">推荐下载</div>
	<div class="border_no_top">
		<?php $mod_artlist = QGMOD_LIST_IDIN("isvouch DESC,hits ASC,orderdate DESC",26);?>
		<?php $_i=0;$mod_artlist=(is_array($mod_artlist))?$mod_artlist:array();foreach($mod_artlist AS  $key=>$value){$_i++; ?>
		<div>&nbsp;※ <a href="msg.php?id=<?php echo $value[id];?>" style="<?php echo $value[style];?>" title="<?php echo $value[subject];?>"><?php echo $value[cut_subject];?></a></div>
		<?php } ?>
		<?php unset($mod_artlist);?>
	</div>
	<div class="space_between"><div class="space_between"></div></div>

	<div class="global_sub">热门下载</div>
	<div class="border_no_top">
		<?php $mod_artlist = QGMOD_LIST_IDIN("istop DESC,hits DESC,orderdate DESC",26);?>
		<?php $_i=0;$mod_artlist=(is_array($mod_artlist))?$mod_artlist:array();foreach($mod_artlist AS  $key=>$value){$_i++; ?>
		<div>&nbsp;※ <a href="msg.php?id=<?php echo $value[id];?>" style="<?php echo $value[style];?>" title="<?php echo $value[subject];?>"><?php echo $value[cut_subject];?></a></div>
		<?php } ?>
		<?php unset($mod_artlist);?>
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
				<td height="23px" width="470px">&nbsp;
					<?php if($value[top]){?><img src="templates/zh/crystal_blue/images/istop.gif" border="0" align="absmiddle"><?php }else{ ?><img src="templates/zh/crystal_blue/images/preart.gif" border="0" align="absmiddle"><?php } ?>&nbsp;
					<a href="msg.php?id=<?php echo $value[id];?>" title="<?php echo $value[subject];?>" style="<?php echo $value[style];?>"><?php echo $value[cut_subject];?></a>
				</td>
				<td width="50px" style="color:#007BA8"><?php echo $value[hits];?></td>
				<td width="100px" style="color:#007BA8" align="right"><?php echo $value[postdate];?></td>
			</tr>
			<?php if($value[ext_docket]){?>
			<tr>
				<td colspan="10"><div style="padding:3px;"><?php echo $value[ext_docket];?></div></td>
			</tr>
			<?php } ?>
			<tr>
				<td colspan="10">
					<table width="100%">
					<tr>
						<td width="33%">软件类别：<a href="<?php echo $siteurl;?>list.php?id=<?php echo $value[cateid];?>" title="<?php echo $value[catename];?>" style="color:#BFBFBF;"><?php echo $value[catename];?></a></td>
						<td width="33%">运行环境：<?php echo $value[ext_2];?></td>
						<td width="33%">授权方式：<?php echo $value[ext_1];?></td>
					</tr>
					</table>
				</td>
			</tr>
			</table>
		</div>
		<?php } ?>
	</div>
	<div align="right"><?php echo $pagelist;?></div>
</div>
<div style="clear:both;"></div>

</div>
</div>
</div>
<?php QG_C_TEMPLATE::p("inc.foot","","0");?>