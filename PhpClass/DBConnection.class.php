<?php
/**
 * Created by PhpStorm.
 * User: Gowthamaravi
 * Date: 2/24/14
 * Time: 6:23 AM
 */



class DBConnection {

    private $connection;

    public static $last_query;

    function __construct() {

        $DB_SERVER = Config::HOST_NAME;

        $DB_USER = Config::USER_NAME;

        $DB_PASS = Config::PASSWORD;

        $DB_NAME = Config::DATABASE_NAME;

        $this->open_connection($DB_SERVER,$DB_USER,$DB_PASS,$DB_NAME);
    }

    private function open_connection($DB_SERVER,$DB_USER,$DB_PASS,$DB_NAME) {

        $this->connection = mysql_connect($DB_SERVER, $DB_USER, $DB_PASS);

        if (!$this->connection) {

            die("DataBase Connection Failed :" . mysql_error());

        } else {

            $db_select = mysql_select_db($DB_NAME, $this->connection);

            if (!$db_select) {

                die("DataBase Database Failed :" . mysql_error());

            }
        }
    }

    public function close_connection() {

        if (isset($this->connection)) {

            mysql_close($this->connection);

            unset($this->connection);

        }

    }

    public  function query($sql) {

        self::$last_query = $sql;

//        $result = $this->escape_value($sql);

        $result = mysql_query($sql);

        $this->confirm_query($result);

        //$final = $this->fetch_array($result);

        return $result;

    }

    public function query_assoc($sql){
        $result = self::query($sql);
        return mysql_fetch_assoc($result);
    }

    public  function query_fetch($sql,$no) {

        self::$last_query = $sql;

//        $result = $this->escape_value($sql);

        $result = mysql_query($sql);

        $this->confirm_query($result);

        $object = array();

        while ($final = $this->fetch_array($result)):

            $object[] = $this ->instantiate($final,$no);

        endwhile;

        return $object;

    }

    public function escape_value($value) {

        $value = mysql_real_escape_string($value);

        return $value;

    }

    private function instantiate($record,$no){

        $obj = array();

        for($i=0; $i<$no;$i++):

            is_null($record[$i])? $obj[] = "&nbsp;":$obj[] = $record[$i] ;

        endfor;

        return $obj;
    }

    public function fetch_array($value){

        return mysql_fetch_array($value);
    }

    private function confirm_query($result){

        if(!$result){

            $output = "Database query failed : ".mysql_error()."<br/><br/>";

            $output .= "Last SQL Query :".  self::$last_query;

            die ($output);
        }

    }

    public function last_id(){
        return mysql_insert_id();
    }


}
