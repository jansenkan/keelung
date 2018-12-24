<?
include "config.inc.php";
/* 顏色表 */
$title_clr       ="cryan";
$title_bg_clr    ="orange";
$title_txt_clr   ="green";
$content_txt_clr ="purple";
$total_clr       ="red";
$ending_clr  	 ="red";
$rank_clr        ="blue";
$mark_clr        ="blue";
$time_clr        ="green";
$number_clr      ="green";

$echo_str = "<html><head>\n";
$echo_str .= "<meta http-equiv=\"Content-Type\" content=\"text/html; Charset=Big5\">\n";
$echo_str .= "<META HTTP-EQUIV=REFRESH CONTENT='".$delay.";URL=list_ps.php'>\n";
$echo_str .= "<Link Rel='stylesheet' Type='text/css' Href='style_c.css'>\n";
$echo_str .= "<title>".$Title."--開票輪播作業</title>\n";
$echo_str .= "</head><body style='margin-top:2'>\n";
$echo_str .= "<center><form action=".$_SERVER['PHP_SELF'].">\n";
$echo_str .= "<font size=+1 color=$title_clr>".$Title."(候選人)</font><br>\n";
   
/* 計票結束時之標題說明 */
if($Ending)
   $echo_str .= "<font size=4 color=$time_clr><b><i>《計票結束》</b></i><font size=3>當選名單為電腦自行判別僅供參考</font></font>";
else
   $echo_str .= "<font size=2 color=$time_clr>畫面更新時間：".$process_date."</font>";
   
   $echo_str .= "<font size=2 color=blue>　(本畫面每".$delay."秒鐘自動輪播一次)</font>\n";
   $echo_str .= "<font size=2> 或手動點選 <a href=list_ps.php>下一畫面》</a></font><br>\n";
   $echo_str .= "<table border=1 cellspacing=0 bordercolorlight=orange bordercolordark=ffffff width=100>\n";
   $echo_str .= "<tr bgcolor=$title_bg_clr>";
   $echo_str .= "<td><font size=2 color=$title_txt_clr>排名</font></td>";
   $echo_str .= "<td><font color=$title_txt_clr>候選人</font></td>";
   $echo_str .= "<td><font size=2 color=$title_txt_clr>當選否</font></td>";
   $echo_str .= "<td><font size=2 color=$title_txt_clr>總得票</font></td>";
   
   $sql_select = "select * from $P_S_tbl order by ps_no";
   $result_c = mysql_query($sql_select);
   $num_ca = mysql_num_rows($result_c);
   while ($ca=mysql_fetch_object($result_c)){
      $echo_str .= "<td><font size=2 color=$title_txt_clr>$ca->ps_name</font></td>";
   }
   $echo_str .= "</tr>\n";

   $sql_select="select * from $Cand_cnt_tbl order by total DESC";
   $result_vt=mysql_query($sql_select);
   /* 尚未開票時之處理 */
   $data = @mysql_num_rows($result_vt);
   if($data<1){
      $echo_str .= "</table>\n";
      $echo_str .= "<br><div style=\"FILTER:glow(color:#308148,strength=3);color:#ff0000;font-size:20px;font-weight=900\">尚無任何記票資料！</div>";
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
      if ($vt->sex=="女") $cand_clr="red"; else $cand_clr="blue";
      $echo_str .= "<tr bgcolor=".$bgclr.">";
      $echo_str .= "<td><font color=$rank_clr>".$c++."</font></td>";
      $echo_str .= "<td nowrap><font color=$cand_clr>(<font color=$number_clr>".$vt->sno."</font>)".$vt->class."-".stripslashes($vt->name)."</font></td>";
      $echo_str .= "<td><font color=$mark_clr><b><i>".(empty($vt->remark)?"&nbsp;":"<img src=images/smile.gif>")."</i></b></font></td>";
      $echo_str .= "<td><font color=$total_clr><b><i>".(empty($vt->total)?"&nbsp;":$vt->total)."</i></b></font></td>";
      for($i=0;$i<$num_ca;$i++){
      	 $tmp = mysql_field_name($result_vt,$i+4);
         $echo_str .= "<td><font color=$content_txt_clr>".(empty($vt->$tmp)?"&nbsp;":$vt->$tmp)."</font></td>";
      }
      $echo_str .= "</tr>\n";
   
   }

   $echo_str .= "</table>\n";
   $echo_str .= "</form></center>\n";
   $echo_str .= "</body></html>\n";
   
echo $echo_str;
?>