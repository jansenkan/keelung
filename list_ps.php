<?
include "config.inc.php";
/* 肅︹ */
$title_clr       ="cryan";
$title_bg_clr    ="#009380";
$title_txt_clr   ="yellow";
$content_txt_clr ="purple";
$total_clr       ="red";
$ending_clr  	   ="red";
$time_clr        ="green";

$echo_str = "<html><head>\n";
$echo_str .= "<meta http-equiv=\"Content-Type\" content=\"text/html; Charset=Big5\">\n";
$echo_str .= "<META HTTP-EQUIV=REFRESH CONTENT='".$delay.";URL=list_cand.php'>\n";
$echo_str .= "<Link Rel='stylesheet' Type='text/css' Href='style_c.css'>\n";
$echo_str .= "<title>".$Title."--秨布近冀穨</title>\n";
$echo_str .= "</head><body style='margin-top:2'>\n";
$echo_str .= "<center><form action=".$_SERVER['PHP_SELF'].">\n";
$echo_str .= "<font size=+1 color=$title_clr>".$Title."(щ布┮)</font><br>\n";
   
/* 璸布挡ぇ夹肈弧 */
if($Ending)
   $echo_str .= "<font size=4 color=$time_clr><b><i>璸布挡</b></i></font>";
else
   $echo_str .= "<font size=2 color=$time_clr>礶穝丁".$process_date."</font>";
   
   $echo_str .= "<font size=2 color=blue>(セ礶–".$delay."牧笆近冀Ω)</font>\n";
   $echo_str .= "<font size=2> ┪も笆翴匡 <a href=list_cand.php>礶</a></font><br>\n";
   $echo_str .= "<table border=1 cellspacing=0 bordercolorlight=#009380 bordercolordark=ffffff width=100>\n";
   $echo_str .= "<tr bgcolor=$title_bg_clr><td><font color=$title_txt_clr>щ布┮</font></td>";
   $echo_str .= "<td><font color=$title_txt_clr>羆布计</font></td>";
   
   $sql_select = "select * from $Candidate_tbl order by sno";
   $result_c = mysql_query($sql_select);
   $num_ca = mysql_num_rows($result_c);
   while ($ca=mysql_fetch_object($result_c)){
      $echo_str .= "<td valign=top><font size=2 color=$title_txt_clr>$ca->sno<br>".stripslashes($ca->name)."</font></td>";
   }
   $echo_str .= "</tr>\n";

   $sql_select="select * from $Vote_tbl order by ps_no";
   $result_vt=mysql_query($sql_select);
   /* ﹟ゼ秨布ぇ矪瞶 */
   $data = @mysql_num_rows($result_vt);
   if($data<1){
      $echo_str .= "</table>\n";
      $echo_str .= "<br><div style=\"FILTER:glow(color:#308148,strength=3);color:#ff0000;font-size:20px;font-weight=900\">﹟礚ヴ癘布戈</div>";
      echo $echo_str;
      exit;
   }
   $c=1;
   while ($vt=mysql_fetch_object($result_vt)){

      if (($c % 2)==0){
         $bgclr="#E0E0E0";
      }else{
         $bgclr="#FFFFFF";
      }
      $c++;
      $echo_str .= "<tr bgcolor=".$bgclr.">";
      $echo_str .= "<td nowrap>".$vt->ps_name."</td>";
      $echo_str .= "<td><font color=$total_clr><b><i>".(empty($vt->total)?"&nbsp;":$vt->total)."</b></i></font></td>";
      for($i=0;$i<$num_ca;$i++){
      	 $tmp = mysql_field_name($result_vt,$i+6);
         $echo_str .= "<td><font color=$content_txt_clr>".(empty($vt->$tmp)?"&nbsp;":$vt->$tmp)."</font></td>";
      }
      $echo_str .= "</tr>\n";
   
   }

   $echo_str .= "</table>\n";
   $echo_str .= "</form></center>\n";
   $echo_str .= "</body></html>\n";
   
echo $echo_str;
?>