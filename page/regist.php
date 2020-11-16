<?php
    require_once '../utils/UserDAO.php';
    
    session_start();
    
    //レスポンス時のメッセージ
    $flash_message = "";
    
    if($_SERVER['REQUEST_METHOD'] === 'POST'){

        $email = $_POST['email'];
        $name = $_POST['name'];
        $nickname = $_POST['nickname'];
        $password = $_POST['password'];
        
        try {
            
            $user_dao = new UserDAO();
            
            $user = new User($name, $nickname, $email, $password);

            $user_dao->regist($user);

            $user_dao = null;
                    
            $_SESSION['flash_message'] = "会員登録が成功しました。";
            // header('Location: login.php');

        } catch (PDOException $e) {
            // echo 'PDO exception: ' . $e->getMessage();
            var_dump($e->getMessage());
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
    <link rel="stylesheet" href="../css/regist.css">
</head>

<body>
    <section class="mainform">
        <form action="regist.php" method="POST">
            <h1>PhotoBook</h1>
            <input class="LoginInfo" type="email" name="email" maxlength=60 placeholder="email">
            
            <input class="LoginInfo" type="text" name="name" maxlength=30 placeholder="name">
            
            <input class="LoginInfo" type="text" name="nickname" maxlength=30 placeholder="nickname">
            
            <input class="LoginInfo" type="password" name="password" minlength=8 maxlength=20 placeholder="password">

            <input id="LoginSubmit" type="submit" value="登録する">
        </form>

        <div class="registration">
            <div class="link">アカウントをお持ちですか？ <a href="login.php">ログインする</a></div>
        </div>
        
        <div class="comment">
            <div class="commenttext">
                <li>・ログインID、パスワードは自由に作成してください。</li>
                <li>・お試しのログインID「aaa」パスワードは「bbb」でログイン可能です。</li>
                <li>・動作確認はGoogle Chromeのみ行っております</li>
            </div>
        </div>
    </section>
</body></html>
