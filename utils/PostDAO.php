<?php
// 外部ファイルの読み込み
require_once '../config/Const.php';
require_once '../models/Post.php';

// データベースとやり取りを行う便利なクラス
class PostDAO{
    
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
    
    // 全テーブル情報を取得するメソッド
    public function get_all_posts(){
        $pdo = $this->get_connection();
        $stmt = $pdo->query('SELECT * FROM posts ORDER BY id DESC');
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'post');
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->close_connection($pdo, $stmt);

        return $posts;
    }
    
    // 単一のid値(post)からpostインスタンスを抜き出すメソッド
    public function get_post_by_id($id){
        $pdo = $this->get_connection();
        $stmt = $pdo->prepare('SELECT * FROM posts WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'post');
        $post = $stmt->fetch();
        $this->close_connection($pdo, $stmt);

        return $post;
    }
    
    // user_id値からpostのリストを抜き出すメソッド
    public function get_postlist_by_userid($user_id){
        $pdo = $this->get_connection();
        $stmt = $pdo->prepare('SELECT * FROM posts WHERE user_id = :user_id');
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $post = $stmt->fetchALL(PDO::FETCH_ASSOC);
        $this->close_connection($pdo, $stmt);

        return $post;
    }
    
    // 複数のid値($follows)からデータを抜き出すメソッド
    public function get_post_by_array_id($follows){
        $pdo = $this->get_connection();
        $sql_string = 'SELECT * FROM posts WHERE user_id in (';
        
        foreach($follows as $follow){
            if($follow === end($follows)){
                $sql_string .= $follow['followed_user_id'];
                $sql_string .= ')';
            }else{
                $sql_string .= $follow['followed_user_id'];
                $sql_string .= ',';
            }
        }
        $stmt = $pdo->prepare($sql_string);
        $stmt->execute();
        $posts = $stmt->fetchALL(PDO::FETCH_ASSOC);
        $this->close_connection($pdo, $stmt);
        
        // 投稿クラスのインスタンスを返す
        return $posts;
    }
    
    // 画像ファイル名を取得するメソッド（uploadフォルダ内のファイルを物理削除するため）
    public function get_image_name_by_id($id){
        $pdo = $this->get_connection();
        $stmt = $pdo->prepare('SELECT * FROM post WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Post');
        $post = $stmt->fetch();

        $this->close_connection($pdo, $stmt);
        
        return $post->image;
    }
    
    // データを1件登録するメソッド
    public function insert($post){
        $pdo = $this->get_connection();
        $sql_string_before = "INSERT INTO posts (user_id, body, image1";
        $sql_string_after = "values(:user_id, :body, :image1";
        if(!is_null($post->image2)){
            $sql_string_before .= ", image2";
            $sql_string_after .= ", :image2";
        }
        if(!is_null($post->image3)){
            $sql_string_before .= ", image3";
            $sql_string_after .= ", :image3";
        }
        if(!is_null($post->image4)){
            $sql_string_before .= ", image4";
            $sql_string_after .= ", :image4";
        }
        if(!is_null($post->image5)){
            $sql_string_before .= ", image5";
            $sql_string_after .= ", :image5";
        }
        $sql_string_before .= ")";
        $sql_string_after .= ")";
        $sql_string = $sql_string_before . $sql_string_after;
        $stmt = $pdo -> prepare($sql_string);
        // バインド処理
        $stmt->bindParam(':user_id', $post->user_id, PDO::PARAM_INT);
        $stmt->bindParam(':body', $post->body, PDO::PARAM_STR);
        $stmt->bindParam(':image1', $post->image1, PDO::PARAM_STR);
        if(!is_null($post->image2)){
            $stmt->bindParam(':image2', $post->image2, PDO::PARAM_STR);    
        }
        if(!is_null($post->image3)){
            $stmt->bindParam(':image3', $post->image2, PDO::PARAM_STR);    
        }
        if(!is_null($post->image4)){
            $stmt->bindParam(':image4', $post->image2, PDO::PARAM_STR);    
        }
        if(!is_null($post->image5)){
            $stmt->bindParam(':image5', $post->image2, PDO::PARAM_STR);    
        }
        $stmt->execute();
        $this->close_connection($pdo, $stmt);
    }
    
    
    // データを更新するメソッド
    public function update($id, $post){
        $pdo = $this->get_connection();
        $image = $this->get_image_name_by_id($id);
        $stmt = $pdo->prepare('UPDATE posts SET body=:body, image=:image WHERE id = :id');

        $stmt->bindParam(':body', $post->body, PDO::PARAM_STR);
        $stmt->bindParam(':image', $post->image, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        $stmt->execute();
        $this->close_connection($pdo, $stmt);
        
        // 画像の物理削除
        if($image !== $post->image){
            unlink(POST_IMAGE_DIR . $image);
        }
    }
    
    // データを削除するメソッド
    public function delete($id){
        $pdo = $this->get_connection();
        $image = $this->get_image_name_by_id($id);
        
        $stmt = $pdo->prepare('DELETE FROM posts WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        $stmt->execute();
        $this->close_connection($pdo, $stmt);
        
        unlink(POST_IMAGE_DIR . $image);

    }
    
    // ファイルをアップロードするメソッド
    public function upload(){
        // ファイルを選択していれば
        if (!empty($_FILES['image']['name'])) {
            // ファイル名をユニーク化
            $image = uniqid(mt_rand(), true); 
            // アップロードされたファイルの拡張子を取得
            $image .= '.' . substr(strrchr($_FILES['image']['name'], '.'), 1);
            $file = POST_IMAGE_DIR . $image;
            
            ChromePhp::log($_FILES);
            ChromePhp::log($_FILES['image']['name']);
            ChromePhp::log($_FILES['image']['tmp_name']);
            // uploadディレクトリにファイル保存
            move_uploaded_file($_FILES['image']['tmp_name'], $file);
            
            return $image;
        }else{
            return null;
        }
    }
}
