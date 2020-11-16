<?php
    header("Content-Type: application/json; charset=UTF-8");
    require_once 'FollowDAO.php';
    include '../page/ChromePhp.php';
    
    $follow_user_id = filter_input(INPUT_POST,"follow_user_id");
    $followed_user_id = filter_input(INPUT_POST,"followed_user_id");
    $pat = filter_input(INPUT_POST,"pat");
    
    $follow_dao = new FollowDAO();
    
    if($pat === 'follow'){
        $follow_dao->insert($follow_user_id,$followed_user_id);    
    }elseif($pat === 'unfollow'){
        $follow_dao->delete($follow_user_id,$followed_user_id);    
    }else{
        
    }
    
    $follow_dao = null;
    
    echo json_encode("");

    exit; //処理の終了
?>