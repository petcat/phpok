<?php
#[更新HTML类]
#[PHPOK2.0Beta版更新HTML功能采用点击更新方式]
#[在后台仅是设置一个标识是否更新HTML]
CLASS Rewrite
{
	var $content;
	var $url;#[网站网址]
	var $tplfolder;#[模板目录，主要是用于更新图片地址]
	var $upfiles;#[上传文件的目录]
	var $urlarray;#[要更新的网址的数组]
	var $imgarray;#[要更新的图片的数组]
	var $lang;#[模板语言包]
	var $input_array;#[更新input里的图片]
	var $js_array;#[更新里面的的JS文件路径]
	var $form_array;#[修正form里的action动作]

	function __construct($siteurl="./",$lang="zh",$tplfolder="templates",$upfiles="upfiles")
	{
		$this->url = defined("RW_SITE_URL") ? RW_SITE_URL : $siteurl;
		if($this->url == "./")
		{
			$this->get_siteurl();#[自动获取系统网址]
		}
		if(substr($this->url,-1) != "/")
		{
			$this->url .= "/";
		}
		$this->siteurl = $this->url;
		$this->tplfolder = defined("RW_TPL_FOLDER") ? RW_TPL_FOLDER : $tplfolder;
		$this->upfiles = defined("RW_UPFILES") ? RW_UPFILES : $upfiles;
		$this->lang = defined("RW_LANG") ? RW_LANG : $lang;
	}

	#[兼容PHP4]
	function Rewrite($siteurl="./",$lang="zh",$tplfolder="templates",$upfiles="upfiles")
	{
		$this->__construct($siteurl,$lang,$tplfolder,$upfiles);
	}

	function set_content($content)
	{
		$this->content = $content;
	}

	#[一个函数进行下面全部操作]
	function qg_rewrite($content="")
	{
		if($content)
		{
			$this->content = $content;
		}
		$this->geturl_array();
		$this->replace_url();
		return $this->content;#[返回rewrite的内容]
	}

	function geturl_array()
	{
		$this->urlarray = array();
		#[获取网址中的Url]
		preg_match_all("/href=[\"|'| ]{0,}(.*?)[\"|'| ]{1,}/is",$this->content,$this->urlarray);
		$this->urlarray = array_unique($this->urlarray[1]);
	}

	function replace_array($var)
	{
		if(!$var || $var == "")
		{
			return false;
		}
		$myarray = $this->$var;
		$chkcount = count($myarray);
		if($chkcount < 1)
		{
			return false;
		}
		foreach($myarray AS $key=>$value)
		{
			$this->global_replace($value);
		}
	}

	function replace_url()
	{
		$chkcount = count($this->urlarray);
		if($chkcount < 1)
		{
			return false;
		}
		#[开始网址替换]
		foreach($this->urlarray AS $key=>$value)
		{
			$value = trim($value);
			$old_value = $value;
			if(!$value || $value == "")
			{
				continue;
			}
			$chk_7 = substr(strtolower($value),0,7);
			#[判断是否有带http://]
			if($chk_7 == "http://" && substr(strtolower($value),0,strlen($this->siteurl)) != $this->siteurl)
			{
				continue;
			}
			#[判断是否是mailto:]
			if($chk_7 == "mailto:")
			{
				continue;
			}
			if(substr($value,0,2) == "./")
			{
				$value = substr($value,2);
			}
			#[to-rewrite-html]
			$value = $this->to_rewrite_url($value);
			#[替换，为了防止带分页的替换错误，这里进行三次替换]
			if($value != $old_value)
			{
				$this->str_replace_array($old_value,$value);
			}
		}
	}


	function global_replace($msg)
	{
		$msg = trim($msg);
		$old = $msg;
		if(!$msg || $msg == "")
		{
			return false;
		}
		if(substr(strtolower($msg),0,7) == "http://")
		{
			return false;
		}
		if(substr($msg,0,2) == "./")
		{
			$msg = substr($msg,2);
		}
		$msg = $this->siteurl.$msg;
		if($msg != $old)
		{
			$this->str_replace_array($old,$msg);
		}
		return true;
	}

	function str_replace_array($old,$new)
	{
		$this->content = str_replace("='".$old."'","='".$new."'",$this->content);
		$this->content = str_replace('="'.$old.'"','="'.$new.'"',$this->content);
		$this->content = str_replace("=".$old." ","=".$new." ",$this->content);
	}

	function to_rewrite_url($url)
	{
		#[判断参数类型]
		$chkurl = basename($url);
		#echo $chkurl."<br />";
		@$ext = strstr($chkurl,"?");
		if($ext)
		{
			@$chkurl = str_replace($ext,"",$chkurl);
		}
		#[首页，不支持任何参数]
		if($chkurl == "index.php")
		{
			$return_url = "index.html";
			return $return_url;
		}#[home页]
		elseif($chkurl == "home.php")
		{
			if(!$ext)
			{
				$return_url = "home.html";
				return $return_url;
			}
			else
			{
				return $url;
			}
		}#[列表页]
		elseif($chkurl == "list.php")
		{
			$ext_array = $this->url_ext_array($ext);
			$return_url = "list";
			$return_url .= "-".$ext_array["id"];
			$ext_array["pageid"] = intval($ext_array["pageid"]);
			$ext_array["pageid"] = $ext_array["pageid"] >0 ? $ext_array["pageid"] : 1;
			#[如果有模板]
			$return_url .= "-".$ext_array["pageid"];
			$return_url .= ".html";
			return $return_url;
		}#[内容页]
		elseif($chkurl == "msg.php")
		{
			$ext_array = $this->url_ext_array($ext);
			$return_url = "msg";
			$return_url .= "-".$ext_array["id"];
			$ext_array["pageid"] = intval($ext_array["pageid"]);
			$ext_array["pageid"] = intval($ext_array["pageid"]);
			if($ext_array["pageid"] && $ext_array["pageid"] != 0)
			{
				$return_url .= "-".$ext_array["pageid"];
			}
			$return_url .= ".html";
			return $return_url;
		}
		elseif($chkurl == "special.php")
		{
			$ext_array = $this->url_ext_array($ext);
			$return_url = "special-".$ext_array["id"];
			$ext_array["pageid"] = intval($ext_array["pageid"]);
			if($ext_array["pageid"] && $ext_array["pageid"] != 0)
			{
				$return_url .= "-".$ext_array["pageid"];
			}
			$return_url .= ".html";
			return $return_url;
		}
		elseif($chkurl == "book.php")
		{
			$ext_array = $this->url_ext_array($ext);
			$return_url = "book-";
			if($ext_array["act"] == "add")
			{
				$return_url .= "add";
			}
			else
			{
				$return_url .= "list";
			}
			$ext_array["pageid"] = intval($ext_array["pageid"]);
			$ext_array["pageid"] = $ext_array["pageid"] >0 ? $ext_array["pageid"] : 1;
			#[如果有模板]
			$return_url .= "-".$ext_array["pageid"];
			$return_url .= ".html";
			return $return_url;
		}
		else
		{
			return $url;
		}
	}

	function url_ext_array($ext)
	{
		if(substr($ext,0,1) == "?")
		{
			$ext = substr($ext,1);
		}
		$array = $explode_array = array();
		$explode_array = explode("&",$ext);
		foreach($explode_array AS $key=>$value)
		{
			$value = trim($value);
			if($value)
			{
				$v_m = explode("=",$value);
				if($v_m[0] && $v_m[1])
				{
					$array[$v_m[0]] = $v_m[1];
				}
			}
		}
		return $array;
	}


	function set_lang($lang)
	{
		$this->lang = $lang;
	}

	function set_tplfolder($tplfolder)
	{
		$this->tplfolder = $tplfolder;
	}

	function set_upfiles($upfiles)
	{
		$this->upfiles = $upfiles;
	}

	function set_siteurl($url)
	{
		$this->url = $url;
	}

	function get_siteurl()
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
		$this->url = $myurl;
	}
}
?>