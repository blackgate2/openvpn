<?php

class auth {
    private $login;
    private $passwd;
    //encryption
    private $encrypt;       //set to true to use md5 encryption for the password
    private $sol1;
    private $sol2;
    private $q;
    private $msg;
    public function __construct($msg) {
        $this->login=$_POST['login'];
        $this->passwd=$_POST['passwd'];
        $this->encrypt = true;
        $this->sol1 = commonConsts::sol_pass1;
        $this->sol2 = commonConsts::sol_pass2;
        $this->msg = $msg;
        $this->q = DB::Open();
        
    }

    public function hesh_pass($pass){
       // exit($pass);
        return md5($this->sol1.$pass.$this->sol2);
    }
    public function get_pass(){
       // exit($pass);
        return $this->passwd;
    }
    //login function
    public function login($login, $passwd ,$user_type) {
        $this->LogActivity($login, 'try_login', $passwd);
        //check if encryption is used
        if ($this->encrypt == true) {
            $passwd = $this->hesh_pass($passwd);
          
        }
        
        
        $authquery = "SELECT u.id,u.name,u.tip_user,u.login,u.passwd FROM users u 
                   WHERE status='1' AND login='?' AND passwd = '?' AND tip_user='?'";

        if ($this->q->qry($authquery, $login, $passwd, $user_type)) {
            $numrows = $this->q->numrows();
            $row = $this->q->getrow();
        }
       
        if ($numrows != 1) {
            return false;
        } elseif (!isset($_SESSION['login']) && !isset($_SESSION['passwd']) && $row['login'] == $login && $row['passwd'] == $passwd) {
            $_SESSION['login'] = $this->login;
            $_SESSION['passwd'] = $this->passwd;
            $_SESSION['auth_user_id'] = $row['id'];
            $_SESSION['auth_user_fio'] = $row['name'];
            $_SESSION['auth_tip_user'] = $row['tip_user'];
            return true;
        } elseif (isset($_SESSION['login']) && isset($_SESSION['passwd']) &&
                $row['login'] == $login && $row['passwd'] == $passwd) {
            return true;
        } else {
            return false;
        }
    }

    private function LogActivity($username, $action, $additionalinfo = "none")
	{
            $ip = $_SERVER['REMOTE_ADDR'];
            $date = date("Y-m-d H:i:s");
            $query = "INSERT INTO activitylog (date, username, action, additionalinfo, ip) VALUES ('?', '?', '?', '?', '?')";

            if ($this->q->qry($query,$date,$username,$action,$additionalinfo,$ip)){
                return true;
            }

	}
    //logout function
    public function logout() {
        session_destroy();
        return;
    }

    //check if loggedin
    public function logincheck($login) {

        $this->q->qry("SELECT login FROM users WHERE login = '?';", $login);

        if ($this->q->numrows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function compare_sess($login) {

        if ($this->logincheck($login) && $_SESSION['login']==$login){
            return true;
        }else{
            return false;
        }
        
    }

    //reset password
    public function passwordreset() {
        //generate new password
        $this->passwd= $this->createPassword();
        
        //check if encryption is used
        if ($this->encrypt == true) {
            return  md5($this->sol1.$this->passwd.$this->sol2);
        } else {
            return  $this->passwd;
        }

        
    }

    //create random password with 8 alphanumerical characters
    public function createPassword() {
        $chars = "abcdefghijkmnopqrstuvwxyz023456789";
        srand((double) microtime() * 1000000);
        $i = 0;
        $pass = '';
        while ($i <= 7) {
            $num = rand() % 33;
            $tmp = substr($chars, $num, 1);
            $pass = $pass . $tmp;
            $i++;
        }
        return $pass;
    }

    //login form
    public function loginForm() {

        return '
                <form id="form_login"  action="/user" method="post" enctype="multipart/form-data">
                    <div class="form_field">
                    <input type="text"  id="ulogin" class="text ui-widget-content ui-corner-all" 
                    placeholder="'.$this->msg['login_field'].'"/>
                    </br><a href="/user_create" id="button_ucreate">'.$this->msg['button_ucreate'].'</a>
                    </div>
                    <div class="form_field">
                    <input type="password" id="upasswd" class="text ui-widget-content ui-corner-all"  
                    placeholder="'.$this->msg['passwd_field'].'"/>
                    </br><a href="/user_sendpass" id="button_usendpass">'.$this->msg['button_usendpass'].'</a>
                    </div>
                    <div class="form_submit">
                    <a href="javascript:" id="button_ulogin"></a>
                    </div>
                </form>

                <div id="login_mess" class="plashka"></div>


';
    }

    public function userInfo($umenu) {

        return '<div id="login_fio"> '.$_SESSION['auth_user_fio'].'</b>'.$umenu.'</div>';
    } 
    public function userNotLogin() {
        return '<b>Error auth</b>'.time();
    } 
    //function to install logon table
}

?>