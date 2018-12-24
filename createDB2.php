<?
#   模範生選舉
#   MySQL 資料庫及資料表建置程式
#   by jansen 2003.03.16
#--------------------------------------------------------
#   Server version	3.22.32

include "config.inc.php";

if($Lock){
   echo "<center><font color=red size=5>抱歉！本功能已遭系統鎖定，須解鎖後才能使用！</font></center>";
   exit;
}
   
echo "<html><head>\n
<META HTTP-EQUIV=REFRESH CONTENT='3;URL=admin.php'>\n
</head><body>\n";

/* 先drop掉與投開票統計資料有關之資料表 */
$sql_drop_tbl = "drop table ".$Vote_tbl;
mysql_query($sql_drop_tbl);
$sql_drop_tbl = "drop table ".$Cand_cnt_tbl;
mysql_query($sql_drop_tbl);

/* 重新建立新的資料表 */
#
# Table structure for table 'vote_count'
# 投票所導向之票數統計資料表
$sql = "CREATE TABLE ".$Vote_tbl." (
   rid      int(5)  NOT NULL auto_increment,
   ps_no    int(5),
   ps_name  text not null , 
   voters   int(5),
   invalid  int(5),
   total    int(5),";
   
$sql_select="select * from $Candidate_tbl order by sno";
$result_ca=mysql_query($sql_select);

while ($ca=mysql_fetch_object($result_ca)){
   $sql .= "sn".$ca->sno." int(5),";
} 

$sql .= "
   ZoneNum  text not null,
   ZoneName text not null,
   primary key (rid)
   
);";
   
if (mysql_query($sql)){
   echo "<font color=green>Table <".$Vote_tbl."> Create success(【".$Vote_tbl."】資料表建置成功)</font><br><br>";
}else {
   echo "<font color=red>Table <".$Vote_tbl."> Create Error!(【".$Vote_tbl."】資料表建置失敗！)<br>
   MayBe Lose DataBase $DbName or Table ".$Vote_tbl." already exist !</font><br>";
}

/* 新增投票所資料 */
$sql_select="select * from $P_S_tbl order by ps_no";
$result_ps=mysql_query($sql_select);

while ($ps=mysql_fetch_object($result_ps)){
   $sql_insert = "insert into $Vote_tbl (ps_no,ps_name,voters) values ('$ps->ps_no','$ps->ps_name','$ps->voters')";
   
   if(mysql_query($sql_insert)){
      echo "<font color=green>新增《".$ps->ps_name."》投票所成功！</font><br>";	
   }else{
      echo "<font color=red>新增《".$ps->ps_name."》投票所失敗！</font><br>";	
    }
} 
echo "<br><br>";
#
# Table structure for table 'cand_count'
# 候選人導向之票數統計資料表
$sql = "CREATE TABLE ".$Cand_cnt_tbl." (
   rid      int(5)  NOT NULL auto_increment,
   sno      int(5),
   name     text not null , 
   total    int(5),";
   
$sql_select="select * from $P_S_tbl order by ps_no";
$result_ca=mysql_query($sql_select);

while ($ca=mysql_fetch_object($result_ca)){
   $sql .= "ps".$ca->ps_no." int(5),";
} 

$sql .= "
   class    text not null , 
   sex      char (2) not null , 
   history  text,
   remark   text,
   primary key (rid)
   
);";
   
if (mysql_query($sql)){
   echo "<font color=green>Table <".$Cand_cnt_tbl."> Create success(【".$Cand_cnt_tbl."】資料表建置成功)</font><br><br>";
}else {
   echo "<font color=red>Table <".$Cand_cnt_tbl."> Create Error!(【".$Cand_cnt_tbl."】資料表建置失敗！)<br>
   MayBe Lose DataBase $DbName or Table ".$Cand_cnt_tbl." already exist !</font><br>";
}

/* 新增候選人資料 */
$sql_select="select * from $Candidate_tbl order by sno";
$result_ps=mysql_query($sql_select);

while ($ps=mysql_fetch_object($result_ps)){
   $sql_insert = "insert into $Cand_cnt_tbl (sno,name,class,sex,history,remark) values ('$ps->sno','$ps->name','$ps->class','$ps->sex','$ps->history','$ps->remark')";
   
   if(mysql_query($sql_insert)){
      echo "<font color=green>新增《".$ps->name."》候選人成功！</font><br>";	
   }else{
      echo "<font color=red>新增《".$ps->name."》候選人失敗！</font><br>";	
    }
} 

/* 鎖定與檔案結構相關之系統功能 */
$sql_update="update config set _lock='1'";
mysql_query($sql_update);

echo "</body></html>\n";

?>
