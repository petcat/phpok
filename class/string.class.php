<?php
#============================
#	Filename: string.class.php
#	Note	: 字符串管理
#	Version : 2.0
#	Author  : qinggan
#	Update  : 2008-03-11
#============================
class QG_C_STRING
{
	var $script = false;
	var $iframe = false;
	var $style = false;

	function __construct($script=false,$iframe=false,$style=false)
	{
		$this->script = $script;
		$this->iframe = $iframe;
		$this->style = $style;
	}

	#[兼容PHP4]
	function QG_C_STRING($script=false,$iframe=false,$style=false)
	{
		$this->__construct($script,$iframe,$style);
	}

	function __destruct()
	{
		return true;
	}

	#[设置状态属性]
	function set($var,$status)
	{
		$this->$var = $status;
	}

	function safe($msg)
	{
		if(!$msg)
		{
			return false;
		}
		if(is_array($msg))
		{
			foreach($msg AS $key=>$value)
			{
				$msg[$key] = $this->safe($value);
			}
		}
		else
		{
			$msg = trim($msg);
			$old = array("&amp;","&nbsp;","'",'"',"<",">","\t","\r");
			$new = array("&"," ","&#39;","&quot;","&lt;","&gt;","&nbsp; &nbsp; ","");
			$msg = str_replace($old,$new,$msg);
			$msg = str_replace("   ","&nbsp; &nbsp;",$msg);
			$old = array("/<script(.*)<\/script>/isU","/<frame(.*)>/isU","/<\/fram(.*)>/isU","/<iframe(.*)>/isU","/<\/ifram(.*)>/isU","/<style(.*)<\/style>/isU");
			$new = array("","","","","","");
			$msg = preg_replace($old,$new,$msg);
		}
		return $msg;
	}

	function html($msg)
	{
		if(is_array($msg))
		{
			foreach($msg AS $key=>$value)
			{
				$msg[$key] = $this->html($value);
			}
		}
		else
		{
			$msg = trim($msg);
			$msg = stripslashes($msg);
			if(!$this->script)
			{
				$msg = preg_replace("/<script(.*)<\/script>/isU","",$msg);
			}
			if(!$this->iframe)
			{
				$msg = preg_replace("/<frame(.*)>/isU","",$msg);
				$msg = preg_replace("/<\/fram(.*)>/isU","",$msg);
				$msg = preg_replace("/<iframe(.*)>/isU","",$msg);
				$msg = preg_replace("/<\/ifram(.*)>/isU","",$msg);
			}
			if(!$this->style)
			{
				$msg = preg_replace("/<style(.*)<\/style>/isU","",$msg);
			}
			#[超链接在新窗口打开]
			$msg = preg_replace("/<a(.*)target=[ |'|\"](.*)[ |'|\"](.*)>/isU","<a\\1 \\3>",$msg);
			$msg = preg_replace("/<a(.*)>/isU","<a\\1 target='_blank'>",$msg);
			#[替换网址]
			$url = $this->get_url();
			$msg = str_replace($url,"",$msg);
			$msg = addslashes($msg);
		}
		return $msg;
	}

	#[截取字符长度，仅支持UTF-8]
	function cut($string,$length,$dot="…")
	{
		if(strlen($string) <= $length)
		{
			return $string;
		}
		$strcut = '';
		$n = $tn = $noc = 0;
		while ($n < strlen($string))
		{
			$t = ord($string[$n]);
			if($t == 9 || $t == 10 || (32 <= $t && $t <= 126))
			{
				$tn = 1; $n++; $noc++;
			}
			elseif(194 <= $t && $t <= 223)
			{
				$tn = 2; $n += 2; $noc += 2;
			}
			elseif(224 <= $t && $t < 239)
			{
				$tn = 3; $n += 3; $noc += 2;
			}
			elseif(240 <= $t && $t <= 247)
			{
				$tn = 4; $n += 4; $noc += 2;
			}
			elseif(248 <= $t && $t <= 251)
			{
				$tn = 5; $n += 5; $noc += 2;
			}
			elseif($t == 252 || $t == 253)
			{
				$tn = 6; $n += 6; $noc += 2;
			}
			else
			{
				$n++;
			}

			if ($noc >= $length)
			{
				break;
			}
		}
		if ($noc > $length)
		{
			$n -= $tn;
		}
		$strcut = substr($string, 0, $n);
		return $strcut.$dot;
	}

	#[编码转换，使用PHP里的iconv功能]
	function charset($msg, $s_code="UTF-8", $e_code="GBK")
	{
		if(!$msg)
		{
			return false;
		}
		if(is_array($msg))
		{
			foreach($msg AS $key=>$value)
			{
				$msg[$key] = $this->charset($value,$s_code,$e_code);
			}
		}
		else
		{
			if(function_exists("iconv"))
			{
				$msg = iconv($s_code,$e_code,$msg);
			}
		}
		return $msg;
	}

	function format($msg,$f=false)
	{
		$status = get_magic_quotes_gpc();
		if(!$status || $f)
		{
			if(is_array($msg))
			{
				foreach($msg AS $key=>$value)
				{
					$msg[$key] = $this->format($value,$f);
				}
			}
			else
			{
				$msg = addslashes($msg);
			}
		}
		return $msg;
	}

	function num_format($a,$ext=2)
	{
		if(!$a || $a == 0)
		{
			return false;
		}
		if($a <= 1024)
		{
			$a = "1 KB";
		}
		elseif($a>1024 && $a<(1024*1024))
		{
			$a = round(($a/1024),$ext)." KB";
		}
		elseif($a>=(1024*1024) && $a<(1024*1024*1024))
		{
			$a = round(($a/(1024*1024)),$ext)." MB";
		}
		else
		{
			$a = round(($a/(1024*1024*1024)),$ext)." GB";
		}
		return $a;
	}

	function get_url()
	{
		$myurl = "http://".str_replace("http://","",$_SERVER["SERVER_NAME"]);
		$docu = $_SERVER["PHP_SELF"];
		$array = explode("/",$docu);
		$count = count($array);
		if($count>1)
		{
			foreach($array AS $key=>$value)
			{
				$value = trim($value);
				if($value)
				{
					if(($key+1) < $count)
					{
						$myurl .= "/".$value;
					}
				}
			}
		}
		$myurl .= "/";
		return $myurl;
	}
}
?>