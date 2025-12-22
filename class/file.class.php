<?php
#------------------------------------------------------------------------------
#[谢您使用情感家园企业站程序：qgweb]
#[本程序由情感开发完成，当前版本：5.0]
#[本程序基于LGPL授权发布]
#[官方网站：www.phpok.com   www.qinggan.net]
#[客服邮箱：qinggan@188.com]
#[文件：file.php]
#------------------------------------------------------------------------------

class FILES
{
	var $readCount = 0;
	function FILES()
	{
		//
	}

	function qgRead($file="")
	{
		if($file)
		{
			$this->readCount++;
			$check = strtolower($file);
			if(strpos($check,"http://") === false)
			{
				if(!file_exists($file))
				{
					return false;
				}
			}
			$content = file_get_contents($file);
			$content = str_replace("<?php die('forbidden'); ?>\n","",$content);
			return $content;
		}
		else
		{
			return false;
		}
	}

	#[存储数据]
	function qgWrite($content,$file,$var="",$type="wb")
	{
		$this->qgMake($file,"file");
		if(is_array($content) && $var)
		{
			$content = $this->__array($content,$var);
			$content = "<?php \n".$content."\n ?".">";
		}
		else
		{
			$content = "<?php die('forbidden'); ?>\n".$content;
		}
		$this->_write($content,$file,$type);
		return true;
	}

	#[存储php等源码文件]
	function qgHtml($content,$file)
	{
		$this->qgMake($file,"file");
		$this->_write($content,$file,"wb");
		return true;
	}

	#[删除数据操作]
	#[这一步操作一定要小心，在程序中最好严格一些，不然有可能将整个目录删掉！]
	function qgDelete($file,$type="file")
	{
		$array = $this->_dir_list($file);
		if(is_array($array))
		{
			foreach($array as $key=>$value)
			{
				if(file_exists($value))
				{
					@unlink($value);
				}
			}
		}
		else
		{
			if(file_exists($array) && is_file($array))
			{
				@unlink($array);
			}
		}
		//如果要删除目录，同时设置
		if($type == "folder")
		{
			rmdir($file);
		}
		return true;
	}

	#[创建文件或目录]
	function qgMake($file,$type="dir")
	{
		$array = explode("/",$file);
		$count = count($array);
		$msg = "";
		if($type == "dir")
		{
			for($i=0;$i<$count;$i++)
			{
				$msg .= $array[$i];
				if(!file_exists($msg) && ($array[$i]))
				{
					mkdir($msg,0777);
				}
				$msg .= "/";
			}
		}
		else
		{
			for($i=0;$i<($count-1);$i++)
			{
				$msg .= $array[$i];
				if(!file_exists($msg) && ($array[$i]))
				{
					mkdir($msg,0777);
				}
				$msg .= "/";
			}
			@touch($file);//创建文件
		}
		return true;
	}

	#[复制操作]
	function qgCopy($old,$new,$recover=true)
	{
		if(substr($new,-1) == "/")
		{
			$this->qgMake($new,"dir");
		}
		else
		{
			$this->qgMake($new,"file");
		}
		if(is_file($new))
		{
			if($recover)
			{
				@unlink($new);
			}
			else
			{
				return false;
			}
		}
		else
		{
			$new = $new.basename($old);
		}
		copy($old,$new);
		return true;
	}

	#[文件移动操作]
	function qgMove($old,$new,$recover=true)
	{
		if(substr($new,-1) == "/")
		{
			$this->qgMake($new,"dir");
		}
		else
		{
			$this->qgMake($new,"file");
		}
		if(is_file($new))
		{
			if($recover)
			{
				@unlink($new);
			}
			else
			{
				return false;
			}
		}
		else
		{
			$new = $new.basename($old);
		}
		@rename($old,$new);
		return true;
	}

	#[获取文件夹列表]
	function qgDir($folder)
	{
		$this->readCount++;
		return $this->_dir_list($folder);
	}

	function _dir_list($file,$type="folder")
	{
		if(substr($file,-1) == "/") $file = substr($file,0,-1);
		if($type == "file")
		{
			return $file;
		}
		elseif(is_dir($file))
		{
			$handle = opendir($file);
			$array = array();
			while(false !== ($myfile = readdir($handle)))
			{
				if($myfile != "." && $myfile != "..") $array[] = $file."/".$myfile;
			}
			closedir($handle);
			return $array;
		}
		else
		{
			return $file;
		}
	}

	function __array($array,$var,$content="")
	{
		foreach($array AS $key=>$value)
		{
			if(is_array($value))
			{
				$content .= $this->__array($value,"".$var."[\"".$key."\"]");
			}
			else
			{
				$old_str = array("\n",'"',"<?php","?>","\r");
				$new_str = array("","'","&lt;?php","?&gt;","");
				$value = str_replace($old_str,$new_str,$value);
				$content .= "\$".$var."[\"".$key."\"] = \"".$value."\";\n";
			}
		}
		return $content;
	}

	#[打开文件]
	function _open($file,$type="wb")
	{
		$handle = @fopen($file,$type);
		$this->readCount++;
		return $handle;
	}

	#[写入信息]
	function _write($content,$file,$type="wb")
	{
		global $system_time;
		$content = stripslashes($content);
		$handle = $this->_open($file,$type);
		@fwrite($handle,$content);
		unset($content);
		$this->close($handle);
		#[设置文件创建的时间]
		$system_time = $system_time ? $system_time : time();
		@touch($file,$system_time);
		return true;
	}

	function close($handle)
	{
		return @fclose($handle);
	}
}
?>