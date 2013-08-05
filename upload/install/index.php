<?php
date_default_timezone_set("PRC");
header('Content-Type: text/html; charset=utf-8');
define('QCS_ROOT', dirname(__FILE__).'/../');
@set_time_limit(1000);
@set_magic_quotes_runtime(0);
error_reporting(0);
require('include/install.func.php');
require('include/db_mysql.class.php');
$env_items = array(
	'操作系统' => array('c' => 'PHP_OS', 'r' => '不限制', 'b' => '类Unix'),
	'PHP 版本' => array('c' => 'PHP_VERSION', 'r' => '4.3', 'b' => '5.0'),
	'附件上传' => array('r' => '不限制', 'b' => '2M'),
	'GD 库' => array('r' => '1.0', 'b' => '2.0'),
	'磁盘空间' => array('r' => '50M', 'b' => '不限制'),
);
$dirfile_items = array(
	'home' => array('type' => 'dir', 'path' => './'),
	'global_config' => array('type' => 'file', 'path' => './Conf/config.php'),
	'uc_config' => array('type' => 'file', 'path' => './Conf/uc_config.php'),
	'global_config_inc' => array('type' => 'file', 'path' => './Conf/config.inc.php'),
	'uc_config_inc' => array('type' => 'file', 'path' => './Conf/uc_config.inc.php'),
	//'home_runtime' => array('type' => 'dir', 'path' => './Runtime'),
	//'home_runtime_cache' => array('type' => 'dir', 'path' => './Runtime/Cache'),
    //'home_runtime_data' => array('type' => 'dir', 'path' => './Runtime/Data'),
   // 'home_runtime_logs' => array('type' => 'dir', 'path' => './Runtime/Logs'),
   // 'home_runtime_temp' => array('type' => 'dir', 'path' => './Runtime/Temp'),
	'public' => array('type' => 'dir', 'path' => './Public'),
    'public_backup' => array('type' => 'dir', 'path' => './Public/avatar/avatar_dir'),
    'apps' => array('type' => 'dir', 'path' => './Public/ueditor/php/upload'),
	'upload' => array('type' => 'dir', 'path' => './Public/upload'),
	'thumbs' => array('type' => 'dir', 'path' => './Public/thumbs'),
	'ucenter' => array('type' => 'dir', 'path' => './uc_client/data/cache')
);

$lockfile = QCS_ROOT.'./Public/install.lock';
$allow_method = array('show_license', 'env_check', 'app_reg', 'db_init', 'ext_info');

$step = intval($_REQUEST['step']) ? intval($_REQUEST['step']) : 0;
$method = $_REQUEST['method'];

if(empty($method) || !in_array($method, $allow_method)) {
	$method = isset($allow_method[$step]) ? $allow_method[$step] : '';
}

if(empty($method)) {
	show_msg('未定义方法', $method);
}

if(file_exists($lockfile) && $method != 'ext_info') {
	show_msg('安装锁定，已经安装过了，如果您确定要重新安装，请到服务器上删除<br /> '.str_replace(QCS_ROOT, '', $lockfile), '');
}

$PHP_SELF = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
$etserver = 'http://'.preg_replace("/\:\d+/", '', $_SERVER['HTTP_HOST']).($_SERVER['SERVER_PORT'] && $_SERVER['SERVER_PORT'] != 80 ? ':'.$_SERVER['SERVER_PORT'] : '');
$default_ucapi = $etserver.'/ucenter';
$default_appurl = $etserver.substr($PHP_SELF, 0, strpos($PHP_SELF, 'install/') - 1);

if($method == 'show_license') {
	show_license();
} elseif($method == 'env_check') {
	env_check($env_items);
	dirfile_check($dirfile_items);
	show_env_result($env_items, $dirfile_items);
} elseif($method == 'app_reg') {
    if ($_POST['submitname']) {
        $siteinfo=$_POST['siteinfo'];
        $app_name = $siteinfo['sitename'] ? $siteinfo['sitename'] : 'QuoraCms';
        $app_url = $siteinfo['siteurl'] ? $siteinfo['siteurl'] : $default_appurl;
		$step = $step + 1;
		header("Location: index.php?step=$step&sitename=$app_name&siteurl=$app_url");
		exit;
    } else {
        show_setup();
    }
} else if ($method == 'db_init') {
    if ($_POST['submitname']) {
        $step = $step + 1;
        $dbinfo=$_POST['dbinfo'];
        $admininfo=$_POST['admininfo'];

		if(empty($dbinfo['dbhost']) || empty($dbinfo['dbname']) || empty($dbinfo['dbuser'])) {
			show_msg('数据库信息没有填写完成', '');
		} else {
			if(!$link = @mysql_connect($dbinfo['dbhost'],$dbinfo['dbuser'],$dbinfo['dbpw'])) {
				$errno = @mysql_errno($link);
				$error = @mysql_error($link);
				if($errno == 1045) {
					show_msg('无法连接数据库，请检查数据库用户名或者密码是否正确', $error);
				} elseif($errno == 2003) {
					show_msg('无法连接数据库，请检查数据库是否启动，数据库服务器地址是否正确', $error);
				} else {
					show_msg('数据库连接错误', $error);
				}
			}
			if(mysql_get_server_info() > '4.1') {
				mysql_query("CREATE DATABASE IF NOT EXISTS `".$dbinfo['dbname']."` DEFAULT CHARACTER SET utf8", $link);
			} else {
				mysql_query("CREATE DATABASE IF NOT EXISTS `".$dbinfo['dbname']."`", $link);
			}
			if(mysql_errno()) {
				show_msg('无法创建新的数据库，请检查数据库名称填写是否正确', mysql_error());
			}
			mysql_close($link);
		}

        if(strpos($dbinfo['tablepre'], '.') !== false) {
			show_msg('数据表前缀为空，或者格式错误，请检查', $tablepre);
		}

        if($admininfo['username'] && $admininfo['password'] && $admininfo['email'] && ($admininfo['password']==$admininfo['password2'])) {
			if(StrLenW2($admininfo['username'])>12 || StrLenW2($admininfo['username'])<3 || !$admininfo['username']) {
				show_msg('非法用户名，用户名长度不应当超过 12 个英文字符，一般是中文，字母或者数字', $admininfo['username']);
			} elseif(!strstr($admininfo['email'], '@') || $admininfo['email'] != stripslashes($admininfo['email']) || $admininfo['email'] != htmlspecialchars($admininfo['email'])) {
				show_msg('Email 地址错误，此邮件地址已经被使用或者格式无效，请更换为其他地址',$admininfo['email']);
			}
		} else {
			show_msg('管理员信息不完整，请检查管理员账号，密码，邮箱','');
		}

        save_config_file($dbinfo,QCS_ROOT.'./Conf/config.inc.php');
		save_uc_config_file($dbinfo,QCS_ROOT.'./Conf/uc_config.inc.php');
		
		touch($lockfile);

        $db = new dbstuff;
		$db->connect($dbinfo['dbhost'],$dbinfo['dbuser'],$dbinfo['dbpw'],$dbinfo['dbname'],0,true);
        @mysql_query("set names utf8");
        $tablepre=$dbinfo['tablepre'];
        $sql = file_get_contents(QCS_ROOT.'./install/include/data.sql');
		$sql = str_replace("\r\n", "\n", $sql);

        show_header();
	    show_install();
        runquery($sql);
		$auth_code=generate_key();
		$invitecode=uniqid() . rand(1000, 9999);
		$regtime=time();
		$pwd=md5(strrev(md5($admininfo['password'])).base64_encode($admininfo['password']));
        $db->query("INSERT INTO {$tablepre}user (name,pwd,province,city,county,email,invitecode,invitecount,regtime) VALUES ('$admininfo[username]', '$pwd','$admininfo[province]','$admininfo[city]','$admininfo[county]','$admininfo[email]','$invitecode',5,'$regtime');");
		$db->query("UPDATE {$tablepre}setting SET value = '{$auth_code}' WHERE name = 'auth_key'");
		$db->query("UPDATE {$tablepre}setting SET value = '$dbinfo[sitename]' WHERE name = 'site_name'");
        curl_post('http://www.quoracms.com/qcs/index.php?m=Index&a=addsite',"from=".$default_appurl."&type=install");

        echo '<script type="text/javascript">$("#laststep").removeAttr("disabled");$("#laststep").val("安装完成");$("#laststep").bind("click",function(){window.location=\'index.php?method=ext_info\'});setTimeout(function(){window.location=\'index.php?method=ext_info\'}, 3000);</script>'."\r\n";
	    show_footer();
    } else {
        show_dbinit();
    }
} elseif($method == 'ext_info') {
    show_header();
    echo '</div><div class="main" ><ul style="line-height: 200%; margin-left: 30px;">';
    echo '<li><a href="../index.php">安装成功，点击进入</a><br>';
    echo '<script>setTimeout(function(){window.location=\'../index.php\'}, 2000);</script>浏览器2秒后会自动跳转页面，无需人工干预</li>';
    echo '</ul></div>';
    show_footer();
}
?>