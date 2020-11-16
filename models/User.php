<?php
class User{

    public $id;
    public $name;
    public $nickname;
    public $email;
    public $password;
    public $created_at;
    
    public function __construct(){
        $a = func_get_args();
        $i = func_num_args();
        if (method_exists($this,$f = "__construct".$i)) {
            call_user_func_array(array($this,$f),$a);
        }
    }
    
    public function __construct4($name,$nickname,$email,$password){
        $this->name = $name;
        $this->nickname = $nickname;
        $this->email = $email;
        $this->password = $password;
    }
}
?>