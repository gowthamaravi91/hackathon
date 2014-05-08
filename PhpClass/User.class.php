<?php

class User {

    public  $current_time;

    public  $last_time;

    public  $username;

    public static function authenticate($user="",$pass=""){

        $db = new DBConnection();

        $sql = "SELECT * FROM `admin_tab` WHERE  username = '".$db->escape_value($user)."' and password = '".$db->escape_value($pass)."' LIMIT 1 ";

        $result = $db->query($sql);

        $db->close_connection();

        if(mysql_num_rows($result) == 1 ){

            $row = mysql_fetch_assoc($result);

             return self::update($row['id'])? $row['id'] : false ;

        }else{

            return false;
        }

    }

    private static function update($id){

        date_default_timezone_set('Asia/Colombo');

        $db = new DBConnection();

        $result = $db->query("UPDATE `admin_tab` SET `last_login_time`=`time_` , `time_`='" . date('Y-m-d H:i:s ') . " ' WHERE `id`=". $id);

        $db->close_connection();

        if($result){

            return true;

        }else{

            return false;

        }

    }

    public  function get_user($id=0) {

        $db = new DBConnection();

        $obj = $db->query_fetch("SELECT username , last_login_time , time_ FROM admin_tab WHERE id =".$id,3);

        $db->close_connection();

        foreach ($obj as $val):

            $this->username=$val[0];

            $this->last_time = $val[1];

            $this->current_time = $val[2];

        endforeach;
    }
}