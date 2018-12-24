<?
   session_start();
   session_unset();
   session_destroy();
   if(isset($_GET['action']) and ($_GET['action']=='close_win')){
      $echo_str  = "<html>\n<meta http-equiv=\"Content-Type\" content=\"text/html; Charset=Big5\">\n";
      $echo_str .= "<META HTTP-EQUIV=REFRESH CONTENT='3;URL=\"javascript:window.close();\"'>\n";
      $echo_str .= "<body><center><font color=red size=+2>".(isset($_GET['msg'])?$_GET['msg']:'')."</font><br><br>--- 本視窗 3 秒鐘後會自動關閉 ---</center></body>";
      echo $echo_str;
      exit;
   }
   header("Location: index.php ");
   exit();
?>