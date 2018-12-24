<?
#---------------------------------------------------
#   絪щ秨布┮布计ぇ祘Α
#   by jansen since 2003.03.16
#---------------------------------------------------

include "config.inc.php";
/* 跑计砞﹚ */
$sno     = isset($_POST['sno'])?$_POST['sno']:'';
$num_ca  = isset($_POST['num_ca'])?$_POST['num_ca']:'';
$remark  = isset($_POST['remark'])?$_POST['remark']:'';
$action  = isset($_POST['action'])?$_POST['action']:'';
$rid     = isset($_GET['rid'])?$_GET['rid']:'';
$rid     = isset($_POST['rid'])?$_POST['rid']:$rid;
$mode    = isset($_GET['mode'])?$_GET['mode']:'';
$mode    = isset($_POST['mode'])?$_POST['mode']:$mode;

if($action=="update"){
   $sql_update = "update $Cand_cnt_tbl set remark='$remark' where rid='$rid'";
   mysql_query($sql_update);
   header("location:".$_SERVER['PHP_SELF']);
   exit;

}elseif($action=="update_old"){

   $sql_select="select * from $Cand_cnt_tbl order by sno";
   $result=mysql_query($sql_select);
   $num_ps = mysql_num_rows($result);
   /* 絪匡布计 */
   $sql_update = "update $Cand_cnt_tbl set ";
   $cnt=0;
   for($i=0;$i<$num_ca;$i++){
     $tmp = mysql_field_name($result,$i+4);
     $num = ($_POST[$tmp])?$_POST[$tmp]:$_GET[$tmp];
     $sql_update .= $tmp."='".$num."',";
     $cnt += $num;
     
     /* 恶щ秨布┮旧ぇ眔布计 */
     $tmp2="sn".$sno;
     $tmp_sno=trim(substr($tmp,2,strlen($tmp)));
     $sql_update_cand_cnt="update $Vote_tbl set ".$tmp2."='".$$tmp."' where ps_no='".$tmp_sno."'";
     mysql_query($sql_update_cand_cnt);    
   }
   
   $sql_update .= "total='$cnt',remark='$remark' where rid='$rid'";
   mysql_query($sql_update);
   
   /* 羆щ秨布┮旧ぇ眔布计 */
   $sql_select="select * from $Vote_tbl order by ps_no";
   $result_vt=mysql_query($sql_select);
   while ($ca=mysql_fetch_object($result_vt)){
      $cnt=0;
      for($i=0;$i<$num_ps;$i++){
         $tmp = mysql_field_name($result_vt,$i+6);
         $cnt += $ca->$tmp;
      }
      $sql_update = "update $Vote_tbl set total='".$cnt."' where rid='".$ca->rid."'";
      mysql_query($sql_update);
   }
     
   header("location:".$_SERVER['PHP_SELF']);
   exit;
   
}else{

   $echo_str  = "<html><head>\n";
   $echo_str .= "<meta http-equiv=\"Content-Type\" content=\"text/html; Charset=Big5\">\n";
   $echo_str .= "<Link Rel='stylesheet' Type='text/css' Href='style_c.css'>\n";
   $echo_str .= "<title>".$Title."--爹癘恨瞶穨</title>\n";
   $echo_str .= '
<script language="JavaScript">
<!--
function setBG(TheColor,TheObject) {TheObject.bgColor=TheColor}
//-->
</script>';
   $echo_str .= "</head><body>\n";
   $echo_str .= "<center><form action=".$_SERVER['PHP_SELF']." method=post>\n";
   $echo_str .= "э匡布计の爹癘恨瞶╰参\n";
   $echo_str .= "<table border=1 cellspacing=0 bordercolorlight=orange bordercolordark=ffffff width=100>\n";
   $echo_str .= "<tr bgcolor=orange><td>笆</td><td>絪腹</td><td>匡</td><td>讽匡</td><td>眔布计</td>";
   /*
   $sql_select = "select * from $Vote_tbl order by ps_no";
   $result_c = mysql_query($sql_select);
   $num_ca = mysql_num_rows($result_c);
   while ($ca=mysql_fetch_object($result_c)){
      $echo_str .= "<td align=center>".$ca->ps_no."<br><font color=green>".$ca->ps_name."</font></td>";
   }
   */
   $echo_str .= "<td></td>";
   $echo_str .= "</tr>\n";
      
   $sql_select="select * from $Cand_cnt_tbl order by sno";
   $result_vt=mysql_query($sql_select);
   $c=1;
   while ($vt=mysql_fetch_object($result_vt)){
      /* э家Α*/
      if(($vt->rid==$rid)and($mode=='form')){
         $echo_str .= "<tr bgcolor=pink>";
         $echo_str .= "<td>";
         $echo_str .= "<input type=\"hidden\" name=\"action\" value=\"update\">";
         $echo_str .= "<input type=\"hidden\" name=\"rid\" value=\"$vt->rid\">";
         $echo_str .= "<input type=\"hidden\" name=\"sno\" value=\"$vt->sno\">";
         $echo_str .= "<input type=\"hidden\" name=\"num_ca\" value=\"$num_ca\">";
         $echo_str .= "<input type=\"submit\" name=\"B1\" value=\"OK\">";
         $echo_str .= "</td>";
         $echo_str .= "<td align=center>".$vt->sno."</td>";
         $echo_str .= "<td nowrap>".$vt->class.$vt->name."</td>";
         $echo_str .= "<td align=center>".select_option($vt->remark)."</td>";
         $echo_str .= "<td>".(empty($vt->total)?"&nbsp;":$vt->total)."</td>";
         /*
         for($i=0;$i<$num_ca;$i++){
         	 $tmp = mysql_field_name($result_vt,$i+4);
            $echo_str .= "<td align=center><input style='color=purple;background=yellow' type=\"text\" name=\"$tmp\" size=\"1\" value=\"".$vt->$tmp."\"></td>";
         }
         */
         $echo_str .= "<td><input type=\"submit\" name=\"B2\" value=\"OK\"></td>";
         $echo_str .= "</tr>\n";
      }else{
         if($vt->sex=="") $cand_clr="red"; else $cand_clr="blue";
         $echo_str .= "<tr onMouseOver=setBG('#FFFF00',this) onMouseout=setBG('',this)>";
         $echo_str .= "<td><input type=\"button\" value=\"э\" name=\"B1\" onclick=\"location='".$_SERVER['PHP_SELF']."?mode=form&rid=".$vt->rid."'\"></td>";
         $echo_str .= "<td align=center>".$vt->sno."</td>";
         $echo_str .= "<td nowrap><font color=$cand_clr>".$vt->class.$vt->name."</font></td>";
         $echo_str .= "<td align=center><font color=green>".(empty($vt->remark)?"&nbsp;":$vt->remark)."</font></td>";
         $echo_str .= "<td><font color=red>".(empty($vt->total)?"&nbsp;":$vt->total)."</font></td>";
         /*
         for($i=0;$i<$num_ca;$i++){
         	 $tmp = mysql_field_name($result_vt,$i+4);
            $echo_str .= "<td align=center>".(empty($vt->$tmp)?"&nbsp;":$vt->$tmp)."</td>";
         }
         */
         $echo_str .= "<td></td>";
         $echo_str .= "</tr>\n";  
      }
   }

   $echo_str .= "</table>\n";
   $echo_str .= "</form></center>\n";
   $echo_str .= "</body></html>\n";
}

echo $echo_str;

/* 讽匡ぇ匡兜虫(select) */
function select_option($rem)
{

   $script = "<select name=remark>\n";
   $script .= "<option value=''".(($rem=='')?' selected':'')."></option>\n";
   $script .= "<option value='V'".(($rem=='V')?' selected':'').">V</option>\n";
   $script .= "</select>\n";
   
   return $script;
}
?>