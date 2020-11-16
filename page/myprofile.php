<?php
    // 外部ファイルの読み込み
    require_once '../utils/UserDAO.php';
    require_once '../utils/PostDAO.php';
    require_once '../utils/FollowDAO.php';
    include 'ChromePhp.php';
    
    // セッションスタート
    session_start();
    
    // 変数の初期化
    $user_id = "";
    
    // ログインしているのならば
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
    }else{
        header('Location: login.php');
        exit;
    }
    
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $name = $_POST['name'];
        $nickname = $_POST['nickname'];
        $email = $_POST['email'];
        $profile = $_POST['profile'];
        
        try{
            $user_dao_upd = new UserDAO();
            
            $image = $user_dao_upd->upload($_FILES);
            
            ChromePhp::log($image);
            
            $user_dao_upd->update_user_by_id($user_id,$name,$nickname,$email,$profile,$image);
            
            $user_dao_upd = null;
            
        } catch (PDOException $e) {
            ChromePhp::log($e->getMessage());
            exit;
        }
    }    
    
    try {
        // ログインユーザのプロフィールデータを取得
        $user_dao = new UserDAO();
        $user = $user_dao->get_user_by_id($user_id);

        $user_dao = null;
        
        // ログインユーザの投稿データを全件取得
        $post_dao = new PostDAO();
        $posts = $post_dao->get_postlist_by_userid($user_id);
        ChromePhp::log($posts);
        $posts_count = count($posts);
        
        $post_dao = null;
        
        // ログインユーザのフォロー関係のリストを取得
        $follow_dao = new FollowDAO();
        
        $follows = $follow_dao->get_follow_by_id($user_id);
        $follows_count = count($follows);
        
        $followeds = $follow_dao->get_followed_by_id($user_id);
        $followeds_count = count($followeds);
        
        $follow_dao = null;
        
        // フラッシュメッセージの取得とセッションからの削除
        if(isset($_SESSION['flash_message']) === true){
            $flash_message = $_SESSION['flash_message'];
            $_SESSION['flash_message'] = null;
        }
            
    } catch (PDOException $e) {
        ChromePhp::log($e->getMessage());
        exit;
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
    <script src="../js/dropdown_menu.js"></script>
    <script src="../js/dropdown_search.js"></script>
    <script src="../js/auto_submit.js"></script>
    <script src="../js/ajax_getData.js"></script>
    <script src="../js/profileimage.js"></script>
    <link rel="stylesheet" href="../css/myprofile.css">
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
                    <div class="editprofile">
                        <form action="myprofile.php" method="post" enctype="multipart/form-data">
                            <div class="email">email:</div>
                            <input class="editname" type="email" name="email" value="<?php print $user->email;?>">
                            <div class="flexview">
                                <div class="namelabel">name:</div>
                                <input class="editname" type="text" name="name" value="<?php print $user->name;?>">
                            </div>
                            <div class="flexview">
                                <div class="nicknamelabel">nickname:</div>
                                <input class="editnickname" type="text" name="nickname" value="<?php print $user->nickname;?>">
                            </div>
                            <div class="flexview">
                                <div class="profile">profile:</div>
                                <textarea class="editprofiletext" rows="5" cols="100" name="profile" ><?php print $user->profile;?></textarea>
                            </div>
                            <div class="flexview">
                                <div class="imagelabel">profile_image:</div>
                                <input id="fileinput" type="file" name="image" accept="image/jpeg" >
                            </div>
                            <input type="submit" value=" 更 新 ">
                        </form>
                    </div>
                </div>
            </div>
        </main>

    </div>
</body></html>