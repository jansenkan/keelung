<?
/* 將資料由MySQL資料庫中轉出Excel檔 */

include("config.inc.php");
$action    = isset($_GET['action'])?$_GET['action']:'';

/* 1.轉出計票結果 */

   /* A.轉出之Excel檔檔名(侯選人導向) */
   $filename =  "計票資料1.xls";
   header("Content-disposition: filename=$filename");
   header("Content-type: application/octetstream");
   header("Pragma: no-cache");
   header("Expires: 0");
   
   $echo_head = "
	<html>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=big5\">
	<body><table border=1 style=\"border-collapse: collapse;\">";
   
   /* 寫入抬頭 */
   $echo_str = "<tr><td>編號</td><td>黨籍</td><td>姓名</td><td>性別</td><td>當選</td><td>得票數</td>";
   $sql_select = "select * from $P_S_tbl order by ps_no";
   $result_c = mysql_query($sql_select);
   $num_ca = mysql_num_rows($result_c);
   $cols=6;
   while ($ca=mysql_fetch_object($result_c)){
      $echo_str .= "<td>".$ca->ps_name."</td>";
      $cols++;
   }
   $echo_str .= "</tr>\n";
   
   /* 寫入計票資料 */
   $sql_select="select * from $Cand_cnt_tbl order by total DESC";
   $result_vt=mysql_query($sql_select);
   $c=0;
   while ($vt=mysql_fetch_object($result_vt)){
      $echo_str .= "<tr><td>".$vt->sno."</td><td>".$vt->class."</td><td>".$vt->name."</td><td>".$vt->sex."</td><td>".$vt->remark."</td><td>".$vt->total."</td>";
      for($i=0;$i<$num_ca;$i++){
      	 $tmp = mysql_field_name($result_vt,$i+4);
         $echo_str .= "<td>".$vt->$tmp."</td>";
      }
      $echo_str .= "</tr>\n";

   }
$echo_str .= "</table></body><html>";
echo $echo_head."<tr><td colspan=".$cols.">計票結果資料1</td></tr>".$echo_str;

   /* B.轉出之Excel檔檔名(以投票所導向) */
   $filename =  "計票資料2.xls";
   header("Content-disposition: filename=$filename");
   header("Content-type: application/octetstream");
   header("Pragma: no-cache");
   header("Expires: 0");
   
   $echo_head = "
	<html>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=big5\">
	<body><table border=1 style=\"border-collapse: collapse;\">";
   
   /* 寫入抬頭 */
   $echo_str = "<tr><td>行政分區</td><td>投票所編號</td><td>投票所名稱</td><td>得票數</td>";
   $sql_select = "select * from $Candidate_tbl order by sno";
   $result_ca = mysql_query($sql_select);
   $num_ca = mysql_num_rows($result_ca);
   $cols=4;
   while ($ca=mysql_fetch_object($result_ca)){
      $echo_str .= "<td>".stripslashes($ca->name)."</td>";
      $cols++;
   }
   $echo_str .= "</tr>\n";
   
   /* 寫入計票資料 */
   $sql_select="select * from $Vote_tbl order by ps_no";
   $result_vt=mysql_query($sql_select);
   $c=0;
   while ($vt=mysql_fetch_object($result_vt)){
      $echo_str .= "<tr><td>".get_zone_name_from_ps_no($vt->ps_no)."</td><td>".$vt->ps_no."</td><td>".$vt->ps_name."</td><td>".$vt->total."</td>";
      for($i=0;$i<$num_ca;$i++){
      	 $tmp = mysql_field_name($result_vt,$i+6);
         $echo_str .= "<td>".$vt->$tmp."</td>";
      }
      $echo_str .= "</tr>\n";

   }
$echo_str .= "</table></body><html>";
echo $echo_head."<tr><td colspan=".$cols.">計票結果資料2</td></tr>".$echo_str;

   
/* 2.轉出當選名單 */

   /* 轉出之Excel檔檔名 */
   $filename =  "當選資料.xls";
   header("Content-disposition: filename=$filename");
   header("Content-type: application/octetstream");
   header("Pragma: no-cache");
   header("Expires: 0");
   
   $echo_str = "
	<html>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=big5\">
	<body><table border=1>";
   
   /* 寫入抬頭 */
   $echo_str .= "<tr><td>編號</td><td>黨籍<</td><td>姓名</td><td>性別</td><td>得票數</td></tr>\n";
   $cols=5;
   
   /* 寫入計票資料 */
   $sql_select="select * from $Cand_cnt_tbl where remark='V' order by total DESC";
   $result_vt=mysql_query($sql_select);
   while ($vt=mysql_fetch_object($result_vt)){
      $echo_str .= "<tr><td>".$vt->sno."</td><td>".$vt->class."</td><td>".$vt->name."</td><td>".$vt->sex."</td><td>".$vt->total."</td></tr>\n";
   }

$echo_str .= "</table></body><html>";
echo $echo_head."<tr><td colspan=".$cols.">當選結果資料</td></tr>".$echo_str;


?>
