<?
include("config.inc.php");
session_id()?'':session_start();
/* 璸计竟 */
/* ﹚竡癘魁翴綷丁 session 跑计 */
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
$echo_str .= "<td><a href=admin.php?id_cate=id1 title='╰参恨瞶'><font style='font-size:20px;font-weight:900'>“ ╰参恨瞶</font></a></td>";
$echo_str .= "</tr>";
$echo_str .= "<tr>";
$echo_str .= "<td><a href=upd_vote.php?id_cate=id2&action=all target=_blank title='だ跋布计祅魁'><font style='font-size:20px;font-weight:900'>“ だ跋布计祅魁</font></a></td>";
$echo_str .= "</tr>";
$echo_str .= "<tr>";
$echo_str .= "<td><a href=upd_vote.php?id_cate=id3&action=single target=_blank title='щ秨布┮布计祅魁'><font style='font-size:20px;font-weight:900'>“ щ秨布┮布计祅魁</font></a></td>";
$echo_str .= "</tr>";
$echo_str .= "<tr>";
$echo_str .= "<td><a href=index1.htm target=_blank title='匡虏ざ近冀╰参'><font style='font-size:20px;font-weight:900'>“ 匡虏ざ近冀╰参</font></a></td>";
$echo_str .= "</tr>";
$echo_str .= "<tr>";
$echo_str .= "<td><a href=list_ps.php target=_blank title='秨布近冀╰参'><font style='font-size:20px;font-weight:900'>“ 秨布近冀╰参</font></a></td>";
$echo_str .= "</tr>";
$echo_str .= "<tr>";
$echo_str .= "<td><a href=list_cand_all.php target=_blank title='秨布近冀╰参(场匡)'><font style='font-size:20px;font-weight:900'>“ 秨布近冀╰参(┮Τ匡)</font></a></td>";
$echo_str .= "</tr>";
$echo_str .= "<tr>";
//$echo_str .= "<td style=\"FILTER: glow(color:#FFFF00,strength=3);color: #ffffff\"><div style=\"width: 300; height: 9\" class=coffee onMouseOver=\"this.style.filter='glow(color=#009300, strength=6)'\" onMouseOut='this.style.filter=\"\"'><a href=list_cand_all_f.php target=_blank title='秨布近冀╰参(–6)'><font style='font-size:20px;font-weight:900'>“ 秨布近冀╰参(–6)</font></a></div></td>";
$echo_str .= "<td><a href=index4.htm target=_blank title='秨布近冀╰参(–6)'><font style='font-size:20px;font-weight:900'>“ 秨布近冀╰参(–6)</font></a></td>";
$echo_str .= "</tr>";
$echo_str .= "<tr>";
$echo_str .= "<td><a href=index5.htm target=_blank title='秨布近冀╰参(だ澄礶)'><font style='font-size:20px;font-weight:900'>“ 秨布近冀╰参(だ澄礶)</font></a></td>";
$echo_str .= "</tr>";
$echo_str .= "<tr>";
$echo_str .= "<td><a href=index6.htm target=_blank title='秨布近冀╰参(だ跋参璸)'><font style='font-size:20px;font-weight:900'>“ 秨布近冀╰参(だ跋参璸)</font></a></td>";
$echo_str .= "</tr>";
$echo_str .= "</table>\n";
//$echo_str .= "<font color=FFFFFF>".$Hits."</font>";
$echo_str .= "</center>\n";
$echo_str .= "</body></html>\n";
   
echo $echo_str;

?>