<?php
  include('inc/connect.php');
  session_start();
  switch ($_GET['action']) {
  	case 'delete':
  		if(isset($_GET['id'])){
  			$id = $_GET['id'];
  			$result = $connect->query(" DELETE FROM table_baiviet WHERE id=$id ");
  			header("Location: " . $_SERVER[ 'HTTP_REFERER' ]);
  		}
  		break;
    case 'like':
  			if(empty($_SESSION)){
          die();
        }
        else{
          $id = $_GET['id'];
          $accId = $_SESSION['id'];
          $check = $connect->query("SELECT * FROM list_like WHERE postID=$id AND userID=$accId");
          if($check->num_rows == 1){
              die();
          }
          else{
             $log = $connect->query("INSERT INTO list_like(postID,userID) VALUES($id,$accId)");
             $result = $connect->query(" SELECT * FROM table_baiviet WHERE id=$id ");
             $rows = $result->fetch_object();
             $like = $rows->likes;
             $addLike = $connect->query(" UPDATE table_baiviet SET likes=$like+1 WHERE id=$id");
          }
        }
  		break;
      case 'comment':
         if(empty($_SESSION)){
           die();
         }
         else{
           if(trim($text)){
             die();
           }
           else{
             $userID = $_GET['userID'];
             if($_GET['userID'] !== $_SESSION['id']){
                die();
             }
             else{
               $text = htmlspecialchars(addslashes($_GET['text']));
               $postid = $_GET['postId'];
               $addComment = $connect->query("INSERT INTO comment(postid,content,time,userId) VALUES($postid,'{$text}','{$time}',$userID) ");
              }
            }
         }
      break;
  }
?>