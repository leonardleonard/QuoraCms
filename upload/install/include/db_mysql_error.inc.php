<?php
$timestamp = time();
$errmsg = '';

$dberror = $this->error();
$dberrno = $this->errno();

if($dberrno == 1114) {
	exit;
} else {
	if($message) {
		$errmsg = "<b>MYSQL info</b>: $message\n\n";
	}
	$errmsg .= "<b>Time</b>: ".gmdate("Y-n-j g:ia", $timestamp + ($GLOBALS['timeoffset'] * 3600))."\n";
	$errmsg .= "<b>Script</b>: ".$GLOBALS['PHP_SELF']."\n\n";
	if($sql) {
		$errmsg .= "<b>SQL</b>: ".htmlspecialchars($sql)."\n";
	}
	$errmsg .= "<b>Error</b>:  $dberror\n";
	$errmsg .= "<b>Errno.</b>:  $dberrno";
	echo "<p style=\"font-family: Verdana, Tahoma; font-size: 11px; background: #FFFFFF;\">";
	echo nl2br(str_replace($GLOBALS['tablepre'], '[Table]', $errmsg));
	echo '</p>';
	exit;
}
?>