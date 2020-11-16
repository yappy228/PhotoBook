<?php
class Comment{
    
    public $id;
    public $user_id;
    public $post_id;
    public $content;
    public $created_at;
    
    public function __construct(){
        $a = func_get_args();
        $i = func_num_args();
        if (method_exists($this,$f = "__construct".$i)) {
            call_user_func_array(array($this,$f),$a);
        }
    }
    
    public function __construct3($user_id, $post_id, $content){
        $this->user_id = $user_id;
        $this->post_id = $post_id;
        $this->content = $content;
    }
}
?>