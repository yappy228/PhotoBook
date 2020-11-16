<?php
    require_once '../utils/UserDAO.php';
    include 'ChromePhp.php';
    
    session_start();
    
    if($_SERVER['REQUEST_METHOD'] === 'POST'){

        // フォームからの入力値を取得
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        try {
            // ユーザインタフェース
            $user_dao = new UserDAO();
            
            $user = $user_dao->login($email, $password);
            
            $user_dao = null;
            
            if($user){
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user->id;
                header('Location: home.php');
            }else{
                $flash_message = "正しいログインIDとパスワードを入力してください。";
                echo $flash_message;
            }

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
    <link rel="stylesheet" href="../css/login.css">
</head>

<body>
    <section class="mainform">
        <form action="login.php" method="POST">
            <h1>PhotoBook</h1>
            <input class="LoginInfo" type="email" name="email" maxlength=30 placeholder="Mailaddress">
            
            <input class="LoginInfo" type="password" name="password" minlength=8 maxlength=20 placeholder="Password">

            <input id="LoginSubmit" type="submit" value="ログイン">
        </form>

        <div class="registration">
            <div class="link">アカウントをお持ちでないですか？ <a href="regist.php">登録する</a></div>
        </div>
        
        <div class="comment">
            <div class="commenttext">
                <li>・ログインID、パスワードは自由に作成してください。</li>
                <li>・データが設定されているユーザおよびパスワードは以下の通りです。</li>
                <li>1.ID:sample01@sample.co.jp PASS:sample01</li>
                <li>2.ID:sample02@sample.co.jp PASS:sample02</li>
                <li>3.ID:sample03@sample.co.jp PASS:sample03</li>
                <li>・動作確認はGoogle Chromeのみで行っております</li>
            </div>
        </div>
    </section>
</body></html>
