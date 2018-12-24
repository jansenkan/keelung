<?
//---------------------------------------------
//    更改比賽標題及系統參數 changetitle.php
//---------------------------------------------
//include("chkuser.inc.php");
include("config.inc.php");
/* 不列入選單之除外單位 */
//$except_org = array("org_sift","org_list");
//$org_table = ($System=='ON')?$Org_tbl:$_POST['org_table'];
//$grp_table = ($System=='ON')?$Grp_tbl:$_POST['grp_table'];

// 更改比賽標題表單
if (!isset($_POST['action'])){
   $echo_str = head();
   $echo_str .="
<center>
<form action=".$_SERVER['PHP_SELF']." method=post>
<font color=blue size=4>".$Title."</font>
<input type=hidden name=action value=go>
<table cellspacing=0 cellpadding=5 border=1 style=\"border-collapse: collapse\" bordercolor=cryan>
<tr><td colspan=2 bgcolor=cryan align=center>
        <font size=4 color=white>系統參數設定</font></td></tr>
<tr><td align=right>原活動標題：</td>
    <td><label style='background:#F0F000;'>&nbsp;".$Title."&nbsp;</label></td></tr>
<tr><td align=right>新活動標題：</td>
    <td><input type=text STYLE='FONT-SIZE:16px;COLOR:#0080C0;background-color:#FEEBD8;' name=titlen size=60 value=".$Title."></td></tr>
<tr><td align=right>系統管理者帳號：</td>
  <td><input type=text STYLE='FONT-SIZE:16px;COLOR:#0080C0;background-color:#FEEBD8;' name=user_name value=".$Uname."></td></tr>
<tr><td align=right>系統管理者密碼：</td>
  <td><input type=password STYLE='FONT-SIZE:16px;COLOR:#0080C0;background-color:#FEEBD8;' name=user_pass value=".$Pword."></td></tr>
<tr><td align=right>畫面刷新秒數1：</td>
  <td><input type=text STYLE='FONT-SIZE:16px;COLOR:#0080C0;background-color:#FEEBD8;' name=refresh_sec1 size=3 maxlength=3 value=".$RefreshSec1.">&nbsp;值：1~999&nbsp;<font color=cryan size=-1>(開票進行中之刷新秒數)</font></td></tr>
<tr><td align=right>畫面刷新秒數2：</td>
  <td><input type=text STYLE='FONT-SIZE:16px;COLOR:#0080C0;background-color:#FEEBD8;' name=refresh_sec2 size=3 maxlength=3 value=".$RefreshSec2.">&nbsp;值：1~999&nbsp;<font color=cryan size=-1>(開票結束後之刷新秒數，以減輕系統負擔)</font></td></tr>
<tr><td align=right>篤定當選票數：</td>
    <td><input type=text STYLE='FONT-SIZE:16px;COLOR:#0080C0;background-color:#FEEBD8;' name=ballot2comein size=6 maxlength=6 value=".$Ballot2ComeIn.">&nbsp;值：1~999999&nbsp;<font color=cryan size=-1>(達標時，該侯選人可逕行宣佈當選之票數)</font></td></tr>
<tr><td align=right>本次活動狀況：</td>
    <td><select name=ending STYLE='FONT-SIZE:16px;COLOR:#0080C0;background-color:#FEEBD8;'>
        <option value='0'".(($Ending=='0')?" selected":"").">進行中…</option>
        <option value='1'".(($Ending=='1')?" selected":"").">結束了！</option>
        </select>&nbsp;<font color=cryan size=-1>(本設定影響系統參數的取用)</font></td></tr>
<tr><td colspan=2 bgcolor=cryan align=center>
        <input type=submit name=B1 value=儲存更新></td></tr>
</table>            
</form>
</center>";
   $echo_str .= foot();
   echo $echo_str;
   exit();
}


// 檢查密碼是否合法
if ($_POST['action']=="go"){
   
   $echo_str = head()."<center>\n";
   /*
   if (($_SESSION['username']<>$AdminName['root']) or ($_SESSION['password']<>$AdminPwd['root'])){
      $echo_str .="<label style='background:#FFFF00'><font color=red size=5>本功能限管理者使用！！<br>(故資料未更改)</font></label><br><br>";
      $echo_str .="｜<a href='javascript:window.close()'>關閉本視窗</a>｜";
      echo $echo_str."</center>".foot();
      exit();
   }
   */
   $titlen=addslashes($_POST['titlen']);

   $sql_update= "update config set title='$titlen',admin_name='".$_POST['user_name']."',admin_pass='".$_POST['user_pass']."',
                                   refresh_sec1='".$_POST['refresh_sec1']."',refresh_sec2='".$_POST['refresh_sec2']."',
                                   ballot2comein='".$_POST['ballot2comein']."',ending='".$_POST['ending']."'";
   $result=mysql_query($sql_update);
   
   $echo_str .="<label style='background:#FFFF00'><font color=red size=5>活動標題及系統參數更新完成！</font></label><br><br>";
   $echo_str .="｜<a href='javascript:window.close()'>關閉本視窗</a>";
   $echo_str .="｜<a href='".$_SERVER['PHP_SELF']."'>回設定畫面</a>";
   $echo_str .="｜<a href=logout.php>登出</a>｜";
   echo $echo_str."</center>".foot();
}

/* 顯示比較符號(>,<,=)之選單 */
function compar_mode($cm,$no)
{
   $cmode = array(">",">=","==","<=","<");
   $cmodeW= array("大於","大於等於","等於","小於等於","小於");
   $cnt = sizeof($cmode);
   $sel_str = "<select size=1 STYLE='FONT-SIZE:13px;COLOR:#009300;background-color:F0FFDD;' name=compar".$no.">\n";
   for($i=0;$i<$cnt;$i++){
      $sel_str .= "<option ".(($cm==$cmode[$i])?"selected ":"")."value='".$cmode[$i]."'>".$cmodeW[$i]."</option>\n";
   }
   $sel_str .= "</select>\n";
   
   return $sel_str;
}

/* 顯示參賽組別之選單 */
function grp_mode($gm,$no)
{
   global $Grp_tbl;
   
   $sel_str = "<select size=1 STYLE='FONT-SIZE:13px;COLOR:#009300;background-color:F0FFDD;' name=group".$no.">\n";
   $sqlstr="select * from $Grp_tbl";
   $sqlqry=mysql_query($sqlstr);
   while($o=mysql_fetch_object($sqlqry)){
      $sel_str .= '<option '.(($gm==($o->Group_Num*1))?"selected ":"").'value="'.$o->Group_Num.'">'.$o->Group_Num.$o->Group_Name.'組</option>\n';
   }    
   $sel_str .= "</select>\n";
   
   return $sel_str;
}

/* 顯示邏輯符號(and,or)之選單 */
function logic_mode($lm,$no)
{
   $lmode = array("and","or");
   $lmodeW = array("且","或");
   $cnt = sizeof($lmode);
   $sel_str = "<select size=1 STYLE='FONT-SIZE:16px;COLOR:red;background-color:F0FFDD;' name=logic".$no.">\n";
   for($i=0;$i<$cnt;$i++){
      $sel_str .= "<option ".(($lm==$lmode[$i])?"selected ":"")."value=".$lmode[$i].">".$lmodeW[$i]."</option>\n";
   }
   $sel_str .= "</select>\n";
   
   return $sel_str;
}

/* 顯示單位或組別資料表之選單 
   $sel_name : 下拉式選單名稱
   $prefix   : 欲比對之資料表字首
   $default  : 預設之資料表名稱
   $system   : 系統狀態(ON or OFF)
   $except   : 不列入選單之除外選項陣列
*/
function select_opts($sel_name,$prefix,$default,$system,$except=array())
{
   global $DbName;
   
   $sel_str = "<select size=1 STYLE='FONT-SIZE:16px;COLOR:#009300;background-color:F0FFDD;' name=$sel_name".(($system=='ON')?" disabled":"").">\n";
   $result = mysql_list_tables ($DbName); 
   $i = 0; 
   while ($i < mysql_num_rows ($result)) { 
      $tb_name = mysql_tablename ($result, $i); 
      $tbn = substr($tb_name,0,strlen($prefix));
      if(($tbn==$prefix) and !in_array($tb_name,$except))
         $sel_str .= '<option '.(($default==($tb_name))?"selected ":"").'value="'.$tb_name.'">'.$tb_name.'</option>\n';
      $i++;
   }
   $sel_str .= "</select>\n";
   
   return $sel_str;
}
?>