<?php
#============================
#	Filename: tplfolder.class.php
#	Note	: 模板目录选择
#	Version : 2.0
#	Author  : qinggan
#	Update  : 2008-03-31
#============================
if(!defined("PHPOK_SET"))
{
	exit("Access Denied");
}
if(!defined("LANGUAGE_ID"))
{
	exit("<h3>Error...</h3>");
}
class QG_C_TPLFOLDER
{
	var $DB;
	var $prefix;
	var $tplid = 1;

	function __construct($DB,$prefix="qinggan_")
	{
		$this->DB = $DB;
		$this->prefix = $prefix;
		$this->tplid = $_SESSION["template_id"] ? $_SESSION["template_id"] : 1;
	}

	#[兼容PHP4]
	function QG_C_TPLFOLDER($DB,$prefix="qinggan_")
	{
		$this->__construct($DB,$prefix);
	}

	#[显示模板目录]
	function folder()
	{
		$qg_systpl = $_GET["template"] ? $_GET["template"] : $_POST["template"];
		$qg_syslang = $_GET["langsign"] ? $_GET["langsign"] : $_POST["langsign"];
		if(!$qg_systpl && !$qg_syslang)
		{
			return $_SESSION["tpl_folder"] ? $_SESSION["tpl_folder"] : $this->get_default();
		}
		else
		{
			$rs = $this->DB->qgGetOne("SELECT * FROM ".$this->prefix."tpl WHERE folder='".$qg_systpl."' AND language='".LANGUAGE_ID."'");
			if($rs)
			{
				$this->tplid = $rs["id"];
				return $rs["folder"];
			}
			else
			{
				return $this->get_default();
			}
		}
	}

	function get_default()
	{
		$rs = $this->DB->qgGetOne("SELECT * FROM ".$this->prefix."tpl WHERE isdefault='1' AND language='".LANGUAGE_ID."'");
		if($rs)
		{
			$this->tplid = $rs["id"];
			return $rs["folder"];
		}
		else
		{
			$this->tplid = 1;
			return "default";
		}
	}

	function tplid()
	{
		return $this->tplid;
	}
}
?>