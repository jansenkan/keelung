<?
/*--------------------------------
   眔布だ猂 
----------------------------------*/
include "config.inc.php";
$action    = isset($_GET['action'])?$_GET['action']:'';
$start_rec = isset($_GET['start_rec'])?$_GET['start_rec']:0;
/* 肅︹ */
$title_clr       = "cryan";
$title_bg_clr    = "#666666";
$title_txt_clr   = "yellow";
$content_txt_clr = "purple";
$total_clr       = "red";
$ending_clr  	   = "red";
$time_clr        = "green";
//
$count['voters']       = 0; // 羆匡羭计
$count['valid']        = 0; // 羆щ布计
$count['invalid']      = 0; // 羆礚布计
$count['voter_in']     = 0; // 秨布计(ゼщ布)参璸
$count['voter_not_in'] = 0; // ゼ秨布计参璸
$count['ps_in']        = 0; // 秨щ布┮参璸
$count['ps_not_in']    = 0; // ゼ秨щ布┮参璸
$num_circle = array('1' => 'j', '2' => 'k', '3' => 'l', '4' => 'm', '5' => 'n');
//
$sql_select_ca="select * from $Candidate_tbl order by sno*1";
$result_ca = mysql_query($sql_select_ca);
$num_ca = mysql_num_rows($result_ca);
//
$echo_str = "<html><head>\n";
$echo_str .= "<meta http-equiv=\"Content-Type\" content=\"text/html; Charset=Big5\">\n";

// 跋だ猂
if($action=='all'){
   $echo_str .= "<META HTTP-EQUIV=REFRESH CONTENT='".$delay.";URL=".$_SERVER['PHP_SELF']."?action=all'>\n";
   $echo_str .= "<Link Rel='stylesheet' Type='text/css' Href='style_c.css'>\n";
   $echo_str .= "<title>".$Title."--秨布近冀穨</title>\n";
   $echo_str .= "</head><body style='margin-top:2;background:#E0E0E0'>\n";
   $echo_str .= "<center>\n";
//   $echo_str .= "<font size=2 color=blue>(セ礶–".$delay."牧笆近冀Ω)</font>\n";
   $echo_str .= "<font size=2> ┪も笆翴匡 <a href=".$_SERVER['PHP_SELF']."?action=all>穝</a></font><br>\n";
   
   $sql_select="select * from $Vote_tbl order by ps_no";
   $result_vt=mysql_query($sql_select);

   // 参璸
   while ($vt=mysql_fetch_object($result_vt)){
      $count['valid']   += $vt->total*1;
      $count['invalid'] += $vt->invalid*1;
      $count['voters']  += $vt->voters*1;
      if($vt->total*1 > 0){
      	 $count['ps_in']++;
         $count['voter_in']  += $vt->voters*1;
      }else{
         $count['ps_not_in']++;
         $count['voter_not_in']  += $vt->voters*1;
      }
      
   }
   // 埃计 0 矪瞶
   if($count['voter_in'])
      $percent = sprintf("%4.1f",(($count['valid']+$count['invalid'])*100) / $count['voter_in']);
   else
      $percent = '0.0';
   // 参璸戈
   $echo_str .= "<table border=0 cellspacing=0 cellpadding=0 style=\"border-collapse: collapse;font-family:夹发砰\" bordercolor=#009380 width=100%>\n";
   $echo_str .= "<tr><td width=1% style='font-size:30px;background:#666666;color:#FFFFFF;' nowrap>&nbsp;&nbsp;<br>&nbsp;跋&nbsp;</td><td>\n";
   $echo_str .= "<table border=1 cellspacing=0 style=\"border-collapse: collapse\" bordercolor=#666666 width=100%>\n";
   $echo_str .= "<tr style='font-size:28px'><td align=right width=16% nowrap>秨布┮</td><td width=16%>".$count['ps_in']."</td><td align=right width=16% nowrap>Τ布</td><td width=16%>".$count['valid']."</td><td align=right width=16% nowrap>щ布舦</td><td width=20%>".$count['voters']."</td></tr>\n";
   $echo_str .= "<tr style='font-size:28px'><td align=right width=16% nowrap>ゼ秨布┮</td><td width=16%>".$count['ps_not_in']."</td><td align=right width=16% nowrap>礚布</td><td width=16%>".$count['invalid']."</td><td align=right width=16% nowrap>щ布瞯</td><td width=20%>".$percent."</td></tr>\n";
   $echo_str .= "</table>\n";
   $echo_str .= "</td></tr></html>\n";
   $echo_str .= "</center>\n";
   $echo_str .= "</body></html>\n";
	
// だ跋だ猂(щ布┮)
}elseif($action=='zone_ps'){
   $sql_select_z="select * from $Zone_tbl order by ZoneNum*1";
   $result_z = mysql_query($sql_select_z);
   $num_z = mysql_num_rows($result_z);
   if(!isset($start_rec) or ($start_rec >= $num_z)){$start_rec = 0;$sort = 1;}

   $echo_str .= "<META HTTP-EQUIV=REFRESH CONTENT='".$delay.";URL=".$_SERVER['PHP_SELF']."?action=zone_ps&start_rec=".($start_rec+1)."'>\n";
   $echo_str .= "<Link Rel='stylesheet' Type='text/css' Href='style_c.css'>\n";
   $echo_str .= "<title>".$Title."--秨布近冀穨</title>\n";
   $echo_str .= "</head><body style='margin-top:2px;margin-right:2px;background:#FFFFD0'>\n";
   $echo_str .= "<center>\n";
//   $echo_str .= "<font size=+1 color=$title_clr>".$Title."(щ布┮)</font><br>\n";
   
   /* 璸布挡ぇ夹肈弧 */
//   if($Ending)
//       $echo_str .= "<font size=4 color=$time_clr><b><i>璸布挡</b></i></font>";
//   else
//      $echo_str .= "<font size=2 color=$time_clr>礶穝丁".$process_date."</font>";
   
//   $echo_str .= "<font size=2 color=blue>(セ礶–".$delay."牧笆近冀Ω)</font>\n";
   $echo_str .= "<font size=2> ┪も笆翴匡 <a href=".$_SERVER['PHP_SELF']."?action=zone_ps&start_rec=".($start_rec+1).">穝</a></font><br>\n";
   
   $sql_select_z="select * from $Zone_tbl order by ZoneNum*1 LIMIT ".$start_rec.", 1";
   $result_z = mysql_query($sql_select_z);
   $oz = mysql_fetch_object($result_z);
   $zone_no = $oz->ZoneNum;
   $zone_na = $oz->ZoneName;
   
   $echo_str .= "<table border=0 cellspacing=0 style=\"border-collapse: collapse\" bordercolor=#009380 width=100%>\n";
   $echo_str .= "<tr><td colspan=2>\n";
   $echo_str .= "<table border=1 cellspacing=0 style=\"border-collapse: collapse;font-family:夹发砰\" bordercolor=#666666 width=100%>\n";
   $echo_str .= "<tr><td style='font-size:24px;background:#666666;color:#FFFFFF;' width=1% nowrap>&nbsp;だ&nbsp;跋&nbsp;</td>\n";
   $echo_str .= "<td style='font-size:24px;' align=center>".$zone_na."</td>\n";
   $echo_str .= "</tr></table>\n";
   $echo_str .= "</td></tr>\n";
   $echo_str .= "<tr><td colspan=2>\n";
   $tmp_str2  = "</td></tr>\n";
   $tmp_str2 .= "<tr><td colspan=2>\n";
   $tmp_str2 .= "<table border=1 cellspacing=0 style=\"border-collapse: collapse\"  bordercolor=#009380 width=100%>\n";
   $tmp_str2 .= "<tr bgcolor=$title_bg_clr><td><font color=$title_txt_clr>щ布┮</font></td>";
   $tmp_str2 .= "<td><font color=$title_txt_clr>匡羭</font></td>";
   $tmp_str2 .= "<td><font color=$title_txt_clr>Τ布</font></td>";
   $tmp_str2 .= "<td><font color=$title_txt_clr>礚布</font></td>";
   $tmp_str2 .= "<td><font color=$title_txt_clr>щ布瞯</font></td>";
/*   
   $sql_select = "select * from $Candidate_tbl order by sno";
   $result_c = mysql_query($sql_select);
   $num_ca = mysql_num_rows($result_c);
   while ($ca=mysql_fetch_object($result_c)){
      $tmp_str2 .= "<td valign=top><font size=2 color=$title_txt_clr>$ca->sno<br>".stripslashes($ca->name)."</font></td>";
   }
*/
   $tmp_str2 .= "</tr>\n";

   // 縵匡だ跋ぇщ布┮
   $sql_select="select * from $P_S_sift_tbl where ZoneNum='$zone_no' order by ps_no";
   $result_zone=mysql_query($sql_select);
   $o_zone=mysql_fetch_object($result_zone);
   $sub_range = "('".$o_zone->ps_no."'";
   while($o_zone=mysql_fetch_object($result_zone)){
      $sub_range .= ",'".$o_zone->ps_no."'";
   }
   $sub_range .= ")";
   
   $sql_select="select * from $Vote_tbl where ps_no IN ".$sub_range." order by ps_no";
   $result_vt=mysql_query($sql_select);
   /* ﹟ゼ秨布ぇ矪瞶 */
   $data = @mysql_num_rows($result_vt);
   if($data<1){
      $tmp_str2 .= "</table>\n";
      $tmp_str2 .= "<br><div style=\"FILTER:glow(color:#308148,strength=3);color:#ff0000;font-size:20px;font-weight=900\">﹟礚ヴ癘布戈</div>";
      echo $echo_str.$tmp_str2;
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
      $tmp_str2 .= "<tr bgcolor=".$bgclr.">";
      $tmp_str2 .= "<td>".$vt->ps_name."</td>";
      $tmp_str2 .= "<td><font color=$total_clr><b><i>".(empty($vt->voters)?"&nbsp;":$vt->voters)."</b></i></font></td>";
      $tmp_str2 .= "<td><font color=$total_clr><b><i>".(empty($vt->total)?"&nbsp;":$vt->total)."</b></i></font></td>";
      $tmp_str2 .= "<td><font color=$total_clr><b><i>".(empty($vt->invalid)?"&nbsp;":$vt->invalid)."</b></i></font></td>";
      $tmp_str2 .= "<td><font color=$total_clr><b><i>".(empty($vt->total)?"&nbsp;":$vt->total)."</b></i></font></td>";
      // 参璸
      $count['valid']   += $vt->total*1;
      $count['invalid'] += $vt->invalid*1;
      $count['voters']  += $vt->voters*1;
      if($vt->total*1 > 0){
      	 $count['ps_in']++;
         $count['voter_in']  += $vt->voters*1;
      }else{
         $count['ps_not_in']++;
         $count['voter_not_in']  += $vt->voters*1;
      }
/*      
      for($i=0;$i<$num_ca;$i++){
      	 $tmp = mysql_field_name($result_vt,$i+6);
         $tmp_str2 .= "<td><font color=$content_txt_clr>".(empty($vt->$tmp)?"&nbsp;":$vt->$tmp)."</font></td>";
      }
*/
      $tmp_str2 .= "</tr>\n";
   
   }
   // 埃计 0 矪瞶
   if($count['voter_in'])
      $percent = sprintf("%4.1f",(($count['valid']+$count['invalid'])*100) / $count['voter_in']);
   else
      $percent = '0.0';
   // 参璸戈
   $tmp_str1 .= "<table border=1 cellspacing=0 style=\"border-collapse: collapse;font-size:16px\" bordercolor=#666666 width=100%>\n";
   $tmp_str1 .= "<tr><td align=right width=1% nowrap>щ布舦</td><td width=49%>".$count['voters']."</td><td align=right width=1% nowrap>щ布瞯</td><td width=49%>".$percent."%</td></tr>\n";
   $tmp_str1 .= "<tr><td align=right width=1% nowrap>秨布┮</td><td width=49%>".$count['ps_in']."</td><td align=right width=1% nowrap>Τ布</td><td width=49%>".$count['valid']."</td></tr>\n";
   $tmp_str1 .= "<tr><td align=right width=1% nowrap>ゼ秨布┮</td><td width=49%>".$count['ps_not_in']."</td><td align=right width=1% nowrap>礚布</td><td width=49%>".$count['invalid']."</td></tr>\n";
   $tmp_str1 .= "</table>\n";
   //
   $echo_str .= $tmp_str1.$tmp_str2;
   $echo_str .= "</table>\n";
   $echo_str .= "</td></tr></table>\n";
   $echo_str .= "</center>\n";
   $echo_str .= "</body></html>\n";

// だ跋だ猂(匡羭)
}elseif($action=='zone_ca'){
   $sql_select_z="select * from $Zone_tbl order by ZoneNum*1";
   $result_z = mysql_query($sql_select_z);
   $num_z = mysql_num_rows($result_z);
   if(!isset($start_rec) or ($start_rec >= $num_z)){$start_rec = 0;$sort = 1;}

   $echo_str .= "<META HTTP-EQUIV=REFRESH CONTENT='".$delay.";URL=".$_SERVER['PHP_SELF']."?action=zone_ca&start_rec=".($start_rec+1)."'>\n";
   $echo_str .= "<Link Rel='stylesheet' Type='text/css' Href='style_c.css'>\n";
   $echo_str .= "<title>".$Title."--秨布近冀穨</title>\n";
   $echo_str .= "</head><body style='margin-top:2px;margin-right:2px;background:#FFFFD0'>\n";
   $echo_str .= "<center>\n";
//   $echo_str .= "<font size=+1 color=$title_clr>".$Title."(щ布┮)</font><br>\n";
   
   /* 璸布挡ぇ夹肈弧 */
//   if($Ending)
//       $echo_str .= "<font size=4 color=$time_clr><b><i>璸布挡</b></i></font>";
//   else
//      $echo_str .= "<font size=2 color=$time_clr>礶穝丁".$process_date."</font>";
   
//   $echo_str .= "<font size=2 color=blue>(セ礶–".$delay."牧笆近冀Ω)</font>\n";
   $echo_str .= "<font size=2> ┪も笆翴匡 <a href=".$_SERVER['PHP_SELF']."?action=zone_ca&start_rec=".($start_rec+1).">穝</a></font><br>\n";
   
   $sql_select_z="select * from $Zone_tbl order by ZoneNum*1 LIMIT ".$start_rec.", 1";
   $result_z = mysql_query($sql_select_z);
   $oz = mysql_fetch_object($result_z);
   $zone_no = $oz->ZoneNum;
   $zone_na = $oz->ZoneName;
   
   $echo_str .= "<table border=0 cellspacing=0 style=\"border-collapse: collapse\" bordercolor=#009380 width=100%>\n";
   $echo_str .= "<tr><td colspan=2>\n";
   $echo_str .= "<table border=1 cellspacing=0 style=\"border-collapse: collapse;font-family:夹发砰\" bordercolor=#666666 width=100%>\n";
   $echo_str .= "<tr><td style='font-size:28px;background:#666666;color:#FFFFFF;' width=1% nowrap>&nbsp;だ&nbsp;跋&nbsp;</td>\n";
   $echo_str .= "<td style='font-size:32px;' align=center>".$zone_na."</td>\n";
   $echo_str .= "</tr></table>\n";
   $echo_str .= "</td></tr>\n";
   $echo_str .= "<tr><td colspan=2>\n";
   $tmp_str2  = "</td></tr>\n";
   $tmp_str2 .= "<tr><td colspan=2>\n";
   $tmp_str2 .= "<table border=1 cellspacing=0 style=\"border-collapse: collapse\"  bordercolor=#333333 width=100%>\n";
   $tmp_str2 .= "<tr bgcolor=$title_bg_clr style='font-size:24px'><td><font color=$title_txt_clr>獼匡</font></td>";
   $tmp_str2 .= "<td align=center><font color=$title_txt_clr>眔布计</font></td>";
   $tmp_str2 .= "<td align=center><font color=$title_txt_clr>眔布瞯</font></td>";
/*   
   $sql_select = "select * from $Candidate_tbl order by sno";
   $result_c = mysql_query($sql_select);
   $num_ca = mysql_num_rows($result_c);
   while ($ca=mysql_fetch_object($result_c)){
      $tmp_str2 .= "<td valign=top><font size=2 color=$title_txt_clr>$ca->sno<br>".stripslashes($ca->name)."</font></td>";
   }
*/
   $tmp_str2 .= "</tr>\n";

   // 縵匡だ跋ぇщ布┮
   $sql_select="select * from $P_S_sift_tbl where ZoneNum='$zone_no' order by ps_no";
   $result_zone=mysql_query($sql_select);
   $o_zone=mysql_fetch_object($result_zone);
   $sub_range = "('".$o_zone->ps_no."'";
   while($o_zone=mysql_fetch_object($result_zone)){
      $sub_range .= ",'".$o_zone->ps_no."'";
   }
   $sub_range .= ")";
   
   $sql_select="select * from $Vote_tbl where ps_no IN ".$sub_range." order by ps_no";
   $result_vt=mysql_query($sql_select);
   /* ﹟ゼ秨布ぇ矪瞶 */
   $data = @mysql_num_rows($result_vt);
   if($data<1){
      $tmp_str2 .= "</table>\n";
      $tmp_str2 .= "<br><div style=\"FILTER:glow(color:#308148,strength=3);color:#ff0000;font-size:20px;font-weight=900\">﹟礚ヴ癘布戈</div>";
      echo $echo_str.$tmp_str2;
      exit;
   }
   $c=1;
   for($i=0;$i<$num_ca;$i++){
      $count['idv'][$i] = 0;
   }
   //
   while ($vt=mysql_fetch_object($result_vt)){

      // 参璸
      $count['valid']   += $vt->total*1;
      $count['invalid'] += $vt->invalid*1;
      $count['voters']  += $vt->voters*1;
      if($vt->total*1 > 0){
      	 $count['ps_in']++;
         $count['voter_in']  += $vt->voters*1;
      }else{
         $count['ps_not_in']++;
         $count['voter_not_in']  += $vt->voters*1;
      }
//      
      for($i=0;$i<$num_ca;$i++){
      	 $tmp = mysql_field_name($result_vt,$i+6);
      	 $count['idv'][$i] += $vt->$tmp*1;
//         $tmp_str2 .= "<td><font color=$content_txt_clr>".(empty($vt->$tmp)?"&nbsp;":$vt->$tmp)."</font></td>";
      }
//
   }
   // 埃计 0 矪瞶
   if($count['voter_in'])
      $percent = sprintf("%4.1f",(($count['valid']+$count['invalid'])*100) / $count['voter_in']);
   else
      $percent = '0.0';
   // 参璸戈
   $tmp_str1 = "<table border=1 cellspacing=0 style='border-collapse: collapse;font-size:18px;FONT-FAMILY:\"Times New Roman\",\"Times\",\"serif\"' bordercolor=#666666 width=100%>\n";
   $tmp_str1 .= "<tr><td align=right width=1% nowrap>щ布舦</td><td width=49%>".$count['voters']."</td><td align=right width=1% nowrap>щ布瞯</td><td width=49%>".$percent."%</td></tr>\n";
   $tmp_str1 .= "<tr><td align=right width=1% nowrap>秨布┮</td><td width=49%>".$count['ps_in']."</td><td align=right width=1% nowrap>Τ布</td><td width=49%>".$count['valid']."</td></tr>\n";
   $tmp_str1 .= "<tr><td align=right width=1% nowrap>ゼ秨布┮</td><td width=49%>".$count['ps_not_in']."</td><td align=right width=1% nowrap>礚布</td><td width=49%>".$count['invalid']."</td></tr>\n";
   $tmp_str1 .= "</table>\n";
   //
   for($i=0;$i<$num_ca;$i++){
      if (($i % 2)==0){
         $bgclr="#E0E0E0";
      }else{
         $bgclr="#FFFFFF";
      }
      // 埃计 0 矪瞶
      if($count['voter_in'])
         $percent2 = sprintf("%4.1f",(($count['idv'][$i])*100) / $count['voter_in']);
      else
         $percent2 = '0.0';
      $tmp_str2 .= "<tr bgcolor=".$bgclr." style='font-size:24px;font-family:\"Times New Roman\",\"Times\",\"serif\",\"夹发砰\"'>";
      $tmp_str2 .= "<td><font style='font-size:30px;font-family:Wingdings 2;'>".$num_circle[$i+1]."</font>".get_cand_name($i+1)."</td>";
      $tmp_str2 .= "<td align=center><font color=$total_clr><b>".(empty($count['idv'][$i])?"&nbsp;":$count['idv'][$i])."</b></font></td>";
      $tmp_str2 .= "<td align=center><font color=$total_clr><b>".$percent2."%</b></font></td>";
      $tmp_str2 .= "</tr>\n";
   }
//   echo ($num_ca*1)."<br>";exit;
   $echo_str .= $tmp_str1.$tmp_str2;
   $echo_str .= "</table>\n";
   $echo_str .= "</td></tr></table>\n";
   $echo_str .= "</center>\n";
   $echo_str .= "</body></html>\n";

// ゼㄌ砏﹚よΑ聅凝セ呼
}else{
   $echo_str .= "</head><body style='margin-top:2'>\n";
   $echo_str .= "<center>\n";
   $echo_str .= "<h1>叫ㄌ砏﹚よΑ聅凝セ呼<br><br>Invalid Browse behavior!</h1>\n";
   $echo_str .= "</center>\n";
   $echo_str .= "</body></html>\n";
}

echo $echo_str;
?>