<?
/* �N��ƥ�MySQL��Ʈw����XExcel�� */

include("config.inc.php");
$action    = isset($_GET['action'])?$_GET['action']:'';

/* 1.��X�p�����G */

   /* A.��X��Excel���ɦW(�J��H�ɦV) */
   $filename =  "�p�����1.xls";
   header("Content-disposition: filename=$filename");
   header("Content-type: application/octetstream");
   header("Pragma: no-cache");
   header("Expires: 0");
   
   $echo_head = "
	<html>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=big5\">
	<body><table border=1 style=\"border-collapse: collapse;\">";
   
   /* �g�J���Y */
   $echo_str = "<tr><td>�s��</td><td>���y</td><td>�m�W</td><td>�ʧO</td><td>���</td><td>�o����</td>";
   $sql_select = "select * from $P_S_tbl order by ps_no";
   $result_c = mysql_query($sql_select);
   $num_ca = mysql_num_rows($result_c);
   $cols=6;
   while ($ca=mysql_fetch_object($result_c)){
      $echo_str .= "<td>".$ca->ps_name."</td>";
      $cols++;
   }
   $echo_str .= "</tr>\n";
   
   /* �g�J�p����� */
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
echo $echo_head."<tr><td colspan=".$cols.">�p�����G���1</td></tr>".$echo_str;

   /* B.��X��Excel���ɦW(�H�벼�ҾɦV) */
   $filename =  "�p�����2.xls";
   header("Content-disposition: filename=$filename");
   header("Content-type: application/octetstream");
   header("Pragma: no-cache");
   header("Expires: 0");
   
   $echo_head = "
	<html>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=big5\">
	<body><table border=1 style=\"border-collapse: collapse;\">";
   
   /* �g�J���Y */
   $echo_str = "<tr><td>��F����</td><td>�벼�ҽs��</td><td>�벼�ҦW��</td><td>�o����</td>";
   $sql_select = "select * from $Candidate_tbl order by sno";
   $result_ca = mysql_query($sql_select);
   $num_ca = mysql_num_rows($result_ca);
   $cols=4;
   while ($ca=mysql_fetch_object($result_ca)){
      $echo_str .= "<td>".stripslashes($ca->name)."</td>";
      $cols++;
   }
   $echo_str .= "</tr>\n";
   
   /* �g�J�p����� */
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
echo $echo_head."<tr><td colspan=".$cols.">�p�����G���2</td></tr>".$echo_str;

   
/* 2.��X���W�� */

   /* ��X��Excel���ɦW */
   $filename =  "�����.xls";
   header("Content-disposition: filename=$filename");
   header("Content-type: application/octetstream");
   header("Pragma: no-cache");
   header("Expires: 0");
   
   $echo_str = "
	<html>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=big5\">
	<body><table border=1>";
   
   /* �g�J���Y */
   $echo_str .= "<tr><td>�s��</td><td>���y<</td><td>�m�W</td><td>�ʧO</td><td>�o����</td></tr>\n";
   $cols=5;
   
   /* �g�J�p����� */
   $sql_select="select * from $Cand_cnt_tbl where remark='V' order by total DESC";
   $result_vt=mysql_query($sql_select);
   while ($vt=mysql_fetch_object($result_vt)){
      $echo_str .= "<tr><td>".$vt->sno."</td><td>".$vt->class."</td><td>".$vt->name."</td><td>".$vt->sex."</td><td>".$vt->total."</td></tr>\n";
   }

$echo_str .= "</table></body><html>";
echo $echo_head."<tr><td colspan=".$cols.">��ﵲ�G���</td></tr>".$echo_str;


?>
