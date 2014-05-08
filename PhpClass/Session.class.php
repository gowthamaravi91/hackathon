<?php
/**
 * Created by PhpStorm.
 * User: Gowthamaravi
 * Date: 2/24/14
 * Time: 8:00 AM
 */



class Session {

    private  $logged_in = false;

    public $user_id;

    function __construct() {

        @session_start();

        $this->check_login();

        if ($this->logged_in) {

        } else {

        }
    }

    // id dosen't config yat
    public function login($user) {

        if ($user) {

            $this->user_id = $_SESSION['user_id'] = base64_encode($user);

            $this->logged_in = TRUE;
        }
    }

    public function logout() {

        unset($_SESSION['user_id']);

        unset($this->user_id);

        $this->logged_in = FALSE ;

    }

    public function is_logged_in() {

            return $this->logged_in;

    }



    private function check_login() {

        if (isset($_SESSION['user_id'])) {

            $this->user_id = $_SESSION['user_id'];

            $this->logged_in = TRUE;

        } else {

            unset($this->user_id);

            $this->logged_in = FALSE;
        }
    }

}

$session = new Session();