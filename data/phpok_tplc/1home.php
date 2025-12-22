<?php if(!defined('PHPOK_SET')){die('<h3>Error...</h3>');}?><!-- 网站首页 -->
<?php QG_C_TEMPLATE::p("inc.head","","0");?>
<div align="center">
<div id="body">
<div class="table">


<div id="left_2">
	<!-- 翻页图片 -->
	<div><?php QG_C_TEMPLATE::p("inc.picplayer","","0");?></div>
	<!-- 两列文章+一列投票 -->
	<div style="padding-top:3px;padding-bottom:3px;">
		<div class="float_left" style="width:247px">
			<?php $msglist = QGMOD_MSGLIST(2,28,"hits ASC,orderdate DESC,id DESC",false,10);?>
			<div class="global_sub"><a href="list.php?id=<?php echo $msglist[id];?>"><?php echo $msglist[catename];?></a></div>
			<div class="border_no_top">
				<?php $_i=0;$msglist["list"]=(is_array($msglist["list"]))?$msglist["list"]:array();foreach($msglist["list"] AS  $key=>$value){$_i++; ?>
				<div>&nbsp;&#8251; <a href="msg.php?id=<?php echo $value[id];?>" style="<?php echo $value[style];?>" title="<?php echo $value[subject];?>"><?php echo $value[cut_subject];?></a></div>
				<?php } ?>
			</div>
			<?php unset($msglist);?>
		</div>
		<div class="float_left" style="width:232px;_width:236px;padding-left:2px;padding-right:2px;">
			<?php $msglist = QGMOD_MSGLIST(3,28,"hits ASC,orderdate DESC,id DESC",false,10);?>
			<div class="global_sub"><a href="list.php?id=<?php echo $msglist[id];?>"><?php echo $msglist[catename];?></a></div>
			<div class="border_no_top">
				<?php $_i=0;$msglist["list"]=(is_array($msglist["list"]))?$msglist["list"]:array();foreach($msglist["list"] AS  $key=>$value){$_i++; ?>
				<div>&nbsp;&#8251; <a href="msg.php?id=<?php echo $value[id];?>" style="<?php echo $value[style];?>" title="<?php echo $value[subject];?>"><?php echo $value[cut_subject];?></a></div>
				<?php } ?>
			</div>
			<?php unset($msglist);?>
		</div>
		<div class="float_left" style="width:247px">
			<?php $vote = QGMOD_VOTE(1);?>
			<div class="global_sub"><a href="vote.php?id=18"><?php echo $vote[subject];?></a></div>
			<div style="display:none;"><form method="post" action="vote.php?id=18&act=submit"></div>
			<div class="border_no_top">
				<?php $_i=0;$vote["list"]=(is_array($vote["list"]))?$vote["list"]:array();foreach($vote["list"] AS  $key=>$value){$_i++; ?>
				<div><?php echo $value[vote_input];?> <?php echo $value[subject];?></div>
				<?php } ?>
				<div align="center">
					<input type="image" src="templates/zh/default/images/submit.gif" style="border:0px;" />
					<a href="vote.php?id=18&act=view"><img src="templates/zh/default/images/view.gif" border="0"></a>
				</div>
			</div>
			<div style="display:none;"></form></div>
			<?php unset($vote);?>
		</div>
		<div style="clear:both;"></div>
	</div>
	<!-- 最新图片 -->
	<div class="border">
		<div style="padding:3px">
			<table>
			<tr>
				<?php $msglist = QGMOD_PICLIST_ALL("new","picture","21");?>
				<?php $_i=0;$msglist=(is_array($msglist))?$msglist:array();foreach($msglist AS  $key=>$value){$_i++; ?>
				<td style="width:150px;height:150px;margin:1px;border:1px #D4D4D4 solid;text-align:center;"><a href="msg.php?id=<?php echo $value[id];?>" title="<?php echo $value[subject];?>"><img src="<?php echo $value[thumb];?>" border="0" width="100px" height="100px" alt="<?php echo $value[subject];?>"></a></td>
				<?php if($_i%5===0){echo '</tr><tr>';}?>
				<?php } ?>
				<?php unset($msglist);?>
			</tr>
			</table>
		</div>
	</div>
	<div class="space_between"><div class="space_between"></div></div>
	<div>
		<?php $msglist = QGMOD_PICLIST_ALL("new","product","10");?>
		<div class="global_sub">最新产品</div>
		<div class="border_no_top">
			<table width="99%">
			<tr>
				<?php $_i=0;$msglist=(is_array($msglist))?$msglist:array();foreach($msglist AS  $key=>$value){$_i++; ?>
				<td width="20%" height="120px" align="center"><a href="msg.php?id=<?php echo $value[id];?>" title="<?php echo $value[subject];?>"><img src="<?php echo $value[thumb];?>" border="0" width="100px" height="100px" alt="<?php echo $value[subject];?>"></a></td>
				<td valign="top" width="29%">
					<div class="wtr">名称：<a href="msg.php?id=<?php echo $value[id];?>" title="<?php echo $value[subject];?>" style="<?php echo $value[style];?>"><?php echo $value[cut_subject];?></a></div>
					<div class="wtr">规格：<?php echo $value[ext_standard];?></div>
					<div class="dtr">编号：<?php echo $value[ext_number];?></div>
					<div class="wtr">市场价格：<?php echo $value[ext_marketprice];?> 元</div>
					<div class="dtr">本站价格：<span style="color:red;"><?php echo $value[ext_shopprice];?></span> 元</div>
				</td>
				<?php if($_i%2===0){echo '</tr><tr>';}?>
				<?php } ?>
			</tr>
			</table>
		</div>
		<?php unset($msglist);?>
	</div>
</div>
<div id="middle_2"><div class="space_between"></div></div>
<div id="right_2">
	<div><?php QG_C_TEMPLATE::p("inc.login","","0");?></div>
	<div class="space_between"><img src="templates/zh/default/images/blank.gif" border="0" width="1" height="1"></div>
	<div>
		<?php $special = QGMOD_SPECIAL(1,255);?>
		<div class="global_sub"><a href="<?php echo $special[url];?>"><?php echo $special[subject];?></a></div>
		<div class="border_no_top"><div style="padding:5px;">　　<?php echo $special[content];?><div align="right"><a href="<?php echo $special[url];?>"<?php echo $special[target];?>><img src="templates/zh/default/images/more.jpg" border="0"></a></div></div></div>
		<?php unset($special);?>
	</div>
	<div class="space_between"><div class="space_between"></div></div>
	<div>
		<?php $catelist = QGMOD_CATELIST("11,12,25,26,39,43,51,62");?>
		<div class="global_sub">产品分类</div>
		<div class="border_no_top">
			<?php $_i=0;$catelist=(is_array($catelist))?$catelist:array();foreach($catelist AS  $key=>$value){$_i++; ?>
			<div style="padding-left:20px;"><a href="list.php?id=<?php echo $value[id];?>"><?php echo $value[catename];?></a></div>
			<?php } ?>
		</div>
		<?php unset($catelist);?>
	</div>
	<div class="space_between"><div class="space_between"></div></div>
	<div><?php QG_C_TEMPLATE::p("inc.search","","0");?></div>
</div>

<div style="clear:both;"></div>

</div>
</div>
</div>
<?php QG_C_TEMPLATE::p("inc.foot","","0");?>