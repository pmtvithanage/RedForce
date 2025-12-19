<?php 
  // time zone
  date_default_timezone_set('Asia/Colombo');

  function time_convert($time){
    $time = strtotime($time);
    $current_time = time();
    $time_difference = $current_time - $time;

    $seconds = $time_difference;
    $minutes = round($seconds / 60);
    $hours = round($seconds / 3600);
    $days = round($seconds / 86400);
    $weeks = round($seconds / 604800);
    $months = round($seconds / 2629440);
    $years = round($seconds / 31553280);

    if($seconds <= 60){
      return "Just Now";
    }elseif($minutes <= 60){
      if($minutes == 1){
        return "1 minute ago";
      }else{
        return "$minutes minutes ago";
      }
    }elseif($hours <= 24){
      if($hours == 1){
        return "1 hour ago";
      }else{
        return "$hours hours ago";
      }
    }elseif($days <= 7){
      if($days == 1){
        return "yesterday";
      }else{
        return "$days days ago";
      }
    }elseif($weeks <= 4.3){
      if($weeks == 1){
        return "1 week ago";
      }else{
        return "$weeks weeks ago";
      }
    }elseif($months <= 12){
      if($months == 1){
        return "1 month ago";
      }else{
        return "$months months ago";
      }
    }else{
      if($years == 1){
        return "1 year ago";
      }else{
        return "$years years ago";
      }
    }
  }
?>