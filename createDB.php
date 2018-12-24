<?
#   模範生選舉
#   MySQL 資料庫及資料表建置程式
#   by jansen 2003.03.16
#--------------------------------------------------------
#   Server version	3.22.32
include "config.inc.php";
/* 變數初值設定 */
$go = isset($_GET['go'])?$_GET['go']:'';

if($Lock){
   echo "<center><font color=red size=5>抱歉！本功能已遭系統鎖定，須解鎖後才能使用！</font></center>";
   exit;
}
/* 所要建立之資料表名稱 */
  $tblname1 = $Candidate_tbl;	/* 候選人資料表名稱 */
  $tblname2 = $P_S_tbl;				/* 投開票所資料表名稱 */
  $tblname3 = "config";				/* 系統資訊資料表名稱 */
  $tblname4 = $P_S_sift_tbl;	/* 投開票所分區篩選資料表名稱 */
  $tblname5 = $Zone_tbl;			/* 各分區資料表名稱 */

/* 選取資料庫(判斷資料庫是否已經存在) */
if(mysql_select_db($DbName) and ($go<>1)){
   echo "<center><font color=red size=+4>危險警告！</font><br><br>";
   echo "<font color=green size=+1>【".$DbName."】資料庫已存在！</font><br><br>";
   echo "<font color=green size=+1>若強制重建，則(1)候選人資料(2)投開票所資料(3)開票統計資料，皆會被破壞！</font><br><br>\n";
   echo "<font color=green size=+1>若為第一次建立，則無影響！</font><br><br>\n";
   echo "<a href=".$_SERVER['PHP_SELF']."?go=1>強制重建！(請三思而後行)</a><br></center>";
   exit;
}else{
   if ($go==1){
      echo "<font color=blue size=+1>刪除資料庫…</font><br>\n";
      $sql = 'DROP DATABASE '.$DbName;
      if(mysql_query($sql)){
         echo "<font color=#009300>資料庫 ".$DbName." 刪除完成！</font><br>\n";
      }else{
         echo "<font color=red>資料庫 ".$DbName." 刪除失敗！</font><br>\n";
      }
   }
   /* 刪除所有照片檔 */
//   $pic = $path_img_abs."*.*";
//   unlink($pic);
}

/* 建立資料庫 */
echo "<br><font color=blue size=+1>建立資料庫…</font><br>\n";
$sql = 'CREATE DATABASE '.$DbName;
if (mysql_query($sql)){
    print ("<font color=green>Database created successfully(【".$DbName."】資料庫建立成功)</font><br>\n");
} else {
    printf ("<font color=red>Error creating database(【".$DbName."】資料庫建立失敗): %s</font>\n", mysql_error());
    exit;
}

/* 選取資料庫 */
echo "<br><font color=blue size=+1>選取資料庫…</font><br>\n";
if(mysql_select_db($DbName)){
   echo "<font color=green>select_db success(【".$DbName."】資料庫選取成功)</font><br>";
}else{
   echo"<font color=red>MayBe We Lose DataBase $DbName !(【".$DbName."】資料庫不存在！)</font><br>";
   exit;
}

/* 建立資料表 */
echo "<br><font color=blue size=+1>建立資料表…</font><br>\n";
#
# Table structure for table 'candidate'(候選人名錄)
#
$sql="CREATE TABLE ".$tblname1." (
   rid     int(5)  NOT NULL auto_increment,
   sno     tinyint , 
   class   text not null , 
   name    text not null , 
   sex     char (2) not null , 
   pic     text not null ,
   history text not null ,
   remark  text not null ,
   primary key (rid)
   
);";
   
if (mysql_query($sql)){
   echo "<font color=green>Table <".$tblname1."> Create success(【".$tblname1."】資料表建置成功)</font><br><br>";
}else {
   echo "<font color=red>Table <".$tblname1."> Create Error!(【".$tblname1."】資料表建置失敗！)<br>
   MayBe Lose DataBase $DbName or Table ".$tblname1." already exist !</font><br><br>";
}

#
# Table structure for table 'polling_station'(投票所名錄)
#
$sql="CREATE TABLE ".$tblname2." (
   rid      int(5)  NOT NULL auto_increment,
   ps_no    int(5),
   ps_name  text not null ,
   voters   text not null ,
   ps_user  text not null ,
   password varchar (32) not null ,
   ps_ip  	varchar(15),
   remark   text not null ,
   _lock    char (1) not null  , 
   primary key (rid)
   
);";
   
if (mysql_query($sql)){
   echo "<font color=green>Table <".$tblname2."> Create success(【".$tblname2."】資料表建置成功)</font></font><br><br>";
}else {
   echo "<font color=red>Table <".$tblname2."> Create Error!(【".$tblname2."】資料表建置失敗！)<br>
   MayBe Lose DataBase $DbName or Table ".$tblname2." already exist !</font><br><br>";
}

#
# Table structure for table 'config'
#
$sql="CREATE TABLE ".$tblname3." (
   rid        int(5)  NOT NULL auto_increment,
   title      text not null , 
   admin_name text not null ,
   admin_pass text not null ,
   _lock      char(1), 
   ending     char(1),
   ballot2comein 	int(11),
   refresh_sec1  	char(3),
   refresh_sec2  	char(3),
   bg_img  	  text,
   remark     text not null ,
   primary key (rid)
   
);";
   
if (mysql_query($sql)){
   echo "<font color=green>Table <".$tblname3."> Create success(【".$tblname3."】資料表建置成功)</font><br><br>";
}else {
   echo "<font color=red>Table <".$tblname3."> Create Error!(【".$tblname3."】資料表建置失敗！)<br>
   MayBe Lose DataBase $DbName or Table ".$tblname3." already exist !</font><br><br>";
}

echo "<br><font color=blue size=+1>插入預設值…</font><br>\n";
/* 內定資料插入 */
$sql="insert into ".$tblname3." (title,admin_name,admin_pass,_lock,ending,ballot2comein,refresh_sec1,refresh_sec2,remark) values
      ('XXXX選舉線上開票系統',
       'admin',
       'admin',
       '0',
       '0',
       '',
       '20',
       '999',
       '')";

if (mysql_query($sql)){
   echo "<font color=green>Table <".$tblname3."> Insert default data success(【".$tblname3."】資料表內定資料插入成功)</font><br>";
}

echo "<br><font color=blue size=+1>建立資料表…</font><br>\n";
#
# Table structure for table 'ps_sift'(投開票所分區篩選資料表名稱)
#
$sql="CREATE TABLE ".$tblname4." (
   Rid      int(5)  NOT NULL auto_increment,
   ZoneNum  text not null,
   ps_no  	text not null,
   ps_name  text not null,
   primary key (Rid)
   
);";
   
if (mysql_query($sql)){
   echo "<font color=green>Table <".$tblname2."> Create success(【".$tblname2."】資料表建置成功)</font></font><br><br>";
}else {
   echo "<font color=red>Table <".$tblname2."> Create Error!(【".$tblname2."】資料表建置失敗！)<br>
   MayBe Lose DataBase $DbName or Table ".$tblname2." already exist !</font><br><br>";
}

#
# Table structure for table 'zone'(各分區資料表名稱)
#
$sql="CREATE TABLE ".$tblname5." (
   Rid      int(5)  NOT NULL auto_increment,
   ZoneNum   text not null,
   ZoneName  text not null ,
   ZoneUser  text not null ,
   ZonePass  text not null ,
   ZoneIP    varchar(15),
   _lock     char (1) not null  , 
   remark    text not null ,
   primary key (Rid)
   
);";
   
if (mysql_query($sql)){
   echo "<font color=green>Table <".$tblname2."> Create success(【".$tblname2."】資料表建置成功)</font></font><br><br>";
}else {
   echo "<font color=red>Table <".$tblname2."> Create Error!(【".$tblname2."】資料表建置失敗！)<br>
   MayBe Lose DataBase $DbName or Table ".$tblname2." already exist !</font><br><br>";
}

?>
