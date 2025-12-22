<?php
function table2sql($table)
{
	global $DB;
	$tabledump = "DROP TABLE IF EXISTS ".$table.";\n";
	$query = $DB->qgQuery("SHOW CREATE TABLE ".$table,"NUM");
	$rows = $DB->qgGetOne();
	$tabledump .= $rows[1].";\n\n";
	return $tabledump;
}

function recover_data($sql)
{
	global $DB;
	$sql=str_replace("\r","\n",$sql);
	$sql_array=explode(";\n",$sql);
	foreach($sql_array as $key=>$value)
	{
		$value = trim($value);
		if($value == "#" || $value == "--")
		{
			$queryy = explode("\n",$value);
			$value = '';
			foreach($queryy as $v2)
			{
				if($v2[0]!='#') $value.=$v2;
			}
		}
		if($value)
		{
			$DB->qgQuery($value);
		}
	}
	return true;
}

#[判断权限]
if($_SESSION["admin"]["typer"] != "system")
{
	Error("对不起，您没有权限操作当前功能","admin.php?file=index");
}

#[数据库备份、恢复操作]
if($sys_act == "back")
{
	$filename = SafeHtml($filename);
	$numid = intval($numid) + 1;
	$tbls = SafeHtml($tbls);
	if($tbls)
	{
		$tables = explode(",",$tbls);#[如果有传递表名]
	}
	else
	{
		$tables = $DB->qgListTables();#[显示表名数组]
	}
	$table_id = intval($table_id);#[获取当前的表ID]
	$table_count = count($tables);#[表名数量]
	if($table_count < 1)
	{
		Error("没有选中指定表信息或当前数据库中没有表信息...","admin.php?file=sqlbak&act=qgbak");
	}
	#[存放数据表结构]
	$bakfile = "data/sqlback/".date("Ymd",$system_time)."_".$filename."/_tables_frame.php";
	#[存放数据表名称]
	$bak_tables_file ="data/sqlback/".date("Ymd",$system_time)."_".$filename."/_tables_name.php";
	if(!$table_id || $table_id == 0 || !file_exists($bakfile) || !file_exists($bak_tables_file))
	{
		$table_array = array();
		#[备份数据表表名]
		for($i=0;$i<$table_count;$i++)
		{
			$table = $tables[$i];
			$table_array[$i] = table2sql($table);
		}
		$msg = implode("\n",$table_array);
		$FS->qgWrite($msg,$bakfile);
		$FS->qgWrite(implode(",",$tables),$bak_tables_file);
	}
	$start_id = intval($start_id);#[开始内容ID]
	$pageid = intval($pageid);#[当前分页ID]
	if($table_id < $table_count)
	{
		$table = $tables[$table_id];#[数据表名]
		if(!$table)
		{
			Error("表名为空...","admin.php?file=sqlbak&act=qgbak");
		}
		$total_count = intval($total_count);
		if(!$total_count || $total_count == 0)
		{
			$rs = $DB->qgGetOne("select count(*) as count from `".$table."`");
			$count = $rs["count"];//获取个数
		}
		else
		{
			$count = $total_count;
		}
		$psize = 1000;//最大数不超过1000
		if($count<$psize)
		{
			$psize = $count;
		}
		if($count && $start_id < $count)
		{
			$query = $DB->qgQuery("select * from `".$table."` limit ".$start_id.",".$psize,"NUM");//
			$numfields = $DB->qgNumFields();
			$tabledump = "";
			while($rslist = $DB->qgGetOne())
			{
				$tabledump .= "INSERT INTO ".$table." VALUES (";
				$comma = '';
				for($i = 0; $i < $numfields; $i++)
				{
					$tabledump .= $comma.('\''.$DB->qgEscapeString($rslist[$i]).'\'');
					$comma = ',';
				}
				$tabledump .= ");\n";
				//---------------
				$start_id++;
				if(strlen($tabledump)>=(2048*1000))
				{
					$bakfile = "data/sqlback/".date("Ymd",$system_time)."_".$filename."/".$table."_".$pageid.".php";
					$tabledump = addslashes($tabledump);
					$FS->qgWrite($tabledump,$bakfile);
					unset($tabledump);//清空内容！
					Error("正在备份数据表 ".$table." 信息，当前已经写入第 ".($pageid+1)." 页，即将写入第 ".($pageid+2)." 页信息","admin.php?file=sqlbak&act=back&filename=".$filename."&table_id=".$table_id."&start_id=".$start_id."&pageid=".($pageid+1)."&total_count=".$count."&tbls=".$tbls);
				}
			}
			if($tabledump)
			{
				$tabledump = addslashes($tabledump);
				if($pageid>0)
				{
					$bakfile = "data/sqlback/".date("Ymd",$system_time)."_".$filename."/".$table."_".($pageid-1).".php";
					$msg = $FS->qgRead($bakfile);
					if(strlen($msg) < (2048*1000))
					{
						$FS->qgWrite($tabledump,$bakfile,"","ab");
						unset($msg,$tabledump);
						$newpageid = $pageid;
					}
					else
					{
						$bakfile = "data/sqlback/".date("Ymd",$system_time)."_".$filename."/".$table."_".$pageid.".php";
						$FS->qgWrite($tabledump,$bakfile);
						unset($tabledump);
						$newpageid = $pageid + 1;
					}
				}
				else
				{
					$bakfile = "data/sqlback/".date("Ymd",$system_time)."_".$filename."/".$table."_".$pageid.".php";
					$FS->qgWrite($tabledump,$bakfile);
					unset($tabledump);
					$newpageid = $pageid + 1;
				}
			}
			if($start_id<$count)
			{
				Error("正在备份数据表 ".$table." 信息。","admin.php?file=sqlbak&act=back&filename=".$filename."&table_id=".$table_id."&start_id=".$start_id."&pageid=".$newpageid."&total_count=".$count."&tbls=".$tbls);
			}
			else
			{
				Error("数据表 ".$table." 信息已经备份完毕，将开始备份下一个数据表！","admin.php?file=sqlbak&act=back&filename=".$filename."&table_id=".($table_id+1)."&start_id=0&pageid=0&total_count=0&tbls=".$tbls);
			}
		}
		else
		{
			Error("数据表 ".$table." 信息为空，将开始下一个数据表信息备份","admin.php?file=sqlbak&act=back&filename=".$filename."&table_id=".($table_id+1)."&start_id=0&pageid=0&total_count=0&tbls=".$tbls);
		}
	}
	Error("数据备份成功...","admin.php?file=sqlbak&act=list");
}
elseif($sys_act == "optimize" || $sys_act == "repair")
{
	$note = $sys_act == "optimize" ? "优化" : "修复";
	#[优化数据表]
	$tbls = SafeHtml($tbls);
	if($tbls)
	{
		$tables = explode(",",$tbls);#[如果有传递表名]
	}
	else
	{
		$tables = $DB->qgListTables();#[显示表名数组]
	}
	$table_id = intval($table_id);
	if($table_id < count($tables))
	{
		$table = $tables[$table_id];
		if($table)
		{
			$sql = strtoupper($sys_act)." TABLE ".$tables[$table_id];
			$DB->qgQuery($sql);
			Error("开始优化数据表 ".$table." 请稍后...","admin.php?file=sqlbak&act=".$sys_act."&tbls=".$tbls."&table_id=".($table_id+1));
		}
		else
		{
			Error("找不到指定的数据表...","admin.php?file=sqlbak&act=".$sys_act."&tbls=".$tbls."&table_id=".($table_id+1));
		}
	}
	Error("数据表信息已全部".$note."完毕...","admin.php?file=sqlbak&act=list");
}
elseif($sys_act == "recover")
{
	$filename = SafeHtml($filename);
	if(!$filename)
	{
		Error("操作错误...","admin.php?file=sqlbak&act=list");
	}
	#[恢复数据表结构]
	$recover_file = "data/sqlback/".$filename."/_tables_frame.php";
	if(!file_exists($recover_file))
	{
		Error("备份数据已被破坏，请选择其他备份数据进行恢复...","admin.php?file=sqlbak&act=qgrecover");
	}
	$sql = $FS->qgRead($recover_file);
	recover_data($sql);
	Error("数据表结构已经恢复，正在恢复相关数据...","admin.php?file=sqlbak&act=recover_data&filename=".$filename);
}
elseif($sys_act == "recover_data")
{
	$filename = SafeHtml($filename);
	if(!$filename)
	{
		Error("操作错误...","admin.php?file=sqlbak&act=qgrecover");
	}
	#[恢复数据表结构]
	$recover_file = "data/sqlback/".$filename."/_tables_frame.php";
	if(!file_exists($recover_file))
	{
		Error("备份数据已被破坏，请选择其他备份数据进行恢复...","admin.php?file=sqlbak&act=qgrecover");
	}
	$recover_tables = "data/sqlback/".$filename."/_tables_name.php";
	if(!file_exists($recover_tables))
	{
		Error("备份数据已被破坏，请选择其他备份数据进行恢复...","admin.php?file=sqlbak&act=qgrecover");
	}
	$table_id = intval($table_id);
	$pageid = intval($pageid);
	$tbls = $FS->qgRead($recover_tables);
	if(!$tbls)
	{
		Error("备份数据已被破坏，请选择其他备份数据进行恢复...","admin.php?file=sqlbak&act=qgrecover");
	}
	$tables = explode(",",$tbls);
	$mytables = array();
	foreach($tables AS $key=>$value)
	{
		$value = trim($value);
		if($value)
		{
			$mytables[] = $value;
		}
	}
	if(count($mytables)<1)
	{
		Error("要恢复的数据表为空，可能该备份数据已被破坏，请选择其他备份数据进行恢复...","admin.php?file=sqlbak&act=qgrecover");
	}
	if($table_id < count($mytables))
	{
		$table = $mytables[$table_id];
		$recover_file = "data/sqlback/".$filename."/".$table."_".$pageid.".php";
		if(!file_exists($recover_file))
		{
			Error("指定的数据表信息为空或未备份，正在恢复下一个数据表...","admin.php?file=sqlbak&act=recover_data&filename=".$filename."&table_id=".($table_id+1)."&pageid=0");
		}
		$sql = $FS->qgRead($recover_file);
		if($sql)
		{
			recover_data($sql);
		}
		$next_file = "data/sqlback/".$filename."/".$table."_".($pageid+1).".php";
		if(file_exists($next_file))
		{
			Error("正在恢复数据表 ".$table." 相关数据，请稍候...","admin.php?file=sqlbak&act=recover_data&filename=".$filename."&table_id=".$table_id."&pageid=".($pageid+1));
		}
		else
		{
			Error("数据表 ".$table." 信息已恢复完毕，正在恢复下一个数据表信息...","admin.php?file=sqlbak&act=recover_data&filename=".$filename."&table_id=".($table_id+1)."&pageid=0");
		}
	}
	Error("指定备份数据已经恢复...","admin.php?file=sqlbak&act=qgrecover");
}
elseif($sys_act == "qgrecover")
{
	#[获取已备份的数据表信息]
	$baklist = array();
	$read_list = $FS->qgDir("data/sqlback");
	$tables = $DB->qgListTables();#[显示表名数组]
	foreach((is_array($read_list) ? $read_list : array()) AS $key=>$value)
	{
		$my_array = array();
		$bak_name = basename($value);
		$my_array["filename"] = $bak_name;
		if(!file_exists("data/sqlback/".$bak_name."/_tables_name.php"))
		{
			$my_array["tables"] = "备份文件已经被破坏掉";
		}
		else
		{
			$my_array["tables"] = $FS->qgRead("data/sqlback/".$bak_name."/_tables_name.php");
			if(count($tables) == count(explode(",",$my_array["tables"])))
			{
				$my_array["tables"] = "All Tables!";
			}
		}
		#[计算备份文件的大小]
		$bak_file = $FS->qgDir("data/sqlback/".$bak_name);
		$filesize = 0;
		foreach($bak_file AS $key=>$value)
		{
			$filesize += filesize($value);
		}
		#[转换文件大小]
		$filesize = format_filesize($filesize);
		$my_array["filesize"] = $filesize;
		if(!file_exists("data/sqlback/".$bak_name."/_tables_frame.php"))
		{
			$my_array["time"] = "备份文件已损坏";
		}
		else
		{
			$my_array["time"] = date("Y-m-d H:i:s",filemtime("data/sqlback/".$bak_name."/_tables_frame.php"));
		}
		$baklist[] = $my_array;
	}
}
elseif($sys_act == "delete")
{
	#[删除备份文件]
	$filename = SafeHtml($filename);
	if(!$filename)
	{
		Error("操作不正确...","admin.php?file=sqlbak&act=qgrecover");
	}
	$FS->qgDelete("data/sqlback/".$filename,"folder");
	Error("备份文件 ".$filename." 已删除...","admin.php?file=sqlbak&act=qgrecover");
}
else
{
	$tables_list = $DB->qgGetAll("SHOW TABLE STATUS FROM ".$DB->data);#[显示表状态]
	$tables = array();
	foreach($tables_list AS $key=>$value)
	{
		$value["Data_length"] = $value["Data_length"] + $value["Index_length"];
		$value["Data_length"] = format_filesize($value["Data_length"]);#[当前]
		$value["Data_free"] = format_filesize($value["Data_free"]);#[多余文件大小]
		$tables[] = $value;
	}
}
Foot("sqlbak.qg");
?>