<?
#---------------------------------------------------
#   �s�W��}���ҥD�{��
#   by jansen since 2003.03.16
#---------------------------------------------------
include "config.inc.php";
session_id()?'':session_start();
/* �D�t�κ޲z�̡A�Ч�D�I */
if($_SESSION['root']<>"sys_player"){
   header("location:index.php");
   exit;
}
/* �ܼƪ�ȳ]�w */
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
   echo "<center><font color=red size=5>��p�I���\��w�D�t����w�A�������~��ϥΡI</font></center>";
   exit;
}
// ���W��   
if($action=="all_lock"){
   $sql_update = "update $P_S_tbl set _lock='1'";
   mysql_query($sql_update);
   header("location:".$_SERVER['PHP_SELF']);
   exit;

// ������
}elseif($action=="all_unlock"){
   $sql_update = "update $P_S_tbl set _lock=''";
   mysql_query($sql_update);
   header("location:".$_SERVER['PHP_SELF']);
   exit;

}elseif($action=="insert" and !empty($ps_name)){

   /* �s�W��}���� */
   $sql_insert = "insert into $P_S_tbl (ps_no,ps_name,voters,ps_user,password,ps_ip,_lock,remark) values ('".($ps_no*1)."','$ps_name','".($voters*1)."','$ps_user','$password','$ps_ip','$_lock','$remark')";
   mysql_query($sql_insert);
   header("location:".$_SERVER['PHP_SELF']);
   exit;
   
}elseif($action=="delete"){

   if($confirm=="yes"){
      /* �R����}���� */
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
      $echo_str .= "<font color=red size=7><b>�M�Iĵ�i�I</b></font><br><br>\n";
      $echo_str .= "<center><font color=green size=5><b>�� �z�T�w�n�R���u".$o->ps_no."_".$o->ps_name."�v�벼�Ҹ�ƶܡH ��</b></font><br><br>\n";
      $echo_str .= "<font color=red>���@���R���A�h��ƱN�L�k���^�I</font><br><br>\n";
      $echo_str .= "<input type=\"button\" value=\"�ګܽT�w\" name=\"B1\" onclick=\"location='".$_SERVER['PHP_SELF']."?action=delete&confirm=yes&rid=".$rid."&".(session_id()?SID:'')."'\">&nbsp;&nbsp;&nbsp;&nbsp;";
      $echo_str .= "<input type=\"button\" value=\"���@�@��\" name=\"B1\" onclick=\"location='javascript:window.history.back()'\"><br></center>";
      $echo_str .= "<br><br>\n";
      $echo_str .= "</td></tr></table>\n";
      $echo_str .= "</center></body></html>\n";
      echo $echo_str;
      exit;
   }
   
}elseif($action=="upd_confirm"){

   /* �ק��}���� */
   $sql_update = "update $P_S_tbl set ps_no='".($ps_no*1)."',ps_name='$ps_name',voters='".($voters*1)."',ps_user='$ps_user',password='$password',ps_ip='$ps_ip',_lock='$_lock',remark='$remark' where rid='".$rid."'";
   mysql_query($sql_update);
   header("location:".$_SERVER['PHP_SELF']);
   exit;
   
}else{

   $echo_str  = "<html><head>\n";
   $echo_str .= "<meta http-equiv=\"Content-Type\" content=\"text/html; Charset=Big5\">\n";
   $echo_str .= "<Link Rel='stylesheet' Type='text/css' Href='style_c.css'>\n";
   $echo_str .= "<title>".$Title."--�s�W��}����</title>\n";
   $echo_str .= '
<script language="JavaScript">
<!--
function setBG(TheColor,TheObject) {TheObject.bgColor=TheColor}
//-->
</script>';
   $echo_str .= "</head><body>\n";
   $echo_str .= "<center><form action=".$_SERVER['PHP_SELF']." method=post>\n";
   $echo_str .= "<font color=#009300 size=+1>�s�W��}���Һ޲z�{��</font>\n";
   $echo_str .= "<table border=1 cellspacing=0 style=\"border-collapse: collapse\" bordercolor=orange>\n";
   $echo_str .= "<tr bgcolor=#FFC993 align=center><td><a name=insert>�֭p</a></td><td>�s��</td><td>��}���ҦW��</td><td>�벼�v�H��</td><td>�޲z�b��</td><td>�޲z�K�X</td><td>IP����</td><td>��p</td><td>�Ƶ�</td><td>�ʧ@</td></tr>\n";

   $sql_select="select * from $P_S_tbl order by ps_no DESC";
   $result_ps=mysql_query($sql_select);
   $ops=mysql_fetch_object($result_ps);
   
   /* �s�W�벼�Ҫ�� */
   if($action=="form"){
      $echo_str .= "<tr bgcolor=FFBBFF>";
      $echo_str .= "<td><font color=cryan>�s�W</font></td>";
      $echo_str .= "<td><input type=\"text\" name=\"ps_no\" size=\"4\" value=\"".((isset($ops->ps_no)?$ops->ps_no:0)+1)."\"></td>";
      $echo_str .= "<td><input type=\"text\" name=\"ps_name\" size=\"10\"></td>";
      $echo_str .= "<td><input type=\"text\" name=\"voters\" size=\"10\"></td>";
      $echo_str .= "<td><input type=\"text\" name=\"ps_user\" size=\"10\"></td>";
      $echo_str .= "<td><input type=\"text\" name=\"password\" size=\"10\"></td>";
      $echo_str .= "<td><input type=\"text\" name=\"ps_ip\" size=\"10\"></td>";
      $echo_str .= "<td><select name=_lock><option value=''>����</option><option value='1'>��w</option></select></td>";
      $echo_str .= "<td><input type=\"text\" name=\"remark\" size=\"10\"></td>";
      $echo_str .= "<td><input type=\"submit\" name=\"B1\" value=\"�x�s\" style='background:red;color:yellow'>";
      $echo_str .= "<input type=\"button\" value=\"����\" name=\"B2\" style='background:pink;' onclick=\"location='".$_SERVER['PHP_SELF']."'\"></td>";
      $echo_str .= "</tr>\n";
      $echo_str .= "<input type=\"hidden\" name=\"action\" value=\"insert\">";
   }else{
      $echo_str .= "<tr><td colspan=10 align=center>";
      $echo_str .= "<input type=\"button\" value=\"�s�W��}����\" name=\"B1\" onclick=\"location='".$_SERVER['PHP_SELF']."?action=form#insert'\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
      $echo_str .= "<input type=\"button\" value=\"���W��\" name=\"B1\" onclick=\"location='".$_SERVER['PHP_SELF']."?action=all_lock'\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
      $echo_str .= "<input type=\"button\" value=\"������\" name=\"B1\" onclick=\"location='".$_SERVER['PHP_SELF']."?action=all_unlock'\">";
      $echo_str .= "</td></tr>\n";
   }

   $sql_select="select * from $P_S_tbl order by ps_no";
   $result_ps=mysql_query($sql_select);
   $c=1;
   while ($ps=mysql_fetch_object($result_ps)){
      if(($action=="update") and ($rid==$ps->rid)){
      	 $c++;
         $echo_str .= "<tr bgcolor=".$alert_bg_clr.">";
         $echo_str .= "<td><font color=cryan><a name=update>�ק�</a></font></td>";
         $echo_str .= "<td><input type=\"text\" name=\"ps_no\" size=\"4\" value=\"".$ps->ps_no."\" style=\"color:red;background:FFFF88\"></td>";
         $echo_str .= "<td><input type=\"text\" name=\"ps_name\" size=\"10\" value=\"".$ps->ps_name."\" style=\"color:red;background:FFFF88\"></td>";
         $echo_str .= "<td><input type=\"text\" name=\"voters\" size=\"5\" value=\"".$ps->voters."\" style=\"color:red;background:FFFF88\"></td>";
         $echo_str .= "<td><input type=\"text\" name=\"ps_user\" size=\"10\" value=\"".$ps->ps_user."\" style=\"color:red;background:FFFF88\"></td>";
         $echo_str .= "<td><input type=\"text\" name=\"password\" size=\"10\" value=\"".$ps->password."\" style=\"color:red;background:FFFF88\"></td>";
         $echo_str .= "<td><input type=\"text\" name=\"ps_ip\" size=\"10\" value=\"".$ps->ps_ip."\" style=\"color:red;background:FFFF88\"></td>";
         $echo_str .= "<td><select name=_lock><option value=''".(($ps->_lock<>'1')?" selected":'').">����</option><option value='1'".(($ps->_lock=='1')?" selected":'').">��w</option></select></td>";
         $echo_str .= "<td><input type=\"text\" name=\"remark\" size=\"10\" value=\"".$ps->remark."\" style=\"color:red;background:FFFF88\"></td>";
         $echo_str .= "<td><input type=\"submit\" name=\"B1\" value=\"�x�s\" style='background:red;color:yellow'>";
         $echo_str .= "<input type=\"button\" value=\"����\" name=\"B2\" style='background:pink;' onclick=\"location='".$_SERVER['PHP_SELF']."'\"></td>";
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
         $echo_str .= "<td>".(empty($ps->_lock)?"&nbsp;":"<font color=red>�w��</font>")."</td>";
         $echo_str .= "<td>".(empty($ps->remark)?"&nbsp;":$ps->remark)."</td>";
         $echo_str .= "<td><input type=\"button\" value=\"�R��\" name=\"B1\" onclick=\"location='".$_SERVER['PHP_SELF']."?action=delete&rid=".$ps->rid."'\">";
         $echo_str .= "<input type=\"button\" value=\"�ק�\" name=\"B2\" onclick=\"location='".$_SERVER['PHP_SELF']."?action=update&rid=".$ps->rid."#update'\"></td>";
         $echo_str .= "</tr>\n";
      }
   }

   $echo_str .= "</table>\n";
   $echo_str .= "</form></center>\n";
   $echo_str .= "</body></html>\n";
}

echo $echo_str;
?>