<?php
$VERSION='v1.0 beta 2';
$RELEASE='20130709';

function show_msg($title, $error_msg = 'ok', $quit = TRUE) {
    show_header();
    global $step;
    $errormsg = $comment = '';
    if($error_msg) {
        if(!empty($error_msg)) {
            foreach ((array)$error_msg as $k => $v) {
                if(is_numeric($k)) {
                    $comment .= "<li><em class=\"red\">".$v."</em></li>";
                }
            }
        }
    }
    if($step > 0) {
        echo "<div class=\"desc\"><b>$title</b><ul>$comment</ul>";
    } else {
        echo "</div><div class=\"main\" ><b>$title</b><ul style=\"line-height: 200%; margin-left: 30px;\">$comment</ul>";
    }
    if($quit) {
        echo '<br /><span class="red">您必须解决以上问题，安装才可以继续</span><br /><br /><br />';
    }
    echo '<input class="b" type="button" onclick="history.back()" value="点击返回上一步" /><br /><br /><br /></div>';
    $quit && show_footer();
}

function show_dbinit() {
    show_header();
    echo '
	<form method="post" action="index.php">
    <input type="hidden" name="step" value="3">
    <div id="form_items_3" ><br /><div class="desc"><b>填写数据库信息</b></div>
    <table class="tb2">
	<script type="text/javascript">
	$(document).ready(function(){
			$.getScript("http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js",function(_result){   
					if (remote_ip_info.ret == "1"){
					$("#province").val(remote_ip_info.province);
					$("#city").val(remote_ip_info.city); 
					$("#county").val(remote_ip_info.district);  
					} 
				})
		})
	</script>
        <tr><th class="tbopt">&nbsp;数据库服务器:</th>
        <td><input class="i" type="text" name="dbinfo[dbhost]" value="localhost" size="35" class="txt"></td>
        <td>&nbsp;数据库服务器地址, 一般为 localhost</td>
        </tr>
        <tr><th class="tbopt">&nbsp;数据库名:</th>
        <td><input class="i" type="text" name="dbinfo[dbname]" value="QuoraCms" size="35" class="txt"></td>
        <td>&nbsp;</td>
        </tr>
        <tr><th class="tbopt">&nbsp;数据库用户名:</th>
        <td><input class="i" type="text" name="dbinfo[dbuser]" value="root" size="35" class="txt"></td>
        <td>&nbsp;</td>
        </tr>
        <tr><th class="tbopt">&nbsp;数据库密码:</th>
        <td><input class="i" type="text" name="dbinfo[dbpw]" value="" size="35" class="txt"></td>
        <td>&nbsp;</td>
        </tr>
        <tr><th class="tbopt">&nbsp;数据表前缀:</th>
        <td><input class="i" type="text" name="dbinfo[tablepre]" value="qcs_" size="35" class="txt"></td>
        <td>&nbsp;同一数据库运行多个QCS时，请修改前缀<input type="hidden" value="'.$_GET['sitename'].'" name="dbinfo[sitename]" /><input type="hidden" value="'.$_GET['siteurl'].'" name="dbinfo[siteurl]" /></td>
        </tr>
    </table>
    <div class="desc"><b>填写管理员信息</b></div>
    <table class="tb2">
        <tr><th class="tbopt">&nbsp;管理员账号:</th>
        <td><input class="i" type="text" name="admininfo[username]" value="admin" size="35" class="txt"></td>
        <td>&nbsp;</td>
        </tr>
        <tr><th class="tbopt">&nbsp;管理员密码:</th>
        <td><input class="i" type="password" name="admininfo[password]" value="" size="35" class="txt"></td>
        <td>&nbsp;管理员密码不能为空</td>
        </tr>
        <tr><th class="tbopt">&nbsp;重复密码:</th>
        <td><input class="i" type="password" name="admininfo[password2]" value="" size="35" class="txt"></td>
        <td>&nbsp;</td>
        </tr>
        <tr><th class="tbopt">&nbsp;管理员 Email:</th>
        <td><input class="i" type="text" name="admininfo[email]" value="admin@admin.com" size="35" class="txt"></td>
        <td><input type="hidden" name="admininfo[province]" id="province" /><input type="hidden" name="admininfo[city]" id="city" /><input type="hidden" name="admininfo[county]" id="county" /> &nbsp;</td>
        </tr>
    </table></div><table class="tb2">
    <tr><th class="tbopt">&nbsp;</th>
    <td><input class="b" type="submit" name="submitname" value="下一步" class="btn">
    </td>
    <td>&nbsp;</td>
    </tr>
    </table>
    </form>';
    show_footer();
}

function StrLenW2($str){
    return (strlen($str)+mb_strlen($str,'UTF8'))/2;
}

function show_setup() {
    global $default_appurl;
    show_header();
    echo '
	
	<form method="post" action="index.php">
    <input type="hidden" name="step" value="2">
    <div id="form_items_2" style="margin-left:10px;">
        <table class="tb2">
            <tr><th class="tbopt">&nbsp;站点名称:</th>
            <td><input class="i" type="text" name="siteinfo[sitename]" value="QuoraCms" size="35" class="txt"></td>
            <td>&nbsp;</td>
            </tr>
            <tr><th class="tbopt">&nbsp;站点 URL:</th>
            <td><input class="i" type="text" name="siteinfo[siteurl]" value="'.$default_appurl.'" size="35" class="txt"></td>
            <td>&nbsp;</td>
            </tr>
        </table>
    </div>
    <table class="tb2">
        <tr><th class="tbopt">&nbsp;</th>
        <td><input class="b" style=" margin-left:10px" type="submit" name="submitname" value="下一步" class="btn">
        </td>
        <td>&nbsp;</td>
        </tr>
    </table>
    </form>';
    show_footer();
}

function save_config_file($config, $file) {
	$success = false;
	$config = "<?php
    return array(
        'DB_TYPE'=>'mysql',
        'DB_HOST'=>'$config[dbhost]',
        'DB_NAME'=>'$config[dbname]',
        'DB_USER'=>'$config[dbuser]',
        'DB_PWD'=>'$config[dbpw]',
        'DB_PORT'=>3306,
        'DB_PREFIX'=>'$config[tablepre]',
        'APP_DEBUG'=>false,
		'DATA_CACHE_TIME'=>'3600',
		'TMPL_L_DELIM'=> '@#',
		'TMPL_R_DELIM'=> '#@',
		'LOG_RECORD' =>false,
		'URL_PATHINFO_DEPR' => '-',
	  	'URL_PATHINFO_MODEL' => 2,
		'URL_CASE_INSENSITIVE' => true,
		'PAGE_ROLLPAGE'=>8, 
		'PAGE_LISTROWS'=>20, 
		'TMPL_PARSE_STRING'=>array
			(
			 '__PUBLIC__' => '$config[siteurl]/Public',
			 '__SITE__' => '$config[siteurl]',
			)
    );
    ?>";
	if($fp = fopen($file, 'w')) {
		fwrite($fp, $config);
		fclose($fp);
		$success = true;
	}
	return $success;
}

function save_uc_config_file($config,$file) {
	$success = false;
	$config = '<?php
        $dbhost="'.$config[dbhost].'";
        $dbname="'.$config[dbname].'";
        $dbuser="'.$config[dbuser].'";
        $dbpw="'.$config[dbpw].'";
        $tablepre="'.$config[tablepre].'";
		 $pconnect = 0;
		$dbcharset = "utf-8";
		$cookiedomain =""; 
		$cookiepath ="/";
    ?>';
	if($fp = fopen($file, 'w')) {
		fwrite($fp, $config);
		fclose($fp);
		$success = true;
	}
	return $success;
}

function show_license() {
	global $step;
	$next = $step + 1;
    show_header();
    $license = '<div class="license"><h1>QuoraCms 中文版授权协议</h1>
	<p>感谢您选择 QuoraCms社会化问答产品。我们将继续努力打造顶尖的社会化问答解决方案。</p>
    <p>版权所有 &copy; 2012-2013，QCS官方为 QuoraCms 产品的开发商，依法独立拥有 QuoraCms产品著作权并保留所有权利。QCS官方网址为 http://www.quoracms.com。</p>
    <p>QuoraCms 著作权已在中华人民共和国国家版权局注册，著作权受到法律和国际公约保护。使用者：无论个人或组织、盈利与否、用途如何（包括以学习和研究为目的），均需仔细阅读本协议，在理解、同意、并遵守本协议的全部条款后，方可开始使用 QuoraCms软件。</p>
    <p>本授权协议适用且仅适用于QuoraCms所有版本，QCS官方拥有对本授权协议的最终解释权。</p>
<h3>I. 协议许可的权利</h3>
    <ol>
    <li>您可以在完全遵守本最终用户授权协议的基础上，将本软件应用于非商业用途，而不必支付软件版权授权费用。</li>
    <li>您可以在协议规定的约束和限制范围内修改 QuoraCms源代码或界面风格以适应您的网站要求。</li>
    <li>您拥有使用本软件构建的网站中全部会员资料、信息内容及相关信息的所有权，并独立承担与信息内容的相关法律义务。</li>
    <li>获得商业授权之后，您可以将本软件应用于商业用途。</li>
    </ol>
    <h3>II. 协议规定的约束和限制</h3>
    <ol>
    <li>未获商业授权之前，不得将本软件用于商业用途（包括但不限于企业网站、经营性网站、以营利为目或实现盈利的网站）。购买商业授权请登陆http://www.quoracms.com参考相关说明。</li>
    <li>不得对本软件或与之关联的商业授权进行出租、出售、抵押或发放子许可证。</li>
    <li>无论如何，即无论用途如何、是否经过修改或美化、修改程度如何，只要使用QuoraCms的整体或任何部分，未经书面许可，网站页面页脚处的QuoraCms名称和网站（http://www.quoracms.com）的链接都必须保留，而不能清除或修改。</li>
    <li>禁止在QuoraCms 的整体或任何部分基础上以发展任何派生版本、修改版本或第三方版本用于重新分发。</li>
    <li>如果您未能遵守本协议的条款，您的授权将被终止，所被许可的权利将被收回，并承担相应法律责任。</li>
    </ol>
    <h3>III. 有限担保和免责声明</h3>
    <ol>
    <li>本软件及所附带的文件是作为不提供任何明确的或隐含的赔偿或担保的形式提供的。</li>
    <li>用户出于自愿而使用本软件，您必须了解使用本软件的风险，在尚未购买产品技术服务之前，我们不承诺提供任何形式的技术支持，也不承担任何因使用本软件而产生的一切问题的相关责任。</li>
    <li>QCS官方不对使用本软件中的问答信息内容承担责任。</li>
    </ol>
    <p>有关QuoraCms 最终用户授权协议、商业授权与技术服务的详细内容，均由QuoraCms官方网站独家提供。QCS官方拥有在不事先通知的情况下，修改授权协议和服务价目表的权力，修改后的协议或价目表对自改变之日起的新授权用户生效。</p>
    <p>电子文本形式的授权协议如同双方书面签署的协议一样，具有完全的和等同的法律效力。您一旦开始安装QuoraCms，即被视为完全理解并接受本协议的各项条款，在享有上述条款授予的权力的同时，受到相关的约束和限制。协议许可范围以外的行为，将直接违反本授权协议并构成侵权，我们有权随时终止授权，责令停止损害，并保留追究相关责任的权力。</p></div>';
    echo '</div>
    <div class="main" >
    <div class="licenseblock">'.$license.'</div>
    <div class="btnbox marginbot">
        <form method="get" autocomplete="off" action="index.php">
        <input type="hidden" name="step" value="'.$next.'">
        <input class="b" type="submit" value="我同意" style="padding: 5px">&nbsp;
        <input class="b" type="button" value="我不同意" style="padding: 5px" onclick="javascript: window.close(); return false;">
        </form>
    </div>';
    show_footer();
}

function dirfile_check(&$dirfile_items) {
	foreach($dirfile_items as $key => $item) {
		$item_path = $item['path'];
		if($item['type'] == 'dir') {
			if(!dir_writeable(QCS_ROOT.$item_path)) {
				if(is_dir(QCS_ROOT.$item_path)) {
					$dirfile_items[$key]['status'] = 0;
					$dirfile_items[$key]['current'] = '+r';
				} else {
					$dirfile_items[$key]['status'] = -1;
					$dirfile_items[$key]['current'] = 'nodir';
				}
			} else {
				$dirfile_items[$key]['status'] = 1;
				$dirfile_items[$key]['current'] = '+r+w';
			}
		} else {
			if(file_exists(QCS_ROOT.$item_path)) {
				if(is_writable(QCS_ROOT.$item_path)) {
					$dirfile_items[$key]['status'] = 1;
					$dirfile_items[$key]['current'] = '+r+w';
				} else {
					$dirfile_items[$key]['status'] = 0;
					$dirfile_items[$key]['current'] = '+r';
				}
			} else {
				if(dir_writeable(dirname(QCS_ROOT.$item_path))) {
					$dirfile_items[$key]['status'] = 1;
					$dirfile_items[$key]['current'] = '+r+w';
				} else {
					$dirfile_items[$key]['status'] = -1;
					$dirfile_items[$key]['current'] = 'nofile';
				}
			}
		}
	}
}

function env_check(&$env_items) {
	foreach($env_items as $key => $item) {
		if($key == 'PHP 版本') {
			$env_items[$key]['current'] = PHP_VERSION;
		} elseif($key == '附件上传') {
			$env_items[$key]['current'] = @ini_get('file_uploads') ? ini_get('upload_max_filesize') : 'unknow';
		} elseif($key == 'GD 库') {
			$tmp = function_exists('gd_info') ? gd_info() : array();
			$env_items[$key]['current'] = empty($tmp['GD Version']) ? 'noext' : $tmp['GD Version'];
			unset($tmp);
		} elseif($key == '磁盘空间') {
			if(function_exists('disk_free_space')) {
				$env_items[$key]['current'] = floor(disk_free_space(QCS_ROOT) / (1024*1024)).'M';
			} else {
				$env_items[$key]['current'] = 'unknow';
			}
		}elseif($key=='zend支持')
			{
				$env_items[$key]['current']=extension_loaded('zend optimizer')?'支持':'不支持';
			} elseif(isset($item['c'])) {
			$env_items[$key]['current'] = constant($item['c']);
		}
		$env_items[$key]['status'] = 1;
		if($item['r'] != 'notset' && strcmp($env_items[$key]['current'], $item['r']) < 0) {
			$env_items[$key]['status'] = 0;
		}
	}
}

function show_env_result(&$env_items, &$dirfile_items) {
	$env_str = $file_str = $dir_str = '';
	$error_code = 0;

	foreach($env_items as $key => $item) {
		if($key == 'php' && strcmp($item['current'], $item['r']) < 0) {
			show_msg('php_version_too_low', $item['current']);
		}
		$status = 1;
		if($item['r'] != '不限制') {
			if(intval($item['current']) && intval($item['r'])) {
				if(intval($item['current']) < intval($item['r'])) {
					$status = 0;
					$error_code = 31;
				}
			} else {
				if(strcmp($item['current'], $item['r']) < 0) {
					$status = 0;
					$error_code = 31;
				}
			}
		}
        $env_str .= "<tr>\n";
        $env_str .= "<td>".$key."</td>\n";
        $env_str .= "<td class=\"padleft\">".$item['r']."</td>\n";
        $env_str .= "<td class=\"padleft\">".$item['b']."</td>\n";
        $env_str .= ($status ? "<td class=\"w pdleft1\">" : "<td class=\"nw pdleft1\">").$item['current']."</td>\n";
        $env_str .= "</tr>\n";
	}

	foreach($dirfile_items as $key => $item) {
		$tagname = $item['type'] == 'file' ? 'File' : 'Dir';
		$variable = $item['type'].'_str';
        $$variable .= "<tr>\n";
        $$variable .= "<td>$item[path]</td><td class=\"w pdleft1\">可写</td>\n";
        if($item['status'] == 1) {
            $$variable .= "<td class=\"w pdleft1\">可写</td>\n";
        } elseif($item['status'] == -1) {
            $error_code = 31;
            $$variable .= "<td class=\"nw pdleft1\">目录不存在</td>\n";
        } else {
            $error_code = 31;
            $$variable .= "<td class=\"nw pdleft1\">不可写</td>\n";
        }
        $$variable .= "</tr>\n";
	}
    show_header();

    echo "<h2 class=\"title\">开始安装</h2>\n";
    echo "<table class=\"tb\" style=\"margin:20px 0 20px 55px;\">\n";
    echo "<tr>\n";
    echo "\t<th>项目</th>\n";
    echo "\t<th class=\"padleft\">QuoraCms配置</th>\n";
    echo "\t<th class=\"padleft\">QuoraCms最佳</th>\n";
    echo "\t<th class=\"padleft\">当前服务器</th>\n";
    echo "</tr>\n";
    echo $env_str;
    echo "</table>\n";

    echo "<h2 class=\"title\">目录、文件权限检查</h2>\n";
    echo "<table class=\"tb\" style=\"margin:20px 0 20px 55px;width:90%;\">\n";
    echo "\t<tr>\n";
    echo "\t<th>目录文件</th>\n";
    echo "\t<th class=\"padleft\">所需状态</th>\n";
    echo "\t<th class=\"padleft\">当前状态</th>\n";
    echo "</tr>\n";
    echo $file_str;
    echo $dir_str;
    echo "</table>\n";

    echo "<h2 class=\"title\">所需PHP函数检查</h2>\n";
    echo "<table class=\"tb\" style=\"margin:20px 0 20px 55px;width:90%;\">\n";
    echo "\t<tr>\n";
    echo "\t<th>函数名称</th>\n";
    echo "\t<th class=\"padleft\">检查结果</th>\n";
    echo "</tr>\n";
    if (function_exists('mb_convert_encoding')) {
        echo '<tr><td>mb_convert_encoding()</td><td class="w pdleft1">可用</td></tr>';
    } else {
        $error_code=31;
        echo '<tr><td>mb_convert_encoding()</td><td class="nw pdleft1">不可用</td></tr>';
    }
    if (function_exists('file_put_contents')) {
        echo '<tr><td>file_put_contents()</td><td class="w pdleft1">可用</td></tr>';
    } else {
        $error_code=31;
        echo '<tr><td>file_put_contents()</td><td class="nw pdleft1">不可用</td></tr>';
    }
    if (function_exists('imagecreatetruecolor')) {
        echo '<tr><td>imagecreatetruecolor()</td><td class="w pdleft1">可用</td></tr>';
    } else {
        $error_code=31;
        echo '<tr><td>imagecreatetruecolor()</td><td class="nw pdleft1">不可用</td></tr>';
    }
	 if (function_exists('curl_init')) {
        echo '<tr><td>curl_init()</td><td class="w pdleft1">可用</td></tr>';
    } else {
        $error_code=31;
        echo '<tr><td>curl_init()</td><td class="nw pdleft1">不可用</td></tr>';
    }
    echo "</table>\n";

    show_next_step(2, $error_code);
    show_footer();
}

function show_env_result_update(&$env_items, &$dirfile_items) {
	$env_str = $file_str = $dir_str = '';
	$error_code = 0;

	foreach($dirfile_items as $key => $item) {
		$tagname = $item['type'] == 'file' ? 'File' : 'Dir';
		$variable = $item['type'].'_str';
        $$variable .= "<tr>\n";
        $$variable .= "<td>$item[path]</td><td class=\"w pdleft1\">可写</td>\n";
        if($item['status'] == 1) {
            $$variable .= "<td class=\"w pdleft1\">可写</td>\n";
        } elseif($item['status'] == -1) {
            $error_code = 31;
            $$variable .= "<td class=\"nw pdleft1\">目录不存在</td>\n";
        } else {
            $error_code = 31;
            $$variable .= "<td class=\"nw pdleft1\">不可写</td>\n";
        }
        $$variable .= "</tr>\n";
	}
    show_update_header();

    echo "<h2 class=\"title\">目录、文件权限检查</h2>\n";
    echo "<table class=\"tb\" style=\"margin:20px 0 20px 55px;width:90%;\">\n";
    echo "\t<tr>\n";
    echo "\t<th>目录文件</th>\n";
    echo "\t<th class=\"padleft\">所需状态</th>\n";
    echo "\t<th class=\"padleft\">当前状态</th>\n";
    echo "</tr>\n";
    echo $file_str;
    echo $dir_str;
    echo "</table>\n";

    echo "<h2 class=\"title\">所需PHP函数检查</h2>\n";
    echo "<table class=\"tb\" style=\"margin:20px 0 20px 55px;width:90%;\">\n";
    echo "\t<tr>\n";
    echo "\t<th>函数名称</th>\n";
    echo "\t<th class=\"padleft\">检查结果</th>\n";
    echo "</tr>\n";
    if (function_exists('mb_convert_encoding')) {
        echo '<tr><td>mb_convert_encoding()</td><td class="w pdleft1">可用</td></tr>';
    } else {
        $error_code=31;
        echo '<tr><td>mb_convert_encoding()</td><td class="nw pdleft1">不可用</td></tr>';
    }
    if (function_exists('file_put_contents')) {
        echo '<tr><td>file_put_contents()</td><td class="w pdleft1">可用</td></tr>';
    } else {
        $error_code=31;
        echo '<tr><td>file_put_contents()</td><td class="nw pdleft1">不可用</td></tr>';
    }
    if (function_exists('imagecreatetruecolor')) {
        echo '<tr><td>imagecreatetruecolor()</td><td class="w pdleft1">可用</td></tr>';
    } else {
        $error_code=31;
        echo '<tr><td>imagecreatetruecolor()</td><td class="nw pdleft1">不可用</td></tr>';
    }
	if (function_exists('curl_init')) {
        echo '<tr><td>curl_init()</td><td class="w pdleft1">可用</td></tr>';
    } else {
        $error_code=31;
        echo '<tr><td>curl_init()</td><td class="nw pdleft1">不可用</td></tr>';
    }
    echo "</table>\n";

    show_next_step_update(1, $error_code);
    show_footer();
}

function show_next_step($step, $error_code) {
	echo "<form action=\"index.php\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"step\" value=\"$step\" />";
	if(isset($GLOBALS['hidden'])) {
		echo $GLOBALS['hidden'];
	}
	if($error_code == 0) {
		$nextstep = "<input class=\"b\" type=\"button\" onclick=\"history.back();\" value=\"上一步\"><input class=\"b\" type=\"submit\" value=\"下一步\">\n";
	} else {
		$nextstep = "<input class=\"b\" type=\"button\" disabled=\"disabled\" value=\"请将以上红叉部分修正再试\">\n";
	}
	echo "<div class=\"btnbox marginbot\">".$nextstep."</div>\n";
	echo "</form>\n";
}

function dir_writeable($dir) {
	$writeable = 0;
	if(!is_dir($dir)) {
		@mkdir($dir, 0777);
	}
	if(is_dir($dir)) {
		if($fp = @fopen("$dir/test.txt", 'w')) {
			@fclose($fp);
			@unlink("$dir/test.txt");
			$writeable = 1;
		} else {
			$writeable = 0;
		}
	}
	return $writeable;
}

function show_header() {
	global $step,$VERSION;
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>QuoraCms 安装向导</title>
    <link rel="stylesheet" href="images/style.css" type="text/css" media="all" />
    <script type="text/javascript" src="../Public/jquery.js"></script>
    <script type="text/javascript">
    function showmessage(message) {
		$("#notice").html($("#notice").html()+message+"<br />");
	}
    </script>
    </head>
    <div class="container">
    <div class="header">
    <h1 class="install">QuoraCms 安装向导</h1>
    <span>QuoraCms '.$VERSION.' </span>';

	$step > 0 && show_step($step);
}

function show_footer($quit = true) {
	echo '<div class="footer">&copy;2012 - 2013 <a href="http://www.quoracms.com/">QuoraCms.com</a></div></div></div></body></html>';
	$quit && exit();
}

function show_step($step) {
	global $method;
	$laststep = 4;

    if ($method=='env_check') {
        $title = '开始安装';
        $comment = '环境以及文件目录权限检查';
    } else if ($method=='app_reg') {
        $title = '设置站点信息';
        $comment = '设置站点名称以及URL地址';
    } else if ($method=='db_init') {
        $title = '安装数据库';
        $comment = '正在执行数据库安装';
    }

	$stepclass = array();
	for($i = 1; $i <= $laststep; $i++) {
		$stepclass[$i] = $i == $step ? 'current' : ($i < $step ? '' : 'unactivated');
	}
	$stepclass[$laststep] .= ' last';

	echo '<div class="setup step'.$step.'">
            <h2>'.$title.'</h2>
            <p>'.$comment.'</p>
        </div>
    </div>
    <div class="main">';
}

function show_install() {
    ?>
    <script type="text/javascript">
    function showmessage(message) {
        $('#notice').html($('#notice').html() + message + '<br />');
        $('#notice').scrollTop(10000000);
    }
    function initinput() {/*window.location='index.php?method=ext_info';*/}
    </script>
    <div class="main"><div class="btnbox" style="margin-bottom:10px"><div id="notice"></div></div><div class="btnbox marginbot"><input type="button" name="submit" value="正在安装..." disabled style="height: 25" id="laststep" class="b" onclick="initinput()"></div>
    <?php
}

function runquery($sql) {
	global $tablepre, $db,$default_appurl;
	if(!isset($sql) || empty($sql)) return;
	$sql = str_replace("\r", "\n", str_replace(' qcs_', ' '.$tablepre, $sql));
	$sql = str_replace("\r", "\n", str_replace(' `qcs_', ' `'.$tablepre, $sql));
	$ret = array();
	$num = 0;
	foreach(explode(";\n", trim($sql)) as $query) {
		$ret[$num] = '';
		$queries = explode("\n", trim($query));
		foreach($queries as $query) {
			$ret[$num] .= (isset($query[0]) && $query[0] == '#') || (isset($query[1]) && isset($query[1]) && $query[0].$query[1] == '--') ? '' : $query;
		}
		$num++;
	}
	unset($sql);
	foreach($ret as $query) {
		$query = trim($query);
      //  $query=str_replace('__PUBLIC__',$default_appurl.'/Public',$query);
		if($query) {
			if(substr($query, 0, 12) == 'CREATE TABLE') {
				$name = preg_replace("/CREATE TABLE IF NOT EXISTS `([a-z0-9_]+)` .*/is", "\\1", $query);
				showjsmessage('建立数据表 '.$name.' ... 成功');
				$db->query(createtable($query));
			} else {
				$db->query($query);
			}
		}
	}
}

function createtable($sql) {
	$type = strtoupper(preg_replace("/^\s*CREATE TABLE\s+.+\s+\(.+?\).*(ENGINE|TYPE)\s*=\s*([a-z]+?).*$/isU", "\\2", $sql));
	$type = in_array($type, array('MYISAM', 'HEAP', 'MEMORY')) ? $type : 'MYISAM';
	return preg_replace("/^\s*(CREATE TABLE\s+.+\s+\(.+?\)).*$/isU", "\\1", $sql).
	(mysql_get_server_info() > '4.1' ? " ENGINE=$type DEFAULT CHARSET=utf8" : " TYPE=$type");
}

function showjsmessage($message) {
	echo '<script type="text/javascript">showmessage(\''.addslashes($message).' \');</script>'."\r\n";
	flush();
	ob_flush();
}

function deleteDir($dirName){
    if(!is_dir($dirName)){
        @unlink($dirName);
        return false;
    }
    $handle = @opendir($dirName);
    while(($file = @readdir($handle)) !== false){
        if($file != '.' && $file != '..'){
            $dir = $dirName . '/' . $file;
            is_dir($dir) ? deleteDir($dir) : @unlink($dir);
        }
    }
    closedir($handle);
    return rmdir($dirName);
}

function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
    global $webaddr;
    $auth_key=md5($webaddr);
	$ckey_length = 4;
	$key = md5($key ? $key : $auth_key);
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);
	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);
	$result = '';
	$box = range(0, 255);
	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}
	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}
	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}
	if($operation == 'DECODE') {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		return $keyc.str_replace('=', '', base64_encode($result));
	}
}
function generate_key() {
	$random = random(32);
	$info = md5($_SERVER['SERVER_SOFTWARE'].$_SERVER['SERVER_NAME'].$_SERVER['SERVER_ADDR'].$_SERVER['SERVER_PORT'].$_SERVER['HTTP_USER_AGENT'].time().rand(0,9999));
	$return = '';
	for($i=0; $i<64; $i++) {
		$p = intval($i/2);
		$return[$i] = $i % 2 ? $random[$p] : $info[$p];
	}
	return implode('', $return);
}
function random($length) {
	$hash = '';
	$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
	$max = strlen($chars) - 1;
	PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
	for($i = 0; $i < $length; $i++) {
		$hash .= $chars[mt_rand(0, $max)];
	}
	return $hash;
}
function curl_post($url,$vars) {
    if (function_exists('curl_init')) {
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$vars);
        $data=curl_exec($ch);
        curl_close($ch);
        if ($data) return $data;
        else return 'error';
    }
}
?>