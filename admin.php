<?
#---------------------------------------------------
#   模範生選舉計票管理主程式
#   by jansen since 2003.03.16
#---------------------------------------------------
//$sp_title = "模範生選舉計票管理";
//$requiredFullname = array("林炘焄","甘之信");
include("chkuser.inc.php");
include "config.inc.php";
session_id()?'':session_start();
/* 非系統管理者，請改道！ */
if($_SESSION['root']<>"sys_player"){
   header("location:index.php");
   exit;
}
/* 變數初值設定 */
$action = isset($_GET['action'])?$_GET['action']:'';

/* 重新鎖定*/
if($action=='lock'){
   $sql_update = "update config set _lock='1'";
   mysql_query($sql_update);
   header("location:".$_SERVER['PHP_SELF']);
   exit;
}

/* 解除鎖定*/
if($action=='unlock'){
   $sql_update = "update config set _lock=''";
   mysql_query($sql_update);
   header("location:".$_SERVER['PHP_SELF']);
   exit;
}

/* 取消當選註記 */
if($action=='unmark'){
   $echo_str = "<html><head>\n
<META HTTP-EQUIV=REFRESH CONTENT='3;URL=".$_SERVER['PHP_SELF']."'>\n
</head><body>\n";
   $sql_select="select * from $Cand_cnt_tbl";

   $result=mysql_query($sql_select);
   while($o=mysql_fetch_object($result)){
      $target=$o->rid;
      $sql_update="update $Cand_cnt_tbl set remark='' where rid='".$target."'";
      mysql_query($sql_update);
   }
   
   $sql_update="update config set ending=''";
   mysql_query($sql_update);
   
   $echo_str .= "<center><h2>取消當選註記完成！！！</h2></center>";
   
   echo $echo_str;
   exit;
}

/* 當選人註記處理程式碼(for keelung) */
if($action=='ending2'){
   $sql_select="select * from $Cand_cnt_tbl order by total DESC";
   $result = mysql_query($sql_select);
   $o = mysql_fetch_object($result);
   $rid = $o->rid;
   
   $sql_update="update $Cand_cnt_tbl set remark='V' where rid='".$rid."'";
   mysql_query($sql_update);
   
   /* 系統做計票完成註記 */
   $sql_update = "update config set ending='1'";
   mysql_query($sql_update);
   
   header("location:".$_SERVER['PHP_SELF']);
   exit;
	
}

/* 當選人註記處理程式碼 */
if($action=='ending'){

   /* 各年級男女生保障名額各一名 */
   $sql_select = array();
   $sql_select[1]="select * from $Cand_cnt_tbl where class LIKE '1%' and sex='女' order by total DESC";
   $sql_select[2]="select * from $Cand_cnt_tbl where class LIKE '1%' and sex='男' order by total DESC";
   $sql_select[3]="select * from $Cand_cnt_tbl where class LIKE '2%' and sex='女' order by total DESC";
   $sql_select[4]="select * from $Cand_cnt_tbl where class LIKE '2%' and sex='男' order by total DESC";
   $sql_select[5]="select * from $Cand_cnt_tbl where class LIKE '3%' and sex='女' order by total DESC";
   $sql_select[6]="select * from $Cand_cnt_tbl where class LIKE '3%' and sex='男' order by total DESC";

   echo "<font color=orange>各年級保障名額當選人(男、女各1名)</font>";
   echo "<table border=1 style=\"border-collapse: collapse\" cellspacing=0 bordercolor=orange>";
   echo "<tr><td>編號</td><td>班級</td><td>姓名</td><td>姓別</td><td>得票數</td></tr>";
   foreach($sql_select as $k => $sql){
      $result=mysql_query($sql);
      $o=mysql_fetch_object($result);
      echo "<tr align=center><td>".$o->sno."</td><td>".$o->class."</td><td>".$o->name."</td><td>".$o->sex."</td><td><font color=red>".$o->total."</font></td></tr>";
      $target=$o->rid;
      $sql_update="update $Cand_cnt_tbl set remark='V' where rid='".$target."'";
      mysql_query($sql_update);
   }
   echo "</table><br>";
   
   /* 各年級除保障名額外之前二名 */
   unset($sql_select);
   $sql_select = array();
   $sql_select[1]="select * from $Cand_cnt_tbl where class LIKE '1%' and remark<>'V' order by total DESC";
   $sql_select[2]="select * from $Cand_cnt_tbl where class LIKE '2%' and remark<>'V' order by total DESC";
   $sql_select[3]="select * from $Cand_cnt_tbl where class LIKE '3%' and remark<>'V' order by total DESC";

   echo "<font color=blue>各年級除保障名額外之前二名當選人</font>";
   echo "<table border=1 style=\"border-collapse: collapse\" cellspacing=0 bordercolor=blue>";
   echo "<tr><td>編號</td><td>班級</td><td>姓名</td><td>姓別</td><td>得票數</td></tr>";
   foreach($sql_select as $k => $sql){
      $result=mysql_query($sql);
      for($j=1;$j<=2;$j++){
         $o=mysql_fetch_object($result);
         echo "<tr align=center><td>".$o->sno."</td><td>".$o->class."</td><td>".$o->name."</td><td>".$o->sex."</td><td><font color=red>".$o->total."</font></td></tr>";
         $target=$o->rid;
         $sql_update="update $Cand_cnt_tbl set remark='V' where rid='".$target."'";
         mysql_query($sql_update);
      }
   }
   echo "</table><br>";

   /* 全年級未當選之候選人之前四名 */
   unset($sql_select);
   $sql_select="select * from $Cand_cnt_tbl where remark<>'V' order by total DESC";

   echo "<font color=green>全年級未當選之前四名當選人</font>";
   echo "<table border=1 style=\"border-collapse: collapse\" cellspacing=0 bordercolor=green>";
   echo "<tr><td>編號</td><td>班級</td><td>姓名</td><td>姓別</td><td>得票數</td></tr>";
   $result=mysql_query($sql_select);
   for($j=1;$j<=4;$j++){
      $o=mysql_fetch_object($result);
      echo "<tr align=center><td>".$o->sno."</td><td>".$o->class."</td><td>".$o->name."</td><td>".$o->sex."</td><td><font color=red>".$o->total."</font></td></tr>";
      $target=$o->rid;
      $sql_update="update $Cand_cnt_tbl set remark='V' where rid='".$target."'";
      mysql_query($sql_update);
   }
   echo "</table><br>";

   /* 系統做計票完成註記 */
   $sql_update = "update config set ending='1'";
   mysql_query($sql_update);
   exit;
}

/* ----- 管理選單由此開始 ----- */
/* 畫面更新計時秒數 */
$refresh_time    =20;

/* 顏色表 */
$title_clr       ="cryan";
$title_bg_clr    ="#009380";
$title_txt_clr   ="yellow";
$content_txt_clr ="purple";
$table_clr       ="orange";
$lock_clr        ="C0C0C0";
$cnt = 1;
$echo_str  = "<html><head>\n";
$echo_str .= "<META HTTP-EQUIV=REFRESH CONTENT='".$refresh_time.";URL=".$_SERVER['PHP_SELF']."'>\n";
$echo_str .= "<Link Rel='stylesheet' Type='text/css' Href='style_c.css'>\n";
$echo_str .= "<title>".$Title."--系統管理</title>\n";
$echo_str .= "
<script language=\"JavaScript\">
<!--
function setBG(TheColor,TheObject) {TheObject.bgColor=TheColor}
//-->
</script>";
$echo_str .= "</head><body>\n";
$echo_str .= "<center>";
$echo_str .= "<font size=4 color=$title_clr>$Title</font><br>\n";
$echo_str .= "<table border=0><tr><td valign=top>\n\n";
$echo_str .= "<table border=1 style=\"border-collapse: collapse\" cellspacing=0 cellpadding=3 bordercolor=$table_clr>\n";
$echo_str .= "<tr bgcolor=$title_bg_clr><td align=center>";
$echo_str .= "<font color=$title_txt_clr><b>系統管理功能選單</b></font>";
$echo_str .= "</td></tr>\n";
$echo_str .= "<tr bgcolor=#FFFF00><td align=center>";
$echo_str .= "以下功能僅在<b><font color=#FF0000>第一次啟用時</font></b>才能點選，<br>請謹慎使用！";
$echo_str .= "</td></tr>\n";

/* 鎖定時的處理方式 */
if($Lock){
   $echo_str .= "<tr><td>";
   $echo_str .= "<font color=$lock_clr>".$cnt++.".重新啟動線上即時開票系統(已被鎖定)</font>";
}else{
   $echo_str .= "<tr><td bgcolor=#D0D0D0>";
   $echo_str .= "<a href=createDB.php target=_blank>".$cnt++.".重新啟動線上即時開票系統</a>";
}  

$echo_str .= "</td></tr>\n";
$echo_str .= "<tr bgcolor=orange><td align=center>";
$echo_str .= "<font color=FFFFFF>以下二項為投開票之預備動作，<br>請確實完成！</font>";
$echo_str .= "</td></tr>\n";
$echo_str .= "<tr><td>";

/* 鎖定時的處理方式 */
if($Lock)
   $echo_str .= "<font color=$lock_clr>".$cnt++.".建立候選人名錄(已被鎖定)</font>";
else
   $echo_str .= "<a href=add_cand.php target=_blank>".$cnt++.".建立候選人名錄</a>";

$echo_str .= "</td></tr>\n";
$echo_str .= "<tr><td>";

/* 鎖定時的處理方式 */
if($Lock)
   $echo_str .= "<font color=$lock_clr>".$cnt++.".建立投開票所名錄(已被鎖定)</font>";
else
   $echo_str .= "<a href=add_p_s.php target=_blank>".$cnt++.".建立投開票所名錄</a>";

$echo_str .= "</td></tr>\n";
$echo_str .= "<tr><td>";

/* 鎖定時的處理方式 */
if($Lock)
   $echo_str .= "<font color=$lock_clr>".$cnt++.".建立各分區名錄(已被鎖定)</font>";
else
   $echo_str .= "<a href=set_zone.php target=_blank>".$cnt++.".建立各分區名錄</a>";

$echo_str .= "</td></tr>\n";
$echo_str .= "<tr bgcolor=red><td align=center>";
$echo_str .= "<font color=FFFFFF>請確認以上三項名錄建立完成後<br>才能進行以下動作</font>";
$echo_str .= "</td></tr>\n";
$echo_str .= "<tr><td>";

/* 鎖定時的處理方式 */
if($Lock)
   $echo_str .= "<font color=$lock_clr>".$cnt++.".啟動線上即時開票機制(已被鎖定)</font>";
else{
   $echo_str .= "<a href=createDB2.php>".$cnt++.".啟動線上即時開票機制</a><br>";
   $echo_str .= "<a href=".$_SERVER['PHP_SELF']."?action=lock>重新鎖定</a>";
}
$echo_str .= "</td></tr>";

/* 鎖定時的處理方式 */
if($Lock){
   $echo_str .= "<tr bgcolor=red><td align=center>";
   $echo_str .= "<font color=FFFFFF>除非要重新啟用系統，否則請勿解鎖！<br>以維系統運作正常！</font>";
   $echo_str .= "</td></tr>\n";
   $echo_str .= "<tr onMouseOver=setBG('#FFFFA0',this) onMouseout=setBG('',this)><td>";
   $echo_str .= "<a href=".$_SERVER['PHP_SELF']."?action=unlock>解除鎖定</a><br><font color=red>若有「增」「刪」候選人或投開票所，<br>則需重新「<font color=cryan><b>".($cnt-1).".啟動線上即時開票機制</b></font>」<br>且所有開票資料將被刪除！</font>";
   $echo_str .= "</td></tr>\n";
}
$echo_str .= "<tr onMouseOver=setBG('#FFFFA0',this) onMouseout=setBG('',this)><td>";
$echo_str .= "<a href=changetitle.php target=_blank>".$cnt++.".系統標題暨參數設定</a>";
$echo_str .= "</td></tr>\n";
$echo_str .= "<tr onMouseOver=setBG('#FFFFA0',this) onMouseout=setBG('',this)><td>";
$echo_str .= "<a href=list_cand_writing.php target=_blank>".$cnt++.".候選人名錄列印(A3橫印，每頁6人)</a>";
$echo_str .= "</td></tr>\n";
$echo_str .= "<tr onMouseOver=setBG('#FFFFA0',this) onMouseout=setBG('',this)><td>";
$echo_str .= "<a href=logout.php?url=".$_SERVER['PHP_SELF'].">".$cnt++.".登出本管理選單</a>";
$echo_str .= "</td></tr>\n";
$echo_str .= "</table>\n\n";
$echo_str .= "</td><td valign=top>\n\n";

/* 鎖定時的處理方式 */
if($Lock){
   $echo_str .= "<table border=1 style=\"border-collapse: collapse\" cellspacing=0 cellpadding=3 bordercolor=$table_clr>\n";
   $echo_str .= "<tr bgcolor=green><td align=center>";
   $echo_str .= "<font color=FFFFFF>一般功能選單</font>";
   $echo_str .= "</td></tr>\n";
   $echo_str .= "<tr onMouseOver=setBG('#FFFFA0',this) onMouseout=setBG('',this)><td>";
   $echo_str .= "<a href=upd_vote.php?action=all target=_blank>登錄所有投開票所之票數</a>";
   $echo_str .= "</td></tr>\n";
   $echo_str .= "<tr onMouseOver=setBG('#FFFFA0',this) onMouseout=setBG('',this)><td>";
   $echo_str .= "<a href=upd_vote.php?action=single target=_blank>登錄單一投開票所之票數</a>";
   $echo_str .= "</td></tr>\n";
   /* 計票結束時的處理方式 */
   if($Ending){
      $echo_str .= "<tr onMouseOver=setBG('#FFFFA0',this) onMouseout=setBG('',this)><td>";
      $echo_str .= "<a href=".$_SERVER['PHP_SELF']."?action=unmark><font color=red>移除</font>所有當選人員之註記</a>";
      $echo_str .= "</td></tr>\n";
   }else{
      $echo_str .= "<tr onMouseOver=setBG('#FFFFA0',this) onMouseout=setBG('',this)><td>";
      $echo_str .= "<a href=".$_SERVER['PHP_SELF']."?action=ending2>計票結束，判別當選人並加註記</a>";
      $echo_str .= "</td></tr>\n";
   }

   $echo_str .= "<tr onMouseOver=setBG('#FFFFA0',this) onMouseout=setBG('',this)><td>";
   $echo_str .= "<a href=upd_cand.php target=_blank>修改當選人員註記</a>";
   $echo_str .= "</td></tr>\n";
   $echo_str .= "<tr onMouseOver=setBG('#FFFFA0',this) onMouseout=setBG('',this)><td>";
   $echo_str .= "<a href=to_excel.php target=_blank>將計票結果轉為 excel 檔</a><br>轉檔完成後，請點選「檔案/另存新檔」";
   $echo_str .= "</td></tr>\n";
   $echo_str .= "<tr onMouseOver=setBG('#FFFFA0',this) onMouseout=setBG('',this)><td>";
   $echo_str .= "<a href=to_word.php target=_blank>將計票結果轉為 word 檔</a><br>轉檔完成後，請點選「檔案/另存新檔」";
   $echo_str .= "</td></tr>\n";
   $echo_str .= "<tr onMouseOver=setBG('#FFFFA0',this) onMouseout=setBG('',this)><td>";
//   $echo_str .= "<a href=to_csv.php target=_blank>將計票結果轉為 csv 檔</a>";
   $echo_str .= "</td></tr>\n";
   $echo_str .= "<tr onMouseOver=setBG('#FFFFA0',this) onMouseout=setBG('',this)><td>";
//   $echo_str .= "按下滑鼠右鍵並選「另存目標」以下載<br><<a href=\"計票資料.csv\">計票資料.csv</a>>及<<a href=當選資料.csv>當選資料.csv</a>>";
   $echo_str .= "</td></tr>\n";
   $echo_str .= "<tr bgcolor=pink><td align=center>";
   $echo_str .= "<font color=cryan>以下功能為可公開散佈之連結</font>";
   $echo_str .= "</td></tr>\n";
   $echo_str .= "<tr onMouseOver=setBG('#FFFFA0',this) onMouseout=setBG('',this)><td>";
   $echo_str .= "<a href=list_ps.php target=_blank>開票即時輪播系統</a>";
   $echo_str .= "</td></tr>\n";
   $echo_str .= "<tr onMouseOver=setBG('#FFFFA0',this) onMouseout=setBG('',this)><td>";
   $echo_str .= "<a href=list_cand_all.php target=_blank>開票即時輪播系統(全部候選人+小字型)</a>";
   $echo_str .= "</td></tr>\n";
   $echo_str .= "<tr onMouseOver=setBG('#FFFFA0',this) onMouseout=setBG('',this)><td>";
   $echo_str .= "<a href=list_cand_all_f.php?action=rank_only target=_blank>開票即時輪播系統(少人+大字型)</a>";
   $echo_str .= "</td></tr>\n";
   $echo_str .= "<tr onMouseOver=setBG('#FFFFA0',this) onMouseout=setBG('',this)><td>";
   $echo_str .= "<a href=disp_cand.php?sno=1 target=_blank>候選人簡介輪播系統</a>";
   $echo_str .= "</td></tr>\n";
   $echo_str .= "<tr onMouseOver=setBG('#FFFFA0',this) onMouseout=setBG('',this)><td>";
   $echo_str .= "<a href=index5.htm target=_blank>開票即時輪播系統(分割畫面)</a>";
   $echo_str .= "</td></tr>\n";
   $echo_str .= "<tr onMouseOver=setBG('#FFFFA0',this) onMouseout=setBG('',this)><td>";
   $echo_str .= "<a href=index6.htm target=_blank>開票即時輪播系統(分區統計)</a>";
   $echo_str .= "</td></tr>\n";
}
$echo_str .= "</table>\n\n";
$echo_str .= "</td></tr></table>\n";
$echo_str .= "</center>\n";
$echo_str .= "</body></html>\n";
   
echo $echo_str;

?>