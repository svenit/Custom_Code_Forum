<?php
  session_start();
  include('../inc/connect.php');
  include('../inc/session.php');
  if(isset($_GET['post_id'])){
  	$postid = (int)htmlspecialchars($_GET['post_id']);
  	$sql = $connect->query("SELECT * FROM table_baiviet WHERE id=$postid");
  	$num = $sql->num_rows;
  	if($num == 0){
  		header('Location: 404');
  	}
  	$rows= $sql->fetch_object();
  }
  else{
  	   header('Location: 404');
  }
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
	 <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.2.min.js"></script>
	  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.12/css/all.css" integrity="sha384-G0fIWCsCzJIMAVNQPfjH08cyYaUtMwjJwqiRKxxE/rx96Uroj1BtIQ6MLJuheaO9" crossorigin="anonymous">
</head>
<body>
	<div id="header">
		<div id="header-feature">
			<li class="logo upper"><a href="../index.php">HIFORUM</a></li>
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
            	  	echo "<a href='../login' class='login'>ĐĂNG NHẬP</a>";
            	  }
            	  else{
                    ?>
                      <img src="<?php echo $accInfor->avatar; ?>" class='user-avatar'><?php echo $accInfor->fullname;echo $accInfor->role == "Admin" ? " <i class='ion-ios-checkmark verify' title='Administrator'></i>" : ""; ?>
                      <ul class="account-box">
                      	 <img src="<?php echo $accInfor->avatar; ?>" class="lg-avatar">
                      	 <a href=""><li class="account"><i class="ion-person"></i> Cá nhân</li></a>
                      	 <a href=""><li class="security"><i class="ion-ios-locked"></i> Bảo mật</li></a>
                      	 <a href="../logout.php"><li class="log-out"><i class="ion-power"></i> Đăng xuất</li></a>
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
				<a href="../index.php"><span class="direct-object upper">Trang chủ</span></a>
				<span class="direct-object upper"><i class="ion-ios-arrow-right"></i></span>
				<a href=""><span class="direct-object upper"><?php echo $rows->title ?></span></a>
			</div>
			<div id="box-news">
				<div id="box-container">
					<div id="author">
			            <?php
				  	  	   $userID = $rows->userID;
				  	  	   $IdPost = $connect->query("SELECT * FROM user WHERE id=$userID");
				  	  	   $inforUser = mysqli_fetch_array($IdPost);
				  	  	   $time_ago = timeAgo($rows->date);
				  	  	 ?>
						<img src="<?php echo $inforUser['avatar']; ?>" class="avatar">
						<p class="fullname <?php echo $inforUser['status'] == 1 ? 'ban' : ''; ?>">
							<?php 
							echo $inforUser['fullname']; 
							echo $inforUser['role'] == "Admin" ? " <i class='ion-ios-checkmark verify' title='Administrator'></i>" : ""; 
							echo $inforUser['status'] == 1 ? " <i class='fas fa-ban banned' title='Tài khoản này đã bị khóa'></i>" : "";?>
					    </p>
					    <p class="timepost"><?php echo $time_ago ?></p>
					</div>
					<div id="main-container">
					  <?php echo $rows->content ?>
					</div>
				</div>
				<div id="box-container">
					<?php 
					   if(empty($_SESSION)){
					   	  echo "<p class='require-login'><a href='../login'>ĐĂNG NHẬP ĐỂ BÌNH LUẬN</a></p>";
					   }
					   else{
					   	  ?>
					   	    <div id='comment-area'>
					   	    	<img src="<?php echo $accInfor->avatar ?>" class="avatar-cmt"><input name='comment' placeholder='Thêm bình luận' class="comment-text"></input><button type="submit" name="comment-btn" onclick="addComment(this)" id="comment-btn"><i class="ion-android-send"></i></button>
					   	    </div>
					   	  <?php
					   }
					?>
					<div id="comment-box">
				  	  <?php
				  	     $comment = $connect->query("SELECT * FROM comment WHERE postid=$postid ORDER BY id DESC ");
				  	     $limitComment = $connect->query("SELECT * FROM comment WHERE postid=$postid ORDER BY id DESC LIMIT 5");
				  	     $countComment = $comment->num_rows;
				  	     if($countComment == 0){
				  	     	echo "<p class='none-cmt'>Chưa có bình luận nào về bài viết này :(</p>";
				  	     }
				  	     else{
				  	     	if(isset($_GET['view_comment'])){
				  	     		$view_comment = $_GET['view_comment'];
				  	     		while($rows = $comment->fetch_object()){
				  	               $userIdCmt = $connect->query("SELECT * FROM user WHERE id=$rows->userID");
				  	               $userCmtInfor = $userIdCmt->fetch_object();
				  	     	     ?>
				  	     	       <div id="comment-list">
				  	     	   	      <img src="<?php echo $userCmtInfor->avatar ?>" class="avatar-cmted <?php if($userID == $userCmtInfor->id){echo "postauthor";}else{echo "";} ?>"><div class="hl-cmt"><span class="fullname"><?php echo $userCmtInfor->fullname; echo $userCmtInfor->role == "Admin" ? " <i class='ion-ios-checkmark verify' title='Administrator'></i>" : "";?></span><?php echo "<span class='timepost'>" . $time_ago = timeAgo($rows->time) . "</span>"; ?> <p class="text-comment">
				  	     	   	  	 <?php echo $rows->content ?>
				  	     	   	     </p></div>
				  	     	       </div>
				  	     	     <?php
				  	            }
				  	     	}
				  	     	else{
				  	     		while($rows = $limitComment->fetch_object()){
				  	              $userIdCmt = $connect->query("SELECT * FROM user WHERE id=$rows->userID");
				  	              $userCmtInfor = $userIdCmt->fetch_object();
				  	     	     ?>
				  	     	       <div id="comment-list">
				  	     	   	      <img src="<?php echo $userCmtInfor->avatar ?>" class="avatar-cmted <?php if($userID == $userCmtInfor->id){echo "postauthor";}else{echo "";} ?>"><div class="hl-cmt"><span class="fullname"><?php echo $userCmtInfor->fullname; echo $userCmtInfor->role == "Admin" ? " <i class='ion-ios-checkmark verify' title='Administrator'></i>" : "";?></span><?php echo "<span class='timepost'>" . $time_ago = timeAgo($rows->time) . "</span>"; ?> <p class="text-comment">
				  	     	   	  	  <?php echo $rows->content ?>
				  	     	   	      </p></div>
				  	     	       </div>
				  	     	    <?php
				  	          }
				  	        }
				  	         if($countComment > 5 && empty($view_comment)){
				  	     	    echo "<a href='?post_id=$postid&view_comment=all#comment-list' class='none-de'><p class='load-more-cmt'>XEM THÊM</p></a>";
				  	         }
				  	         if(isset($view_comment)){
				  	         	echo "<a href='?post_id=$postid' class='none-de'><p class='load-less-cmt'>THU GỌN</p></a>";
				  	         }
				  	    }
				  	  ?>
				  </div>
				</div>
			</div>
				</div>
		<!-- Colum right -->
		<div id="col-right">
			<div id="feature-box">
			</div>
		</div>
	</div>
	<div class="page-loader">
       <div class="loader"></div>
    </div>
</body>
    <script>
    	function addComment(el){
    		$id = <?php if(empty($_SESSION)){echo "";}else{echo $accInfor->id;} ?>;
    		$comment = $('.comment-text').val();
    		if(!$comment.trim()){
    			alert("Không được để trống !");
    			return false;
    		}
    		if($('#comment-btn').hasClass('disable')){alert("Chờ đợi là hạnh phúc :)) ");return false;}
    	    $('#comment-btn').addClass('disable');
    	    setTimeout(function(){$('#comment-btn').removeClass('disable');},10000);
    	    $('.page-loader').fadeIn('fast');
    		$.ajax({
    			type: "GET",
    			url: "/Forum/action.php",
    			data: "action=comment&postId=<?php echo $postid; ?>&text="+$comment+"&userID="+$id,
    			success:function(){
    				$('.page-loader').fadeOut('slow');
    				$('.comment-text').val('');
    				<?php if(!empty($_SESSION)){
    					?>  
    					   $('#comment-box').prepend("<div id='comment-list'><img src='<?php echo $accInfor->avatar ?>' class='avatar-cmted'><div class='hl-cmt'><span class='fullname'><?php echo $accInfor->fullname;echo $accInfor->role == 'Admin' ? " <i class='ion-ios-checkmark verify' title='Administrator'></i>" : ""; ?></span><span class='timepost'> Vừa xong</span><p class='text-comment'>" + $comment + "</p></div></div>");
    					   $('.none-cmt').hide();
    					<?php
    				} ?>
    			},
                error:function(){
                	alert('Error !');
                	$('.page-loader').fadeOut('slow');
                }
    		})
    	}
    </script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/codemirror.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/mode/xml/xml.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.7.6/js/froala_editor.pkgd.min.js"></script>
</html>