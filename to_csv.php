<?
/* 將資料由MySQL資料庫中寫入CSV檔 */

include("config.inc.php");

/* 1.轉出計票結果 */

   /* 轉出之CSV檔檔名 */
   $fname="\\Apache\\htdocs\\ttjh\\vote\\計票資料.csv";
   $fp = fopen ($fname,"w");
   
   echo "<font color=blue>正在寫入計票結果資料...</font><br>";
   
   /* 寫入抬頭 */
   $tmp_str ="編號,班級,姓名,性別,當選,得票數";
   $sql_select = "select * from $P_S_tbl order by ps_no";
   $result_c = mysql_query($sql_select);
   $num_ca = mysql_num_rows($result_c);
   while ($ca=mysql_fetch_object($result_c)){
      $tmp_str .= ",".$ca->ps_name;
   }
   $tmp_str .= "\n";
   fwrite($fp,$tmp_str);
   
   /* 寫入計票資料 */
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
         echo $vt->sno."-".$vt->class."-".$vt->name."寫入完成！<br>";
      else
         echo "<font color=red>".$vt->sno."-".$vt->class."-".$vt->name."寫入失敗！</font><br>";
      $c++;
   }
   echo "<font color=blue>共寫入 ".$c." 筆資料！</font><br>";
   echo "<font color=red> 計票結果資料寫入完成！</font><br>";
   echo "-------------------------------------------<br>";
   fclose($fp);
   
/* 2.轉出當選名單 */

   /* 轉出之CSV檔檔名 */
   $fname="\\Apache\\htdocs\\ttjh\\vote\\當選資料.csv";
   $fp = fopen ($fname,"w");
   
   echo "<font color=blue>正在寫入當選名單資料...</font><br>";
   
   /* 寫入抬頭 */
   $tmp_str ="編號,班級,姓名,性別,得票數\n";
   fwrite($fp,$tmp_str);
   
   /* 寫入計票資料 */
   $sql_select="select * from $Cand_cnt_tbl where remark='V' order by total DESC";
   $result_vt=mysql_query($sql_select);
   $c=0;
   while ($vt=mysql_fetch_object($result_vt)){
      $output=$vt->sno.",".$vt->class.",".$vt->name.",".$vt->sex.",".$vt->total."\n";
      if(fwrite($fp,$output))
         echo $vt->sno."-".$vt->class."-".$vt->name."寫入完成！<br>";
      else
         echo "<font color=red>".$vt->sno."-".$vt->class."-".$vt->name."寫入失敗！</font><br>";
      $c++;
   }
   echo "<font color=blue>共寫入 ".$c." 筆資料！</font><br>";
   echo "<font color=red> 當選名單資料寫入完成！</font><br>";
   echo "-------------------------------------------<br>";
   fclose($fp);
   
?>
<a href='javascript:window.close();'>關閉本視窗</a>