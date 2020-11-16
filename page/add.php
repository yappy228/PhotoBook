<?php
    require_once '../utils/PostDAO.php';
    include 'ChromePhp.php';
    
    session_start();

    $user_id = "";
    $flash_message = "";

    // ログインしているのならば
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
    }else{
        header('Location: login.php');
        exit;
    }
    
    if($_SERVER['REQUEST_METHOD'] === 'POST'){

        $body = $_POST['body'];

        try {
            
            $post_dao = new PostDAO();
            
            $image1 = $post_dao->upload($_FILES);
            
            $post = new Post($user_id, $body, $image1, $image2, $image3, $image4, $image5);

            $post_dao->insert($post);
            
            $post_dao = null;
            
            // header('Location: add.php');

        } catch (PDOException $e) {
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
    <script src="../js/dropdown_menu.js"></script>
    <script src="../js/dropdown_search.js"></script>
    <script src="../js/file_upload.js"></script>
    <script src="../js/auto_submit.js"></script>
    <script src="../js/ajax_getData.js"></script>
    <link rel="stylesheet" href="../css/add.css">
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
                    <div class="viewSection">
                        <div class="imageView">
                            <div class="blank">
                                <i class="fas fa-images fa-9x"></i>
                            </div>

                            <div class="preview"></div>
                        </div>
                    </div>

                    <div class="toukouSection">
                        <div class="inputComment">
                            <form class="CommentPost" formaction="add.php" method="post" enctype="multipart/form-data">
                                <div class="drawboxfile">
                                    <div class="selectfile">
                                        <div>・アップロードするファイルを選択：</div>
                                        <input id="fileinput" type="file" name="image" accept="image/jpeg">
                                    </div>
                                </div>
                                <div class="drawboxcomment">
                                    <textarea rows="5" cols="40" type="text" name="body" placeholder="ここにコメントを入力"></textarea>
                                </div>
                                <div class="drawboxsubmit">
                                    <input type="submit" value="投稿する" class="submitbutton">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body></html>


