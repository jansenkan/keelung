<?
#---------------------------------------------------
#   新增投開票所主程式
#   by jansen since 2003.03.16
#---------------------------------------------------
include "config.inc.php";
session_id()?'':session_start();
/* 非系統管理者，請改道！ */
if($_SESSION['root']<>"sys_player"){
   header("location:index.php");
   exit;
}
/* 變數初值設定 */
$ps_no    = isset($_POST['ps_no'])?$_POST['ps_no']:'';
$ps_name  = isset($_POST['ps_name'])?$_POST['ps_name']:'';
$voters   = isset($_POST['voters'])?$_POST['voters']:'';
$ps_user  = isset($_POST['ps_user'])?$_POST['ps_user']:'';
$password = isset($_POST['password'])?$_POST['password']:'';
$ps_ip    = isset($_POST['ps_ip'])?$_POST['ps_ip']:'';
$_lock    = isset($_POST['_lock'])?$_POST['_lock']:'';
$remark   = isset($_POST['remark'])?$_POST['remark']:'';
$action   = isset($_GET['action'])?$_GET['action']:'';
$action   = isset($_POST['action'])?$_POST['action']:$action;
$confirm  = isset($_GET['confirm']) ?$_GET['confirm'] :'';
$alert_bg_clr    ="yellow";

if($Lock){
   echo "<center><font color=red size=5>抱歉！本功能已遭系統鎖定，須解鎖後才能使用！</font></center>";
   exit;
}
// 全上鎖   
if($action=="all_lock"){
   $sql_update = "update $P_S_tbl set _lock='1'";
   mysql_query($sql_update);
   header("location:".$_SERVER['PHP_SELF']);
   exit;

// 全解鎖
}elseif($action=="all_unlock"){
   $sql_update = "update $P_S_tbl set _lock=''";
   mysql_query($sql_update);
   header("location:".$_SERVER['PHP_SELF']);
   exit;

}elseif($action=="insert" and !empty($ps_name)){

   /* 新增投開票所 */
   $sql_insert = "insert into $P_S_tbl (ps_no,ps_name,voters,ps_user,password,ps_ip,_lock,remark) values ('".($ps_no*1)."','$ps_name','".($voters*1)."','$ps_user','$password','$ps_ip','$_lock','$remark')";
   mysql_query($sql_insert);
   header("location:".$_SERVER['PHP_SELF']);
   exit;
   
}elseif($action=="delete"){

   if($confirm=="yes"){
      /* 刪除投開票所 */
      $sql_delete = "delete from $P_S_tbl where rid='".$rid."'";
      mysql_query($sql_delete);
      header("location:".$_SERVER['PHP_SELF']);
      exit;

   }else{
      $sql_select = "select * from $P_S_tbl where rid='".$rid."'";
      $result=mysql_query($sql_select);
      $o=mysql_fetch_object($result);
      $echo_str  = "<html><head>\n";
      $echo_str .= "<meta http-equiv=\"Content-Type\" content=\"text/html; Charset=Big5\">\n";
      $echo_str .= "<body><center>\n";
      $echo_str .= "<table style=\"border-collapse: collapse;border:2px dashed\" bordercolor=\"#FF0000\" border=1 bgcolor=yellow>";
      $echo_str .= "<tr><td align=center><br>\n";
      $echo_str .= "<font color=red size=7><b>危險警告！</b></font><br><br>\n";
      $echo_str .= "<center><font color=green size=5><b>☆ 您確定要刪除「".$o->ps_no."_".$o->ps_name."」投票所資料嗎？ ☆</b></font><br><br>\n";
      $echo_str .= "<font color=red>※一旦刪除，則資料將無法挽回！</font><br><br>\n";
      $echo_str .= "<input type=\"button\" value=\"我很確定\" name=\"B1\" onclick=\"location='".$_SERVER['PHP_SELF']."?action=delete&confirm=yes&rid=".$rid."&".(session_id()?SID:'')."'\">&nbsp;&nbsp;&nbsp;&nbsp;";
      $echo_str .= "<input type=\"button\" value=\"取　　消\" name=\"B1\" onclick=\"location='javascript:window.history.back()'\"><br></center>";
      $echo_str .= "<br><br>\n";
      $echo_str .= "</td></tr></table>\n";
      $echo_str .= "</center></body></html>\n";
      echo $echo_str;
      exit;
   }
   
}elseif($action=="upd_confirm"){

   /* 修改投開票所 */
   $sql_update = "update $P_S_tbl set ps_no='".($ps_no*1)."',ps_name='$ps_name',voters='".($voters*1)."',ps_user='$ps_user',password='$password',ps_ip='$ps_ip',_lock='$_lock',remark='$remark' where rid='".$rid."'";
   mysql_query($sql_update);
   header("location:".$_SERVER['PHP_SELF']);
   exit;
   
}else{

   $echo_str  = "<html><head>\n";
   $echo_str .= "<meta http-equiv=\"Content-Type\" content=\"text/html; Charset=Big5\">\n";
   $echo_str .= "<Link Rel='stylesheet' Type='text/css' Href='style_c.css'>\n";
   $echo_str .= "<title>".$Title."--新增投開票所</title>\n";
   $echo_str .= '
<script language="JavaScript">
<!--
function setBG(TheColor,TheObject) {TheObject.bgColor=TheColor}
//-->
</script>';
   $echo_str .= "</head><body>\n";
   $echo_str .= "<center><form action=".$_SERVER['PHP_SELF']." method=post>\n";
   $echo_str .= "<font color=#009300 size=+1>新增投開票所管理程式</font>\n";
   $echo_str .= "<table border=1 cellspacing=0 style=\"border-collapse: collapse\" bordercolor=orange>\n";
   $echo_str .= "<tr bgcolor=#FFC993 align=center><td><a name=insert>累計</a></td><td>編號</td><td>投開票所名稱</td><td>投票權人數</td><td>管理帳號</td><td>管理密碼</td><td>IP控管</td><td>鎖況</td><td>備註</td><td>動作</td></tr>\n";

   $sql_select="select * from $P_S_tbl order by ps_no DESC";
   $result_ps=mysql_query($sql_select);
   $ops=mysql_fetch_object($result_ps);
   
   /* 新增投票所表單 */
   if($action=="form"){
      $echo_str .= "<tr bgcolor=FFBBFF>";
      $echo_str .= "<td><font color=cryan>新增</font></td>";
      $echo_str .= "<td><input type=\"text\" name=\"ps_no\" size=\"4\" value=\"".((isset($ops->ps_no)?$ops->ps_no:0)+1)."\"></td>";
      $echo_str .= "<td><input type=\"text\" name=\"ps_name\" size=\"10\"></td>";
      $echo_str .= "<td><input type=\"text\" name=\"voters\" size=\"10\"></td>";
      $echo_str .= "<td><input type=\"text\" name=\"ps_user\" size=\"10\"></td>";
      $echo_str .= "<td><input type=\"text\" name=\"password\" size=\"10\"></td>";
      $echo_str .= "<td><input type=\"text\" name=\"ps_ip\" size=\"10\"></td>";
      $echo_str .= "<td><select name=_lock><option value=''>不鎖</option><option value='1'>鎖定</option></select></td>";
      $echo_str .= "<td><input type=\"text\" name=\"remark\" size=\"10\"></td>";
      $echo_str .= "<td><input type=\"submit\" name=\"B1\" value=\"儲存\" style='background:red;color:yellow'>";
      $echo_str .= "<input type=\"button\" value=\"取消\" name=\"B2\" style='background:pink;' onclick=\"location='".$_SERVER['PHP_SELF']."'\"></td>";
      $echo_str .= "</tr>\n";
      $echo_str .= "<input type=\"hidden\" name=\"action\" value=\"insert\">";
   }else{
      $echo_str .= "<tr><td colspan=10 align=center>";
      $echo_str .= "<input type=\"button\" value=\"新增投開票所\" name=\"B1\" onclick=\"location='".$_SERVER['PHP_SELF']."?action=form#insert'\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
      $echo_str .= "<input type=\"button\" value=\"全上鎖\" name=\"B1\" onclick=\"location='".$_SERVER['PHP_SELF']."?action=all_lock'\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
      $echo_str .= "<input type=\"button\" value=\"全解鎖\" name=\"B1\" onclick=\"location='".$_SERVER['PHP_SELF']."?action=all_unlock'\">";
      $echo_str .= "</td></tr>\n";
   }

   $sql_select="select * from $P_S_tbl order by ps_no";
   $result_ps=mysql_query($sql_select);
   $c=1;
   while ($ps=mysql_fetch_object($result_ps)){
      if(($action=="update") and ($rid==$ps->rid)){
      	 $c++;
         $echo_str .= "<tr bgcolor=".$alert_bg_clr.">";
         $echo_str .= "<td><font color=cryan><a name=update>修改</a></font></td>";
         $echo_str .= "<td><input type=\"text\" name=\"ps_no\" size=\"4\" value=\"".$ps->ps_no."\" style=\"color:red;background:FFFF88\"></td>";
         $echo_str .= "<td><input type=\"text\" name=\"ps_name\" size=\"10\" value=\"".$ps->ps_name."\" style=\"color:red;background:FFFF88\"></td>";
         $echo_str .= "<td><input type=\"text\" name=\"voters\" size=\"5\" value=\"".$ps->voters."\" style=\"color:red;background:FFFF88\"></td>";
         $echo_str .= "<td><input type=\"text\" name=\"ps_user\" size=\"10\" value=\"".$ps->ps_user."\" style=\"color:red;background:FFFF88\"></td>";
         $echo_str .= "<td><input type=\"text\" name=\"password\" size=\"10\" value=\"".$ps->password."\" style=\"color:red;background:FFFF88\"></td>";
         $echo_str .= "<td><input type=\"text\" name=\"ps_ip\" size=\"10\" value=\"".$ps->ps_ip."\" style=\"color:red;background:FFFF88\"></td>";
         $echo_str .= "<td><select name=_lock><option value=''".(($ps->_lock<>'1')?" selected":'').">不鎖</option><option value='1'".(($ps->_lock=='1')?" selected":'').">鎖定</option></select></td>";
         $echo_str .= "<td><input type=\"text\" name=\"remark\" size=\"10\" value=\"".$ps->remark."\" style=\"color:red;background:FFFF88\"></td>";
         $echo_str .= "<td><input type=\"submit\" name=\"B1\" value=\"儲存\" style='background:red;color:yellow'>";
         $echo_str .= "<input type=\"button\" value=\"取消\" name=\"B2\" style='background:pink;' onclick=\"location='".$_SERVER['PHP_SELF']."'\"></td>";
         $echo_str .= "<input type=\"hidden\" name=\"action\" value=\"upd_confirm\">";
         $echo_str .= "<input type=\"hidden\" name=\"rid\" value=\"".$ps->rid."\">";
         $echo_str .= "</tr>\n";
      }else{
         $echo_str .= "<tr onMouseOver=setBG('#E0FFD0',this) onMouseout=setBG('',this)>";
         $echo_str .= "<td>".$c++."</td>";
         $echo_str .= "<td>".(empty($ps->ps_no)?"&nbsp;":$ps->ps_no)."</td>";
         $echo_str .= "<td>".(empty($ps->ps_name)?"&nbsp;":$ps->ps_name)."</td>";
         $echo_str .= "<td>".(empty($ps->voters)?"&nbsp;":$ps->voters)."</td>";
         $echo_str .= "<td>".(empty($ps->ps_user)?"&nbsp;":$ps->ps_user)."</td>";
         $echo_str .= "<td>".(empty($ps->password)?"&nbsp;":$ps->password)."</td>";
         $echo_str .= "<td>".(empty($ps->ps_ip)?"&nbsp;":$ps->ps_ip)."</td>";
         $echo_str .= "<td>".(empty($ps->_lock)?"&nbsp;":"<font color=red>已鎖</font>")."</td>";
         $echo_str .= "<td>".(empty($ps->remark)?"&nbsp;":$ps->remark)."</td>";
         $echo_str .= "<td><input type=\"button\" value=\"刪除\" name=\"B1\" onclick=\"location='".$_SERVER['PHP_SELF']."?action=delete&rid=".$ps->rid."'\">";
         $echo_str .= "<input type=\"button\" value=\"修改\" name=\"B2\" onclick=\"location='".$_SERVER['PHP_SELF']."?action=update&rid=".$ps->rid."#update'\"></td>";
         $echo_str .= "</tr>\n";
      }
   }

   $echo_str .= "</table>\n";
   $echo_str .= "</form></center>\n";
   $echo_str .= "</body></html>\n";
}

echo $echo_str;
?>