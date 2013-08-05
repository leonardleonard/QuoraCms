<?php
session_start();
$_SESSION['uid']==NULL&&exit;
function make_avatar_path($uid, $dir = '.') {
        $uid = sprintf("%09d", $uid);
        $dir1 = substr($uid, 0, 3);
        $dir2 = substr($uid, 3, 2);
        $dir3 = substr($uid, 5, 2);
        !is_dir($dir . '/' . $dir1) && mkdir($dir . '/' . $dir1, 0777);
        !is_dir($dir . '/' . $dir1 . '/' . $dir2) && mkdir($dir . '/' . $dir1 . '/' . $dir2, 0777);
        !is_dir($dir . '/' . $dir1 . '/' . $dir2 . '/' . $dir3) && mkdir($dir . '/' . $dir1 . '/' . $dir2 . '/' . $dir3, 0777);
        !file_exists($dir . '/' . $dir1 . '/index.html') && touch($dir . '/' . $dir1 . '/index.html');
        !file_exists($dir . '/' . $dir1 . '/' . $dir2 . '/index.html') && touch($dir . '/' . $dir1 . '/' . $dir2 . '/index.html');
        !file_exists($dir . '/' . $dir1 . '/' . $dir2 . '/' . $dir3 . '/index.html') && touch($dir . '/' . $dir1 . '/' . $dir2 . '/' . $dir3 . '/index.html');
    }
    function get_avatar_path($uid) {
        $uid = sprintf("%09d", $uid);
        $dir1 = substr($uid, 0, 3);
        $dir2 = substr($uid, 3, 2);
        $dir3 = substr($uid, 5, 2);
        return $dir1 . '/' . $dir2 . '/' . $dir3;
    }
	$rs = array();
			$avatarpath = get_avatar_path($_SESSION['uid']) ;
			$avatarrealdir  = realpath('.'.DIRECTORY_SEPARATOR.'avatar_dir'.DIRECTORY_SEPARATOR.$avatarpath );
			!is_dir( $avatarrealdir )&&make_avatar_path($_SESSION['uid'],'.'.DIRECTORY_SEPARATOR.'avatar_dir');
			$avatarrealdir  = realpath('.'.DIRECTORY_SEPARATOR.'avatar_dir'.DIRECTORY_SEPARATOR.$avatarpath );
			include('Image.class.php');
			$filepath=$avatarrealdir.'.'.DIRECTORY_SEPARATOR.$_SESSION['uid'].'.jpg';
			$len = file_put_contents($filepath,file_get_contents("php://input"));
			Image::thumb($filepath,'','',160,160,false,'_mid');
			Image::thumb($filepath,'','',74,74,false,'_min');
			unlink($filepath);
			$rs['status'] = 1;
			print json_encode($rs);
?>