<?
#   �ҽd�Ϳ��|
#   MySQL ��Ʈw�θ�ƪ�ظm�{��
#   by jansen 2003.03.16
#--------------------------------------------------------
#   Server version	3.22.32
include "config.inc.php";
/* �ܼƪ�ȳ]�w */
$go = isset($_GET['go'])?$_GET['go']:'';

if($Lock){
   echo "<center><font color=red size=5>��p�I���\��w�D�t����w�A�������~��ϥΡI</font></center>";
   exit;
}
/* �ҭn�إߤ���ƪ�W�� */
  $tblname1 = $Candidate_tbl;	/* �Կ�H��ƪ�W�� */
  $tblname2 = $P_S_tbl;				/* ��}���Ҹ�ƪ�W�� */
  $tblname3 = "config";				/* �t�θ�T��ƪ�W�� */
  $tblname4 = $P_S_sift_tbl;	/* ��}���Ҥ��Ͽz���ƪ�W�� */
  $tblname5 = $Zone_tbl;			/* �U���ϸ�ƪ�W�� */

/* �����Ʈw(�P�_��Ʈw�O�_�w�g�s�b) */
if(mysql_select_db($DbName) and ($go<>1)){
   echo "<center><font color=red size=+4>�M�Iĵ�i�I</font><br><br>";
   echo "<font color=green size=+1>�i".$DbName."�j��Ʈw�w�s�b�I</font><br><br>";
   echo "<font color=green size=+1>�Y�j��ءA�h(1)�Կ�H���(2)��}���Ҹ��(3)�}���έp��ơA�ҷ|�Q�}�a�I</font><br><br>\n";
   echo "<font color=green size=+1>�Y���Ĥ@���إߡA�h�L�v�T�I</font><br><br>\n";
   echo "<a href=".$_SERVER['PHP_SELF']."?go=1>�j��ءI(�ФT��ӫ��)</a><br></center>";
   exit;
}else{
   if ($go==1){
      echo "<font color=blue size=+1>�R����Ʈw�K</font><br>\n";
      $sql = 'DROP DATABASE '.$DbName;
      if(mysql_query($sql)){
         echo "<font color=#009300>��Ʈw ".$DbName." �R�������I</font><br>\n";
      }else{
         echo "<font color=red>��Ʈw ".$DbName." �R�����ѡI</font><br>\n";
      }
   }
   /* �R���Ҧ��Ӥ��� */
//   $pic = $path_img_abs."*.*";
//   unlink($pic);
}

/* �إ߸�Ʈw */
echo "<br><font color=blue size=+1>�إ߸�Ʈw�K</font><br>\n";
$sql = 'CREATE DATABASE '.$DbName;
if (mysql_query($sql)){
    print ("<font color=green>Database created successfully(�i".$DbName."�j��Ʈw�إߦ��\)</font><br>\n");
} else {
    printf ("<font color=red>Error creating database(�i".$DbName."�j��Ʈw�إߥ���): %s</font>\n", mysql_error());
    exit;
}

/* �����Ʈw */
echo "<br><font color=blue size=+1>�����Ʈw�K</font><br>\n";
if(mysql_select_db($DbName)){
   echo "<font color=green>select_db success(�i".$DbName."�j��Ʈw������\)</font><br>";
}else{
   echo"<font color=red>MayBe We Lose DataBase $DbName !(�i".$DbName."�j��Ʈw���s�b�I)</font><br>";
   exit;
}

/* �إ߸�ƪ� */
echo "<br><font color=blue size=+1>�إ߸�ƪ�K</font><br>\n";
#
# Table structure for table 'candidate'(�Կ�H�W��)
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
   echo "<font color=green>Table <".$tblname1."> Create success(�i".$tblname1."�j��ƪ�ظm���\)</font><br><br>";
}else {
   echo "<font color=red>Table <".$tblname1."> Create Error!(�i".$tblname1."�j��ƪ�ظm���ѡI)<br>
   MayBe Lose DataBase $DbName or Table ".$tblname1." already exist !</font><br><br>";
}

#
# Table structure for table 'polling_station'(�벼�ҦW��)
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
   echo "<font color=green>Table <".$tblname2."> Create success(�i".$tblname2."�j��ƪ�ظm���\)</font></font><br><br>";
}else {
   echo "<font color=red>Table <".$tblname2."> Create Error!(�i".$tblname2."�j��ƪ�ظm���ѡI)<br>
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
   echo "<font color=green>Table <".$tblname3."> Create success(�i".$tblname3."�j��ƪ�ظm���\)</font><br><br>";
}else {
   echo "<font color=red>Table <".$tblname3."> Create Error!(�i".$tblname3."�j��ƪ�ظm���ѡI)<br>
   MayBe Lose DataBase $DbName or Table ".$tblname3." already exist !</font><br><br>";
}

echo "<br><font color=blue size=+1>���J�w�]�ȡK</font><br>\n";
/* ���w��ƴ��J */
$sql="insert into ".$tblname3." (title,admin_name,admin_pass,_lock,ending,ballot2comein,refresh_sec1,refresh_sec2,remark) values
      ('XXXX���|�u�W�}���t��',
       'admin',
       'admin',
       '0',
       '0',
       '',
       '20',
       '999',
       '')";

if (mysql_query($sql)){
   echo "<font color=green>Table <".$tblname3."> Insert default data success(�i".$tblname3."�j��ƪ��w��ƴ��J���\)</font><br>";
}

echo "<br><font color=blue size=+1>�إ߸�ƪ�K</font><br>\n";
#
# Table structure for table 'ps_sift'(��}���Ҥ��Ͽz���ƪ�W��)
#
$sql="CREATE TABLE ".$tblname4." (
   Rid      int(5)  NOT NULL auto_increment,
   ZoneNum  text not null,
   ps_no  	text not null,
   ps_name  text not null,
   primary key (Rid)
   
);";
   
if (mysql_query($sql)){
   echo "<font color=green>Table <".$tblname2."> Create success(�i".$tblname2."�j��ƪ�ظm���\)</font></font><br><br>";
}else {
   echo "<font color=red>Table <".$tblname2."> Create Error!(�i".$tblname2."�j��ƪ�ظm���ѡI)<br>
   MayBe Lose DataBase $DbName or Table ".$tblname2." already exist !</font><br><br>";
}

#
# Table structure for table 'zone'(�U���ϸ�ƪ�W��)
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
   echo "<font color=green>Table <".$tblname2."> Create success(�i".$tblname2."�j��ƪ�ظm���\)</font></font><br><br>";
}else {
   echo "<font color=red>Table <".$tblname2."> Create Error!(�i".$tblname2."�j��ƪ�ظm���ѡI)<br>
   MayBe Lose DataBase $DbName or Table ".$tblname2." already exist !</font><br><br>";
}

?>
