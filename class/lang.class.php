<?php
#============================
#	Filename: lang.class.php
#	Note	: 语言管理
#	Version : 2.0
#	Author  : qinggan
#	Update  : 2008-03-11
#============================
class QG_C_LANG
{
	var $DB;
	var $prefix;
	function __construct($DB,$prefix)
	{
		$this->DB = $DB;
		$this->prefix = $prefix;
	}

	#[兼容PHP4操作]
	function QG_C_LANG($DB,$prefix)
	{
		$this->__construct($DB,$prefix);
	}

	#[判断是否有变换语言]
	function lang()
	{
		$qg_syslang = $_GET["langsign"] ? $_GET["langsign"] : $_POST["langsign"];
		if($qg_syslang)
		{
			$rslang = $this->DB->qgGetOne("SELECT id,name,sign FROM ".$this->prefix."lang WHERE sign='".$qg_syslang."'");
			if(file_exists("langs/".$qg_syslang.".php") && $rslang)
			{
				include_once("langs/".$qg_syslang.".php");
				define("LANGUAGE_ID",$rslang["id"]);
				define("LANGUAGE_NAME",$rslang["name"]);
				define("LANGUAGE_SIGN",$qg_syslang);
				unset($rslang);
				$_SESSION["qglang"]["sign"] = $qg_syslang;
				$_SESSION["qglang"]["id"] = LANGUAGE_ID;
				$_SESSION["qglang"]["name"] = LANGUAGE_NAME;
			}
			else
			{
				$langs = $this->getdefault();
			}
		}
		else
		{
			if($_SESSION["qglang"]["sign"] && $_SESSION["qglang"]["name"] && $_SESSION["qglang"]["id"])
			{
				if(file_exists("langs/".$_SESSION["qglang"]["sign"].".php"))
				{
					include_once("langs/".$_SESSION["qglang"]["sign"].".php");
				}
				else
				{
					$_SESSION["qglang"]["sign"] = "zh";
					include_once("langs/zh.php");
				}
				define("LANGUAGE_ID",$_SESSION["qglang"]["id"]);
				define("LANGUAGE_NAME",$_SESSION["qglang"]["name"]);
				define("LANGUAGE_SIGN",$_SESSION["qglang"]["sign"]);
			}
			else
			{
				$langs = $this->getdefault();
			}
		}
		return $langs;
	}

	function getdefault()
	{
		$rslang = $this->DB->qgGetOne("SELECT id,name,sign FROM ".$this->prefix."lang WHERE ifdefault='1'");
		if($rslang)
		{
			if(file_exists("langs/".$rslang["sign"].".php"))
			{
				define("LANGUAGE_ID",$rslang["id"]);
				define("LANGUAGE_NAME",$rslang["name"]);
				include_once("langs/".$rslang["sign"].".php");
				$_SESSION["qglang"]["sign"] = $rslang["sign"];
				$_SESSION["qglang"]["id"] = LANGUAGE_ID;
				$_SESSION["qglang"]["name"] = LANGUAGE_NAME;
			}
			else
			{
				define("LANGUAGE_ID",1);
				define("LANGUAGE_NAME","简体中文");
				include_once("langs/zh.php");
				$_SESSION["qglang"]["sign"] = "zh";
				$_SESSION["qglang"]["id"] = LANGUAGE_ID;
				$_SESSION["qglang"]["name"] = LANGUAGE_NAME;
				define("LANGUAGE_SIGN","zh");
			}
		}
		else
		{
			define("LANGUAGE_ID",1);
			define("LANGUAGE_NAME","简体中文");
			include_once("langs/zh.php");
			$_SESSION["qglang"]["sign"] = "zh";
			$_SESSION["qglang"]["id"] = LANGUAGE_ID;
			$_SESSION["qglang"]["name"] = LANGUAGE_NAME;
			define("LANGUAGE_SIGN","zh");
		}
		return $langs;
	}
}
?>