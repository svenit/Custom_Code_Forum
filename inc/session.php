<?php
  if(empty($_SESSION)){
  	echo "";
  }
  else{
  	$accId = $_SESSION['id'];
  	$accPassword = $_SESSION['password'];
  	$accGet = $connect->query("SELECT * FROM user WHERE id=$accId");
  	$accInfor = $accGet->fetch_object();
  	setcookie('saveID',$accInfor->id,time() + (86400 * 30));
    setcookie('saveUser',$accInfor->username,time() + (86400 *  30));
  	setcookie('savePass',$accPassword,time() + (86400 * 30));
    if($accInfor->status == 1){
      header("Location: ../Forum/logout.php");
    }
  }
?>