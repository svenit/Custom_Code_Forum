<?php
 include('../inc/connect.php');
 session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>HiForum | Cộng đồng chia sẻ</title>
	<link rel="stylesheet" href="../asset/core.css">
	<link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto+Condensed:700|Roboto:400,700&amp;amp;subset=vietnamese" class="next-head"/>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>
	<div id="header">
		<div id="header-feature">
			<li class="logo upper"><a href="../">HiForum</a></li>
			<li class="top-post upper"><a href="">TOP BÀI VIẾT</a></li>
			<li class="category upper"><a href="">DANH MỤC</a></li>
			<li class="creat upper"><a href="">VIẾT BÀI</a></li>
            <li class="search">
               <form method="POST">
            	 <input type="text" name="search-input" placeholder="">
            	 <button type="submit" name="search-button"><i class="ion-ios-search-strong"></i></button>
               </form>
               <?php
                 if(isset($_POST['search-button'])){
                 	$query = addslashes(htmlspecialchars($_POST['search-input']));
                 	header("Location: ../search?query=$query");
                 }
               ?>
            </li>
            <li class="user-account">
            	<?php
            	  if(empty($_SESSION)){
            	  	echo "<a href='' class='login'>ĐĂNG NHẬP</a>";
            	  }
            	  else{
                    ?>
                      <img src="<?php echo $accInfor->avatar; ?>" class='user-avatar'><?php echo $accInfor->fullname; echo $accInfor->role == "Admin" ? " <i class='ion-ios-checkmark verify' title='Administrator'></i>" : ""; ?>
                      <ul class="account-box">
                      	 <img src="<?php echo $accInfor->avatar; ?>" class="lg-avatar">
                      	 <a href=""><li class="account"><i class="ion-person"></i> Cá nhân</li></a>
                      	 <a href=""><li class="security"><i class="ion-ios-locked"></i> Bảo mật</li></a>
                      	 <a href="logout.php"><li class="log-out"><i class="ion-power"></i> Đăng xuất</li></a>
                      </ul>
                    <?php
            	  }
            	?>
            </li>
		</div>
	</div>
	<div id="body">
		<div id="iframe-login">
			<div id="box-news">
					<img src="https://cdn.dribbble.com/users/310943/screenshots/2792692/empty-state-illustrations.gif" alt="" id="bg-login">
				<div id="login-control">
					<form method="POST"><h1 class='label-login'>ĐĂNG NHẬP NGAY</h1>
		  <input type="text" placeholder="Tài khoản" name="username" value="<?php if(empty($_COOKIE['saveUser'])){echo "";}else{echo $_COOKIE['saveUser'];} ?>">
		  <input type="password" placeholder="Mật khẩu" name="password" value="<?php if(empty($_COOKIE['savePass'])){echo "";}else{echo $_COOKIE['savePass'];} ?>">
		  <button type="submit" name="login">ĐĂNG NHẬP</button>
	   </form>
	   <p class='rcm-regist'>Bạn chưa có tài khoản ? <a href=''>Đăng kí ngay</a></p>
	   <?php
	  if(isset($_COOKIE['saveUser']) && isset($_COOKIE['savePass'])){
	  	$loguser = addslashes($_COOKIE['saveUser']);
	  	$logpass = addslashes(md5($_COOKIE['savePass']));
	  	$sql = $connect->query("SELECT * FROM user WHERE username='{$loguser}' AND password='{$logpass}'");
	  	if($sql->num_rows == 1){
	  		$infor = $sql->fetch_object();
	  		if($infor->status == 1){
	  			echo "<p class='login-error'>Tài khoản của bạn đã bị khóa do vi phạm một số điều khoản của chúng tôi</p>";
	  			session_destroy();
                unset($_COOKIE['saveUser']);
                unset($_COOKIE['savePass']);
                setcookie('saveUser','',time() + 0 );
                setcookie('savePass','',time() + 0 );
	  		}
	  		else{
	  			$_SESSION['id'] = $infor->id;
	  		    $_SESSION['password'] = $_POST['password'];
	  		    header('Location: /Forum');
	  		}
	  	}
	  	else{
	  		echo "<p class='login-error'>Sai tài khoản hoặc mật khẩu</p>";
	  	}
	  }
	  else{
	  	 if(isset($_POST['login'])){
	  	 	$loguser = addslashes($_POST['username']);
	  	    $logpass = addslashes(md5($_POST['password']));
	  	    $sql = $connect->query("SELECT * FROM user WHERE username='{$loguser}' AND password='{$logpass}'");
	  	    if($sql->num_rows == 1){
	  		  $infor = $sql->fetch_object();
	  		  if($infor->status == 1){
	  		  	echo "<p class='login-error'>Tài khoản của bạn đã bị khóa do vi phạm một số điều khoản của chúng tôi</p>";
	  		    session_destroy();
                unset($_COOKIE['saveUser']);
                unset($_COOKIE['savePass']);
                setcookie('saveUser','',time() + 0 );
                setcookie('savePass','',time() + 0 );
	  		  }
	  		   else{
	  			$_SESSION['id'] = $infor->id;
	  		    $_SESSION['password'] = $_POST['password'];
	  		    header('Location: /Forum');
	  		   }
	  	     }
	  	   else{
	  		  echo "<p class='login-error'>Sai tài khoản hoặc mật khẩu</p>";
	  	    }
	  	 }
	  }
	?>
	</div>
			</div>
	</div>
</div>
</body>
</html>
