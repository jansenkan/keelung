<?
/*--------------------------------
   �J��H�o������ 
----------------------------------*/
include "config.inc.php";
$action    = isset($_GET['action'])?$_GET['action']:'';
$mode      = isset($_GET['mode'])?$_GET['mode']:'by_num';
$sort      = isset($_GET['sort'])?$_GET['sort']:1;
$start_rec = isset($_GET['start_rec'])?$_GET['start_rec']:0;
/* �C��� */
$title_clr       = "cryan";
$title_bg_clr    = "#E87B14";
$title_txt_clr   = "FFFFFF";
$content_txt_clr = "purple";
$ending_clr   	 = "red";
$time_clr        = "green";
$total_clr       = "red";
$rank_clr        = "black";
$mark_clr        = "darkblue";
$number_clr      = "green";
$total_clr_cnt   = "cryan";
$rank_clr_cnt    = "#333333";
$mark_clr_cnt    = "#333333";
$number_clr_cnt  = "#333333";
$cand_clr_cnt    = "#333333";
$num_per_page    = 5;   /* �C���H�� */
//$delay           = 7;	/* ������� */
$num_circle = array('1' => 'j', '2' => 'k', '3' => 'l', '4' => 'm', '5' => 'n');
// ���o�J��H�H��
$sql_select_c="select * from $Candidate_tbl order by sno*1";
$result_c = mysql_query($sql_select_c);
$num_c = mysql_num_rows($result_c);
if(!isset($start_rec) or ($start_rec >= $num_c)){$start_rec = 0;$sort = 1;}

$echo_str = "<html><head>\n";
$echo_str .= "<meta http-equiv=\"Content-Type\" content=\"text/html; Charset=Big5\">\n";
$echo_str .= "<META HTTP-EQUIV=REFRESH CONTENT='".$delay.";URL=".$_SERVER['PHP_SELF']."?action=".$action."&mode=".$mode."&start_rec=".($start_rec+$num_per_page)."&sort=".($sort+$num_per_page)."'>\n";
$echo_str .= "<Link Rel='stylesheet' Type='text/css' Href='style_c.css'>\n";
$echo_str .= "<title>".$Title."--�}�������@�~</title>\n";
$echo_str .= "</head><body style='margin-top:2px;margin-left:2px'>\n";
$echo_str .= "<center>\n";
//$echo_str .= "<font size=+1 color=$title_clr>".$Title."(�Կ�H)</font><br>\n";
   
/* �p�������ɤ����D���� */
//if($Ending)
//   $echo_str .= "<font size=4 color=$time_clr><b><i>�m�p�������n</b></i><font size=3>���W�欰�q���ۦ�P�O�ȨѰѦ�</font></font>";
//else
//   $echo_str .= "<font size=2 color=$time_clr>�e����s�ɶ��G".$process_date."</font>";
   
$echo_str .= "<font size=2 color=blue>�@(���e���C <font color=red>".$delay."</font> �����۰ʧ�s�@��)</font>\n";
$echo_str .= "<font size=2> �Τ���I�� <a href=".$_SERVER['PHP_SELF']."?action=".$action."&mode=".$mode."&start_rec=".($start_rec+$num_per_page)."&sort=".($sort+$num_per_page).">��s�n</a></font>&nbsp;&nbsp;\n";
$echo_str .= "<font size=2><a href=".$_SERVER['PHP_SELF']."?action=".$action."&mode=by_num&start_rec=".($start_rec+$num_per_page)."&sort=".($sort+$num_per_page).">�̽s���n</a></font>&nbsp;&nbsp;\n";
$echo_str .= "<font size=2><a href=".$_SERVER['PHP_SELF']."?action=".$action."&mode=by_rank&start_rec=".($start_rec+$num_per_page)."&sort=".($sort+$num_per_page).">�̱ƦW�n</a></font><br>\n";

// �H��F������ܲ���
if($action=='zone_detail'){
   $vote_ca = array();	// �`���ư}�C
   $rank    = array();  // �W���}�C
   $voters  = array();  // �벼�v�H�}�C
   $valid   = array();  // ���Ĳ��}�C
   $invalid = array();  // �L�Ĳ��}�C
   $done    = array();  // �w�}�������}�C
   $undone  = array();  // ���}�������}�C
   // ���o�H�J��H�s����key�Ȫ�<�`���ư}�C>
   if($mode=='by_rank')
      $sql_select_ca="select * from $Cand_cnt_tbl order by total DESC limit ".$start_rec.",".$num_per_page;
   else
      $sql_select_ca="select * from $Cand_cnt_tbl order by sno limit ".$start_rec.",".$num_per_page;
   $result_ca=mysql_query($sql_select_ca);
   while($o_ca = mysql_fetch_object($result_ca)){
      $vote_ca[$o_ca->sno] = '';
   }
   // ���o�H�J��H�s����key�Ȫ�<�W���}�C>
   $sql_select_ca="select * from $Cand_cnt_tbl order by total DESC limit ".$start_rec.",".$num_per_page;
   $result_ca=mysql_query($sql_select_ca);
   $r_cnt = 1;
   while($o_ca = mysql_fetch_object($result_ca)){
      $rank[$o_ca->sno] = $r_cnt++;
      if($o_ca->remark) $crown[$o_ca->sno] = 1; else $crown[$o_ca->sno] = 0;
   }
   // �^�����ϸ��
   $sql_select_z="select * from $Zone_tbl order by ZoneNum*1";
   $result_z = mysql_query($sql_select_z);
   $vote = array();
   while($oz = mysql_fetch_object($result_z)){
      $zone_no = $oz->ZoneNum;
      $zone_na = $oz->ZoneName;
      $z_name[$zone_no] = $zone_na;
      // �z����Ϥ��벼��
      $sql_select="select * from $P_S_sift_tbl where ZoneNum='$zone_no' order by ps_no";
      $result_zone=mysql_query($sql_select);
      $o_zone=mysql_fetch_object($result_zone);
      $sub_range = "('".$o_zone->ps_no."'";
      while($o_zone=mysql_fetch_object($result_zone)){
         $sub_range .= ",'".$o_zone->ps_no."'";
      }
      $sub_range .= ")";
      // �H�z��᪺�����^���ﲼ��
      $sql_select="select * from $Vote_tbl where ps_no IN ".$sub_range." order by ps_no";
      $result_vt=mysql_query($sql_select);
      while($o_vt=mysql_fetch_object($result_vt)){
         foreach($vote_ca as $sno => $v){
            $field = "sn".$sno;
            if(!isset($vote[$sno][$zone_no])) $vote[$sno][$zone_no] = 0;
            $vote[$sno][$zone_no] += $o_vt->$field*1;	// ��@�J��H���ϲ��Ʋ֭p
            $vote_ca[$sno] += $o_vt->$field*1;				// ��@�J��H�Ҧ����Ʋ֭p
         }
         if(!isset($voters[$zone_no]))  $voters[$zone_no]=0;
         if(!isset($invalid[$zone_no])) $invalid[$zone_no]=0;
         if(!isset($valid[$zone_no]))   $valid[$zone_no]=0;
         if(!isset($done[$zone_no]))    $done[$zone_no]=0;
         if(!isset($undone[$zone_no]))  $undone[$zone_no]=0;
         $voters[$zone_no]  += $o_vt->voters;					// ��@���ϧ벼�v�H�Ʋ֭p
         $invalid[$zone_no] += $o_vt->invalid;				// ��@���ϵL�Ĳ��Ʋ֭p
         $valid[$zone_no]   += $o_vt->total;					// ��@���Ϧ��Ĳ��Ʋ֭p
         if($o_vt->total > 0) $done[$zone_no]++;			// ��@���Ϥw�}���������ҼƲ֭p
         else                 $undone[$zone_no]++;		// ��@���ϥ��}���������ҼƲ֭p
      }
   }
   $echo_str .= "<table border=0 cellpadding=0 style=\"border-collapse: collapse\" width=100><tr>\n";
   $echo_str .= "<td valign=top>\n";
   $echo_str .= "<table border=1 cellspacing=0 style=\"border-collapse: collapse\" bordercolor=orange width=100%>\n";
   $echo_str .= "<tr class=cand_f_title bgcolor=$title_bg_clr>";
   $echo_str .= "<td nowrap align=center><font color=$title_txt_clr>�s��</font></td>";
   $echo_str .= "<td nowrap align=center><font color=$title_txt_clr>�Կ�H</font></td>";
   $echo_str .= "<td nowrap align=center><font color=$title_txt_clr>�X�@�p</font></td>";
   foreach($z_name as $zno => $zna){
      $echo_str .= "<td nowrap align=center><font color=$title_txt_clr>".$zna."</font></td>";
   }
   $echo_str .= "<td nowrap align=center><font color=$title_txt_clr>�ƦW</font></td>";
   $echo_str .= "</tr>\n";
   $sort = 0;
   // �J��H���ϲ���
   foreach($vote_ca as $sno => $total){
      if (($sort++ % 2)==0){
         $bgclr="#E0E0E0";
      }else{
         $bgclr="#FFFFFF";
      }
      $cand_clr="#2C6EFF";
//      if ($vt->sex=="�k") $cand_clr="#FF5096"; 
      $echo_str .= "<tr class=cand_f_detail bgcolor=".$bgclr.">";
      $echo_str .= "<td align=center bgcolor=darkblue style='font-size:48px;color:#FFFFFF;font-family:Wingdings 2;'>".$num_circle[$sno]."</td>";
      $echo_str .= "<td align=center nowrap style='font-family:�з���'><font color=$cand_clr>".($crown[$sno]?"<img src=images/crown1.gif>":"").get_cand_name($sno)."</font></td>";
      $echo_str .= "<td align=center><font color=$total_clr><b>".$total."</b></font></td>";
      foreach($z_name as $zno => $zna){
         $echo_str .= "<td align=center><font color=$mark_clr><b>".(isset($vote[$sno][$zno])?$vote[$sno][$zno]:'')."</b></font></td>";
      }
      $echo_str .= "<td align=center bgcolor=yellow><font color=$rank_clr>".$rank[$sno]."</font></td>";
      $echo_str .= "</tr>\n";
   }
   // ���ϲέp(�벼�v�H��)
   $cnt['voters'] = 0;
   $echo_tmp = '';
   foreach($z_name as $zno => $zna){
      $voters[$zno] = isset($voters[$zno])?$voters[$zno]:0;
      $echo_tmp .= "<td align=center><font color=$mark_clr_cnt><b>".$voters[$zno]."</b></font></td>";
      $cnt['voters'] += $voters[$zno]*1;
   }
   $echo_str .= "<tr class=cand_f_detail_analysis bgcolor=#FFFFD0>";
   $echo_str .= "<td align=center><font color=$number_clr_cnt>��</font></td>";
   $echo_str .= "<td align=center nowrap><font color=$cand_clr_cnt>���|�v�H��</font></td>";
   $echo_str .= "<td align=center><font color=$total_clr_cnt><b>".$cnt['voters']."</b></font></td>";
   $echo_str .= $echo_tmp;
   $echo_str .= "<td align=center><font color=$rank_clr_cnt>&nbsp;</font></td>";
   $echo_str .= "</tr>\n";
   // ���ϲέp(���Ĳ���)
   $cnt['valid'] = 0;
   $echo_tmp = '';
   foreach($z_name as $zno => $zna){
      $valid[$zno] = isset($valid[$zno])?$valid[$zno]:0;
      $echo_tmp .= "<td align=center><font color=$mark_clr_cnt><b>".$valid[$zno]."</b></font></td>";
      $cnt['valid'] += $valid[$zno]*1;
   }
   $echo_str .= "<tr class=cand_f_detail_analysis bgcolor=#FFFFD0>";
   $echo_str .= "<td align=center><font color=$number_clr_cnt>��</font></td>";
   $echo_str .= "<td align=center nowrap><font color=$cand_clr_cnt>���Ĳ���</font></td>";
   $echo_str .= "<td align=center><font color=$total_clr_cnt><b>".$cnt['valid']."</b></font></td>";
   $echo_str .= $echo_tmp;
   $echo_str .= "<td align=center><font color=$rank_clr_cnt>&nbsp;</font></td>";
   $echo_str .= "</tr>\n";
   // ���ϲέp(�L�Ĳ���)
   $cnt['invalid'] = 0;
   $echo_tmp = '';
   foreach($z_name as $zno => $zna){
      $invalid[$zno] = isset($invalid[$zno])?$invalid[$zno]:0;
      $echo_tmp .= "<td align=center><font color=$mark_clr_cnt><b>".$invalid[$zno]."</b></font></td>";
      $cnt['invalid'] += $invalid[$zno]*1;
   }
   $echo_str .= "<tr class=cand_f_detail_analysis bgcolor=#FFFFD0>";
   $echo_str .= "<td align=center><font color=$number_clr_cnt>��</font></td>";
   $echo_str .= "<td align=center nowrap><font color=$cand_clr_cnt>�L�Ĳ���</font></td>";
   $echo_str .= "<td align=center><font color=$total_clr_cnt><b>".$cnt['invalid']."</b></font></td>";
   $echo_str .= $echo_tmp;
   $echo_str .= "<td align=center><font color=$rank_clr_cnt>&nbsp;</font></td>";
   $echo_str .= "</tr>\n";
   // ------------ ���o���ϲέp��� ----------- begin
   // �i�P���ϲέp���X�p�Ȱ���e���
   get_count_all($count);
   // ------------ ���o���ϲέp��� ----------- end
   // ���ϲέp(�벼�v)
   $echo_str .= "<tr class=cand_f_detail_analysis bgcolor=#FFFFD0>";
   $echo_str .= "<td align=center><font color=$number_clr_cnt>��</font></td>";
   $echo_str .= "<td align=center nowrap><font color=$cand_clr_cnt>�벼�v</font></td>";
   if(isset($voters[$zno]) and $voters[$zno])
      $percent = sprintf("%4.1f",(($cnt['valid']+$cnt['invalid'])*100) / $cnt['voters']);
   else
      $percent = '0.0';
   $echo_str .= "<td align=center><font color=$total_clr_cnt><b>".$percent."%</b></font></td>";
//   $echo_str .= "<td align=center><font color=$total_clr_cnt><b>".$count['percentage']."%</b></font></td>";
   foreach($z_name as $zno => $zna){
      if(isset($voters[$zno]) and $voters[$zno])
         $percent = sprintf("%4.1f",(($valid[$zno]+$invalid[$zno])*100) / $voters[$zno]);
      else
         $percent = '0.0';
      $echo_str .= "<td align=center><font color=$mark_clr_cnt><b>".$percent."%</b></font></td>";
   }
//   $echo_str .= $echo_tmp;
   $echo_str .= "<td align=center><font color=$rank_clr_cnt>&nbsp;</font></td>";
   $echo_str .= "</tr>\n";
   // ���ϲέp(�w�}���Ҽ�)
   $cnt['done'] = 0;
   $echo_tmp = '';
   foreach($z_name as $zno => $zna){
      $open = 0;
      if(isset($done[$zno]) and ($done[$zno]>0)) $open = 1; 
      $echo_tmp .= "<td align=center".(($open)?"":" bgcolor=green")."><font color=$mark_clr_cnt><b>".(($open)?$done[$zno]:"<font color=#FFFFFF><b>���}</b></font>")."</b></font></td>";
      if(!isset($done[$zno])) $done[$zno]=0;
      $cnt['done'] += $done[$zno]*1;
   }
   $echo_str .= "<tr class=cand_f_detail_analysis bgcolor=#FFFFD0>";
   $echo_str .= "<td align=center><font color=$number_clr_cnt>��</font></td>";
   $echo_str .= "<td align=center nowrap><font color=$cand_clr_cnt>�w�}���Ҽ�</font></td>";
   $echo_str .= "<td align=center".(($cnt['done']>0)?"":" bgcolor=green")."><font color=$total_clr_cnt><b>".(($cnt['done']>0)?$cnt['done']:"<font color=#FFFFFF><b>���}</b></font>")."</b></font></td>";
   $echo_str .= $echo_tmp;
   $echo_str .= "<td align=center><font color=$rank_clr_cnt>&nbsp;</font></td>";
   $echo_str .= "</tr>\n";
   // ���ϲέp(���}���Ҽ�)
   $cnt['undone'] = 0;
   $echo_tmp = '';
   foreach($z_name as $zno => $zna){
      $over = 1;
      if(isset($undone[$zno]) and ($undone[$zno]>0)) $over = 0; 
      $echo_tmp .= "<td align=center".(($over)?" bgcolor=#FF0000":"")."><font color=$mark_clr_cnt><b>".(($over)?"<font color=#FFFFFF><b>����</b></font>":$undone[$zno])."</b></font></td>";
      if(!isset($undone[$zno])) $undone[$zno]=0;
      $cnt['undone'] += $undone[$zno]*1;
   }
   $echo_str .= "<tr class=cand_f_detail_analysis bgcolor=#FFFFD0>";
   $echo_str .= "<td align=center><font color=$number_clr_cnt>��</font></td>";
   $echo_str .= "<td align=center nowrap><font color=$cand_clr_cnt>���}���Ҽ�</font></td>";
   $echo_str .= "<td align=center".(($cnt['undone']==0)?" bgcolor=#FF0000":"")."><font color=$total_clr_cnt><b>".(($cnt['undone']==0)?"<font color=#FFFFFF><b>����</b></font>":$cnt['undone'])."</b></font></td>";
   $echo_str .= $echo_tmp;
   $echo_str .= "<td align=center><font color=$rank_clr_cnt>&nbsp;</font></td>";
   $echo_str .= "</tr>\n";
   $echo_str .= "</table>\n";
   //
   // �έp���
   $echo_str .= "<br><table border=0 cellspacing=0 cellpadding=0 style=\"border-collapse: collapse;font-family:�з���\" bordercolor=#009380 width=100%>\n";
   $echo_str .= "<tr><td width=1% style='font-size:36px;background:#666666;color:#FFFFFF;' nowrap>&nbsp;����&nbsp;<br>&nbsp;�έp&nbsp;</td><td>\n";
   $echo_str .= "<table border=1 cellspacing=0 style=\"border-collapse: collapse\" bordercolor=#666666 width=100%>\n";
   $echo_str .= "<tr style='font-size:34px'><td align=right width=16% nowrap>�w�}���ҡG</td><td align=center width=16%>".$count['ps_in']."</td><td align=right width=16% nowrap>���Ĳ��G</td><td align=center width=16%>".$count['valid']."</td><td align=right width=16% nowrap>���|�v�G</td><td align=center width=20%>".$count['voters']."�H</td></tr>\n";
   $echo_str .= "<tr style='font-size:34px'><td align=right width=16% nowrap>���}���ҡG</td><td align=center width=16%>".$count['ps_not_in']."</td><td align=right width=16% nowrap>�L�Ĳ��G</td><td align=center width=16%>".$count['invalid']."</td><td align=right width=16% nowrap>�벼�v�G</td><td align=center width=20%>".$count['percentage']."�H</td></tr>\n";
   $echo_str .= "</table>\n";
   $echo_str .= "</td>\n";
   $echo_str .= "</tr></table>\n";
   $echo_str .= "</center>\n";
   $echo_str .= "</body></html>\n";


// �ȫJ��H���ƤΦW��(�C��5~6�H)
}elseif($action=='rank_only'){
   $echo_str .= "<table border=0 width=100><tr>\n";
   $echo_str .= "<td valign=top>\n";
   $echo_str .= "<table border=1 cellspacing=0 style=\"border-collapse: collapse\" bordercolor=orange width=100>\n";
   $echo_str .= "<tr class=cand_f_title bgcolor=$title_bg_clr>";
   $echo_str .= "<td nowrap align=center><font color=$title_txt_clr>�s��</font></td>";
   $echo_str .= "<td nowrap align=center><font color=$title_txt_clr>�ҡ@�y</font></td>";
   $echo_str .= "<td nowrap align=center><font color=$title_txt_clr>�ԡ@��@�H</font></td>";
   $echo_str .= "<td nowrap align=center><font color=$title_txt_clr>�`�o��</font></td>";
   $echo_str .= "<td nowrap align=center><font color=$title_txt_clr>�o���v</font></td>";
   $echo_str .= "<td nowrap align=center><font color=$title_txt_clr>�ƦW</font></td>";
   $echo_str .= "</tr>\n";
   // ���o�H�J��H�s����key�Ȫ�<�W���}�C>
   $sql_select_ca="select * from $Cand_cnt_tbl order by total DESC limit ".$start_rec.",".$num_per_page;
   $result_ca=mysql_query($sql_select_ca);
   $r_cnt = 1;
   while($o_ca = mysql_fetch_object($result_ca)){
      $rank[$o_ca->sno] = $r_cnt++;
      if($o_ca->remark) $crown[$o_ca->sno] = 1; else $crown[$o_ca->sno] = 0;
   }
   if($mode=='by_rank')
      $sql_select="select * from $Cand_cnt_tbl order by total DESC limit ".$start_rec.",".$num_per_page;
   else
      $sql_select="select * from $Cand_cnt_tbl order by sno limit ".$start_rec.",".$num_per_page;
//   $sql_select="select * from $Cand_cnt_tbl order by total DESC limit ".$start_rec.",".$num_per_page;
   $result_vt=mysql_query($sql_select);
   /* �|���}���ɤ��B�z */
   $data = @mysql_num_rows($result_vt);
   if($data<1){
      $echo_str .= "</table>\n";
      $echo_str .= "<br><div style=\"FILTER:glow(color:#308148,strength=3);color:#ff0000;font-size:20px;font-weight=900\">�|�L����O����ơI</div>";
      echo $echo_str;
      exit;
   }
   get_count_all($count);
   $total = $count['valid']*1 + $count['invalid']*1;
   while ($vt=mysql_fetch_object($result_vt)){
      if (($sort % 2)==0){
         $bgclr="#E0E0E0";
      }else{
         $bgclr="#FFFFFF";
      }
      $cand_clr="#2C6EFF";
//      if ($vt->sex=="�k") $cand_clr="#FF5096"; 
      $echo_str .= "<tr class=cand_f bgcolor=".$bgclr.">";
      $echo_str .= "<td align=center bgcolor=darkblue style='font-size:56px;color:#FFFFFF;font-family:Wingdings 2;'>".$num_circle[$vt->sno]."</td>";
//      $echo_str .= "<td align=center><font color=$number_clr>".$vt->sno."</font></td>";
      $echo_str .= "<td align=center nowrap style='font-size:40px;color:#666666;'>".$vt->class."</td>";
      $echo_str .= "<td align=center nowrap style='font-size:50px;font-family:�з���;'><font color=$cand_clr>".($crown[$vt->sno]?"<img src=images/crown1.gif>":"").$vt->name."</font></td>";
      $echo_str .= "<td align=center><font color=$total_clr><b>".(empty($vt->total)?"&nbsp;":$vt->total)."</b></font></td>";
      if($total)
         $percentage = sprintf("%4.1f",($vt->total*100) / $total);
      else
         $percentage = '0.0';
      $echo_str .= "<td align=center><font color=$mark_clr><b>".$percentage."%</b></font></td>";
      $echo_str .= "<td align=center bgcolor=yellow><font color=$rank_clr>".$rank[$vt->sno]."</font></td>";
      $echo_str .= "</tr>\n";
      $sort++;
   }
   $echo_str .= "</table>\n";
   $echo_str .= "</td>\n";

   $echo_str .= "</tr></table>\n";
   if($Ballot2ComeIn*1 > 0)
      $echo_str .= "<br><label style='background:#0000FF;color:#FFFFFF;font-size:32px;font-weight:900;font-family:\"Times New Roman\",\"Times\",\"serif\"'>&nbsp;�����e�G".$Ballot2ComeIn."&nbsp;��&nbsp;</label>";
//   $echo_str .= "<label style='background:#0000FF;color:#FFFFFF;font-size:22px;font-weight:900'>&nbsp;���p����ƶȨѰѦҡA��ﵲ�G�H�V�ɳB���i���ǡI</label>";
   $echo_str .= "</center>\n";
   $echo_str .= "</body></html>\n";
}

echo $echo_str;
?>