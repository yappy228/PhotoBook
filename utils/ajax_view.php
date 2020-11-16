<?php
    header("Content-Type: application/json; charset=UTF-8");
    require_once 'UserDAO.php';
    require_once 'PostDAO.php';
    require_once 'CommentDAO.php';
    include '../page/ChromePhp.php';
    
    $post_id = filter_input(INPUT_POST,"post_id");
    
    $post_dao = new PostDAO();
    
    $post = $post_dao->get_post_by_id($post_id);
    
    $user_dao = new UserDAO();

    $user = $user_dao->get_user_by_id($post->user_id);
    
    $comment_dao = new CommentDAO();
    
    $comments = $comment_dao->get_all_comments_by_post_id($post->id);
    
    $comments_user = [];
    foreach($comments as $comment){
        $comments_user[$comment['id']] = $user_dao->get_user_by_id($comment['user_id']);
    }
    
    //DOM作成
    $htmlstring = '<div class="view_PostView">';
    $htmlstring .= '<div class="view_leftsection">';
    $htmlstring .= '<div><img src="../upload/posts/' . $post->image1 . '" class="view_PostImage"></div>';
    $htmlstring .= '</div>';
    $htmlstring .= '<div class="view_rightsection">';
    $htmlstring .= '<div class="view_PostProfile">';
    if($user->image == ""){
        $htmlstring .= '<i class="fas fa-user-circle fa-1x"></i>';
    }else{
        $htmlstring .= '<img src="../upload/users/' . $user->image . '" class="view_profileImage">';
    }
    $htmlstring .= '<p>' . $user->nickname . '</p>';
    $htmlstring .= '</div>';
    $htmlstring .= '<div class="CommentSection">';
    
    foreach($comments as $comment){
        $htmlstring .= '<div class="view_Onecomment">';
        if($comments_user[$comment['id']]->image == ""){
            $htmlstring .= '<i class="fas fa-user-circle fa-1x"></i>';
        }else{
            $htmlstring .= '<img src="../upload/users/' . $comments_user[$comment['id']]->image . '" class="view_commentImage">';
        }
        $htmlstring .= '<p>' . $comments_user[$comment['id']]->nickname . ':' . $comment['content'] . '</p>';
        $htmlstring .= '</div>';
    }
    $htmlstring .= '</div>';
    $htmlstring .= '<div class="footerSection">';
    $htmlstring .= '<form class="CommentPost" action="home.php" method="post">';
    $htmlstring .= '<input type="hidden" name="user_id" value="' . $post->user_id . '\">';
    $htmlstring .= '<input type="hidden" name="post_id" value="' . $post->id . '\">';
    $htmlstring .= '<input type="text" name="comment" placeholder="ここにコメントを入力" class="Textarea">';
    $htmlstring .= '<input type="submit" value="投稿する" class="submitbutton">';
    $htmlstring .= '</form></div></div></div>';
    
    echo json_encode("$htmlstring");

    exit; //処理の終了
?>