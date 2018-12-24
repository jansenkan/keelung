<?
#---------------------------------------------------
#   新增候選人主程式
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
$sno     = isset($_POST['sno'])    ?$_POST['sno']    :'';
$class   = isset($_POST['class'])  ?$_POST['class']  :'';
$name    = isset($_POST['name'])   ?$_POST['name']   :'';
$sex     = isset($_POST['sex'])    ?$_POST['sex']    :'';
$history = isset($_POST['history'])?$_POST['history']:'';
$remark  = isset($_POST['remark']) ?$_POST['remark'] :'';
$action  = isset($_GET['action'])  ?$_GET['action']  :'';
$action  = isset($_POST['action']) ?$_POST['action'] :$action;
$rid     = isset($_GET['rid'])     ?$_GET['rid']     :'';
$rid     = isset($_POST['rid'])    ?$_POST['rid']    :$rid;
$confirm = isset($_GET['confirm']) ?$_GET['confirm'] :'';
$MAX_FILE_SIZE = isset($_POST['MAX_FILE_SIZE'])?$_POST['MAX_FILE_SIZE']:1024000;
$upfile        = isset($_FILES['upfile']['tmp_name'])?$_FILES['upfile']['tmp_name']:'';
$upfile_name   = isset($_FILES['upfile']['name'])?$_FILES['upfile']['name']:'';

/* 鎖定警告 */ 
if($Lock){
   echo "<center><font color=red size=5>抱歉！本功能已遭系統鎖定，須解鎖後才能使用！</font></center>";
   exit;
}
   
if($action=="insert"){
   /* 資料為空時不處理 */
   if(empty($sno) or empty($name)){
      header("location:".$_SERVER['PHP_SELF']);
      exit;
   }

   /* 新增候選人 */
   $name    = addslashes($name);
   $history = addslashes($history);
   $remark  = addslashes($remark);
   $history = nl2br($history);
   $sql_insert = "insert into $Candidate_tbl (sno,class,name,sex,history,remark) values ('".($sno*1)."','$class','$name','$sex','$history','$remark')";
   mysql_query($sql_insert);
   $sql_select = "select * from $Candidate_tbl where sno='$sno' and class='$class'";
   $result = mysql_query($sql_select);
   $o = mysql_fetch_object($result);
   $rid_i = $o->rid;
   /* 檔名非空才上傳檔案 */
   if(!empty($upfile_name)){
      $save_name = "p".$rid_i."_".$upfile_name;
      $up_name = $path_img_abs.$save_name;
            
      /* 上傳結果之訊息 */
      if (copy($upfile,$up_name)){
         $sql_update = "update $Candidate_tbl set pic='$save_name' where rid='$rid_i'";
         mysql_query($sql_update);
      }else{
         echo "<font color=red size=+1>相片上傳失敗！</font>[檔名:".$upfile_name."]<br>\n";
         exit;
      }
   }
   header("location:".$_SERVER['PHP_SELF']."?action=form");
   exit;
   
}elseif($action=="delete"){

   if($confirm=="yes"){
      /* 刪除候選人 */
      $sql_select = "select * from $Candidate_tbl where rid='".$rid."'";
      $result = mysql_query($sql_select);
      $o = mysql_fetch_object($result);
      /* 有照片時，才做刪照片的動作 */
      if($o->pic){
         $pic = $path_img_abs.$o->pic;
         unlink($pic);
      }
      $sql_delete = "delete from $Candidate_tbl where rid='".$rid."'";
      mysql_query($sql_delete);
      header("location:".$_SERVER['PHP_SELF']);
      exit;

   }else{
      $sqlstr = "select * from $Candidate_tbl where rid='".$rid."'";
      $result=mysql_query($sqlstr);
      $o=mysql_fetch_object($result);
      $echo_str  = "<html><head>\n";
      $echo_str .= "<meta http-equiv=\"Content-Type\" content=\"text/html; Charset=Big5\">\n";
      $echo_str .= "<body><center>\n";
      $echo_str .= "<table style=\"border-collapse: collapse;border:2px dashed\" bordercolor=\"#FF0000\" border=1 bgcolor=yellow>";
      $echo_str .= "<tr><td align=center><br>\n";
      $echo_str .= "<font color=red size=7><b>危險警告！</b></font><br><br>\n";
      $echo_str .= "<center><font color=green size=5><b>☆ 您確定要刪除「".$o->name."」的侯選資料嗎？ ☆</b></font><br><br>\n";
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

   /* 確定修改候選人 */
   if($upfile_name){
      /* 先刪除原有照片 */
      $sql_select = "select * from $Candidate_tbl where rid='".$rid."'";
      $result = mysql_query($sql_select);
      $o = mysql_fetch_object($result);
      /* 有照片時，才做刪照片的動作 */
      if($o->pic){
         $pic = $path_img_abs.$o->pic;
         unlink($pic);
      }
      /* 上傳新的照片 */
      $save_name = "p".$rid."_".$upfile_name;
      $up_name = $path_img_abs.$save_name;
            
      /* 上傳結果之訊息 */
      if (copy($upfile,$up_name)){
         $sql_update = "update $Candidate_tbl set pic='$save_name' where rid='$rid'";
         mysql_query($sql_update);
      }else{
         echo "<font color=red size=+1>相片上傳失敗！</font>[檔名:".$upfile_name."]<br>\n";
         exit;
      }
   }
   /* 修改其餘資料(不含照片資料) */
   $name    = addslashes($name);
   $history = addslashes($history);
   $remark  = addslashes($remark);
   $history = nl2br($history);
   $sql_update = "update $Candidate_tbl set sno='".($sno*1)."',class='".$class."',name='".$name."',sex='".$sex."',history='".$history."',remark='".$remark."' where rid='".$rid."'";
   mysql_query($sql_update);
   header("location:".$_SERVER['PHP_SELF']);
   exit;
   
}else{

   $echo_str  = "<html><head>\n";
   $echo_str .= "<meta http-equiv=\"Content-Type\" content=\"text/html; Charset=Big5\">\n";
   $echo_str .= "<Link Rel='stylesheet' Type='text/css' Href='style_c.css'>\n";
   $echo_str .= "<title>".$Title."--新增候選人</title>\n";
   $echo_str .= '
<script language="JavaScript">
<!--
function setBG(TheColor,TheObject) {TheObject.bgColor=TheColor}
//-->
</script>';
   $echo_str .= "</head><body>\n";
   $echo_str .= "<center><form action=".$_SERVER['PHP_SELF']." ENCTYPE=\"multipart/form-data\" method=post>\n";
   $echo_str .= "<INPUT TYPE=\"hidden\" NAME=\"MAX_FILE_SIZE\" VALUE=\"1024000\">\n";
   $echo_str .= "<font color=009300 size=+1>新增(修改)候選人管理程式</font>\n";
   $echo_str .= "<table border=1 cellpadding=3 style=\"border-collapse: collapse\" cellspacing=0 bordercolor=#B0B0B0>\n";
   $echo_str .= "<tr bgcolor=orange align=center><td>累計</td><td>編號</td><td>黨　籍</td><td>姓　名</td><td>性別</td><td>簡明政見</td><td>備註</td><td>動作</td></tr>\n";

   $sql_select="select * from $Candidate_tbl order by sno";
   $result_ca=mysql_query($sql_select);
   $cnt=mysql_num_rows($result_ca);
   
   if($action=="form"){
      $echo_str .= "<tr bgcolor=E0E0E0>";
      $echo_str .= "<td><font color=009300>新增</font></td>";
      $echo_str .= "<td><input type=\"text\" name=\"sno\" size=\"2\" value=\"".($cnt+1)."\"></td>";
      $echo_str .= "<td><input type=\"text\" name=\"class\" size=\"8\"></td>";
      $echo_str .= "<td><input type=\"text\" name=\"name\" size=\"8\"></td>";
      $echo_str .= "<td><select name=sex><option>女</option><option>男</option></select></td>";
      $echo_str .= "<td><textarea name=\"history\" cols=\"35\" rows=\"4\"></textarea><br>";
      $echo_str .= "<font color=#009300>照片：</font><input type=\"file\" size=\"25\" name=\"upfile\"><br>";
      $echo_str .= "<font color=#FF0000>(建議比例：寬:高=3:4　　建議尺寸：600x800)</font></td>";
      $echo_str .= "<td><input type=\"text\" name=\"remark\" size=\"5\"></td>";
      $echo_str .= "<td>";
      $echo_str .= "<input type=\"hidden\" name=\"action\" value=\"insert\">";
      $echo_str .= "<input type=\"submit\" name=\"B1\" value=\"儲存\"><br><br>";
      $echo_str .= "<input type=\"button\" value=\"取消\" name=\"B2\" onclick=\"location='".$_SERVER['PHP_SELF']."'\">";
      $echo_str .= "</td></tr>\n";
   }elseif($action=="update"){
      $sql_select="select * from $Candidate_tbl where rid='".$rid."'";
      $result_upd=mysql_query($sql_select);
      $upd=mysql_fetch_object($result_upd);
      $name    = stripslashes($upd->name);
      $history = stripslashes($upd->history);
      $remark  = stripslashes($upd->remark);
      $history = str_replace("<br />","",$history);
      
      $echo_str .= "<tr bgcolor=lightpink>";
      $echo_str .= "<td><font color=red>修改</font></td>";
      $echo_str .= "<td><input type=\"text\" name=\"sno\" size=\"2\" value=\"".$upd->sno."\"></td>";
      $echo_str .= "<td><input type=\"text\" name=\"class\" size=\"8\" value=\"".$upd->class."\"></td>";
      $echo_str .= "<td><input type=\"text\" name=\"name\" size=\"8\" value=\"".$name."\"></td>";
      $echo_str .= "<td align=center".(empty($upd->pic)?"":" valign=bottom")."><select name=sex><option".(($upd->sex=="女")?" selected":"").">女</option><option".(($upd->sex=="男")?" selected":"").">男</option></select><br>";
      $echo_str .= (empty($upd->pic)?"":"<img src=".$path_img_rela.$upd->pic." width=38 height=51>")."</td>";
      $echo_str .= "<td><textarea name=\"history\" cols=\"35\" rows=\"4\">".$history."</textarea><br>";
      $echo_str .= "換照片：<input type=\"file\" size=\"25\" name=\"upfile\"><br>";
      $echo_str .= "<font color=#FF0000>(建議比例：寬:高=3:4　　建議尺寸：600x800)</font></td>";
      $echo_str .= "<td><input type=\"text\" name=\"remark\" size=\"5\" value=\"".$remark."\"></td>";
      $echo_str .= "<td>";
      $echo_str .= "<input type=\"hidden\" name=\"action\" value=\"upd_confirm\">";
      $echo_str .= "<input type=\"hidden\" name=\"rid\" value=\"".$upd->rid."\">";
      $echo_str .= "<input type=\"submit\" name=\"B1\" value=\"確定\"><br><br>";
      $echo_str .= "<input type=\"button\" value=\"取消\" name=\"B2\" onclick=\"location='".$_SERVER['PHP_SELF']."'\">";
      $echo_str .= "</td></tr>\n";
   }else{
      $echo_str .= "<tr><td colspan=8 align=center>";
      $echo_str .= "<input type=\"button\" value=\"新增候選人\" name=\"B1\" onclick=\"location='".$_SERVER['PHP_SELF']."?action=form'\">";
      $echo_str .= "</td></tr>\n";
   }

   $c=1;
   while ($ca=mysql_fetch_object($result_ca)){
      $name    = stripslashes($ca->name);
      $history = stripslashes($ca->history);
      $remark  = stripslashes($ca->remark);
      $bgcolor = ($ca->sex=='男')?"#EEEEFF":"#FFEEEE";
      $echo_str .= "<tr bgcolor=".$bgcolor." align=center onMouseOver=setBG('#E0FFD0',this) onMouseout=setBG('".$bgcolor."',this)>";
      $echo_str .= "<td>".$c++."</td>";
      $echo_str .= "<td>".(empty($ca->sno)?"&nbsp;":$ca->sno)."</td>";
      $echo_str .= "<td>".(empty($ca->class)?"&nbsp;":$ca->class)."</td>";
      $echo_str .= "<td>".(empty($ca->pic)?"":"<img src=".$path_img_rela.$ca->pic." width=60 height=80 style='vertical-align:middle'>&nbsp;").(empty($name)?"&nbsp;":$name)."</td>";
      $echo_str .= "<td>".(empty($ca->sex)?"&nbsp;":$ca->sex)."</td>";
      $echo_str .= "<td align=left>".(empty($history)?"&nbsp;":$history)."</td>";
      $echo_str .= "<td>".(empty($remark)?"&nbsp;":$remark)."</td>";
      $echo_str .= "<td><input type=\"button\" value=\"刪\" name=\"B1\" onclick=\"location='".$_SERVER['PHP_SELF']."?action=delete&rid=".$ca->rid."'\">";
      $echo_str .= "<input type=\"button\" value=\"改\" name=\"B2\" onclick=\"location='".$_SERVER['PHP_SELF']."?action=update&rid=".$ca->rid."'\"></td>";
      $echo_str .= "</tr>\n";
   }

//   $echo_str .= "<tr><td colspan=8 align=center>";
//   $echo_str .= "<input type=\"button\" value=\"新增候選人\" name=\"B1\" onclick=\"location='".$_SERVER['PHP_SELF']."?action=form'\">";
//   $echo_str .= "</td></tr>\n";
   $echo_str .= "</table>\n";
   $echo_str .= "</form></center>\n";
   $echo_str .= "</body></html>\n";
}

echo $echo_str;
?>