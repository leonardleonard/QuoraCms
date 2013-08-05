<?php
class dbstuff {
	var $querynum = 0;
	var $link;
    var $sqlid;
    var $record;
    var $dbcharset='utf-8';
    var $charset='utf-8';

	function connect($dbhost, $dbuser, $dbpw, $dbname = '', $pconnect = 0, $halt = TRUE) {
		if($pconnect) {
			if(!$this->link = @mysql_pconnect($dbhost, $dbuser, $dbpw)) {
				$halt && $this->halt('Can not connect to MySQL server');
			}
		} else {
			if(!$this->link = @mysql_connect($dbhost, $dbuser, $dbpw, 1)) {
				$halt && $this->halt('Can not connect to MySQL server');
			}
		}

		if($this->version() > '4.1') {
			global $charset, $dbcharset;
			if(!$dbcharset && in_array(strtolower($charset), array('gbk', 'big5', 'utf-8'))) {
				$dbcharset = str_replace('-', '', $charset);
			}

			if($dbcharset) {
				@mysql_query("SET character_set_connection=$dbcharset, character_set_results=$dbcharset, character_set_client=binary", $this->link);
			}

			if($this->version() > '5.0.1') {
				@mysql_query("SET sql_mode=''", $this->link);
			}
		}

		if($dbname) {
			@mysql_select_db($dbname, $this->link);
		}
	}

	function select_db($dbname) {
		return mysql_select_db($dbname, $this->link);
	}

	function fetch_array($query, $result_type = MYSQL_ASSOC) {
		return mysql_fetch_array($query, $result_type);
	}

	function query($sql, $type = '') {
		$func = $type == 'UNBUFFERED' && @function_exists('mysql_unbuffered_query') ?'mysql_unbuffered_query' : 'mysql_query';

		if(!($query = $func($sql, $this->link))) {
			if(in_array($this->errno(), array(2006, 2013)) && substr($type, 0, 5) != 'RETRY') {
				$this->close();
				$this->connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect);
				$this->query($sql, 'RETRY'.$type);
			} elseif($type != 'SILENT' && substr($type, 5) != 'SILENT') {
				$this->halt('MySQL Query Error', $sql);
			}
		}

		$this->querynum++;
        $this->sqlid=$query;
		return $query;
	}

	function affected_rows() {
		return mysql_affected_rows($this->link);
	}

	function error() {
		return (($this->link) ? mysql_error($this->link) : mysql_error());
	}

	function errno() {
		return intval(($this->link) ? mysql_errno($this->link) : mysql_errno());
	}

	function result($query, $row) {
		$query = @mysql_result($query, $row);
		return $query;
	}

    function result_first($sql) {
		return $this->result($this->query($sql), 0);
	}

	function num_rows($query) {
		$query = mysql_num_rows($query);
		return $query;
	}

	function num_fields($query) {
		return mysql_num_fields($query);
	}

	function free_result($query) {
		return mysql_free_result($query);
	}

	function insert_id() {
		return ($id = mysql_insert_id($this->link)) >= 0 ? $id : $this->result($this->query("SELECT last_insert_id()"), 0);
	}

	function fetch_row($query) {
		$query = mysql_fetch_row($query);
		return $query;
	}

	function fetch_fields($query) {
		return mysql_fetch_field($query);
	}

	function version() {
		return mysql_get_server_info($this->link);
	}

	function close() {
		return mysql_close($this->link);
	}

    function nr($sql_id="") {
        if(!$sql_id) $sql_id=$this->sqlid;
	    return mysql_num_rows($sql_id);
    }

    function nf($sql_id="") {
        if(!$sql_id) $sql_id=$this->sqlid;
        return mysql_num_fields($sql_id);
    }

    function nextrecord($sql_id="") {
        if(!$sql_id) $sql_id=$this->sqlid;
        if($this->record=mysql_fetch_array($sql_id))  return $this->record;
        else return false;
    }

    function f($name) {
        if($this->record[$name]) return $this->record[$name];
        else return false;
    }

    function lock($tblname,$op="WRITE") {
        if(mysql_query("lock tables ".$tblname." ".$op)) return true;
        else return false;
    }

    function unlock() {
        if(mysql_query("unlock tables")) return true;
        else return false;
    }

    function ar() {
        return @mysql_affected_rows($this->link);
    }

    function i_id() {
        return mysql_insert_id();
    }

	function halt($message = '', $sql = '') {
		define('CACHE_FORBIDDEN', TRUE);
		include('db_mysql_error.inc.php');
	}
}
?>