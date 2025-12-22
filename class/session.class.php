<?php
#[定义session类信息]
CLASS SESSION
{
	var $DB;
	var $prefix;
	var $sessid;

	Function __construct($DB,$prefix="qinggan_")
	{
		$this->DB = $DB;
		$this->prefix = $prefix;
	}

	#[兼容PHP4]
	Function SESSION($DB,$prefix="qinggan_")
	{
		$this->__construct($DB,$prefix);
	}

	Function qgOpen($save_path,$session_name)
	{
		return true;
	}

	Function qgClose()
	{
		return true;
	}

	Function qgRead($sid)
	{
		$this->sessid = $sid;
		$rs = $this->DB->qgGetOne("SELECT * FROM ".$this->prefix."session WHERE id='".$sid."'");
		if(!$rs)
		{
			$this->DB->qgQuery("INSERT INTO ".$this->prefix."session SET id='".$sid."',data='',lasttime='".time()."'");
			return false;
		}
		else
		{
			if($rs["data"])
			{
				return $rs["data"];
			}
			else
			{
				return false;
			}
		}
	}

	Function qgWrite($sid,$data)
	{
		$this->DB->qgQuery("UPDATE ".$this->prefix."session SET data='".$data."',lasttime='".time()."' WHERE id='".$sid."'");
		return true;
	}

	function qgDelete($sid)
	{
		$this->DB->qgQuery("DELETE FROM ".$this->prefix."session WHERE id='".$sid."'");
		return true;
	}

	function qgGc()
	{
		$this->DB->qgQuery("DELETE FROM ".$this->prefix."session WHERE lasttime+1800<'".time()."'");
		return true;
	}

	function sessid()
	{
		return $this->sessid;
	}
}
$SESSION = new session($DB,$prefix);
session_module_name("user");
session_set_save_handler(
	array($SESSION,"qgOpen"),
	array($SESSION,"qgClose"),
	array($SESSION,"qgRead"),
	array($SESSION,"qgWrite"),
	array($SESSION,"qgDelete"),
	array($SESSION,"qgGc")
);
session_start();
?>