<?
include("config.inc.php");
session_id()?'':session_start();
/* �p�ƾ� */
/* �w�q�O���I�\�ɶ��� session �ܼ� */
/*
if(!isset($_SESSION['click_time'])){ session_register('click_time');}
if(click_check($_SESSION['click_time'])){
   $Hits++;
   $sql_update = "update config set hits='".$Hits."'";
   mysql_query($sql_update);
}
*/
$echo_str  = "<html><head>\n";
$echo_str .= "<meta http-equiv=\"Content-Type\" content=\"text/html; Charset=Big5\">\n";
$echo_str .= "<Link Rel='stylesheet' Type='text/css' Href='style_c.css'>\n";
$echo_str .= "<title>".$Title."</title>\n";
$echo_str .= '
<script language="JavaScript">
<!--
function setBG(TheColor,TheObject) {TheObject.bgColor=TheColor}
//-->
</script>';
$echo_str .= "</head><body>\n";
$echo_str .= "<center>";
$echo_str .= "<table border=0 style=\"border-collapse: collapse\" bordercolor=\"#111111\" cellspacing=0>";
$echo_str .= "<tr>";
$echo_str .= "<td><font style='font-size:20px;font-weight:900;color:'>".$Title."</font></td>";
$echo_str .= "</tr>";
$echo_str .= "<tr>";
$echo_str .= "<td>&nbsp;</td>";
$echo_str .= "</tr>";
$echo_str .= "<tr>";
$echo_str .= "<td><a href=admin.php?id_cate=id1 title='�t�κ޲z'><font style='font-size:20px;font-weight:900'>�� �t�κ޲z</font></a></td>";
$echo_str .= "</tr>";
$echo_str .= "<tr>";
$echo_str .= "<td><a href=upd_vote.php?id_cate=id2&action=all target=_blank title='���ϲ��Ƶn��'><font style='font-size:20px;font-weight:900'>�� ���ϲ��Ƶn��</font></a></td>";
$echo_str .= "</tr>";
$echo_str .= "<tr>";
$echo_str .= "<td><a href=upd_vote.php?id_cate=id3&action=single target=_blank title='��}���Ҳ��Ƶn��'><font style='font-size:20px;font-weight:900'>�� ��}���Ҳ��Ƶn��</font></a></td>";
$echo_str .= "</tr>";
$echo_str .= "<tr>";
$echo_str .= "<td><a href=index1.htm target=_blank title='�Կ�H²�������t��'><font style='font-size:20px;font-weight:900'>�� �Կ�H²�������t��</font></a></td>";
$echo_str .= "</tr>";
$echo_str .= "<tr>";
$echo_str .= "<td><a href=list_ps.php target=_blank title='�}���Y�ɽ����t��'><font style='font-size:20px;font-weight:900'>�� �}���Y�ɽ����t��</font></a></td>";
$echo_str .= "</tr>";
$echo_str .= "<tr>";
$echo_str .= "<td><a href=list_cand_all.php target=_blank title='�}���Y�ɽ����t��(�����Կ�H)'><font style='font-size:20px;font-weight:900'>�� �}���Y�ɽ����t��(�Ҧ��Կ�H)</font></a></td>";
$echo_str .= "</tr>";
$echo_str .= "<tr>";
//$echo_str .= "<td style=\"FILTER: glow(color:#FFFF00,strength=3);color: #ffffff\"><div style=\"width: 300; height: 9\" class=coffee onMouseOver=\"this.style.filter='glow(color=#009300, strength=6)'\" onMouseOut='this.style.filter=\"\"'><a href=list_cand_all_f.php target=_blank title='�}���Y�ɽ����t��(�C��6�H)'><font style='font-size:20px;font-weight:900'>�� �}���Y�ɽ����t��(�C��6�H)</font></a></div></td>";
$echo_str .= "<td><a href=index4.htm target=_blank title='�}���Y�ɽ����t��(�C��6�H)'><font style='font-size:20px;font-weight:900'>�� �}���Y�ɽ����t��(�C��6�H)</font></a></td>";
$echo_str .= "</tr>";
$echo_str .= "<tr>";
$echo_str .= "<td><a href=index5.htm target=_blank title='�}���Y�ɽ����t��(���εe��)'><font style='font-size:20px;font-weight:900'>�� �}���Y�ɽ����t��(���εe��)</font></a></td>";
$echo_str .= "</tr>";
$echo_str .= "<tr>";
$echo_str .= "<td><a href=index6.htm target=_blank title='�}���Y�ɽ����t��(���ϲέp)'><font style='font-size:20px;font-weight:900'>�� �}���Y�ɽ����t��(���ϲέp)</font></a></td>";
$echo_str .= "</tr>";
$echo_str .= "</table>\n";
//$echo_str .= "<font color=FFFFFF>".$Hits."</font>";
$echo_str .= "</center>\n";
$echo_str .= "</body></html>\n";
   
echo $echo_str;

?>