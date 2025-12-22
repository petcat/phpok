<?php
class GD
{
	var $pic;
	var $isgd;
	var $position;
	var $t_width;
	var $t_height;
	var $m_width;
	var $m_height;
	var $thumbtype = 1;

	function GD($isgd=0,$pic="",$position=5,$t_width=160,$t_height=120,$m_width="",$m_height="",$thumbtype=1)
	{
		$this->isgd = intval($isgd) ? 1 : 0;
		$this->pic = $pic;
		$this->position = $position ? $position : 5;
		$this->t_width = intval($t_width) ? intval($t_width) : 160;
		$this->t_height = intval($t_height) ? intval($t_height) : 160;
		$this->m_width = intval($m_width) ? intval($m_width) : 0;
		$this->m_height = intval($m_height) ? intval($m_height) : 0;
		$this->thumbtype = intval($thumbtype) ? 1 : 0;
		unset($isgd,$pic,$position,$t_width,$t_height,$m_width,$m_height,$thumbtype);
	}

	function info($photo)
	{
		#需要包含整个文件路径
		if(!file_exists($photo)) return false;
		$infos = getimagesize($photo);
		$info["width"] = $infos[0];
		$info["height"] = $infos[1];
		$info["type"] = $infos[2];
		$info["name"] = substr(basename($photo),0,strrpos(basename($photo),"."));
		unset($infos);
		return $info;
	}

	function thumb($photo,$width="",$height="")
	{
		$width = $width ? $width : $this->t_width;
		$height = $height ? $height : $this->t_height;
		$info = $this->info($photo);
		#[计算是否支持水印，缩略图功能]
		if(!function_exists("imageline"))
		{
			return false;
		}
		#[设置缩略图文件的新名称]
		$new_name = "thumb_".basename($photo);
		$img = $this->___get_img($photo,$info);
		if(empty($img))
		{
			return false;
		}
		if($this->thumbtype == 1)
		{
			$rate_width = $width / $info["width"];
			$rate_height = $height / $info["height"];
			$qg_array = $this->___cutimg($width,$height,$info["width"],$info["height"]);
			$tempx = $qg_array["tempx"];
			$tempy = $qg_array["tempy"];
			$srcx = $qg_array["srcx"];
			$srcy = $qg_array["srcy"];
			$new_width  = ($rate_width  > 1) ? $info["width"]  : $width;
			$new_height = ($rate_height > 1) ? $info["height"] : $height;
		}
		else
		{
			$new_width = $width > $info["width"] ? $info["width"] : $width;
			$new_height = $height > $info["height"] ? $info["height"] : $height;
			if(($info["width"] * $width) > ($info["height"] * $height))
			{
				$new_height = round(($info["height"] * $width) / $info["width"]);
			}
			else
			{
				$new_width = round(($info["width"] * $height) / $info["height"]);
			}
			$tempx = $info["width"];
			$tempy = $info["height"];
			$srcx = 0;
			$srcy = 0;
		}
		if(function_exists("imagecopyresampled"))
		{
			$temp_image = imagecreatetruecolor($new_width, $new_height);
			imagecopyresampled($temp_image, $img, 0, 0, $srcx, $srcy, $new_width, $new_height, $tempx, $tempy);
		}
		else
		{
			$temp_image = imagecreate($new_width, $new_height);
			imagecopyresized($temp_image, $img, 0, 0, $srcx, $srcy, $new_width, $new_height, $tempx, $tempy);
		}
		#[写入新图片地址中]
		##[获取路径]
		$get_path = str_replace(basename($photo),"",$photo);
		if(file_exists($get_path.$new_name))
		{
			unlink($get_path.$new_name);
		}
		$return = $this->___create_image($temp_image,$get_path.$new_name,$info["type"]);
		imagedestroy($img);
		imagedestroy($temp_image);
		#[注销变量信息]
		unset($new_width,$new_height,$tempx,$tempy,$srcx,$srcy,$width,$height,$photo);
		unset($info,$get_path,$photo);
		if($return)
		{
			return basename($return);
		}
	}

	function ___create_image($temp_image,$newfile,$info_type)
	{
		if($info_type == 1)
		{
			imagegif($temp_image,$newfile);
		}
		elseif($info_type == 2)
		{
			imagejpeg($temp_image,$newfile);
		}
		elseif($info_type == 3)
		{
			imagepng($temp_image,$newfile);
		}
		else
		{
			#[如果不存在这些条件，那将文件改为jpg的缩略图]
			$newfile = $newfile.".jpg";
			if(file_exists($newfile))
			{
				unlink($newfile);
			}
			imagejpeg($temp_image,$newfile);
		}
		unset($temp_image,$info_type);
		return $newfile;
	}

	function ___get_img($pic,$info)
	{
		if($info["type"] == 1)
		{
			$img = imagecreatefromgif($pic);
		}
		elseif($info["type"] == 2)
		{
			$img = imagecreatefromjpeg($pic);
		}
		elseif($info["type"] == 3)
		{
			$img = imagecreatefrompng($pic);
		} else {
			$img = "";
		}
		unset($pic,$info);
		return $img;
	}

	function mark($photo,$width="",$height="")
	{
		if(!$this->pic)
		{
			return basename($photo);
		}
		$width = $width ? $width : $this->m_width;
		$height = $height ? $height : $this->m_height;
		#[计算是否支持水印，缩略图功能]
		if(!function_exists("imageline"))
		{
			return false;
		}
		$info = $this->info($photo);
		#[设置水印图片的新名称]
		$new_name = "mark_".basename($photo);
		$img = $this->___get_img($photo,$info);
		if(empty($img))
		{
			return false;
		}
		$width = $width > $info["width"] ? $info["width"] : $width;
		$height = $height > $info["height"] ? $info["height"] : $height;
		if(($info["width"] * $width) > ($info["height"] * $height))
		{
			$height = round(($info["height"] * $width) / $info["width"]);
		}
		else
		{
			$width = round(($info["width"] * $height) / $info["height"]);
		}
		#[根据新图像的宽高来获取新文件要放置的地方
		if (function_exists("imagecreatetruecolor"))
		{
			$new_img = imagecreatetruecolor($width, $height);
			imagecopyresampled($new_img, $img, 0, 0, 0, 0, $width, $height,$info["width"], $info["height"]);
		}
		else
		{
			$new_img = imagecreate($width, $height);
			imagecopyresized($new_img, $img, 0, 0, 0, 0, $width, $height, $info["width"], $info["height"]);
		}
		if($this->pic && file_exists($this->pic))
		{
			$xy = $this->___get_position($width,$height);#[含有图片时使用]
			$water_info = $this->info($this->pic);
			#[获取水印图片的其他信息]
			$my_water = $this->___get_img($this->pic,$water_info);
			imagecopymerge($new_img,$my_water,$xy["x"],$xy["y"],0,0,$water_info["width"],$water_info["height"],65);
		}
		#[写入新图片地址中]
		##[获取路径]
		$get_path = str_replace(basename($photo),"",$photo);
		if(file_exists($get_path.$new_name))
		{
			unlink($get_path.$new_name);
		}
		$return = $this->___create_image($new_img,$get_path.$new_name,$info["type"]);
		imagedestroy($img);
		imagedestroy($new_img);
		if($return)
		{
			return basename($return);
		}
		return false;
	}

	function ___get_position($width,$height,$tfont=true)
	{
		$info = $this->info($this->pic);
		$img = $this->___get_img($this->pic,$info);
		$water_width = imagesx($img);
		$water_height = imagesy($img);
		#[确定位置]
		switch ($this->position)
		{
			case 1:
				$x = 0;
				$y = 0;
			break;
			case 2:
				$x = $width - $water_width;
				$y = 0;
			break;
			case 3:
				$x = ($width/2) - ($water_width/2);
				$y = ($height / 2) - ($water_height / 2);
			break;
			case 4:
				$x = 0;
				$y = $height - $water_height;
			break;
			case 5:
				$x = $width - $water_width;
				$y = $height - $water_height;
			break;
			default:
				$x = $width - $water_width;
				$y = $height - $water_height;
			break;
		}
		return array("x"=>$x,"y"=>$y);
	}

	function ___cutimg($width,$height,$info_width,$info_height)
	{
		$info["width"] = $info_width;
		$info["height"] = $info_height;
		$rate_width = $width / $info["width"];
		$rate_height = $height / $info["height"];
		if($info["width"] >= $width && $info["height"] > $height)
		{
			if($info["width"] > $info["height"])
			{
				$tempx = $width / $rate_height;
				$tempy = $info["height"];
				$srcx = ($info["width"] - $tempx) / 2;
				$srcy = 0;
			}
			else
			{
				$tempx = $info["width"];
				$tempy = $height / $rate_width;
				$srcx = 0;
				$srcy = ($info["height"] - $tempy) / 2;
			}
		}
		else
		{
			if($info["width"] > $info["height"])
			{
				$tempx = $width;
				$tempy = $info["height"];
				$srcx = ($info["width"] - $tempx) / 2;
				$srcy = 0;
			}
			else
			{
				$tempx = $info["width"];
				$tempy = $height;
				$srcx = 0;
				$srcy = ($info["height"] - $tempy) / 2;
			}
		}
		$array["tempx"] = $tempx;
		$array["tempy"] = $tempy;
		$array["srcx"] = $srcx;
		$array["srcy"] = $srcy;
		return $array;
	}
}
?>