<?php
  session_start();
  error_reporting(0);
  include('inc/connect.php');
  include('inc/session.php');
  /* News */
  $newNews = $connect->query("SELECT * FROM table_baiviet");
  /* Set page */
  $limit = 5;
  $countPosts = $newNews->num_rows;
  $each_page = ceil($countPosts/$limit);
  $page = isset($_GET['page']) ? $_GET['page'] : 1;
  if($page < 1){
  	$page = 1;
  }
  elseif($page > $each_page){
  	$page = $each_page;
  }
  $start = ($page - 1)*$limit;
  $arrpost = $connect->query("SELECT * FROM table_baiviet ORDER BY id DESC LIMIT $start,$limit");
  
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>HiForum | Cộng đồng chia sẻ</title>
	<link rel="stylesheet" href="asset/core.css">
	<link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto+Condensed:700|Roboto:400,700&amp;amp;subset=vietnamese" class="next-head"/>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.12/css/all.css" integrity="sha384-G0fIWCsCzJIMAVNQPfjH08cyYaUtMwjJwqiRKxxE/rx96Uroj1BtIQ6MLJuheaO9" crossorigin="anonymous">
</head>
<body>
	<div id="header">
		<div id="header-feature">
			<li class="logo upper"><a href="">HiForum</a></li>
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
                 	header("Location: search?query=$query");
                 }
               ?>
            </li>
            <li class="user-account">
            	<?php
            	  if(empty($_SESSION)){
            	  	echo "<a href='login' class='login'>ĐĂNG NHẬP</a>";
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
		<!-- Colum right -->
		<div id="col-left">
			<div id="direct">
				<a href=""><span class="direct-object upper">Trang chủ</span></a>
			</div>
			<div id="box-news">
				<?php
				  while($rows = $arrpost->fetch_object()){
				  	?>
				  	 <div id="box-container">
				  	  <div id="user-avatar">
				  	  	 <?php
				  	  	   $time_ago = timeAgo($rows->date);
				  	  	   $userID = $rows->userID;
				  	  	   $IdPost = $connect->query("SELECT * FROM user WHERE id=$userID");
				  	  	   $inforUser = mysqli_fetch_array($IdPost);
				  	  	 ?>
				  	  	 <img src="<?php echo $inforUser['avatar']; ?>" class="avatar">
				  	  	 <p class="fullname <?php echo $inforUser['status'] == 1 ? 'ban' : ''; ?>">
				  	  	 	<?php 
				  	  	 	  echo $inforUser['fullname'];
				  	  	 	  echo $inforUser['role'] == "Admin" ? " <i class='ion-ios-checkmark verify' title='Administrator'></i>" : "";
				  	  	 	  echo $inforUser['status'] == 1 ? " <i class='fas fa-ban banned' title='Tài khoản này đã bị khóa'></i>" : "";
				  	  	 	?>
				  	  	 </p>				  	  	 	
				  	  	 <p class="timepost"><?php echo $time_ago; ?></p>
				  	  </div>
				  	  <a href="posts?post_id=<?php echo $rows->id; ?>" id="di-link"><div id="title">
				  	  	<p><?php echo $rows->title; ?></p>
				  	  </div>
				  	  <div id="loading-img">
				  	  	<img src="https://blog.marvelapp.com/wp-content/uploads/2016/11/Facebook-loading.gif" width="100%" height="100%" style="">
				  	  </div>
				  	  <!--<div id="thumnail">
				  	  	<?php
				  	  	   //echo $rows->image == "" ? "" : "<img src='$rows->image'>";
				  	  	?>
				  	  </div>--></a>
				  	  <div id="social-button">
				  	  	<?php
				  	  	   if(!empty($_SESSION)){
				  	  	   	  $checklike = $connect->query("SELECT * FROM list_like WHERE userID=$accId AND postId=$rows->id");
				  	  	      $num = $checklike->num_rows;
				  	  	   }
				  	  	   else{
				  	  	   	  echo "";
				  	  	   }
				  	    ?>
				  	  	<li id="like">
				  	  		<button type="submit" onclick="like(this)" name="like-button" id="like-btn" value="<?php echo $rows->id ?>" class="<?php if($num == 1){echo "liked";}else{echo "";} ?>"><?php echo $rows->likes ?></button><i class="ion-heart"></i>
				  	  	</li>
				  	  	<li id="comment">
				  	  		<?php
				  	  		    $comment = $connect->query("SELECT * FROM comment WHERE postid=$rows->id");
				  	  		?>
				  	  		<a href="posts?post_id=<?php echo $rows->id; ?>#comment-box"><?php echo $comment->num_rows; ?> <i class="ion-chatbox-working"></i></a>
				  	  	</li>
				  	  </div>
				  	 </div>
				  	<?php
				  }
				?>
				<div id="pagenation">
		           <?php 
                     for($i = 1;$i <= $each_page;$i++){
                        if($i > $page + 2  || $i < $page - 2 ){
                           echo "";
                        }
                        else{
                           ?>
                              <li><a href="?page=<?php echo $i ?>" class="<?php if($i == $page){echo 'thispage';} ?>"><?php echo $i ?></a></li>
                           <?php
                         }
                      }
	              ?>
	   </div>
			</div>
		</div>
		<!-- Colum right -->
		<div id="col-right">
			<div id="feature-box">
				<div id="random-post">
					<h3>BÀI VIẾT MỚI</h3>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
<script>
	$(document).ready(function(){
		$('div').find('#loading-img').fadeOut('slow');
	});
	function like(element){
		$id = element.getAttribute('value');
        if($('a').hasClass('login')){
        	alert('Bạn phải đăng nhập để sử dụng chức năng này !');
        	removeClass('liked');
        }
        else{
            $like = $(element).html();
		    $addLike = $like - 1 + 2;
        }
        if($(element).hasClass('liked')){
           alert('Bạn đã Like rồi');
           return false;
        }
		$(element).addClass('liked');
		$.ajax({
           type: "GET",
           url:"action.php",
           data: "action=like&id="+$id,
           success: function(response){
           	  $(element).html($addLike).fadeIn();
           },
           error:function(response){
           	  alert("Lỗi");
           }
		});
	}
</script>
