<?
/* �N��ƥ�MySQL��Ʈw���g�JCSV�� */

include("config.inc.php");

/* 1.��X�p�����G */

   /* ��X��CSV���ɦW */
   $fname="\\Apache\\htdocs\\ttjh\\vote\\�p�����.csv";
   $fp = fopen ($fname,"w");
   
   echo "<font color=blue>���b�g�J�p�����G���...</font><br>";
   
   /* �g�J���Y */
   $tmp_str ="�s��,�Z��,�m�W,�ʧO,���,�o����";
   $sql_select = "select * from $P_S_tbl order by ps_no";
   $result_c = mysql_query($sql_select);
   $num_ca = mysql_num_rows($result_c);
   while ($ca=mysql_fetch_object($result_c)){
      $tmp_str .= ",".$ca->ps_name;
   }
   $tmp_str .= "\n";
   fwrite($fp,$tmp_str);
   
   /* �g�J�p����� */
   $sql_select="select * from $Cand_cnt_tbl order by total DESC";
   $result_vt=mysql_query($sql_select);
   $c=0;
   while ($vt=mysql_fetch_object($result_vt)){
      $output=$vt->sno.",".$vt->class.",".$vt->name.",".$vt->sex.",".$vt->remark.",".$vt->total;
      for($i=0;$i<$num_ca;$i++){
      	 $tmp = mysql_field_name($result_vt,$i+4);
         $output .= ",".$vt->$tmp;
      }
      $output .= "\n";
      if(fwrite($fp,$output))
         echo $vt->sno."-".$vt->class."-".$vt->name."�g�J�����I<br>";
      else
         echo "<font color=red>".$vt->sno."-".$vt->class."-".$vt->name."�g�J���ѡI</font><br>";
      $c++;
   }
   echo "<font color=blue>�@�g�J ".$c." ����ơI</font><br>";
   echo "<font color=red> �p�����G��Ƽg�J�����I</font><br>";
   echo "-------------------------------------------<br>";
   fclose($fp);
   
/* 2.��X���W�� */

   /* ��X��CSV���ɦW */
   $fname="\\Apache\\htdocs\\ttjh\\vote\\�����.csv";
   $fp = fopen ($fname,"w");
   
   echo "<font color=blue>���b�g�J���W����...</font><br>";
   
   /* �g�J���Y */
   $tmp_str ="�s��,�Z��,�m�W,�ʧO,�o����\n";
   fwrite($fp,$tmp_str);
   
   /* �g�J�p����� */
   $sql_select="select * from $Cand_cnt_tbl where remark='V' order by total DESC";
   $result_vt=mysql_query($sql_select);
   $c=0;
   while ($vt=mysql_fetch_object($result_vt)){
      $output=$vt->sno.",".$vt->class.",".$vt->name.",".$vt->sex.",".$vt->total."\n";
      if(fwrite($fp,$output))
         echo $vt->sno."-".$vt->class."-".$vt->name."�g�J�����I<br>";
      else
         echo "<font color=red>".$vt->sno."-".$vt->class."-".$vt->name."�g�J���ѡI</font><br>";
      $c++;
   }
   echo "<font color=blue>�@�g�J ".$c." ����ơI</font><br>";
   echo "<font color=red> ���W���Ƽg�J�����I</font><br>";
   echo "-------------------------------------------<br>";
   fclose($fp);
   
?>
<a href='javascript:window.close();'>����������</a>