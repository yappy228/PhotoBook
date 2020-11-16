<?php
    // 外部ファイルの読み込み
    require_once '../utils/UserDAO.php';
    require_once '../utils/PostDAO.php';
    require_once '../utils/CommentDAO.php';
    require_once '../utils/FollowDAO.php';
    include 'ChromePhp.php';
    
    // セッションスタート
    session_start();
    
    // 変数の初期化
    $user_id = "";
    $flash_message = "";
    
    // ログインしているのならば
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
    }else{
        header('Location: login.php');
        exit;
    }
    
    try {
        // ログインユーザがフォローしているユーザの投稿データを全件取得
        $follow_dao = new FollowDAO();
        $follows = $follow_dao->get_follow_by_id($user_id);

        $follow_dao = null;
        
        $post_dao = new PostDAO();
        $posts = $post_dao->get_post_by_array_id($follows);
        
        $post_dao = null;
        
        // 投稿データに紐づく投稿者のユーザプロフィールを全件取得($post.idに紐づける)
        $user_dao = new UserDAO();
        $post_profile = [];
        foreach($posts as $post){
            $post_profile[$post['id']] = $user_dao->get_user_by_id($post['user_id']);
        }
        
        $user_dao = null;
        
        // 投稿データに紐づく第三者のコメントのデータを全件取得
        $comment_dao = new CommentDAO();
        $comments_array = [];
        $comments_array_count = [];
       
        foreach($posts as $post){
            $comments_array[$post['id']] = $comment_dao->get_all_comments_by_post_id($post['id']);
            $comments_array_count[$post['id']] = count($comments_array[$post['id']]);
        }
        
        $comment_dao = null;
        
        // 第三者のコメントに紐づくユーザのプロフィールを取得（時系列上位2件）
        $user_dao = new UserDAO();
        $comments_user_prof_array = [];
        
        foreach($posts as $post){
            for($i=0 ; $i<2 ; $i++){
                $get_comment_profile_id = $comments_array[$post['id']][$i]['user_id'];
                $comments_user_prof_array[$post['id']][$i] = $user_dao->get_user_by_id($get_comment_profile_id); 
            }
        }

        //
        // DOM表示用のコメントの編集
        //
        // 投稿者のコメント
        $user_prof_com_show = [];
        $user_prof_com_hide = [];
        
        // 第三者のコメント
        $user_ano_com_show = [];
        $user_ano_com_hide = [];
        
        foreach($posts as $post){
            if(!is_null($post['body'])){
                $posttext = $post['body'];
                $posttext = str_replace("\r\n", "" ,$posttext);
                $user_showtext = substr($posttext , 0 , 75);
                $user_hidetext = substr($posttext , 75);
                $user_prof_com_show[$post['id']] = $user_showtext;
                $user_prof_com_hide[$post['id']] = $user_hidetext;
            }else{
                $user_prof_com_show[$post['id']] = null;
                $user_prof_com_hide[$post['id']] = null;
            }
            
            for($i=0 ; $i<2 ; $i++){
                if(!is_null($comments_array[$post['id']][$i]['content'])){
                    $posttext = $comments_array[$post['id']][$i]['content'];
                    $posttext = str_replace("\r\n" , "" ,$posttext);
                    $user_ano_com_show[$post['id']][$i] = substr($posttext , 0 ,75);
                    $user_ano_com_hide[$post['id']][$i] = substr($posttext , 75);
                }else{
                    $user_ano_com_show[$post['id']][$i] = null;
                    $user_ano_com_hide[$post['id']][$i] = null;
                }
            }
        }
        
    } catch (PDOException $e) {
        exit;
    }
    
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $user_id_post = $_SESSION['user_id'];
        $post_id_post = $_POST['post_id'];
        $comment_post = $_POST['comment'];
        
        try{
            $comment_dao = new CommentDAO();
            
            $comment = new Comment($user_id_post, $post_id_post, $comment_post);
            
            $comments = $comment_dao->insert($comment);
            
            $comment_dao = null;
            
            header('Location: home.php');
            
        }catch (PDOException $e) {
            echo 'PDO exception: ' . $e->getMessage();
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
    <script src="../js/rewrite.js"></script>
    <script src="../js/auto_submit.js"></script>
    <script src="../js/ajax_getData.js"></script>
    <link rel="stylesheet" href="../css/home.css">
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
                <?php if(!empty($posts)){ ?>
                    <?php foreach($posts as $post){ ?>
                    
                    <div class="PostView">
                        <div class="PostProfile">
                            <?php if($post_profile[$post['id']]->image){ ?>
                                <img src="../upload/users/<?php print $post_profile[$post['id']]->image;?>">
                            <?php }else{ ?>
                                <i class="fas fa-user-circle fa-2x"></i>
                            <?php } ?>
                            <p><?php print $post_profile[$post['id']]->nickname;?></p>
                        </div>
                        <div>
                            <img src="../upload/posts/<?php print $post["image1"]; ?>" class="PostImage">
                        </div>
                        <div class=contentarea> 
                            <div class="Comment">
                                <div class="row1">
                                    <div class="usernickname"><?php print $post_profile[$post['id']]->nickname;?></div>
                                    <div class="Showtext" post_id="<?php print $post['id'];?>"><?php print $user_prof_com_show[$post['id']];?>
                                    <?php if(($user_prof_com_hide[$post['id']]) != ""){?>
                                        <button class="Tudukiwoyomu">．．続きを読む</button>
                                    <?php } ?>    
                                    </div>
                                </div>
                                <div class="row2">
                                    <div class="Hidetext" post_id="<?php print $post['id'];?>"><?php print $user_prof_com_hide[$post['id']];?></div>
                                </div>
                            </div>
                        
                            <div class="CommentNumber">
                                <div><a class="js-modal-open" data="<?php print $post['id'];?>" data-target="modal01">詳細ビューを表示する</a></div>
                            </div>
                            <div id="modal01" class="modal js-modal">
                                <div class="modal-bg js-modal-close"></div>
                                <div class="modal-content"></div>
                            </div>
                            <?php if($comments_user_prof_array[$post['id']][0] != false){?>
                            <div class="AnoComment1">
                                <div class="row1">
                                    <div class="usernickname"><?php print $comments_user_prof_array[$post['id']][0]->nickname;?></div>
                                    <div class="Showtext" post_id="<?php print $post['id'];?>"><?php print $user_ano_com_show[$post['id']][0];?>
                                    <?php if(($user_ano_com_hide[$post['id']][0]) != ""){?>
                                        <button class="Tudukiwoyomu_Ano1">．．続きを読む</button>
                                    <?php } ?>    
                                    </div>
                                </div>
                                <div class="row2">
                                    <div class="Hidetext" post_id="<?php print $post['id'];?>"><?php print $user_ano_com_hide[$post['id']][0];?></div>
                                </div>
                            </div>
                            <?php }  ?>
                            <?php if($comments_user_prof_array[$post['id']][1] != false){ ?>
                            <div class="AnoComment2">
                                <div class="row1">
                                    <div class="usernickname"><?php print $comments_user_prof_array[$post['id']][1]->nickname;?></div>
                                    <div class="Showtext" post_id="<?php print $post['id'];?>"><?php print $user_ano_com_show[$post['id']][1];?>
                                    <?php if(($user_ano_com_hide[$post['id']][1]) != ""){?>
                                        <button class="Tudukiwoyomu_Ano2">．．続きを読む</button>
                                    <?php } ?>
                                    </div>
                                </div>
                                <div class="row2">
                                    <div class="Hidetext" post_id="<?php print $post['id'];?>"><?php print $user_ano_com_hide[$post['id']][1];?></div>
                                </div>
                            </div>
                            <?php }  ?>
                        </div>
                        <form class="CommentPost" action="home.php" method="post">
                            <input type="hidden" name="user_id" value="<?php print $post["user_id"];?>">
                            <input type="hidden" name="post_id" value="<?php print $post["id"];?>">
                            <input type="text" name="comment" placeholder="ここにコメントを入力" class="Textarea">
                            <input type="submit" value="投稿する" class="submitbutton">
                        </form>
                    </div>
                    <?php } ?>
                <?php }else{ ?>
                    <p>まだ誰もフォローしていません。</p>
                    <p>ヘッダの検索ボックスからユーザを検索してフォローしましょう。</p>
                    <p>サンプルで作成しているユーザは、"aaa","bbb","ccc"がいます。</p>
                <?php } ?>
            </div>
        </main>
    </div>

</body></html>
