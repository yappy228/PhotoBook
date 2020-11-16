<?php
// 外部ファイルの読み込み
require_once '../config/Const.php';
require_once '../models/Follow.php';

class FollowDAO{
    
    public function get_connection(){
        $pdo = new PDO(DSN, DB_USERNAME, DB_PASSWORD);
        return $pdo;
    }
    
    public function close_connection($pdo, $stmt){
        $pdo = null;
        $stmt = null;
    }
    
    // 全テーブル情報を取得するメソッド
    public function get_all_follows(){
        $pdo = $this->get_connection();
        $stmt = $pdo->query('SELECT * FROM follow ORDER BY id ASC');
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Follow');
        $follows = $stmt->fetchAll();
        $this->close_connection($pdo, $stmt);

        return $follows;
    }
    
    // フォローしている人を基にデータを取得するメソッド
    public function get_follow_by_id($follow_user_id){
        $pdo = $this->get_connection();
        $stmt = $pdo->prepare('SELECT * FROM follow WHERE follow_user_id = :follow_user_id');
        $stmt->bindParam(':follow_user_id', $follow_user_id, PDO::PARAM_INT);
        $boolean = $stmt->execute();
        $follows = $stmt->fetchALL(PDO::FETCH_ASSOC);
        $this->close_connection($pdo, $stmt);

        return $follows;
    }
    
    // フォローされている人を基にデータを取得するメソッド
    public function get_followed_by_id($followed_user_id){
        $pdo = $this->get_connection();
        $stmt = $pdo->prepare('SELECT * FROM follow WHERE followed_user_id = :followed_user_id');
        $stmt->bindParam(':followed_user_id', $followed_user_id, PDO::PARAM_INT);
        $boolean = $stmt->execute();
        $follows = $stmt->fetchALL(PDO::FETCH_ASSOC);
        $this->close_connection($pdo, $stmt);

        return $follows;
    }
    
    // AがBをフォローしているかメソッド
    public function get_follow_login_by_id($follow_user_id , $followed_user_id){
        $pdo = $this->get_connection();
        $stmt = $pdo->prepare('SELECT * FROM follow WHERE follow_user_id = :follow_user_id and followed_user_id = :followed_user_id');
        $stmt->bindParam(':follow_user_id', $follow_user_id, PDO::PARAM_INT);
        $stmt->bindParam(':followed_user_id', $followed_user_id, PDO::PARAM_INT);
        $boolean = $stmt->execute();
        $follows = $stmt->fetchALL(PDO::FETCH_ASSOC);
        $this->close_connection($pdo, $stmt);

        return $follows;
    }
    
    // データを1件登録するメソッド
    public function insert($follow_user_id,$followed_user_id){
        $pdo = $this->get_connection();
        $stmt = $pdo -> prepare("INSERT INTO follow(follow_user_id,followed_user_id) values(:follow_user_id,:followed_user_id)");

        $stmt->bindParam(':follow_user_id', $follow_user_id, PDO::PARAM_INT);
        $stmt->bindParam(':followed_user_id', $followed_user_id, PDO::PARAM_INT);

        $boolean = $stmt->execute();
        
        $this->close_connection($pdo, $stmt);
    }
    
    // データを削除するメソッド
    public function delete($follow_user_id,$followed_user_id){
        $pdo = $this->get_connection();
        
        $stmt = $pdo->prepare('DELETE FROM follow WHERE follow_user_id = :follow_user_id and followed_user_id = :followed_user_id');
        $stmt->bindParam(':follow_user_id', $follow_user_id, PDO::PARAM_INT);
        $stmt->bindParam(':followed_user_id', $followed_user_id, PDO::PARAM_INT);
        
        $stmt->execute();
        $this->close_connection($pdo, $stmt);
        
        return;
    }
}
