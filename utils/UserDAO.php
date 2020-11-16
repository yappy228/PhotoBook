<?php
require_once '../config/Const.php';
require_once '../models/User.php';

class UserDAO{
    
    //データベース接続
    public function get_connection(){
        $pdo = new PDO(DSN, DB_USERNAME, DB_PASSWORD);
        return $pdo;
    }
    
    //データベース切断
    public function close_connection($pdo, $stmt){
        $pdo = null;
        $stmt = null;
    }
    
    public function get_all_users(){
        $pdo = $this->get_connection();
        $stmt = $pdo->query('SELECT * FROM users');
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'user');
        $users = $stmt->fetchAll();
        $this->close_connection($pdo, $stmt);
        
        return $users;
    }
    
    public function get_user_by_id($id){
        $pdo = $this->get_connection();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE id=:id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'user');
        $user = $stmt->fetch();
        $this->close_connection($pdo, $stmt);
        
        return $user;
    }
    
    // IDをキーにusersをアップデート
    public function update_user_by_id($id, $name, $nickname, $email, $profile, $image){
        $pdo = $this->get_connection();
        $stmt = $pdo->prepare('UPDATE users SET name=:name, nickname=:nickname, email=:email, profile=:profile, image=:image WHERE id=:id');
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':nickname', $nickname, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':profile', $profile, PDO::PARAM_STR);
        $stmt->bindParam(':image', $image, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $user = $stmt->fetch();
        $this->close_connection($pdo, $stmt);
        
        return;
    }
    
    //会員登録
    public function regist($user){
        $pdo = $this->get_connection();
        $stmt = $pdo -> prepare("INSERT INTO users (name, nickname, email, password) VALUES (:name, :nickname, :email, :password)");

        $stmt->bindParam(':name', $user->name, PDO::PARAM_STR);
        $stmt->bindParam(':nickname', $user->nickname, PDO::PARAM_STR);
        $stmt->bindParam(':email', $user->email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $user->password, PDO::PARAM_STR);
        $stmt->execute();
        $this->close_connection($pdo, $stmt);
    }
    
    // ログイン処理をするメソッド
    public function login($email, $password){
        $pdo = $this->get_connection();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email=:email AND password=:password');
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE,'User');
        $user = $stmt->fetch();
        $this->close_connection($pdo, $stmt);

        return $user;
    }
    
    // ユーザの画像をアップロード
    public function upload(){
        if (!empty($_FILES['image']['name'])) {
            $image = uniqid(mt_rand(), true); 
            $image .= '.' . substr(strrchr($_FILES['image']['name'], '.'), 1);
            $file = USER_IMAGE_DIR . $image;
            
            ChromePhp::log($_FILES);
            ChromePhp::log($file);
            ChromePhp::log($_FILES['image']['tmp_name']);
            // uploadディレクトリにファイル保存
            move_uploaded_file($_FILES['image']['tmp_name'], $file);
            
            return $image;
        }else{
            return null;
        }
    }
    
    // 指定された名前でユーザを前方一致検索
    public function name_search($name){
        $pdo = $this->get_connection();
        $pattern = $name . '%';
        $stmt = $pdo->prepare('SELECT * FROM users WHERE name LIKE :name OR nickname LIKE :nickname');
        $stmt->bindParam(':name', $pattern, PDO::PARAM_STR);
        $stmt->bindParam(':nickname', $pattern, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetchALL(PDO::FETCH_ASSOC);
        $this->close_connection($pdo, $stmt);

        return $user;
    }
}
