<?
/*
   �˴��O�_���X�k�ϥΪ̡A�зf�t login.php �ϥ�
*/
session_start();
if (!isset($_SESSION['username']) or !isset($_SESSION['password']) or !isset($_SESSION['super'])) {
   $echo_str = "<html><head>\n";
   $echo_str .= "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=login.php?id_cate=".$_GET['id_cate']."'>\n";
   $echo_str .= "</head><body>\n";
//   $echo_str .= "<center><font color=red size=5><b>�����������g���ҵ{�ǡA�еy��...�I��</b></font></center>\n";
   $echo_str .= "</body></html>\n";
   echo $echo_str;
   exit();
}
?>