<?
#   �ҽd�Ϳ��|
#   MySQL ��Ʈw�θ�ƪ�ظm�{��
#   by jansen 2003.03.16
#--------------------------------------------------------
#   Server version	3.22.32

include "config.inc.php";

if($Lock){
   echo "<center><font color=red size=5>��p�I���\��w�D�t����w�A�������~��ϥΡI</font></center>";
   exit;
}
   
echo "<html><head>\n
<META HTTP-EQUIV=REFRESH CONTENT='3;URL=admin.php'>\n
</head><body>\n";

/* ��drop���P��}���έp��Ʀ�������ƪ� */
$sql_drop_tbl = "drop table ".$Vote_tbl;
mysql_query($sql_drop_tbl);
$sql_drop_tbl = "drop table ".$Cand_cnt_tbl;
mysql_query($sql_drop_tbl);

/* ���s�إ߷s����ƪ� */
#
# Table structure for table 'vote_count'
# �벼�ҾɦV�����Ʋέp��ƪ�
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
   echo "<font color=green>Table <".$Vote_tbl."> Create success(�i".$Vote_tbl."�j��ƪ�ظm���\)</font><br><br>";
}else {
   echo "<font color=red>Table <".$Vote_tbl."> Create Error!(�i".$Vote_tbl."�j��ƪ�ظm���ѡI)<br>
   MayBe Lose DataBase $DbName or Table ".$Vote_tbl." already exist !</font><br>";
}

/* �s�W�벼�Ҹ�� */
$sql_select="select * from $P_S_tbl order by ps_no";
$result_ps=mysql_query($sql_select);

while ($ps=mysql_fetch_object($result_ps)){
   $sql_insert = "insert into $Vote_tbl (ps_no,ps_name,voters) values ('$ps->ps_no','$ps->ps_name','$ps->voters')";
   
   if(mysql_query($sql_insert)){
      echo "<font color=green>�s�W�m".$ps->ps_name."�n�벼�Ҧ��\�I</font><br>";	
   }else{
      echo "<font color=red>�s�W�m".$ps->ps_name."�n�벼�ҥ��ѡI</font><br>";	
    }
} 
echo "<br><br>";
#
# Table structure for table 'cand_count'
# �Կ�H�ɦV�����Ʋέp��ƪ�
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
   echo "<font color=green>Table <".$Cand_cnt_tbl."> Create success(�i".$Cand_cnt_tbl."�j��ƪ�ظm���\)</font><br><br>";
}else {
   echo "<font color=red>Table <".$Cand_cnt_tbl."> Create Error!(�i".$Cand_cnt_tbl."�j��ƪ�ظm���ѡI)<br>
   MayBe Lose DataBase $DbName or Table ".$Cand_cnt_tbl." already exist !</font><br>";
}

/* �s�W�Կ�H��� */
$sql_select="select * from $Candidate_tbl order by sno";
$result_ps=mysql_query($sql_select);

while ($ps=mysql_fetch_object($result_ps)){
   $sql_insert = "insert into $Cand_cnt_tbl (sno,name,class,sex,history,remark) values ('$ps->sno','$ps->name','$ps->class','$ps->sex','$ps->history','$ps->remark')";
   
   if(mysql_query($sql_insert)){
      echo "<font color=green>�s�W�m".$ps->name."�n�Կ�H���\�I</font><br>";	
   }else{
      echo "<font color=red>�s�W�m".$ps->name."�n�Կ�H���ѡI</font><br>";	
    }
} 

/* ��w�P�ɮ׵��c�������t�Υ\�� */
$sql_update="update config set _lock='1'";
mysql_query($sql_update);

echo "</body></html>\n";

?>
