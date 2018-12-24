<?
/*
   檢測是否為合法使用者，請搭配 login.php 使用
*/
session_start();
if (!isset($_SESSION['username']) or !isset($_SESSION['password']) or !isset($_SESSION['super'])) {
   $echo_str = "<html><head>\n";
   $echo_str .= "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=login.php?id_cate=".$_GET['id_cate']."'>\n";
   $echo_str .= "</head><body>\n";
//   $echo_str .= "<center><font color=red size=5><b>◣本網頁須經驗證程序，請稍候...！◢</b></font></center>\n";
   $echo_str .= "</body></html>\n";
   echo $echo_str;
   exit();
}
?>