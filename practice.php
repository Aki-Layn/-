<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
    
    <?php

    //接続
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

    //テーブル作成
    $sql = "CREATE TABLE IF NOT EXISTS mission5"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
    . "comment TEXT,"
    . "date DATETIME,"
    . "pass char(32)"
	.");";
	$stmt = $pdo->query($sql);

    if(!empty($_POST["name"]) && !empty($_POST["str"]) && empty($_POST["num"]) && !empty($_POST["pass"])){
	    $name = $_POST["name"];
        $comment = $_POST["str"];
        $date =  new DATETIME();
        $date = $date -> format('Y-m-d H:i:s');
        $pass = $_POST["pass"];

        $sql = $pdo -> prepare("INSERT INTO mission5 (name, comment, date, pass) VALUES (:name, :comment,:date,:pass)");
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        $sql -> bindParam(':date', $date, PDO::PARAM_STR);
        $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
	    $sql -> execute();
    }

    else if(!empty($_POST["del"]) && !empty($_POST["del_pass"])){
        $id = $_POST["del"];
        $pass = $_POST["del_pass"];
        $sql = 'SELECT * FROM mission5';
        $stmt = $pdo->query($sql);
        $arrays = $stmt->fetchAll();

        foreach ($arrays as $array){
            if($array['id']==$id){
                $del_pass = $array['pass'];
            }
        }

        //投稿番号のデータがない
        if(empty($del_pass)){
            $no_data ="その番号のデータはありません";
        }
        //パスワード一致してたら削除
        else if($del_pass==$pass){
	        $sql = 'delete from mission5 where id=:id';
	        $stmt = $pdo->prepare($sql);
	        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        }
        else{
            $error_pass = "パスワードが違います";
        }        
        $stmt->execute();
    }

    else if(!empty($_POST["change"]) && !empty($_POST["chpass"])){
        $id = $_POST["change"];
        $pass = $_POST["chpass"];
        $sql = 'SELECT * FROM mission5';
        $stmt = $pdo->query($sql);
        $arrays = $stmt->fetchAll();

        foreach ($arrays as $array){
            if($array['id']==$id){
                $chpass = $array['pass'];
            
                //投稿番号のデータがない
                if(empty($chpass)){
                    $no_data = "その番号のデータはありません";
                }
                else if($chpass==$pass){
                    $data_num = $array['id'];
                    $chname = $array['name'];
                    $chstr = $array['comment'];
                }
                //パスワードが間違っている
                else{
                    $error_pass = "パスワードが違います";
                }
            }   
        }
    }

    else if(!empty($_POST["name"]) && !empty($_POST["str"]) && !empty($_POST["num"]) && !empty($_POST["pass"])){
        $id = $_POST["num"]; 
	    $name = $_POST["name"];
        $comment = $_POST["str"]; 
        $date = new DATETIME();
        $date = $date -> format('Y-m-d H:i:s');
        $pass = $_POST["pass"];
        $sql = 'SELECT * FROM mission5';
        $stmt = $pdo->query($sql);
        $arrays = $stmt->fetchAll();
 
        foreach ($arrays as $array){
            if($array['id']==$id){
                $chpass = $array['pass'];
            }
        }
        
        if($chpass==$pass){//編集したい投稿番号とパスワードが一致している
	        $sql = 'UPDATE mission5 SET name=:name,comment=:comment,date=:date WHERE id=:id';
	        $stmt = $pdo->prepare($sql);
	        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
	        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        }
        else{
            $error_pass = "パスワードが違います";
        }
        $stmt->execute();
    }
    else if(!empty($_POST["name"]) && !empty($_POST["str"]) && empty($_POST["pass"])){
        $null_pass = "パスワード入力してください！！";
    }
    else if(!empty($_POST["del"]) && empty($_POST["del_pass"])){
        $null_pass = "パスワード入力してください！！";
    }
    else if(!empty($_POST["change"]) && empty($_POST["chpass"])){
        $null_pass = "パスワード入力してください！！";
    }  
    ?>
            
    <form action="mission5-1.php" method="post">
        <input type="text" name="name"  placeholder = "名前" value="<?php if(!empty($chname)) {echo $chname;} ?>">
        <input type="text" name="str" placeholder = "コメント" value="<?php if(!empty($chstr)) {echo $chstr;} ?>">
        <input type="hidden" name="num" value="<?php if(!empty($data_num)!=NULL){echo $data_num;} ?>">
        <input type="text" name="pass" placeholder="パスワード">
        <input type="submit" value="送信">
    </form>
    <form action="mission5-1.php" method="post">
        <input type="text" name="del" placeholder = "削除対象番号">
        <input type="text" name="del_pass" placeholder="パスワード">
        <input type="submit" value="削除">
    </form>
    <form action="mission5-1.php" method="post">
        <input type="text" name="change" placeholder="編集対象番号">
        <input type="text" name="chpass" placeholder="パスワード">
        <input type="submit" value="編集">
    </form>

    <?php
        //パスワードが間違っている
        if(!empty($error_pass)){
            echo $error_pass."<br>";
        }
        
        //パスワードが入力されていない
        if(!empty($null_pass)){
            echo $null_pass."<br>";
        }
        
        //データがない
        if(!empty($no_data)){
            echo $no_data."<br>";
        }

       $sql = 'SELECT * FROM mission5';
       $stmt = $pdo->query($sql);
       $arrays = $stmt->fetchAll();
       foreach ($arrays as $array){
           echo $array['id'].$array['name'].$array['comment'].$array['date'].'<br>';
       }   
    ?>
</body>
</html>