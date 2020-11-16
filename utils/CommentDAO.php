<?php
// 外部ファイルの読み込み
require_once '../config/Const.php';
require_once '../models/Comment.php';

// データベースとやり取りを行う便利なクラス
class CommentDAO{
    
    // データベースと接続を行うメソッド
    public function get_connection(){
        $pdo = new PDO(DSN, DB_USERNAME, DB_PASSWORD);
        return $pdo;
    }
    
    // データベースとの切断を行うメソッド
    public function close_connection($pdo, $stmt){
        $pdo = null;
        $stmt = null;
    }
    
    // post_idを指定して、全テーブル情報を取得するメソッド
    public function get_all_comments_by_post_id($post_id){
        $pdo = $this->get_connection();
        
        $stmt = $pdo->prepare("SELECT * FROM comments WHERE post_id=:post_id order by created_at ASC");

        $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
        $stmt->execute();
        
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $this->close_connection($pdo, $stmt);
        
        // コメントの多次元配列を返す
        return $comments;
    }
    
    // データを1件登録するメソッド
    public function insert($comment){
        $pdo = $this->get_connection();
        $stmt = $pdo -> prepare("INSERT INTO comments (user_id, post_id, content) VALUES (:user_id, :post_id, :content)");

        // バインド処理
        $stmt->bindParam(':user_id', $comment->user_id, PDO::PARAM_INT);
        $stmt->bindParam(':post_id', $comment->post_id, PDO::PARAM_INT);
        $stmt->bindParam(':content', $comment->content, PDO::PARAM_STR);

        $stmt->execute();
        
        $this->close_connection($pdo, $stmt);
    }
}
