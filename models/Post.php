<?php
class Post{
    
    public $id;
    public $user_id;
    public $body;
    public $image1;
    public $image2;
    public $image3;
    public $image4;
    public $image5;
    public $created_at;
    
    public function __construct(){
        $a = func_get_args();
        $i = func_num_args();
        if (method_exists($this,$f = "__construct".$i)) {
            call_user_func_array(array($this,$f),$a);
        }
    }
    
    public function __construct7($user_id, $body, $image1, $image2, $image3, $image4, $image5){
        $this->user_id = $user_id;
        $this->body = $body;
        $this->image1 = $image1;
        $this->image2 = $image2;
        $this->image3 = $image3;
        $this->image4 = $image4;
        $this->image5 = $image5;
    }
}
?>