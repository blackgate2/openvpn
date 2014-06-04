<?php

abstract class Database_Object {

    protected static $DB_Name;
    protected static $DB_Open;
    protected static $DB_Conn;

    protected function __construct($database, $hostname, $hostport, $username, $password) {
        self::$DB_Name = $database;
        self::$DB_Conn = mysql_connect($hostname . ":" . $hostport, $username, $password);
        if (!self::$DB_Conn) {
            die('Critical Stop Error: Database Error<br />' . mysql_error());
        }
        mysql_select_db(self::$DB_Name, self::$DB_Conn);
        mysql_query("SET NAMES utf8", self::$DB_Conn);
    }

    private function __clone() {
        
    }

    public function __destruct() {
//            mysql_close(self::$DB_Conn);  <-- commented out due to current shared-link close 'feature'.  If left in, causes a warning that this is not a valid link resource.
    }

}

final class DB extends Database_Object {

    public $result;
    public $row;

    public static function Open($database = commonConsts::dbName, $hostname = commonConsts::dbHost, $hostport = commonConsts::dbPort, $username = commonConsts::dbUser, $password = commonConsts::dbPass) {
        if (!self::$DB_Open) {
            self::$DB_Open = new self($database, $hostname, $hostport, $username, $password);
        } else {
            self::$DB_Open = null;
            self::$DB_Open = new self($database, $hostname, $hostport, $username, $password);
        }
        return self::$DB_Open;
    }

    public function query($query = "") {

        if ($query != "") {
            $this->result = mysql_query($query, self::$DB_Conn) or die(mysql_errno(self::$DB_Conn) . mysql_error(self::$DB_Conn));
            return $this->result;
        }
    }

    //prevent injection
    public function qry($query) {

        $args = func_get_args();
        $query = array_shift($args);
        $query = str_replace("?", "%s", $query);
        $args = array_map('mysql_real_escape_string', $args);
        array_unshift($args, $query);
        $query = call_user_func_array('sprintf', $args);

        if ($query != "") {
            //echo $query.'<br><br><br>';
            $this->result = mysql_query($query, self::$DB_Conn) or die(mysql_errno(self::$DB_Conn) . mysql_error(self::$DB_Conn));
            return $this->result;
        }
    }

    public function getrow() {
        $this->row = mysql_fetch_array($this->result, MYSQL_ASSOC);
        return $this->row;
    }

    public function getrow1() {
        $this->row = mysql_fetch_row($this->row);
        return $this->row;
    }

    public function numrows() {
        return mysql_num_rows($this->result);
    }

    public function lastID() {
        return mysql_insert_id();
    }

    public function error() {
        $this->result = mysql_error();
        return $this->result;
    }

    public function del($table, $condition = '') {
        $this->query("DELETE FROM $table $condition");
    }

    public function drop($table) {
        $this->query("DROP TABLE IF EXISTS $table");
    }

    public function free() {
        return mysql_free_result($this->result);
    }

    public function maxid($table, $f = 'id') {
        $this->query("Select max($f) as maxid From $table ");
        $data = $this->getrow();
        return $data['maxid'];
    }

    public function insert($table, $inserts) {
        $values = array_values($inserts);
        $keys = array_keys($inserts);
        //exit('INSERT INTO `' . $table . '` (`' . implode('`,`', $keys) . '`) VALUES (\'' . implode('\',\'', $values) . '\')');
        return $this->query('INSERT INTO `' . $table . '` (`' . implode('`,`', $keys) . '`) VALUES (\'' . implode('\',\'', $values) . '\')');
    }

    public function update($table, $arr, $condition = '') {
        $str = '';
        foreach ($arr as $k => $v) {
            $v=(is_numeric($v))?$v:'"'.$v.'"';
            $str.= '`' . $k . '` = ' . $v . ',';
        }
        $str = trim($str, ',');
        //exit('UPDATE `' . $table . '` SET ' . $str . ' ' . $condition);
        $this->query('UPDATE `' . $table . '` SET ' . $str . ' ' . $condition);
    }

    public function fetch_data_to_array($sql) {
        $this->query($sql);
        $data = array();
        while ($d = $this->getrow()) {
            $data[] = $d;
        }
        return $data;
    }

    public function fetch_data_to_accoc_array($sql, $key = 'id', $value = 'name') {
        $this->query($sql);
        $data = array();
        while ($d = $this->getrow()) {
            if ($key)
                $data[$d[$key]] = $d[$value];
            else
                $data[] = $d[$value];

        }
        return $data;
    }

    public function get_fetch_data($sql) {
        $this->query($sql);
        
        $data = array();
        while ($d = $this->getrow()) {
            foreach ($d as $k => $v) {
                $data[$k] = $v;
            }
        }
        return $data;
    }

    /* Transactions functions */

    public function begin() {
        $null = mysql_query("START TRANSACTION", self::$DB_Conn);
        return mysql_query("BEGIN", self::$DB_Conn);
        if ($query != "") {
            $this->result = mysql_query($query, self::$DB_Conn) or die(mysql_errno(self::$DB_Conn) . mysql_error(self::$DB_Conn));
            return $this->result;
        }
    }

    public function affected_rows() {
        return mysql_affected_rows();
    }

    public function commit() {
        return mysql_query("COMMIT", self::$DB_Conn);
    }

    public function rollback() {
        return mysql_query("ROLLBACK", self::$DB_Conn);
    }

    public function transaction($q_array) {
        $retval = 1;

        $this->begin();

        foreach ($q_array as $qa) {
            $this->result = mysql_query($qa, self::$DB_Conn) or die(mysql_errno(self::$DB_Conn) . mysql_error(self::$DB_Conn));
            if (mysql_affected_rows() == 0) {
                $retval = 0;
            }
        }

        if ($retval == 0) {
            $this->rollback();
            return false;
        } else {
            $this->commit();
            return true;
        }
    }

}

?>