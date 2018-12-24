<?
/*--------------------------------
   獼匡眔布近冀 
----------------------------------*/
include "config.inc.php";
$action    = isset($_GET['action'])?$_GET['action']:'';
$mode      = isset($_GET['mode'])?$_GET['mode']:'by_num';
$sort      = isset($_GET['sort'])?$_GET['sort']:1;
$start_rec = isset($_GET['start_rec'])?$_GET['start_rec']:0;
/* 肅︹ */
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
$num_per_page    = 5;   /* –计 */
//$delay           = 7;	/* 近冀计 */
$num_circle = array('1' => 'j', '2' => 'k', '3' => 'l', '4' => 'm', '5' => 'n');
// 眔獼匡计
$sql_select_c="select * from $Candidate_tbl order by sno*1";
$result_c = mysql_query($sql_select_c);
$num_c = mysql_num_rows($result_c);
if(!isset($start_rec) or ($start_rec >= $num_c)){$start_rec = 0;$sort = 1;}

$echo_str = "<html><head>\n";
$echo_str .= "<meta http-equiv=\"Content-Type\" content=\"text/html; Charset=Big5\">\n";
$echo_str .= "<META HTTP-EQUIV=REFRESH CONTENT='".$delay.";URL=".$_SERVER['PHP_SELF']."?action=".$action."&mode=".$mode."&start_rec=".($start_rec+$num_per_page)."&sort=".($sort+$num_per_page)."'>\n";
$echo_str .= "<Link Rel='stylesheet' Type='text/css' Href='style_c.css'>\n";
$echo_str .= "<title>".$Title."--秨布近冀穨</title>\n";
$echo_str .= "</head><body style='margin-top:2px;margin-left:2px'>\n";
$echo_str .= "<center>\n";
//$echo_str .= "<font size=+1 color=$title_clr>".$Title."(匡)</font><br>\n";
   
/* 璸布挡ぇ夹肈弧 */
//if($Ending)
//   $echo_str .= "<font size=4 color=$time_clr><b><i>璸布挡</b></i><font size=3>讽匡虫筿福︽度ㄑ把σ</font></font>";
//else
//   $echo_str .= "<font size=2 color=$time_clr>礶穝丁".$process_date."</font>";
   
$echo_str .= "<font size=2 color=blue>(セ礶– <font color=red>".$delay."</font> 牧笆穝Ω)</font>\n";
$echo_str .= "<font size=2> ┪も笆翴匡 <a href=".$_SERVER['PHP_SELF']."?action=".$action."&mode=".$mode."&start_rec=".($start_rec+$num_per_page)."&sort=".($sort+$num_per_page).">穝</a></font>&nbsp;&nbsp;\n";
$echo_str .= "<font size=2><a href=".$_SERVER['PHP_SELF']."?action=".$action."&mode=by_num&start_rec=".($start_rec+$num_per_page)."&sort=".($sort+$num_per_page).">ㄌ絪腹</a></font>&nbsp;&nbsp;\n";
$echo_str .= "<font size=2><a href=".$_SERVER['PHP_SELF']."?action=".$action."&mode=by_rank&start_rec=".($start_rec+$num_per_page)."&sort=".($sort+$num_per_page).">ㄌ逼</a></font><br>\n";

// ︽現だ跋陪ボ布计
if($action=='zone_detail'){
   $vote_ca = array();	// 羆布计皚
   $rank    = array();  // Ω皚
   $voters  = array();  // щ布舦皚
   $valid   = array();  // Τ布皚
   $invalid = array();  // 礚布皚
   $done    = array();  // 秨布ЧΘ皚
   $undone  = array();  // ゼ秨布ЧΘ皚
   // 眔獼匡絪腹key<羆布计皚>
   if($mode=='by_rank')
      $sql_select_ca="select * from $Cand_cnt_tbl order by total DESC limit ".$start_rec.",".$num_per_page;
   else
      $sql_select_ca="select * from $Cand_cnt_tbl order by sno limit ".$start_rec.",".$num_per_page;
   $result_ca=mysql_query($sql_select_ca);
   while($o_ca = mysql_fetch_object($result_ca)){
      $vote_ca[$o_ca->sno] = '';
   }
   // 眔獼匡絪腹key<Ω皚>
   $sql_select_ca="select * from $Cand_cnt_tbl order by total DESC limit ".$start_rec.",".$num_per_page;
   $result_ca=mysql_query($sql_select_ca);
   $r_cnt = 1;
   while($o_ca = mysql_fetch_object($result_ca)){
      $rank[$o_ca->sno] = $r_cnt++;
      if($o_ca->remark) $crown[$o_ca->sno] = 1; else $crown[$o_ca->sno] = 0;
   }
   // 耝だ跋戈
   $sql_select_z="select * from $Zone_tbl order by ZoneNum*1";
   $result_z = mysql_query($sql_select_z);
   $vote = array();
   while($oz = mysql_fetch_object($result_z)){
      $zone_no = $oz->ZoneNum;
      $zone_na = $oz->ZoneName;
      $z_name[$zone_no] = $zone_na;
      // 縵匡だ跋ぇщ布┮
      $sql_select="select * from $P_S_sift_tbl where ZoneNum='$zone_no' order by ps_no";
      $result_zone=mysql_query($sql_select);
      $o_zone=mysql_fetch_object($result_zone);
      $sub_range = "('".$o_zone->ps_no."'";
      while($o_zone=mysql_fetch_object($result_zone)){
         $sub_range .= ",'".$o_zone->ps_no."'";
      }
      $sub_range .= ")";
      // 縵匡だ跋耝匡布计
      $sql_select="select * from $Vote_tbl where ps_no IN ".$sub_range." order by ps_no";
      $result_vt=mysql_query($sql_select);
      while($o_vt=mysql_fetch_object($result_vt)){
         foreach($vote_ca as $sno => $v){
            $field = "sn".$sno;
            if(!isset($vote[$sno][$zone_no])) $vote[$sno][$zone_no] = 0;
            $vote[$sno][$zone_no] += $o_vt->$field*1;	// 虫獼匡だ跋布计仓璸
            $vote_ca[$sno] += $o_vt->$field*1;				// 虫獼匡┮Τ布计仓璸
         }
         if(!isset($voters[$zone_no]))  $voters[$zone_no]=0;
         if(!isset($invalid[$zone_no])) $invalid[$zone_no]=0;
         if(!isset($valid[$zone_no]))   $valid[$zone_no]=0;
         if(!isset($done[$zone_no]))    $done[$zone_no]=0;
         if(!isset($undone[$zone_no]))  $undone[$zone_no]=0;
         $voters[$zone_no]  += $o_vt->voters;					// 虫だ跋щ布舦计仓璸
         $invalid[$zone_no] += $o_vt->invalid;				// 虫だ跋礚布计仓璸
         $valid[$zone_no]   += $o_vt->total;					// 虫だ跋Τ布计仓璸
         if($o_vt->total > 0) $done[$zone_no]++;			// 虫だ跋秨布ЧΘ布┮计仓璸
         else                 $undone[$zone_no]++;		// 虫だ跋ゼ秨布ЧΘ布┮计仓璸
      }
   }
   $echo_str .= "<table border=0 cellpadding=0 style=\"border-collapse: collapse\" width=100><tr>\n";
   $echo_str .= "<td valign=top>\n";
   $echo_str .= "<table border=1 cellspacing=0 style=\"border-collapse: collapse\" bordercolor=orange width=100%>\n";
   $echo_str .= "<tr class=cand_f_title bgcolor=$title_bg_clr>";
   $echo_str .= "<td nowrap align=center><font color=$title_txt_clr>絪腹</font></td>";
   $echo_str .= "<td nowrap align=center><font color=$title_txt_clr>匡</font></td>";
   $echo_str .= "<td nowrap align=center><font color=$title_txt_clr>璸</font></td>";
   foreach($z_name as $zno => $zna){
      $echo_str .= "<td nowrap align=center><font color=$title_txt_clr>".$zna."</font></td>";
   }
   $echo_str .= "<td nowrap align=center><font color=$title_txt_clr>逼</font></td>";
   $echo_str .= "</tr>\n";
   $sort = 0;
   // 獼匡だ跋布计
   foreach($vote_ca as $sno => $total){
      if (($sort++ % 2)==0){
         $bgclr="#E0E0E0";
      }else{
         $bgclr="#FFFFFF";
      }
      $cand_clr="#2C6EFF";
//      if ($vt->sex=="") $cand_clr="#FF5096"; 
      $echo_str .= "<tr class=cand_f_detail bgcolor=".$bgclr.">";
      $echo_str .= "<td align=center bgcolor=darkblue style='font-size:48px;color:#FFFFFF;font-family:Wingdings 2;'>".$num_circle[$sno]."</td>";
      $echo_str .= "<td align=center nowrap style='font-family:夹发砰'><font color=$cand_clr>".($crown[$sno]?"<img src=images/crown1.gif>":"").get_cand_name($sno)."</font></td>";
      $echo_str .= "<td align=center><font color=$total_clr><b>".$total."</b></font></td>";
      foreach($z_name as $zno => $zna){
         $echo_str .= "<td align=center><font color=$mark_clr><b>".(isset($vote[$sno][$zno])?$vote[$sno][$zno]:'')."</b></font></td>";
      }
      $echo_str .= "<td align=center bgcolor=yellow><font color=$rank_clr>".$rank[$sno]."</font></td>";
      $echo_str .= "</tr>\n";
   }
   // だ跋参璸(щ布舦计)
   $cnt['voters'] = 0;
   $echo_tmp = '';
   foreach($z_name as $zno => $zna){
      $voters[$zno] = isset($voters[$zno])?$voters[$zno]:0;
      $echo_tmp .= "<td align=center><font color=$mark_clr_cnt><b>".$voters[$zno]."</b></font></td>";
      $cnt['voters'] += $voters[$zno]*1;
   }
   $echo_str .= "<tr class=cand_f_detail_analysis bgcolor=#FFFFD0>";
   $echo_str .= "<td align=center><font color=$number_clr_cnt>》</font></td>";
   $echo_str .= "<td align=center nowrap><font color=$cand_clr_cnt>匡羭舦计</font></td>";
   $echo_str .= "<td align=center><font color=$total_clr_cnt><b>".$cnt['voters']."</b></font></td>";
   $echo_str .= $echo_tmp;
   $echo_str .= "<td align=center><font color=$rank_clr_cnt>&nbsp;</font></td>";
   $echo_str .= "</tr>\n";
   // だ跋参璸(Τ布计)
   $cnt['valid'] = 0;
   $echo_tmp = '';
   foreach($z_name as $zno => $zna){
      $valid[$zno] = isset($valid[$zno])?$valid[$zno]:0;
      $echo_tmp .= "<td align=center><font color=$mark_clr_cnt><b>".$valid[$zno]."</b></font></td>";
      $cnt['valid'] += $valid[$zno]*1;
   }
   $echo_str .= "<tr class=cand_f_detail_analysis bgcolor=#FFFFD0>";
   $echo_str .= "<td align=center><font color=$number_clr_cnt>》</font></td>";
   $echo_str .= "<td align=center nowrap><font color=$cand_clr_cnt>Τ布计</font></td>";
   $echo_str .= "<td align=center><font color=$total_clr_cnt><b>".$cnt['valid']."</b></font></td>";
   $echo_str .= $echo_tmp;
   $echo_str .= "<td align=center><font color=$rank_clr_cnt>&nbsp;</font></td>";
   $echo_str .= "</tr>\n";
   // だ跋参璸(礚布计)
   $cnt['invalid'] = 0;
   $echo_tmp = '';
   foreach($z_name as $zno => $zna){
      $invalid[$zno] = isset($invalid[$zno])?$invalid[$zno]:0;
      $echo_tmp .= "<td align=center><font color=$mark_clr_cnt><b>".$invalid[$zno]."</b></font></td>";
      $cnt['invalid'] += $invalid[$zno]*1;
   }
   $echo_str .= "<tr class=cand_f_detail_analysis bgcolor=#FFFFD0>";
   $echo_str .= "<td align=center><font color=$number_clr_cnt>》</font></td>";
   $echo_str .= "<td align=center nowrap><font color=$cand_clr_cnt>礚布计</font></td>";
   $echo_str .= "<td align=center><font color=$total_clr_cnt><b>".$cnt['invalid']."</b></font></td>";
   $echo_str .= $echo_tmp;
   $echo_str .= "<td align=center><font color=$rank_clr_cnt>&nbsp;</font></td>";
   $echo_str .= "</tr>\n";
   // ------------ 眔跋参璸戈 ----------- begin
   // 籔だ跋参璸璸暗ユゑ癸
   get_count_all($count);
   // ------------ 眔跋参璸戈 ----------- end
   // だ跋参璸(щ布瞯)
   $echo_str .= "<tr class=cand_f_detail_analysis bgcolor=#FFFFD0>";
   $echo_str .= "<td align=center><font color=$number_clr_cnt>》</font></td>";
   $echo_str .= "<td align=center nowrap><font color=$cand_clr_cnt>щ布瞯</font></td>";
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
   // だ跋参璸(秨布┮计)
   $cnt['done'] = 0;
   $echo_tmp = '';
   foreach($z_name as $zno => $zna){
      $open = 0;
      if(isset($done[$zno]) and ($done[$zno]>0)) $open = 1; 
      $echo_tmp .= "<td align=center".(($open)?"":" bgcolor=green")."><font color=$mark_clr_cnt><b>".(($open)?$done[$zno]:"<font color=#FFFFFF><b>ゼ秨</b></font>")."</b></font></td>";
      if(!isset($done[$zno])) $done[$zno]=0;
      $cnt['done'] += $done[$zno]*1;
   }
   $echo_str .= "<tr class=cand_f_detail_analysis bgcolor=#FFFFD0>";
   $echo_str .= "<td align=center><font color=$number_clr_cnt>》</font></td>";
   $echo_str .= "<td align=center nowrap><font color=$cand_clr_cnt>秨布┮计</font></td>";
   $echo_str .= "<td align=center".(($cnt['done']>0)?"":" bgcolor=green")."><font color=$total_clr_cnt><b>".(($cnt['done']>0)?$cnt['done']:"<font color=#FFFFFF><b>ゼ秨</b></font>")."</b></font></td>";
   $echo_str .= $echo_tmp;
   $echo_str .= "<td align=center><font color=$rank_clr_cnt>&nbsp;</font></td>";
   $echo_str .= "</tr>\n";
   // だ跋参璸(ゼ秨布┮计)
   $cnt['undone'] = 0;
   $echo_tmp = '';
   foreach($z_name as $zno => $zna){
      $over = 1;
      if(isset($undone[$zno]) and ($undone[$zno]>0)) $over = 0; 
      $echo_tmp .= "<td align=center".(($over)?" bgcolor=#FF0000":"")."><font color=$mark_clr_cnt><b>".(($over)?"<font color=#FFFFFF><b>ЧΘ</b></font>":$undone[$zno])."</b></font></td>";
      if(!isset($undone[$zno])) $undone[$zno]=0;
      $cnt['undone'] += $undone[$zno]*1;
   }
   $echo_str .= "<tr class=cand_f_detail_analysis bgcolor=#FFFFD0>";
   $echo_str .= "<td align=center><font color=$number_clr_cnt>》</font></td>";
   $echo_str .= "<td align=center nowrap><font color=$cand_clr_cnt>ゼ秨布┮计</font></td>";
   $echo_str .= "<td align=center".(($cnt['undone']==0)?" bgcolor=#FF0000":"")."><font color=$total_clr_cnt><b>".(($cnt['undone']==0)?"<font color=#FFFFFF><b>ЧΘ</b></font>":$cnt['undone'])."</b></font></td>";
   $echo_str .= $echo_tmp;
   $echo_str .= "<td align=center><font color=$rank_clr_cnt>&nbsp;</font></td>";
   $echo_str .= "</tr>\n";
   $echo_str .= "</table>\n";
   //
   // 参璸戈
   $echo_str .= "<br><table border=0 cellspacing=0 cellpadding=0 style=\"border-collapse: collapse;font-family:夹发砰\" bordercolor=#009380 width=100%>\n";
   $echo_str .= "<tr><td width=1% style='font-size:36px;background:#666666;color:#FFFFFF;' nowrap>&nbsp;跋&nbsp;<br>&nbsp;参璸&nbsp;</td><td>\n";
   $echo_str .= "<table border=1 cellspacing=0 style=\"border-collapse: collapse\" bordercolor=#666666 width=100%>\n";
   $echo_str .= "<tr style='font-size:34px'><td align=right width=16% nowrap>秨布┮</td><td align=center width=16%>".$count['ps_in']."</td><td align=right width=16% nowrap>Τ布</td><td align=center width=16%>".$count['valid']."</td><td align=right width=16% nowrap>匡羭舦</td><td align=center width=20%>".$count['voters']."</td></tr>\n";
   $echo_str .= "<tr style='font-size:34px'><td align=right width=16% nowrap>ゼ秨布┮</td><td align=center width=16%>".$count['ps_not_in']."</td><td align=right width=16% nowrap>礚布</td><td align=center width=16%>".$count['invalid']."</td><td align=right width=16% nowrap>щ布瞯</td><td align=center width=20%>".$count['percentage']."</td></tr>\n";
   $echo_str .= "</table>\n";
   $echo_str .= "</td>\n";
   $echo_str .= "</tr></table>\n";
   $echo_str .= "</center>\n";
   $echo_str .= "</body></html>\n";


// 度獼匡布计のΩ(–5~6)
}elseif($action=='rank_only'){
   $echo_str .= "<table border=0 width=100><tr>\n";
   $echo_str .= "<td valign=top>\n";
   $echo_str .= "<table border=1 cellspacing=0 style=\"border-collapse: collapse\" bordercolor=orange width=100>\n";
   $echo_str .= "<tr class=cand_f_title bgcolor=$title_bg_clr>";
   $echo_str .= "<td nowrap align=center><font color=$title_txt_clr>絪腹</font></td>";
   $echo_str .= "<td nowrap align=center><font color=$title_txt_clr>囊膟</font></td>";
   $echo_str .= "<td nowrap align=center><font color=$title_txt_clr>匡</font></td>";
   $echo_str .= "<td nowrap align=center><font color=$title_txt_clr>羆眔布</font></td>";
   $echo_str .= "<td nowrap align=center><font color=$title_txt_clr>眔布瞯</font></td>";
   $echo_str .= "<td nowrap align=center><font color=$title_txt_clr>逼</font></td>";
   $echo_str .= "</tr>\n";
   // 眔獼匡絪腹key<Ω皚>
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
   /* ﹟ゼ秨布ぇ矪瞶 */
   $data = @mysql_num_rows($result_vt);
   if($data<1){
      $echo_str .= "</table>\n";
      $echo_str .= "<br><div style=\"FILTER:glow(color:#308148,strength=3);color:#ff0000;font-size:20px;font-weight=900\">﹟礚ヴ癘布戈</div>";
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
//      if ($vt->sex=="") $cand_clr="#FF5096"; 
      $echo_str .= "<tr class=cand_f bgcolor=".$bgclr.">";
      $echo_str .= "<td align=center bgcolor=darkblue style='font-size:56px;color:#FFFFFF;font-family:Wingdings 2;'>".$num_circle[$vt->sno]."</td>";
//      $echo_str .= "<td align=center><font color=$number_clr>".$vt->sno."</font></td>";
      $echo_str .= "<td align=center nowrap style='font-size:40px;color:#666666;'>".$vt->class."</td>";
      $echo_str .= "<td align=center nowrap style='font-size:50px;font-family:夹发砰;'><font color=$cand_clr>".($crown[$vt->sno]?"<img src=images/crown1.gif>":"").$vt->name."</font></td>";
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
      $echo_str .= "<br><label style='background:#0000FF;color:#FFFFFF;font-size:32px;font-weight:900;font-family:\"Times New Roman\",\"Times\",\"serif\"'>&nbsp;讽匡耬".$Ballot2ComeIn."&nbsp;布&nbsp;</label>";
//   $echo_str .= "<label style='background:#0000FF;color:#FFFFFF;font-size:22px;font-weight:900'>&nbsp;セ璸布戈度ㄑ把σ讽匡挡狦癡旧矪そ非</label>";
   $echo_str .= "</center>\n";
   $echo_str .= "</body></html>\n";
}

echo $echo_str;
?>