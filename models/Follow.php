<?php

class Follow{
    
    public $id;
    public $follow_user_id;
    public $followed_user_id;
    public $created_at;
    
    public function __construct(){
        $a = func_get_args();
        $i = func_num_args();
        if (method_exists($this,$f = "__construct".$i)) {
            call_user_func_array(array($this,$f),$a);
        }
    }
    
    public function __construct2($follow_user_id,$followed_user_id){
        $this->follow_user_id = $follow_user_id;
        $this->followed_user_id = $followed_user_id;
    }
}
?>