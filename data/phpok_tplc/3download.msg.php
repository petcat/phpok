<?php if(!defined('PHPOK_SET')){die('<h3>Error...</h3>');}?><!-- 文章内容页默认模板 -->
<?php QG_C_TEMPLATE::p("inc.head","","0");?>
<div align="center">
<div id="body">
<div class="table">

<div id="left_1">
	<div><?php QG_C_TEMPLATE::p("inc.login","","0");?></div>
	<div class="space_between"><div class="space_between"></div></div>

	<div class="global_sub">当前分类 &nbsp;<?php if($rs[parentid]){?><a href="list.php?id=<?php echo $rs[parentid];?>">上级分类</a><?php } ?></div>
	<div class="border_no_top">
		<?php $_i=0;$left_catelist=(is_array($left_catelist))?$left_catelist:array();foreach($left_catelist AS  $key=>$value){$_i++; ?>
		<div style="padding-left:20px;"><a href="list.php?id=<?php echo $value[id];?>" style="<?php echo $value[style];?><?php if($value[id] == $cateid){?>;color:red;<?php } ?>"><?php echo $value[catename];?></a></div>
		<?php } ?>
	</div>
	<div class="space_between"><div class="space_between"></div></div>

	<div class="global_sub">推荐软件</div>
	<div class="border_no_top">
		<?php $mod_artlist = QGMOD_MSG_CATEID("isvouch DESC,hits ASC,orderdate DESC",26);?>
		<?php $_i=0;$mod_artlist=(is_array($mod_artlist))?$mod_artlist:array();foreach($mod_artlist AS  $key=>$value){$_i++; ?>
		<div>&nbsp;※ <a href="msg.php?id=<?php echo $value[id];?>" style="<?php echo $value[style];?>" title="<?php echo $value[subject];?>"><?php echo $value[cut_subject];?></a></div>
		<?php } ?>
		<?php unset($mod_artlist);?>
	</div>
	<div class="space_between"><div class="space_between"></div></div>

	<div class="global_sub">热门软件</div>
	<div class="border_no_top">
		<?php $mod_artlist = QGMOD_MSG_CATEID("hits DESC,orderdate DESC",26);?>
		<?php $_i=0;$mod_artlist=(is_array($mod_artlist))?$mod_artlist:array();foreach($mod_artlist AS  $key=>$value){$_i++; ?>
		<div>&nbsp;※ <a href="msg.php?id=<?php echo $value[id];?>" style="<?php echo $value[style];?>" title="<?php echo $value[subject];?>"><?php echo $value[cut_subject];?></a></div>
		<?php } ?>
		<?php unset($mod_artlist);?>
	</div>
</div>

<div id="middle_1"><div class="space_between"></div></div>

<div id="right_1">
	<div class="global_sub">内容：<?php echo $rs[subject];?></div>
	<div class="border_no_top">
		<div class="msg_sub"><?php echo $rs[subject];?></div>
		<div class="msg_date">发布时间：<?php echo $rs[postdate];?> &nbsp; 点击率：<?php echo $rs[hits];?></div>
		<div class="msg_content">
			<table width="98%">
			<tr>
				<td valign="top" width="50%">
					<div class="wtr">语&nbsp;&nbsp;言：<?php echo $rs[ext_0];?></div>
					<div class="dtr">授权方式：<?php echo $rs[ext_1];?></div>
					<div class="wtr">文件大小：<?php echo $rs[ext_size];?></div>
					<div class="wtr">整理日期：<?php echo $rs[postdate];?></div>
					<div class="dtr">浏览次数：<?php echo $rs[hits];?></div>
					<div class="dtr">官方网站：<?php if($rs[ext_5]){?><a href="<?php echo $rs[ext_5];?>" target="_blank">Home Page</a><?php }else{ ?>未知<?php } ?></div>
					<div class="wtr">联系方式：<?php echo $rs[ext_6];?></div>
				</td>
				<td><?php echo QGMOD_AD(3);?></td>
			</tr>
			</table>
			<div class="tr"><?php echo $content;?><div style="clear:both;"></div></div>
		</div>
		<div align="right"><?php echo $pagelist;?></div>
	</div>
</div>
<div style="clear:both;"></div>

</div>
</div>
</div>

<?php QG_C_TEMPLATE::p("inc.foot","","0");?>