<?
#---------------------------------------------------
#   �ҽd�Ϳ��|�p���޲z�D�{��
#   by jansen since 2003.03.16
#---------------------------------------------------
//$sp_title = "�ҽd�Ϳ��|�p���޲z";
//$requiredFullname = array("�L�Q�O","�̤��H");
include("chkuser.inc.php");
include "config.inc.php";
session_id()?'':session_start();
/* �D�t�κ޲z�̡A�Ч�D�I */
if($_SESSION['root']<>"sys_player"){
   header("location:index.php");
   exit;
}
/* �ܼƪ�ȳ]�w */
$action = isset($_GET['action'])?$_GET['action']:'';

/* ���s��w*/
if($action=='lock'){
   $sql_update = "update config set _lock='1'";
   mysql_query($sql_update);
   header("location:".$_SERVER['PHP_SELF']);
   exit;
}

/* �Ѱ���w*/
if($action=='unlock'){
   $sql_update = "update config set _lock=''";
   mysql_query($sql_update);
   header("location:".$_SERVER['PHP_SELF']);
   exit;
}

/* ���������O */
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
   
   $echo_str .= "<center><h2>���������O�����I�I�I</h2></center>";
   
   echo $echo_str;
   exit;
}

/* ���H���O�B�z�{���X(for keelung) */
if($action=='ending2'){
   $sql_select="select * from $Cand_cnt_tbl order by total DESC";
   $result = mysql_query($sql_select);
   $o = mysql_fetch_object($result);
   $rid = $o->rid;
   
   $sql_update="update $Cand_cnt_tbl set remark='V' where rid='".$rid."'";
   mysql_query($sql_update);
   
   /* �t�ΰ��p���������O */
   $sql_update = "update config set ending='1'";
   mysql_query($sql_update);
   
   header("location:".$_SERVER['PHP_SELF']);
   exit;
	
}

/* ���H���O�B�z�{���X */
if($action=='ending'){

   /* �U�~�Ũk�k�ͫO�٦W�B�U�@�W */
   $sql_select = array();
   $sql_select[1]="select * from $Cand_cnt_tbl where class LIKE '1%' and sex='�k' order by total DESC";
   $sql_select[2]="select * from $Cand_cnt_tbl where class LIKE '1%' and sex='�k' order by total DESC";
   $sql_select[3]="select * from $Cand_cnt_tbl where class LIKE '2%' and sex='�k' order by total DESC";
   $sql_select[4]="select * from $Cand_cnt_tbl where class LIKE '2%' and sex='�k' order by total DESC";
   $sql_select[5]="select * from $Cand_cnt_tbl where class LIKE '3%' and sex='�k' order by total DESC";
   $sql_select[6]="select * from $Cand_cnt_tbl where class LIKE '3%' and sex='�k' order by total DESC";

   echo "<font color=orange>�U�~�ūO�٦W�B���H(�k�B�k�U1�W)</font>";
   echo "<table border=1 style=\"border-collapse: collapse\" cellspacing=0 bordercolor=orange>";
   echo "<tr><td>�s��</td><td>�Z��</td><td>�m�W</td><td>�m�O</td><td>�o����</td></tr>";
   foreach($sql_select as $k => $sql){
      $result=mysql_query($sql);
      $o=mysql_fetch_object($result);
      echo "<tr align=center><td>".$o->sno."</td><td>".$o->class."</td><td>".$o->name."</td><td>".$o->sex."</td><td><font color=red>".$o->total."</font></td></tr>";
      $target=$o->rid;
      $sql_update="update $Cand_cnt_tbl set remark='V' where rid='".$target."'";
      mysql_query($sql_update);
   }
   echo "</table><br>";
   
   /* �U�~�Ű��O�٦W�B�~���e�G�W */
   unset($sql_select);
   $sql_select = array();
   $sql_select[1]="select * from $Cand_cnt_tbl where class LIKE '1%' and remark<>'V' order by total DESC";
   $sql_select[2]="select * from $Cand_cnt_tbl where class LIKE '2%' and remark<>'V' order by total DESC";
   $sql_select[3]="select * from $Cand_cnt_tbl where class LIKE '3%' and remark<>'V' order by total DESC";

   echo "<font color=blue>�U�~�Ű��O�٦W�B�~���e�G�W���H</font>";
   echo "<table border=1 style=\"border-collapse: collapse\" cellspacing=0 bordercolor=blue>";
   echo "<tr><td>�s��</td><td>�Z��</td><td>�m�W</td><td>�m�O</td><td>�o����</td></tr>";
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

   /* ���~�ť���蠟�Կ�H���e�|�W */
   unset($sql_select);
   $sql_select="select * from $Cand_cnt_tbl where remark<>'V' order by total DESC";

   echo "<font color=green>���~�ť���蠟�e�|�W���H</font>";
   echo "<table border=1 style=\"border-collapse: collapse\" cellspacing=0 bordercolor=green>";
   echo "<tr><td>�s��</td><td>�Z��</td><td>�m�W</td><td>�m�O</td><td>�o����</td></tr>";
   $result=mysql_query($sql_select);
   for($j=1;$j<=4;$j++){
      $o=mysql_fetch_object($result);
      echo "<tr align=center><td>".$o->sno."</td><td>".$o->class."</td><td>".$o->name."</td><td>".$o->sex."</td><td><font color=red>".$o->total."</font></td></tr>";
      $target=$o->rid;
      $sql_update="update $Cand_cnt_tbl set remark='V' where rid='".$target."'";
      mysql_query($sql_update);
   }
   echo "</table><br>";

   /* �t�ΰ��p���������O */
   $sql_update = "update config set ending='1'";
   mysql_query($sql_update);
   exit;
}

/* ----- �޲z���Ѧ��}�l ----- */
/* �e����s�p�ɬ�� */
$refresh_time    =20;

/* �C��� */
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
$echo_str .= "<title>".$Title."--�t�κ޲z</title>\n";
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
$echo_str .= "<font color=$title_txt_clr><b>�t�κ޲z�\����</b></font>";
$echo_str .= "</td></tr>\n";
$echo_str .= "<tr bgcolor=#FFFF00><td align=center>";
$echo_str .= "�H�U�\��Ȧb<b><font color=#FF0000>�Ĥ@���ҥή�</font></b>�~���I��A<br>���ԷV�ϥΡI";
$echo_str .= "</td></tr>\n";

/* ��w�ɪ��B�z�覡 */
if($Lock){
   $echo_str .= "<tr><td>";
   $echo_str .= "<font color=$lock_clr>".$cnt++.".���s�Ұʽu�W�Y�ɶ}���t��(�w�Q��w)</font>";
}else{
   $echo_str .= "<tr><td bgcolor=#D0D0D0>";
   $echo_str .= "<a href=createDB.php target=_blank>".$cnt++.".���s�Ұʽu�W�Y�ɶ}���t��</a>";
}  

$echo_str .= "</td></tr>\n";
$echo_str .= "<tr bgcolor=orange><td align=center>";
$echo_str .= "<font color=FFFFFF>�H�U�G������}�����w�ưʧ@�A<br>�нT�꧹���I</font>";
$echo_str .= "</td></tr>\n";
$echo_str .= "<tr><td>";

/* ��w�ɪ��B�z�覡 */
if($Lock)
   $echo_str .= "<font color=$lock_clr>".$cnt++.".�إ߭Կ�H�W��(�w�Q��w)</font>";
else
   $echo_str .= "<a href=add_cand.php target=_blank>".$cnt++.".�إ߭Կ�H�W��</a>";

$echo_str .= "</td></tr>\n";
$echo_str .= "<tr><td>";

/* ��w�ɪ��B�z�覡 */
if($Lock)
   $echo_str .= "<font color=$lock_clr>".$cnt++.".�إߧ�}���ҦW��(�w�Q��w)</font>";
else
   $echo_str .= "<a href=add_p_s.php target=_blank>".$cnt++.".�إߧ�}���ҦW��</a>";

$echo_str .= "</td></tr>\n";
$echo_str .= "<tr><td>";

/* ��w�ɪ��B�z�覡 */
if($Lock)
   $echo_str .= "<font color=$lock_clr>".$cnt++.".�إߦU���ϦW��(�w�Q��w)</font>";
else
   $echo_str .= "<a href=set_zone.php target=_blank>".$cnt++.".�إߦU���ϦW��</a>";

$echo_str .= "</td></tr>\n";
$echo_str .= "<tr bgcolor=red><td align=center>";
$echo_str .= "<font color=FFFFFF>�нT�{�H�W�T���W���إߧ�����<br>�~��i��H�U�ʧ@</font>";
$echo_str .= "</td></tr>\n";
$echo_str .= "<tr><td>";

/* ��w�ɪ��B�z�覡 */
if($Lock)
   $echo_str .= "<font color=$lock_clr>".$cnt++.".�Ұʽu�W�Y�ɶ}������(�w�Q��w)</font>";
else{
   $echo_str .= "<a href=createDB2.php>".$cnt++.".�Ұʽu�W�Y�ɶ}������</a><br>";
   $echo_str .= "<a href=".$_SERVER['PHP_SELF']."?action=lock>���s��w</a>";
}
$echo_str .= "</td></tr>";

/* ��w�ɪ��B�z�覡 */
if($Lock){
   $echo_str .= "<tr bgcolor=red><td align=center>";
   $echo_str .= "<font color=FFFFFF>���D�n���s�ҥΨt�ΡA�_�h�ФŸ���I<br>�H���t�ιB�@���`�I</font>";
   $echo_str .= "</td></tr>\n";
   $echo_str .= "<tr onMouseOver=setBG('#FFFFA0',this) onMouseout=setBG('',this)><td>";
   $echo_str .= "<a href=".$_SERVER['PHP_SELF']."?action=unlock>�Ѱ���w</a><br><font color=red>�Y���u�W�v�u�R�v�Կ�H�Χ�}���ҡA<br>�h�ݭ��s�u<font color=cryan><b>".($cnt-1).".�Ұʽu�W�Y�ɶ}������</b></font>�v<br>�B�Ҧ��}����ƱN�Q�R���I</font>";
   $echo_str .= "</td></tr>\n";
}
$echo_str .= "<tr onMouseOver=setBG('#FFFFA0',this) onMouseout=setBG('',this)><td>";
$echo_str .= "<a href=changetitle.php target=_blank>".$cnt++.".�t�μ��D�[�ѼƳ]�w</a>";
$echo_str .= "</td></tr>\n";
$echo_str .= "<tr onMouseOver=setBG('#FFFFA0',this) onMouseout=setBG('',this)><td>";
$echo_str .= "<a href=list_cand_writing.php target=_blank>".$cnt++.".�Կ�H�W���C�L(A3��L�A�C��6�H)</a>";
$echo_str .= "</td></tr>\n";
$echo_str .= "<tr onMouseOver=setBG('#FFFFA0',this) onMouseout=setBG('',this)><td>";
$echo_str .= "<a href=logout.php?url=".$_SERVER['PHP_SELF'].">".$cnt++.".�n�X���޲z���</a>";
$echo_str .= "</td></tr>\n";
$echo_str .= "</table>\n\n";
$echo_str .= "</td><td valign=top>\n\n";

/* ��w�ɪ��B�z�覡 */
if($Lock){
   $echo_str .= "<table border=1 style=\"border-collapse: collapse\" cellspacing=0 cellpadding=3 bordercolor=$table_clr>\n";
   $echo_str .= "<tr bgcolor=green><td align=center>";
   $echo_str .= "<font color=FFFFFF>�@��\����</font>";
   $echo_str .= "</td></tr>\n";
   $echo_str .= "<tr onMouseOver=setBG('#FFFFA0',this) onMouseout=setBG('',this)><td>";
   $echo_str .= "<a href=upd_vote.php?action=all target=_blank>�n���Ҧ���}���Ҥ�����</a>";
   $echo_str .= "</td></tr>\n";
   $echo_str .= "<tr onMouseOver=setBG('#FFFFA0',this) onMouseout=setBG('',this)><td>";
   $echo_str .= "<a href=upd_vote.php?action=single target=_blank>�n����@��}���Ҥ�����</a>";
   $echo_str .= "</td></tr>\n";
   /* �p�������ɪ��B�z�覡 */
   if($Ending){
      $echo_str .= "<tr onMouseOver=setBG('#FFFFA0',this) onMouseout=setBG('',this)><td>";
      $echo_str .= "<a href=".$_SERVER['PHP_SELF']."?action=unmark><font color=red>����</font>�Ҧ����H�������O</a>";
      $echo_str .= "</td></tr>\n";
   }else{
      $echo_str .= "<tr onMouseOver=setBG('#FFFFA0',this) onMouseout=setBG('',this)><td>";
      $echo_str .= "<a href=".$_SERVER['PHP_SELF']."?action=ending2>�p�������A�P�O���H�å[���O</a>";
      $echo_str .= "</td></tr>\n";
   }

   $echo_str .= "<tr onMouseOver=setBG('#FFFFA0',this) onMouseout=setBG('',this)><td>";
   $echo_str .= "<a href=upd_cand.php target=_blank>�ק���H�����O</a>";
   $echo_str .= "</td></tr>\n";
   $echo_str .= "<tr onMouseOver=setBG('#FFFFA0',this) onMouseout=setBG('',this)><td>";
   $echo_str .= "<a href=to_excel.php target=_blank>�N�p�����G�ର excel ��</a><br>���ɧ�����A���I��u�ɮ�/�t�s�s�ɡv";
   $echo_str .= "</td></tr>\n";
   $echo_str .= "<tr onMouseOver=setBG('#FFFFA0',this) onMouseout=setBG('',this)><td>";
   $echo_str .= "<a href=to_word.php target=_blank>�N�p�����G�ର word ��</a><br>���ɧ�����A���I��u�ɮ�/�t�s�s�ɡv";
   $echo_str .= "</td></tr>\n";
   $echo_str .= "<tr onMouseOver=setBG('#FFFFA0',this) onMouseout=setBG('',this)><td>";
//   $echo_str .= "<a href=to_csv.php target=_blank>�N�p�����G�ର csv ��</a>";
   $echo_str .= "</td></tr>\n";
   $echo_str .= "<tr onMouseOver=setBG('#FFFFA0',this) onMouseout=setBG('',this)><td>";
//   $echo_str .= "���U�ƹ��k��ÿ�u�t�s�ؼСv�H�U��<br><<a href=\"�p�����.csv\">�p�����.csv</a>>��<<a href=�����.csv>�����.csv</a>>";
   $echo_str .= "</td></tr>\n";
   $echo_str .= "<tr bgcolor=pink><td align=center>";
   $echo_str .= "<font color=cryan>�H�U�\�ର�i���}���G���s��</font>";
   $echo_str .= "</td></tr>\n";
   $echo_str .= "<tr onMouseOver=setBG('#FFFFA0',this) onMouseout=setBG('',this)><td>";
   $echo_str .= "<a href=list_ps.php target=_blank>�}���Y�ɽ����t��</a>";
   $echo_str .= "</td></tr>\n";
   $echo_str .= "<tr onMouseOver=setBG('#FFFFA0',this) onMouseout=setBG('',this)><td>";
   $echo_str .= "<a href=list_cand_all.php target=_blank>�}���Y�ɽ����t��(�����Կ�H+�p�r��)</a>";
   $echo_str .= "</td></tr>\n";
   $echo_str .= "<tr onMouseOver=setBG('#FFFFA0',this) onMouseout=setBG('',this)><td>";
   $echo_str .= "<a href=list_cand_all_f.php?action=rank_only target=_blank>�}���Y�ɽ����t��(�֤H+�j�r��)</a>";
   $echo_str .= "</td></tr>\n";
   $echo_str .= "<tr onMouseOver=setBG('#FFFFA0',this) onMouseout=setBG('',this)><td>";
   $echo_str .= "<a href=disp_cand.php?sno=1 target=_blank>�Կ�H²�������t��</a>";
   $echo_str .= "</td></tr>\n";
   $echo_str .= "<tr onMouseOver=setBG('#FFFFA0',this) onMouseout=setBG('',this)><td>";
   $echo_str .= "<a href=index5.htm target=_blank>�}���Y�ɽ����t��(���εe��)</a>";
   $echo_str .= "</td></tr>\n";
   $echo_str .= "<tr onMouseOver=setBG('#FFFFA0',this) onMouseout=setBG('',this)><td>";
   $echo_str .= "<a href=index6.htm target=_blank>�}���Y�ɽ����t��(���ϲέp)</a>";
   $echo_str .= "</td></tr>\n";
}
$echo_str .= "</table>\n\n";
$echo_str .= "</td></tr></table>\n";
$echo_str .= "</center>\n";
$echo_str .= "</body></html>\n";
   
echo $echo_str;

?>