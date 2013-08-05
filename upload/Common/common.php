<?php
// +----------------------------------------------------------------------+
// | QuoraCms          			                                          |
// +----------------------------------------------------------------------+
// | Copyright © 2012-2013 QuoraCms WorkGroup All Rights Reserved.        |
// +----------------------------------------------------------------------+
// | 注意：当前您使用的是未授权版本，仅可用于“个人非盈利的网站”并须保留网页底部的       |
// | 的“powered by quoracms”字样，其他网站需购买 QuoraCms使用授权, 例如：政府单   |
// | 位、教育机构、协会团体、企业、以赢利为目的的站点等，购买商业授权请联系官方QQ       |
// | Caution: Currently you are using an unlicensed version, it's free    |
// | for the "personal website" but must retain bottom of the page        |
// | "powered by QuoraCms" words, other sites need to purchase QuoraCms   |
// | license, for example: government agencies, educational institutions, |
// | associations organizations, enterprises and for-profit sites, etc.,  |
// |to buy commercial license, please contact the official QQ.			  |
// +----------------------------------------------------------------------+
// | Author:<QQ:452605524(Quoracms Official)>                       	  |
// | Email :<452605524@qq.com >                               			  |
// | Official Website :<http://www.quoracms.com >                         |
// +----------------------------------------------------------------------+
///////remove xss attack function//////
function remove_xss($val) {
   // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
   // this prevents some character re-spacing such as <java\0script>
   // note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
   $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);
   // straight replacements, the user should never need these since they're normal characters
   // this prevents like <IMG SRC=@avascript:alert('XSS')>
   $search = 'abcdefghijklmnopqrstuvwxyz';
   $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
   $search .= '1234567890!@#$%^&*()';
   $search .= '~`";:?+/={}[]-_|\'\\';
   for ($i = 0; $i < strlen($search); $i++) {
      // ;? matches the ;, which is optional
      // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars
      // @ @ search for the hex values
      $val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;
      // @ @ 0{0,7} matches '0' zero to seven times
      $val = preg_replace('/(�{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
   }
   // now the only remaining whitespace attacks are \t, \n, and \r
   $ra1 = array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
   $ra2 = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
   $ra = array_merge($ra1, $ra2);
   $found = true; // keep replacing as long as the previous round replaced something
   while ($found == true) {
      $val_before = $val;
      for ($i = 0; $i < sizeof($ra); $i++) {
         $pattern = '/';
         for ($j = 0; $j < strlen($ra[$i]); $j++) {
            if ($j > 0) {
               $pattern .= '(';
               $pattern .= '(&#[xX]0{0,8}([9ab]);)';
               $pattern .= '|';
               $pattern .= '|(�{0,8}([9|10|13]);)';
               $pattern .= ')*';
            }
            $pattern .= $ra[$i][$j];
         }
         $pattern .= '/i';
         $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag
         $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
         if ($val_before == $val) {
            // no replacements were made, so exit the loop
            $found = false;
         }
      }
   }
   return $val;
}
////////////////make time more friendly//////
function time_mode($timestamp, $time_limit = 604800, $out_format = 'Y-m-d H:i', $formats = null, $time_now = null)
{
	if ($formats == null)
	{
		$formats = array('YEAR' => '%s 年前', 'MONTH' => '%s 月前', 'DAY' => '%s 天前', 'HOUR' => '%s 小时前', 'MINUTE' => '%s 分钟前', 'SECOND' => '%s 秒前');
	}
	$time_now = $time_now == null ? time() : $time_now;
	$seconds = $time_now - $timestamp;
	if ($seconds == 0)
	{
		$seconds = 1;
	}
	if ($time_limit != null && $seconds > $time_limit)
	{
		return date($out_format, $timestamp);
	}
	$minutes = floor($seconds / 60);
	$hours = floor($minutes / 60);
	$days = floor($hours / 24);
	$months = floor($days / 30);
	$years = floor($months / 12);
	if ($years > 0)
	{
		$diffFormat = 'YEAR';
	}
	else
	{
		if ($months > 0)
		{
			$diffFormat = 'MONTH';
		}
		else
		{
			if ($days > 0)
			{
				$diffFormat = 'DAY';
			}
			else
			{
				if ($hours > 0)
				{
					$diffFormat = 'HOUR';
				}
				else
				{
					$diffFormat = ($minutes > 0) ? 'MINUTE' : 'SECOND';
				}
			}
		}
	}
	$dateDiff = null;
	switch ($diffFormat)
	{
		case 'YEAR' :
			$dateDiff = sprintf($formats[$diffFormat], $years);
			break;
		case 'MONTH' :
			$dateDiff = sprintf($formats[$diffFormat], $months);
			break;
		case 'DAY' :
			$dateDiff = sprintf($formats[$diffFormat], $days);
			break;
		case 'HOUR' :
			$dateDiff = sprintf($formats[$diffFormat], $hours);
			break;
		case 'MINUTE' :
			$dateDiff = sprintf($formats[$diffFormat], $minutes);
			break;
		case 'SECOND' :
			$dateDiff = sprintf($formats[$diffFormat], $seconds);
			break;
	}
	return $dateDiff;
}

function print_arr($arr)
	{
		echo '<pre>';
		print_r($arr);
		echo '</pre>';	
	}
function getsubstr($string, $start = 0,$sublen,$append=true) {
    $pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
    preg_match_all($pa, $string, $t_string);

    if(count($t_string[0]) - $start > $sublen && $append==true) {
        return join('', array_slice($t_string[0], $start, $sublen))."...";
    } else {
        return join('', array_slice($t_string[0], $start, $sublen));
    }
}
/*************decode and encode for php cookie function**************/
function strcode($string, $auth_key, $action='ENCODE') {
    $key = substr(md5($_SERVER["HTTP_USER_AGENT"] . $auth_key), 8, 18);
    $string = $action == 'ENCODE' ? $string : base64_decode($string);
    $len = strlen($key);
    $code = '';
    for ($i = 0; $i < strlen($string); $i++) {
        $k = $i % $len;
        $code .= $string[$i] ^ $key[$k];
    }
    $code = $action == 'DECODE' ? $code : base64_encode($code);
    return $code;
}
function get_keywords($title)
    {
		require('./Common/scws/pscws4.class.php');
        $pscws = new PSCWS4();
		$pscws->set_dict('./Common/scws/scws/dict.utf8.xdb');
		$pscws->set_rule('./Common/scws/scws/rules.utf8.ini');
		$pscws->set_ignore(true);
		$pscws->send_text($title);
		$words = $pscws->get_tops(5);
		$tags = array();
		foreach ($words as $val) {
		    $tags[] = $val['word'];
		}
		$pscws->close();
		return $tags;
}	
function get_avatar($uid,$size)
	{
        $u = sprintf("%09d", $uid);
        $dir1 = substr($u, 0, 3);
        $dir2 = substr($u, 3, 2);
        $dir3 = substr($u, 5, 2);
		if(file_exists('public/avatar/avatar_dir/'.$dir1.'/'.$dir2.'/'.$dir3.'/'.$uid.'_'.$size.'.jpg'))
			{
        		return '/avatar/avatar_dir/'.$dir1.'/'.$dir2.'/'.$dir3.'/'.$uid.'_'.$size.'.jpg';
			}else{
					return '/avatar/default_'.$size.'.jpg';
				 }
	}
 function q($table,$fields,$id,$str){
        $aa=M($table);
        if(empty($str)){
            $expression='getByid';
        }else{
            $expression='getBy'.$str;
        }
        $thisaa=$aa->field($fields)->$expression($id);
        $bb=explode(',',$fields);
        if(count($bb)<=1){
            return $thisaa[$fields];
        }else{
            return $thisaa;
        }        
    }		
function deldir($dir) {
	  $dh=opendir($dir);
	  while ($file=readdir($dh)) {
		if($file!="." && $file!="..") {
		  $fullpath=$dir."/".$file;
		  if(!is_dir($fullpath)) {
			  unlink($fullpath);
		  } else {
			  deldir($fullpath);
		  }
		}
	  }
	  closedir($dh);
	  if(rmdir($dir)) {
		return true;
	  } else {
		return false;
	  }
	}
function string_encode($str)
	{
	 $data = array_filter(explode(" ",$str));
	 $data = array_flip(array_flip($data));
	 foreach ($data as $ss) {
	  if (strlen($ss)>1 ) 
	   $data_code .= str_replace("%","",urlencode($ss)) . " ";
	 }
	 $data_code = trim($data_code);
	 return $data_code;
	}

function str_len($str) 
{ 
	$ccLen=0; 
	$ascLen=strlen($str); 
	$ind=0; 
	$hasCC=ereg("[xA1-xFE]",$str);  
	$hasAsc=ereg("[x01-xA0]",$str);  
	if($hasCC && !$hasAsc)  
	return strlen($str)/2; 
	if(!$hasCC && $hasAsc)
	return strlen($str); 
	for($ind=0;$ind<$ascLen;$ind++) 
	{ 
	if(ord(substr($str,$ind,1))>0xa0) 
	{ 
	$ccLen++; 
	$ind++; 
	} 
	else 
	{ 
	$ccLen++; 
	} 
	} 
	return $ccLen; 
} 	
function getpinyin($str,$charset="utf-8",$ishead = 0) {
    $restr = '';
    $str = trim($str);
    if($charset=="utf-8"){
        $str=iconv("utf-8","gb2312",$str);
    }
    $slen = strlen($str);
    $pinyins=array();
    if ($slen < 2) {
        return $str;
    }
    $fp = fopen('./Common/pinyin.dat', 'r');
    while (!feof($fp)) {
        $line = trim(fgets($fp));
        $pinyins[$line[0] . $line[1]] = substr($line, 3, strlen($line) - 3);
    }
    fclose($fp);
    for ($i = 0; $i < $slen; $i++) {
        if (ord($str[$i]) > 0x80) {
            $c = $str[$i] . $str[$i + 1];
            $i++;
            if (isset($pinyins[$c])) {
                if ($ishead == 0) {
                    $restr .= $pinyins[$c];
                } else {
                    $restr .= $pinyins[$c][0];
                }
            } else {
                $restr .= "";
            }	
        } else if (preg_match("/[a-z0-9]/i", $str[$i])) {
            $restr .= $str[$i];
        } else {
            $restr .= "";
        }
    }
    return ltrim($restr);
}
function multi_array_sort($multi_array,$sort1_key,$sort1=SORT_DESC,$sort2_key,$sort2=SORT_ASC){ 
	if(is_array($multi_array)){ 
		foreach ($multi_array as $row_array){ 
			if(is_array($row_array)){ 
				$key1_array[] = $row_array[$sort1_key];
				$key2_array[] = $row_array[$sort2_key]; 
			}else{ 
				return false; 
			} 
		} 
	}else{ 
		return false; 
	} 
	array_multisort($key1_array,$sort1,$key2_array,$sort2,$multi_array); 
	return $multi_array; 
}
function pwd_encode($pwd)
	{
		return md5(strrev(md5($pwd)).base64_encode($pwd));	
	}
?>	