<?php
    // 外部ファイルの読み込み
    require_once '../utils/UserDAO.php';
    require_once '../utils/PostDAO.php';
    require_once '../utils/FollowDAO.php';
    include 'ChromePhp.php';
    
    // セッションスタート
    session_start();
    
    // 変数の初期化
    $login_user_id = "";
    $flash_message = "";
    
    // ログインしているのならば
    if(isset($_SESSION['user_id'])){
        $login_user_id = $_SESSION['user_id'];
    }else{
        header('Location: login.php');
        exit;
    }
    
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        try {
            $user_id = $_POST['user_id'];
            
            if($user_id == $login_user_id){
                header('Location: myprofile.php');
                exit;
            }
            
            // 参照ユーザのプロフィールデータを取得
            $user_dao = new UserDAO();
            $user = $user_dao->get_user_by_id($user_id);
            
            // 参照ユーザの投稿データを全件取得
            $post_dao = new PostDAO();
            $posts = $post_dao->get_postlist_by_userid($user_id);
            $posts_count = count($posts);
            
            $post_dao = null;
            
            // 参照ユーザのフォロー関係のリストを取得
            $follow_dao = new FollowDAO();
            
            $follows = $follow_dao->get_follow_by_id($user_id);
            $follows_count = count($follows);
            
            $followeds = $follow_dao->get_followed_by_id($user_id);
            $followeds_count = count($followeds);
            
            // ログインユーザが参照ユーザをフォローしているか
            $login_follow = $follow_dao->get_follow_login_by_id($login_user_id,$user_id);
            
        } catch (PDOException $e) {
            ChromePhp::log($e->getMessage());
            exit;
        }
    }
?>

<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="content-type" charset="utf-8">
    <title>PhotoBook</title>
    <meta id="viewport" name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover">
    <script src="../vendor/jquery-3.4.1.min.js"></script>
    <script src="https://kit.fontawesome.com/32225b7516.js" crossorigin="anonymous"></script>
    <script src="../js/continue-read.js"></script>
    <script src="../js/ajax_view.js"></script>
    <script src="../js/dropdown_menu.js"></script>
    <script src="../js/dropdown_search.js"></script>
    <script src="../js/file_upload.js"></script>
    <script src="../js/auto_submit.js"></script>
    <script src="../js/ajax_getData.js"></script>
    <script src="../js/ajax_follow.js"></script>
    <link rel="stylesheet" href="../css/profile.css">
    <link rel="stylesheet" href="../css/ajax-view.css">
</head>

<body>
    <div id="react-root">
        <header>
            <div class="BlankHeadBar"></div>
            <div class="HeadBar">
                <div class="Head">
                    <div class="Title">
                        <div>PhotoBook</div>
                    </div>
                    <div class="SearchBox">
                        <input type="search" name="search" placefolder="ユーザを入力">
                        <input type="submit" name="search_submit" class="search_submit" value="検索">
                        <div class="SearchResult">
                            <div class="triangle_search"></div>
                            <div class="person_menu">
                            </div>
                        </div>
                    </div>
                    <div class="Menu">
                        <div class="icon"><a href="home.php"><i class="fas fa-home "></i></a></div>
                        <div class="icon"><a href="add.php"><i class="fas fa-plus "></i></a></div>
                        <div class="icon">
                            <div class="bottom_menu">
                                <i class="fas fa-user"></i>
                                <div class="pulldown">
                                    <div class="triangle"></div>
                                    <div class="pulldown_menu">
                                        <div class="pulldown_menu_text">
                                            <i class="fas fa-user"></i>
                                            <a href="myprofile.php"><div>プロフィール</div></a>
                                        </div>
                                        <a href="logout.php"><div class="pulldown_menu_text">ログアウト</div></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="MainContent">
            <div class=Mainwrap>
                <div class="HomeMainBar">
                    <div class="profilehead">
                        <?php if(!$user->image){ ?>
                            <i class="fas fa-user-circle fa-9x" id="noselected"></i>
                        <?php }else{ ?>
                            <img src="../upload/users/<?php print $user->image;?>" id="profileimage">
                        <?php } ?>   
                        <div class="profilecontent">
                            <div class="row1">
                                <div class="nickname"><?php print $user->nickname;?></div>
                                <?php if(!$login_follow){ ?>
                                    <button class="follow" follow_user_id="<?php print $_SESSION['user_id'];?>" followed_user_id=<?php print $user->id; ?> pat="follow">フォローする</button>
                                <?php }else{ ?>
                                    <button class="follow" follow_user_id="<?php print $_SESSION['user_id'];?>" followed_user_id=<?php print $user->id; ?> pat="unfollow">フォロー解除</button>
                                <?php } ?>
                            </div>
                            <div class="row2">
                                <div class="postcount">投稿<?php print $posts_count;?>件</div>
                                <div class="followed">フォロワー<?php print $followeds_count;?>人</div>
                                <div class="followcount">フォロー中<?php print $follows_count;?>人</div>
                            </div>
                            <div class="row3">
                                <div class="name"><?php print $user->name;?></div>
                                <div class="profilecomment"><?php print $user->profile;?></div>
                            </div>
                        </div>
                    </div>
                    <div class="imagelist">
                        <?php $i = 0; ?> 
                        <?php foreach($posts as $post){ 
                            if($post === end($array)){?>
                                </div>
                            <?php }elseif($i == 0){ ?>
                                <div class="image_row">
                                    <img src="../upload/posts/<?php print $post['image1'];?>" value="<?php print $post['id'];?>" class="js-modal-open" data="<?php print $post['id'];?>" data-target="modal01">
                                <?php $i += 1;
                            }elseif($i == 2){ ?>
                                    <img src="../upload/posts/<?php print $post['image1'];?>" value="<?php print $post['id'];?>" class="js-modal-open" data="<?php print $post['id'];?>" data-target="modal01">
                                </div>
                                <?php $i = 0;
                            }else{ ?>
                                <img src="../upload/posts/<?php print $post['image1'];?>" value="<?php print $post['id'];?>" class="js-modal-open" data="<?php print $post['id'];?>" data-target="modal01">
                                <?php $i += 1;
                            }?>
                        <?php } ?>
                    </div>
                    <div id="modal01" class="modal js-modal">
                        <div class="modal-bg js-modal-close"></div>
                        <div class="modal-content"></div>
                    </div>
                </div>
            </div>
        </main>

    </div>
</body></html>