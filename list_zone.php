<?
/*--------------------------------
   �o�����R 
----------------------------------*/
include "config.inc.php";
$action    = isset($_GET['action'])?$_GET['action']:'';
$start_rec = isset($_GET['start_rec'])?$_GET['start_rec']:0;
/* �C��� */
$title_clr       = "cryan";
$title_bg_clr    = "#666666";
$title_txt_clr   = "yellow";
$content_txt_clr = "purple";
$total_clr       = "red";
$ending_clr  	   = "red";
$time_clr        = "green";
//
$count['voters']       = 0; // �`���|�H��
$count['valid']        = 0; // �`�벼��
$count['invalid']      = 0; // �`�L�Ĳ���
$count['voter_in']     = 0; // �w�}�X����(�t���벼)�έp
$count['voter_not_in'] = 0; // ���}�X���Ʋέp
$count['ps_in']        = 0; // �w�}�X�벼�Ҳέp
$count['ps_not_in']    = 0; // ���}�X�벼�Ҳέp
$num_circle = array('1' => 'j', '2' => 'k', '3' => 'l', '4' => 'm', '5' => 'n');
//
$sql_select_ca="select * from $Candidate_tbl order by sno*1";
$result_ca = mysql_query($sql_select_ca);
$num_ca = mysql_num_rows($result_ca);
//
$echo_str = "<html><head>\n";
$echo_str .= "<meta http-equiv=\"Content-Type\" content=\"text/html; Charset=Big5\">\n";

// ���Ϥ��R
if($action=='all'){
   $echo_str .= "<META HTTP-EQUIV=REFRESH CONTENT='".$delay.";URL=".$_SERVER['PHP_SELF']."?action=all'>\n";
   $echo_str .= "<Link Rel='stylesheet' Type='text/css' Href='style_c.css'>\n";
   $echo_str .= "<title>".$Title."--�}�������@�~</title>\n";
   $echo_str .= "</head><body style='margin-top:2;background:#E0E0E0'>\n";
   $echo_str .= "<center>\n";
//   $echo_str .= "<font size=2 color=blue>�@(���e���C".$delay."�����۰ʽ����@��)</font>\n";
   $echo_str .= "<font size=2> �Τ���I�� <a href=".$_SERVER['PHP_SELF']."?action=all>��s�n</a></font><br>\n";
   
   $sql_select="select * from $Vote_tbl order by ps_no";
   $result_vt=mysql_query($sql_select);

   // �έp
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
   // ���Ƭ� 0 ���B�z
   if($count['voter_in'])
      $percent = sprintf("%4.1f",(($count['valid']+$count['invalid'])*100) / $count['voter_in']);
   else
      $percent = '0.0';
   // �έp���
   $echo_str .= "<table border=0 cellspacing=0 cellpadding=0 style=\"border-collapse: collapse;font-family:�з���\" bordercolor=#009380 width=100%>\n";
   $echo_str .= "<tr><td width=1% style='font-size:30px;background:#666666;color:#FFFFFF;' nowrap>&nbsp;��&nbsp;<br>&nbsp;��&nbsp;</td><td>\n";
   $echo_str .= "<table border=1 cellspacing=0 style=\"border-collapse: collapse\" bordercolor=#666666 width=100%>\n";
   $echo_str .= "<tr style='font-size:28px'><td align=right width=16% nowrap>�w�}����</td><td width=16%>".$count['ps_in']."</td><td align=right width=16% nowrap>���Ĳ��G</td><td width=16%>".$count['valid']."</td><td align=right width=16% nowrap>�벼�v�G</td><td width=20%>".$count['voters']."�H</td></tr>\n";
   $echo_str .= "<tr style='font-size:28px'><td align=right width=16% nowrap>���}����</td><td width=16%>".$count['ps_not_in']."</td><td align=right width=16% nowrap>�L�Ĳ��G</td><td width=16%>".$count['invalid']."</td><td align=right width=16% nowrap>�벼�v�G</td><td width=20%>".$percent."�H</td></tr>\n";
   $echo_str .= "</table>\n";
   $echo_str .= "</td></tr></html>\n";
   $echo_str .= "</center>\n";
   $echo_str .= "</body></html>\n";
	
// ���Ϥ��R(�H�벼��)
}elseif($action=='zone_ps'){
   $sql_select_z="select * from $Zone_tbl order by ZoneNum*1";
   $result_z = mysql_query($sql_select_z);
   $num_z = mysql_num_rows($result_z);
   if(!isset($start_rec) or ($start_rec >= $num_z)){$start_rec = 0;$sort = 1;}

   $echo_str .= "<META HTTP-EQUIV=REFRESH CONTENT='".$delay.";URL=".$_SERVER['PHP_SELF']."?action=zone_ps&start_rec=".($start_rec+1)."'>\n";
   $echo_str .= "<Link Rel='stylesheet' Type='text/css' Href='style_c.css'>\n";
   $echo_str .= "<title>".$Title."--�}�������@�~</title>\n";
   $echo_str .= "</head><body style='margin-top:2px;margin-right:2px;background:#FFFFD0'>\n";
   $echo_str .= "<center>\n";
//   $echo_str .= "<font size=+1 color=$title_clr>".$Title."(�벼��)</font><br>\n";
   
   /* �p�������ɤ����D���� */
//   if($Ending)
//       $echo_str .= "<font size=4 color=$time_clr><b><i>�m�p�������n</b></i></font>";
//   else
//      $echo_str .= "<font size=2 color=$time_clr>�e����s�ɶ��G".$process_date."</font>";
   
//   $echo_str .= "<font size=2 color=blue>�@(���e���C".$delay."�����۰ʽ����@��)</font>\n";
   $echo_str .= "<font size=2> �Τ���I�� <a href=".$_SERVER['PHP_SELF']."?action=zone_ps&start_rec=".($start_rec+1).">��s�n</a></font><br>\n";
   
   $sql_select_z="select * from $Zone_tbl order by ZoneNum*1 LIMIT ".$start_rec.", 1";
   $result_z = mysql_query($sql_select_z);
   $oz = mysql_fetch_object($result_z);
   $zone_no = $oz->ZoneNum;
   $zone_na = $oz->ZoneName;
   
   $echo_str .= "<table border=0 cellspacing=0 style=\"border-collapse: collapse\" bordercolor=#009380 width=100%>\n";
   $echo_str .= "<tr><td colspan=2>\n";
   $echo_str .= "<table border=1 cellspacing=0 style=\"border-collapse: collapse;font-family:�з���\" bordercolor=#666666 width=100%>\n";
   $echo_str .= "<tr><td style='font-size:24px;background:#666666;color:#FFFFFF;' width=1% nowrap>&nbsp;��&nbsp;��&nbsp;</td>\n";
   $echo_str .= "<td style='font-size:24px;' align=center>".$zone_na."</td>\n";
   $echo_str .= "</tr></table>\n";
   $echo_str .= "</td></tr>\n";
   $echo_str .= "<tr><td colspan=2>\n";
   $tmp_str2  = "</td></tr>\n";
   $tmp_str2 .= "<tr><td colspan=2>\n";
   $tmp_str2 .= "<table border=1 cellspacing=0 style=\"border-collapse: collapse\"  bordercolor=#009380 width=100%>\n";
   $tmp_str2 .= "<tr bgcolor=$title_bg_clr><td><font color=$title_txt_clr>�벼��</font></td>";
   $tmp_str2 .= "<td><font color=$title_txt_clr>���|�H</font></td>";
   $tmp_str2 .= "<td><font color=$title_txt_clr>���Ĳ�</font></td>";
   $tmp_str2 .= "<td><font color=$title_txt_clr>�L�Ĳ�</font></td>";
   $tmp_str2 .= "<td><font color=$title_txt_clr>�벼�v</font></td>";
/*   
   $sql_select = "select * from $Candidate_tbl order by sno";
   $result_c = mysql_query($sql_select);
   $num_ca = mysql_num_rows($result_c);
   while ($ca=mysql_fetch_object($result_c)){
      $tmp_str2 .= "<td valign=top><font size=2 color=$title_txt_clr>$ca->sno<br>".stripslashes($ca->name)."</font></td>";
   }
*/
   $tmp_str2 .= "</tr>\n";

   // �z����Ϥ��벼��
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
   /* �|���}���ɤ��B�z */
   $data = @mysql_num_rows($result_vt);
   if($data<1){
      $tmp_str2 .= "</table>\n";
      $tmp_str2 .= "<br><div style=\"FILTER:glow(color:#308148,strength=3);color:#ff0000;font-size:20px;font-weight=900\">�|�L����O����ơI</div>";
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
      // �έp
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
   // ���Ƭ� 0 ���B�z
   if($count['voter_in'])
      $percent = sprintf("%4.1f",(($count['valid']+$count['invalid'])*100) / $count['voter_in']);
   else
      $percent = '0.0';
   // �έp���
   $tmp_str1 .= "<table border=1 cellspacing=0 style=\"border-collapse: collapse;font-size:16px\" bordercolor=#666666 width=100%>\n";
   $tmp_str1 .= "<tr><td align=right width=1% nowrap>�벼�v�H�G</td><td width=49%>".$count['voters']."</td><td align=right width=1% nowrap>�벼�v�G</td><td width=49%>".$percent."%</td></tr>\n";
   $tmp_str1 .= "<tr><td align=right width=1% nowrap>�w�}���ҡG</td><td width=49%>".$count['ps_in']."</td><td align=right width=1% nowrap>���Ĳ��G</td><td width=49%>".$count['valid']."</td></tr>\n";
   $tmp_str1 .= "<tr><td align=right width=1% nowrap>���}���ҡG</td><td width=49%>".$count['ps_not_in']."</td><td align=right width=1% nowrap>�L�Ĳ��G</td><td width=49%>".$count['invalid']."</td></tr>\n";
   $tmp_str1 .= "</table>\n";
   //
   $echo_str .= $tmp_str1.$tmp_str2;
   $echo_str .= "</table>\n";
   $echo_str .= "</td></tr></table>\n";
   $echo_str .= "</center>\n";
   $echo_str .= "</body></html>\n";

// ���Ϥ��R(�H���|�H)
}elseif($action=='zone_ca'){
   $sql_select_z="select * from $Zone_tbl order by ZoneNum*1";
   $result_z = mysql_query($sql_select_z);
   $num_z = mysql_num_rows($result_z);
   if(!isset($start_rec) or ($start_rec >= $num_z)){$start_rec = 0;$sort = 1;}

   $echo_str .= "<META HTTP-EQUIV=REFRESH CONTENT='".$delay.";URL=".$_SERVER['PHP_SELF']."?action=zone_ca&start_rec=".($start_rec+1)."'>\n";
   $echo_str .= "<Link Rel='stylesheet' Type='text/css' Href='style_c.css'>\n";
   $echo_str .= "<title>".$Title."--�}�������@�~</title>\n";
   $echo_str .= "</head><body style='margin-top:2px;margin-right:2px;background:#FFFFD0'>\n";
   $echo_str .= "<center>\n";
//   $echo_str .= "<font size=+1 color=$title_clr>".$Title."(�벼��)</font><br>\n";
   
   /* �p�������ɤ����D���� */
//   if($Ending)
//       $echo_str .= "<font size=4 color=$time_clr><b><i>�m�p�������n</b></i></font>";
//   else
//      $echo_str .= "<font size=2 color=$time_clr>�e����s�ɶ��G".$process_date."</font>";
   
//   $echo_str .= "<font size=2 color=blue>�@(���e���C".$delay."�����۰ʽ����@��)</font>\n";
   $echo_str .= "<font size=2> �Τ���I�� <a href=".$_SERVER['PHP_SELF']."?action=zone_ca&start_rec=".($start_rec+1).">��s�n</a></font><br>\n";
   
   $sql_select_z="select * from $Zone_tbl order by ZoneNum*1 LIMIT ".$start_rec.", 1";
   $result_z = mysql_query($sql_select_z);
   $oz = mysql_fetch_object($result_z);
   $zone_no = $oz->ZoneNum;
   $zone_na = $oz->ZoneName;
   
   $echo_str .= "<table border=0 cellspacing=0 style=\"border-collapse: collapse\" bordercolor=#009380 width=100%>\n";
   $echo_str .= "<tr><td colspan=2>\n";
   $echo_str .= "<table border=1 cellspacing=0 style=\"border-collapse: collapse;font-family:�з���\" bordercolor=#666666 width=100%>\n";
   $echo_str .= "<tr><td style='font-size:28px;background:#666666;color:#FFFFFF;' width=1% nowrap>&nbsp;��&nbsp;��&nbsp;</td>\n";
   $echo_str .= "<td style='font-size:32px;' align=center>".$zone_na."</td>\n";
   $echo_str .= "</tr></table>\n";
   $echo_str .= "</td></tr>\n";
   $echo_str .= "<tr><td colspan=2>\n";
   $tmp_str2  = "</td></tr>\n";
   $tmp_str2 .= "<tr><td colspan=2>\n";
   $tmp_str2 .= "<table border=1 cellspacing=0 style=\"border-collapse: collapse\"  bordercolor=#333333 width=100%>\n";
   $tmp_str2 .= "<tr bgcolor=$title_bg_clr style='font-size:24px'><td><font color=$title_txt_clr>�J��H</font></td>";
   $tmp_str2 .= "<td align=center><font color=$title_txt_clr>�o����</font></td>";
   $tmp_str2 .= "<td align=center><font color=$title_txt_clr>�o���v</font></td>";
/*   
   $sql_select = "select * from $Candidate_tbl order by sno";
   $result_c = mysql_query($sql_select);
   $num_ca = mysql_num_rows($result_c);
   while ($ca=mysql_fetch_object($result_c)){
      $tmp_str2 .= "<td valign=top><font size=2 color=$title_txt_clr>$ca->sno<br>".stripslashes($ca->name)."</font></td>";
   }
*/
   $tmp_str2 .= "</tr>\n";

   // �z����Ϥ��벼��
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
   /* �|���}���ɤ��B�z */
   $data = @mysql_num_rows($result_vt);
   if($data<1){
      $tmp_str2 .= "</table>\n";
      $tmp_str2 .= "<br><div style=\"FILTER:glow(color:#308148,strength=3);color:#ff0000;font-size:20px;font-weight=900\">�|�L����O����ơI</div>";
      echo $echo_str.$tmp_str2;
      exit;
   }
   $c=1;
   for($i=0;$i<$num_ca;$i++){
      $count['idv'][$i] = 0;
   }
   //
   while ($vt=mysql_fetch_object($result_vt)){

      // �έp
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
   // ���Ƭ� 0 ���B�z
   if($count['voter_in'])
      $percent = sprintf("%4.1f",(($count['valid']+$count['invalid'])*100) / $count['voter_in']);
   else
      $percent = '0.0';
   // �έp���
   $tmp_str1 = "<table border=1 cellspacing=0 style='border-collapse: collapse;font-size:18px;FONT-FAMILY:\"Times New Roman\",\"Times\",\"serif\"' bordercolor=#666666 width=100%>\n";
   $tmp_str1 .= "<tr><td align=right width=1% nowrap>�벼�v�H�G</td><td width=49%>".$count['voters']."</td><td align=right width=1% nowrap>�벼�v�G</td><td width=49%>".$percent."%</td></tr>\n";
   $tmp_str1 .= "<tr><td align=right width=1% nowrap>�w�}���ҡG</td><td width=49%>".$count['ps_in']."</td><td align=right width=1% nowrap>���Ĳ��G</td><td width=49%>".$count['valid']."</td></tr>\n";
   $tmp_str1 .= "<tr><td align=right width=1% nowrap>���}���ҡG</td><td width=49%>".$count['ps_not_in']."</td><td align=right width=1% nowrap>�L�Ĳ��G</td><td width=49%>".$count['invalid']."</td></tr>\n";
   $tmp_str1 .= "</table>\n";
   //
   for($i=0;$i<$num_ca;$i++){
      if (($i % 2)==0){
         $bgclr="#E0E0E0";
      }else{
         $bgclr="#FFFFFF";
      }
      // ���Ƭ� 0 ���B�z
      if($count['voter_in'])
         $percent2 = sprintf("%4.1f",(($count['idv'][$i])*100) / $count['voter_in']);
      else
         $percent2 = '0.0';
      $tmp_str2 .= "<tr bgcolor=".$bgclr." style='font-size:24px;font-family:\"Times New Roman\",\"Times\",\"serif\",\"�з���\"'>";
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

// ���̳W�w�覡�s��������
}else{
   $echo_str .= "</head><body style='margin-top:2'>\n";
   $echo_str .= "<center>\n";
   $echo_str .= "<h1>�Ш̳W�w�覡�s���������I<br><br>Invalid Browse behavior!</h1>\n";
   $echo_str .= "</center>\n";
   $echo_str .= "</body></html>\n";
}

echo $echo_str;
?>