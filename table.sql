<?
/*--------------------------------------------------
   �إ߸�ƪ� SQL �y�k
----------------------------------------------------*/
$create_tbl['config'] = "
CREATE TABLE `config` (
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
   primary key (rid) )";

$create_tbl['candidate'] = "
CREATE TABLE `candidate` (
   rid     int(5)  NOT NULL auto_increment,
   sno     tinyint , 
   class   text not null , 
   name    text not null , 
   sex     char (2) not null , 
   pic     text not null ,
   history text not null ,
   remark  text not null ,
   primary key (rid) )";

$create_tbl['polling_station'] = "
CREATE TABLE `polling_station` (
   rid      int(5)  NOT NULL auto_increment,
   ps_no    int(5),
   ps_name  text not null ,
   voters   text not null ,
   ps_user  text not null ,
   password varchar (32) not null ,
   ps_ip  	varchar(15),
   remark   text not null ,
   _lock    char (1) not null  , 
   primary key (rid) )";

$create_tbl['ps_sift'] = "
CREATE TABLE `ps_sift` (
   Rid      int(5)  NOT NULL auto_increment,
   ZoneNum  text not null,
   ps_no  	text not null,
   ps_name  text not null,
   primary key (Rid) )";

$create_tbl['zone'] = "
CREATE TABLE `zone` (
   Rid      int(5)  NOT NULL auto_increment,
   ZoneNum   text not null,
   ZoneName  text not null ,
   ZoneUser  text not null ,
   ZonePass  text not null ,
   ZoneIP    varchar(15),
   _lock     char (1) not null  , 
   remark    text not null ,
   primary key (Rid) )";


/*--------------------------------------------------
   ���J�w�]�Ȥ� SQL �y�k
----------------------------------------------------*/
$insert_tbl['config'] = "
insert into config (title,admin_name,admin_pass,_lock,ending,ballot2comein,refresh_sec1,refresh_sec2,remark) values
      ('XXXX���|�u�W�}���t��',
       'admin',
       'admin',
       '0',
       '0',
       '',
       '20',
       '999',
       '');";

?>
