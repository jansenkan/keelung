<?
#---------------------------------------------------
#   編修各投開票所票數之主程式
#   by jansen since 2003.03.16
#---------------------------------------------------
include("chkuser.inc.php");
include "config.inc.php";
session_id()?'':session_start();
/* 變數初值設定 */
$ps_no   = isset($_GET['ps_no'])?$_GET['ps_no']:'';
$ps_no   = isset($_POST['ps_no'])?$_POST['ps_no']:$ps_no;
$num_ca  = isset($_GET['num_ca'])?$_GET['num_ca']:'';
$num_ca  = isset($_POST['num_ca'])?$_POST['num_ca']:$num_ca;
$action  = isset($_GET['action'])?$_GET['action']:'';
$action  = isset($_POST['action'])?$_POST['action']:$action;
$rid     = isset($_GET['rid'])?$_GET['rid']:'';
$rid     = isset($_POST['rid'])?$_POST['rid']:$rid;
$mode    = isset($_GET['mode'])?$_GET['mode']:'';
$mode    = isset($_POST['mode'])?$_POST['mode']:$mode;
$ps_name = isset($_GET['ps_name'])?$_GET['ps_name']:'';
$ps_name = isset($_POST['ps_name'])?$_POST['ps_name']:$ps_name;
$redir   = isset($_GET['redir'])?$_GET['redir']:'';
$redir   = isset($_POST['redir'])?$_POST['redir']:$redir;
$zone_no = isset($_GET['zone_no'])?$_GET['zone_no']:'';
$zone_no = isset($_POST['zone_no'])?$_POST['zone_no']:$zone_no;
$alert_bg_clr    ="yellow";
$num_circle = array('1' => 'j', '2' => 'k', '3' => 'l', '4' => 'm', '5' => 'n');

if($action=="update"){

   $sql_select="select * from $Vote_tbl order by ps_no";
   $result_vt=mysql_query($sql_select);
   $num_ps = mysql_num_rows($result_vt);
   /* 編修投開票所票數 */
   $sql_update = "update $Vote_tbl set ";
   $cnt=0;
   for($i=0;$i<$num_ca;$i++){
     $tmp = mysql_field_name($result_vt,$i+6);
     $num = isset($_POST[$tmp])?$_POST[$tmp]:$_GET[$tmp];
     $sql_update .= $tmp."='".$num."',";
     $cnt += $num;
     
     /* 填入候選人導向之得票數 */
     $tmp2="ps".$ps_no;
     $tmp_sno=trim(substr($tmp,2,strlen($tmp)));
     $sql_update_cand_cnt="update $Cand_cnt_tbl set ".$tmp2."='".$$tmp."' where sno='".$tmp_sno."'";
     mysql_query($sql_update_cand_cnt);    
   }
   
   $sql_update .= "total='$cnt',voters='".$_POST['voters']."',invalid='".$_POST['invalid']."' where rid='$rid'";
//   echo $sql_update;exit;
   mysql_query($sql_update);
   
   /* 加總候選人導向之得票數 */
   $sql_select="select * from $Cand_cnt_tbl order by sno";
   $result=mysql_query($sql_select);
   while ($ca=mysql_fetch_object($result)){
      $cnt=0;
      for($i=0;$i<$num_ps;$i++){
         $tmp = mysql_field_name($result,$i+4);
         $cnt += $ca->$tmp;
      }
      $sql_update = "update $Cand_cnt_tbl set total='".$cnt."' where rid='".$ca->rid."'";
      mysql_query($sql_update);
   }
   /* 鎖定投票所 */
   $sql_update="update $P_S_tbl set _lock='1' where ps_no='$ps_no'";
   mysql_query($sql_update);
   // 投票所管理員就此登出
   if($_SESSION['root']=='p_s_player'){ header("location:logout.php?action=close_win&msg=票數已登錄完成"); exit; }
   // 其餘人員繼續
   header("location:".$_SERVER['PHP_SELF']."?action=".$redir."&zone_no=".$zone_no);
   exit;
   
/* 單一投票所登錄選票作業 */   
}elseif($action=='single'){
   // 身份篩選
   if(!isset($_SESSION['root']) or (($_SESSION['root']<>'p_s_player') and ($_SESSION['root']<>'sys_player'))){
   	  header("location:logout.php");
   	  exit;
   }
   $echo_str  = "<html><head>\n";
   $echo_str .= "<meta http-equiv=\"Content-Type\" content=\"text/html; Charset=Big5\">\n";
   $echo_str .= "<Link Rel='stylesheet' Type='text/css' Href='style_c.css'>\n";
   $echo_str .= "<title>".$Title."--投票所登錄選票作業</title>\n";
   $echo_str .= "
<script language=\"JavaScript\">
<!--
function setBG(TheColor,TheObject) {TheObject.bgColor=TheColor}
//-->
</script>\n";
   $echo_str .= "</head><body>\n";
   $echo_str .= "<center><form action=".$_SERVER['PHP_SELF']." method=post>\n";
   $echo_str .= "<font color=009300 size=+1>".$Title."<br>--投票所登錄選票作業</font><br>\n";
   if($mode=='form'){
      $sql_select="select * from $P_S_tbl where UCASE(ps_user)='".strtoupper($_SESSION['username'])."' and password='".strtoupper($_SESSION['password'])."'";
      $result_ps=mysql_query($sql_select);
      $ps=mysql_fetch_object($result_ps);
      // 投票所管理員只可登錄一次
      if(($ps->_lock) and ($_SESSION['root']=='p_s_player')){ header("location:logout.php?action=close_win&msg=票數僅能登錄一次"); exit; }
      $ps_name = $ps->ps_name;
      /* 密碼錯誤時轉向至投票所選單 */
//      if($ps->password<>$vote_pass){
//         header("location:".$_SERVER['PHP_SELF']."?action=single");
//         exit;
//      }
      $echo_str .= "<table border=1 cellspacing=0 style=\"border-collapse: collapse\" bordercolor=orange width=100>\n";
      $echo_str .= "<tr align=center bgcolor=#FFC993 nowrap><td width=1%>動作</td><td width=1% nowrap>編號</td><td width=1%>投票所</td><td style='font-size:36px' width=1%>有效票數</td><td style='font-size:36px' width=1%>投票權人</td>";
//      $echo_str .= "<tr bgcolor=orange><td>動作</td><td>編號</td><td>投票所</td><td>投票數</td><td>選舉人</td>";
   
      $sql_select = "select * from $Candidate_tbl order by sno";
      $result_c = mysql_query($sql_select);
      $num_ca = mysql_num_rows($result_c);
      while ($ca=mysql_fetch_object($result_c)){
         $echo_str .= "<td valign=top style='font-size:36px'>".$ca->sno."<br>".stripslashes($ca->name)."</td>";
      }
      $echo_str .= "<td style='font-size:36px'>無效票數</td><td>動作</td></tr>\n";
   
      /* 顯示登錄表單 */
      $sql_select="select * from $Vote_tbl where ps_name='".$ps_name."'";
      $result_vt=mysql_query($sql_select);
      $vt=mysql_fetch_object($result_vt);
      $echo_str .= "<tr bgcolor=pink>";
      $echo_str .= "<td>";
      $echo_str .= "<input type=\"hidden\" name=\"action\" value=\"update\">";
      $echo_str .= "<input type=\"hidden\" name=\"rid\" value=\"$vt->rid\">";
      $echo_str .= "<input type=\"hidden\" name=\"ps_no\" value=\"$vt->ps_no\">";
      $echo_str .= "<input type=\"hidden\" name=\"num_ca\" value=\"$num_ca\">";
      $echo_str .= "<input type=\"hidden\" name=\"redir\" value=\"single\">";
      $echo_str .= "<input type=\"button\" name=\"B1\" value=\"登出\" onclick=\"location='logout.php'\">";
//      $echo_str .= "<input type=\"submit\" name=\"B1\" value=\"傳送\">";
      $echo_str .= "</td>";
      $echo_str .= "<td align=center>".$vt->ps_no."</td>";
      $echo_str .= "<td align=center><font color=blue size=+1>".$vt->ps_name."</font></td>";
      $echo_str .= "<td align=center>".(empty($vt->total)?"&nbsp;":$vt->total)."</td>";
      $echo_str .= "<td align=center><input style='color:red;background:yellow;font-weight:900' type=\"text\" name=\"voters\" size=\"5\" value=\"".$vt->voters."\"></td>";
      for($i=0;$i<$num_ca;$i++){
      	 $tmp = mysql_field_name($result_vt,$i+6);
         $echo_str .= "<td align=center><input style='color:red;background:yellow;font-weight:900' type=\"text\" name=\"$tmp\" size=\"5\" value=\"".$vt->$tmp."\"></td>";
      }
      $echo_str .= "<td align=center><input style='color:red;background:yellow;font-weight:900' type=\"text\" name=\"invalid\" size=\"5\" value=\"".$vt->invalid."\"></td>";
      $echo_str .= "<td align=center><input type=\"submit\" name=\"B2\" value=\"傳送\" style=\"background:red;color:yellow\"></td>";
      $echo_str .= "</tr>\n";
      $echo_str .= "</table>\n";
      $echo_str .= "<br><label style='background:".$alert_bg_clr."'>&nbsp;<font color=red size=+1>☆ 投票結果只能登錄傳送一次，請謹慎核對正確後再行傳送！ ☆</font>&nbsp;</label>\n";
      $echo_str .= "<br><br><font color=green>【按鍵說明】：「<font color=cryan>Tab</font>」向右(→)跳格。「<font color=cryan>Shift</font>」+「<font color=cryan>Tab</font>」向左(←)跳格。</font>\n";
      $echo_str .= "<br><br>|<a href=logout.php>暫時先登出</a>|\n";
      
   }elseif($mode=='pass'){
      $echo_str .= "<br><label style='background:".$alert_bg_clr."'>&nbsp;請輸入【<font color=blue size=+1>".$ps_name."</font>】投開票所帳號：<input type=text name=vote_user size=10>&nbsp;及&nbsp;密碼：<input type=text name=vote_pass size=10>&nbsp;</label>\n";
      $echo_str .= "<input type=hidden name=action value=single>\n";
      $echo_str .= "<input type=hidden name=mode value=form>\n";
      $echo_str .= "<input type=hidden name=ps_name value=".$ps_name.">\n";
      $echo_str .= "<br><br><font color=red>☆ 輸入完成後，請直接按 Enter 鍵即可。</font>\n";
   }else{
      $echo_str .= "請選擇投開票所\n";
      $echo_str .= "<table border=1 cellspacing=0 bordercolorlight=orange bordercolordark=ffffff>\n";
      $echo_str .= "<tr bgcolor=orange><td>編號</td><td>投開票所名稱</td><td>備註</td></tr>\n";
      $sql_select="select * from $P_S_tbl order by ps_no";
      $result_ps=mysql_query($sql_select);
      while ($ps=mysql_fetch_object($result_ps)){
      	 $bgclr = ($ps->_lock)?"#FFFF80":"#FFFFFF";
         $echo_str .= "<tr bgcolor=".$bgclr." onMouseOver=setBG('#FFFF00',this) onMouseout=setBG('".$bgclr."',this)>";
         $echo_str .= "<td>".(empty($ps->ps_no)?"&nbsp;":$ps->ps_no)."</td>";
         $sql_select2="select * from $Vote_tbl where ps_no='".$ps->ps_no."'";
         $result_ps2=mysql_query($sql_select2);
         $ps2 = mysql_fetch_object($result_ps2);
         if($ps->_lock and $ps2->total)
            $echo_str .= "<td>".(empty($ps->ps_name)?"&nbsp;":$ps->ps_name)."(<font color=green>已登錄且被鎖定</font>)</td>";
         elseif($ps->_lock and !($ps2->total))
            $echo_str .= "<td>".(empty($ps->ps_name)?"&nbsp;":$ps->ps_name)."(<font color=blue>未登錄但被鎖定</font>)</td>";
         elseif(!($ps->_lock) and $ps2->total)
            $echo_str .= "<td>".(empty($ps->ps_name)?"&nbsp;":$ps->ps_name)."(<font color=red>已登錄但未鎖定</font>)</td>";
         else
            $echo_str .= "<td><a href=".$_SERVER['PHP_SELF']."?ps_name=".$ps->ps_name."&action=single&mode=form>".(empty($ps->ps_name)?"&nbsp;":$ps->ps_name)."</a></td>";
         $echo_str .= "<td>".(empty($ps->remark)?"&nbsp;":$ps->remark)."</td>";
         $echo_str .= "</tr>\n";
      }
      $echo_str .= "</table>\n";
      $echo_str .= "<a href='javascript:window.close();'>關閉本視窗</a>|<a href=logout.php>登出</a>\n";
   }
   $echo_str .= "</form></center>\n";
   $echo_str .= "</body></html>\n";
   
   echo $echo_str;
   exit;
   
/* 所有投票所統一登錄選票作業 */   
//}elseif(($action=='all') and (($_SESSION['fullname']=='林炘焄') or ($_SESSION['fullname']=='甘之信'))){
}elseif($action=='all'){
   // 身份篩選
   if(!isset($_SESSION['root']) or (($_SESSION['root']<>'zone_player') and ($_SESSION['root']<>'sys_player'))){
   	  header("location:logout.php");
   	  exit;
   }
   
   $echo_str  = "<html><head>\n";
   $echo_str .= "<Link Rel='stylesheet' Type='text/css' Href='style_c.css'>\n";
   $echo_str .= "<title>".$Title."--登錄選票作業</title>\n";
   $echo_str .= "
<script language=\"JavaScript\">
<!--
function setBG(TheColor,TheObject) {TheObject.bgColor=TheColor}
//-->
</script>\n";
   $ss = (SID=='')?8:9;	/* 若網址列會帶出SID的值時，則要多跳一個表單位置 */
   $echo_str .= "</head><body onLoad=\"set_default(".$ss.")\">\n";
   $echo_str .= "<center><form name=\"myform\" action=".$_SERVER['PHP_SELF']."?action=all method=post>\n";
   $echo_str .= "登錄各投開票所回報票數管理系統<br>\n";
   $echo_str .= "<table border=0 cellspacing=0 style=\"border-collapse: collapse\">\n";
   $echo_str .= "<tr><td>\n";
   $echo_str .= "<table border=0 cellspacing=0 style=\"border-collapse: collapse\" width=100%>\n";
   $echo_str .= "<tr>\n";
   $echo_str .= "<td>行政分區：".select_option_zone($zone_no,'zone_no',$mode)."</td>\n";
   $echo_str .= "<td align=right><a href=logout.php>登出</a></td>\n";
   $echo_str .= "</tr></table>\n";
   $echo_str .= "</td></tr>\n";
   $echo_str .= "<tr><td>\n";
   $echo_str .= "<table border=1 cellspacing=0 cellpadding=2 style=\"border-collapse: collapse\" bordercolor=#E87400 width=100>\n";
   $echo_str .= "<tr align=center bgcolor=#FFC993 nowrap><td width=1%>動作</td><td width=1% nowrap>編號</td><td width=1%>投票所</td><td style='font-size:36px' width=1%>有效票數</td><td style='font-size:36px' width=1%>投票權人</td>";
   
   $sql_select = "select * from $Candidate_tbl order by sno";
   $result_c = mysql_query($sql_select);
   $num_ca = mysql_num_rows($result_c);
   while ($ca=mysql_fetch_object($result_c)){
      $echo_str .= "<td valign=top style='font-size:36px' width=1%><font style='font-size:40px;font-family:Wingdings 2;'>".$num_circle[$ca->sno]."</font><br>".stripslashes($ca->name)."</td>";
   }
   $echo_str .= "<td style='font-size:36px' width=1%>無效票數</td></tr>\n";
   
   /* 在第一列顯示登錄表單 */
   if($mode=="form"){
      $sql_select="select * from $Vote_tbl where rid='".$rid."'";
      $result_vt=mysql_query($sql_select);
      $vt=mysql_fetch_object($result_vt);
      $echo_str .= "<tr align=center bgcolor=pink>\n";
      $echo_str .= "<td>\n";
      $echo_str .= "<input type=\"hidden\" name=\"action\" value=\"update\">\n";
      $echo_str .= "<input type=\"hidden\" name=\"rid\" value=\"".$vt->rid."\">\n";
      $echo_str .= "<input type=\"hidden\" name=\"ps_no\" value=\"".$vt->ps_no."\">\n";
      $echo_str .= "<input type=\"hidden\" name=\"num_ca\" value=\"".$num_ca."\">\n";
      $echo_str .= "<input type=\"hidden\" name=\"redir\" value=\"all\">\n";
      $echo_str .= "<input type=\"button\" name=\"B1\" value=\"取消\" style=\"background:pink;\" onclick=\"location='javascript:window.history.back()'\">\n";
      $echo_str .= "</td>\n";
      $echo_str .= "<td nowrap><font color=#FF0000><b>".$vt->ps_no."</b></font></td>\n";
      $echo_str .= "<td nowrap><font color=#FF0000><b>".$vt->ps_name."</b></font></td>\n";
      $echo_str .= "<td nowrap><font color=#FF0000><b>".(empty($vt->total)?"&nbsp;":$vt->total)."</b></font></td>\n";
      $intval = (SID=='')?0:1;	/* 若網址列會帶出SID的值時，則要多跳一個表單位置 */
      $echo_str .= "<td nowrap><input style='color:red;background:yellow;font-weight:900' type=\"text\" name=\"voters\" size=\"5\" onFocus=\"set_ower(this,".(8+$intval).")\" onBlur=\"unset_ower(this)\" value=\"".$vt->voters."\"></td>\n";
      for($i=0;$i<$num_ca;$i++){
      	 $tmp = mysql_field_name($result_vt,$i+6);
         $echo_str .= "<td><input style='color:red;background:yellow;font-weight:900' type=\"text\" name=\"$tmp\" size=\"5\" onFocus=\"set_ower(this,".($i+9+$intval).")\" onBlur=\"unset_ower(this)\" value=\"".$vt->$tmp."\"></td>\n";
      }
      $echo_str .= "<td><input style='color:blue;background:yellow;font-weight:900' type=\"text\" name=\"invalid\" size=\"5\" value=\"".$vt->invalid."\"></td>\n";
      $echo_str .= "<td><input type=button name=b1 value=儲存 onclick='javascript:this.form.submit();' style=\"background:#FF0000;color:#FFFF00\"></td>\n";
//      $echo_str .= "<td><input type=\"submit\" name=\"B2\" value=\"儲存\" style=\"background:#FF0000;color:#FFFF00\"></td>\n";
      $echo_str .= "</tr>\n";
   }
   // 預設分區
   if(($_SESSION['root']=='sys_player') and (!$zone_no)){
      $sql_select_z="select * from $Zone_tbl order by ZoneNum*1";
      $result_z = mysql_query($sql_select_z);
      $oz = mysql_fetch_object($result_z);
      $zone_no = $oz->ZoneNum;
   }
   // 篩選分區之投票所
   $sql_select="select * from $P_S_sift_tbl where ZoneNum='$zone_no' order by ps_no";
   $result_zone=mysql_query($sql_select);
   $o_zone=mysql_fetch_object($result_zone);
   $sub_range = "('".$o_zone->ps_no."'";
   while($o_zone=mysql_fetch_object($result_zone)){
      $sub_range .= ",'".$o_zone->ps_no."'";
   }
   $sub_range .= ")";
   //
   $sql_select="select * from $Vote_tbl where ps_no IN ".$sub_range." order by ps_no";
   $result_vt=mysql_query($sql_select);
   $c=1;
   while ($vt=mysql_fetch_object($result_vt)){

      $echo_str .= "<tr align=center onMouseOver=setBG('#FFFF00',this) onMouseout=setBG('',this)>\n";
      $echo_str .= "<td><input type=\"button\" value=\"登錄\" name=\"B1\" onclick=\"location='".$_SERVER['PHP_SELF']."?action=all&mode=form&zone_no=$zone_no&rid=".$vt->rid."'\"></td>\n";
      $echo_str .= "<td nowrap>".$vt->ps_no."</td>\n";
      $echo_str .= "<td nowrap>".$vt->ps_name."</td>\n";
      $echo_str .= "<td style='font-weight:900'><font color=red>".(empty($vt->total)?"&nbsp;":$vt->total)."</font></td>\n";
      $echo_str .= "<td style='font-weight:900'><font color=green>".(empty($vt->voters)?"&nbsp;":$vt->voters)."</font></td>\n";
      for($i=0;$i<$num_ca;$i++){
      	 $tmp = mysql_field_name($result_vt,$i+6);
         $echo_str .= "<td>".(empty($vt->$tmp)?"&nbsp;":$vt->$tmp)."</td>";
      }
      $echo_str .= "<td style='font-weight:900'><font color=blue>".(empty($vt->invalid)?"&nbsp;":$vt->invalid)."</font></td>\n";
      $total = $vt->total*1 + $vt->invalid*1;
      if($total > ($vt->voters*1)) $echo_str .= "<td style='background:red;color:yellow;font-weight:900' nowrap>← 不合理</td>\n";
      $echo_str .= "</tr>\n";  
   }

   $echo_str .= "</table>\n";
   $echo_str .= "</td></tr></table>\n";
   $echo_str .= "</form></center>\n";
   $echo_str .= java_script1(1);
   $echo_str .= "</body></html>\n";
}

echo $echo_str;

/* 比賽項目選單函數 */
function select_option_zone($zone_no,$name,$mode=0)
{
   global   $Zone_tbl;

   $script = "<select name=".$name." STYLE='COLOR:brown;background-color:F0FFDD;font-size:12px'".(($mode=='form')?" disabled":" onchange='this.form.submit()'").">\n";
//   $script .= "<option value=''>-----</option>\n";
   if($_SESSION['root']=='sys_player')
      $sql_select="select * from $Zone_tbl order by ZoneNum*1";
   elseif($_SESSION['root']=='zone_player')
      $sql_select="Select * from $Zone_tbl where UCASE(ZoneUser)='".strtoupper($_SESSION['username'])."' and ZonePass='".$_SESSION['password']."' order by ZoneNum*1";

   $result = mysql_query($sql_select);
   while($o=mysql_fetch_object($result)){
       $ZoneNo    = $o->ZoneNum;
       $ZoneName  = stripslashes($o->ZoneName);
              
       $script .= "<option value=".$ZoneNo.(($ZoneNo==$zone_no)?' selected':'').">".$ZoneNo.".".$ZoneName."</option>\n";
   }
   $script .= "</select>\n";
   if($mode=='form') $script .= "<input type=hidden name=zone_no value=$zone_no>\n";
   
   return $script;
}
?>