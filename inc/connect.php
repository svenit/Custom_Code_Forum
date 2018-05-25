<?php

 $servername = "localhost";
 $userName = "root";
 $passWord = "";
 $database = "thaithuandb";

 $connect = new mysqli($servername,$userName,$passWord,$database);

 if($connect->connect_error){
 	die("Can not connect to Database" . $connect->connect_error);
 } 
 else{
 	$connect->set_charset('utf8');
 }
 /* Date */
  date_default_timezone_set('Asia/Ho_Chi_Minh');
  $second = strftime('%S');
  $minute = strftime('%M');
  $hour = strftime('%H');
  $day = date('d');
  $month = date('m');
  $year = date('y');
  $date_join = "$day/$month/$year";
  $time = "$year-$month-$day $hour:$minute:$second";
 /* Time ago */
 function timeAgo($time_ago)
{
    $time_ago = strtotime($time_ago);
    $cur_time   = time();
    $time_elapsed   = $cur_time - $time_ago;
    $seconds    = $time_elapsed ;
    $minutes    = round($time_elapsed / 60 );
    $hours      = round($time_elapsed / 3600);
    $days       = round($time_elapsed / 86400 );
    $weeks      = round($time_elapsed / 604800);
    $months     = round($time_elapsed / 2600640 );
    $years      = round($time_elapsed / 31207680 );
    if($seconds <= 60){
        return "Vừa xong";
    }
    else if($minutes <=60){
        if($minutes==1){
            return "1 phút trước";
        }
        else{
            return "$minutes phút trước";
        }
    }
    else if($hours <=24){
        if($hours==1){
            return "1 giờ trước";
        }else{
            return "$hours giờ trước";
        }
    }
    else if($days <= 7){
        if($days==1){
            return "Hôm qua";
        }else{
            return "$days ngày trước";
        }
    }
    else if($weeks <= 4.3){
        if($weeks==1){
            return "1 tuần trước";
        }else{
            return "$weeks tuần trước";
        }
    }
    else if($months <=12){
        if($months==1){
            return "1 tháng trước";
        }else{
            return "$months tháng trước";
        }
    }
    else{
        if($years==1){
            return "1 năm trước";
        }else{
            return "$years năm trước";
        }
    }
}
?>