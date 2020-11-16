<?php
    header("Content-Type: application/json; charset=UTF-8");
    require_once 'UserDAO.php';
    include '../page/ChromePhp.php';
    
    $name = filter_input(INPUT_POST,"name");
    $user_dao = new UserDAO();
    
    $users = $user_dao->name_search($name);
    
    $user_dao = null;
    
    echo json_encode($users);

    exit; //処理の終了
?>