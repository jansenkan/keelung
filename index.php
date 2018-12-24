<?
include("config.inc.php");
session_id()?'':session_start();
/* 計數器 */
/* 定義記錄點閱時間的 session 變數 */
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
$echo_str .= "<td><a href=admin.php?id_cate=id1 title='系統管理'><font style='font-size:20px;font-weight:900'>※ 系統管理</font></a></td>";
$echo_str .= "</tr>";
$echo_str .= "<tr>";
$echo_str .= "<td><a href=upd_vote.php?id_cate=id2&action=all target=_blank title='分區票數登錄'><font style='font-size:20px;font-weight:900'>※ 分區票數登錄</font></a></td>";
$echo_str .= "</tr>";
$echo_str .= "<tr>";
$echo_str .= "<td><a href=upd_vote.php?id_cate=id3&action=single target=_blank title='投開票所票數登錄'><font style='font-size:20px;font-weight:900'>※ 投開票所票數登錄</font></a></td>";
$echo_str .= "</tr>";
$echo_str .= "<tr>";
$echo_str .= "<td><a href=index1.htm target=_blank title='候選人簡介輪播系統'><font style='font-size:20px;font-weight:900'>※ 候選人簡介輪播系統</font></a></td>";
$echo_str .= "</tr>";
$echo_str .= "<tr>";
$echo_str .= "<td><a href=list_ps.php target=_blank title='開票即時輪播系統'><font style='font-size:20px;font-weight:900'>※ 開票即時輪播系統</font></a></td>";
$echo_str .= "</tr>";
$echo_str .= "<tr>";
$echo_str .= "<td><a href=list_cand_all.php target=_blank title='開票即時輪播系統(全部候選人)'><font style='font-size:20px;font-weight:900'>※ 開票即時輪播系統(所有候選人)</font></a></td>";
$echo_str .= "</tr>";
$echo_str .= "<tr>";
//$echo_str .= "<td style=\"FILTER: glow(color:#FFFF00,strength=3);color: #ffffff\"><div style=\"width: 300; height: 9\" class=coffee onMouseOver=\"this.style.filter='glow(color=#009300, strength=6)'\" onMouseOut='this.style.filter=\"\"'><a href=list_cand_all_f.php target=_blank title='開票即時輪播系統(每頁6人)'><font style='font-size:20px;font-weight:900'>※ 開票即時輪播系統(每頁6人)</font></a></div></td>";
$echo_str .= "<td><a href=index4.htm target=_blank title='開票即時輪播系統(每頁6人)'><font style='font-size:20px;font-weight:900'>※ 開票即時輪播系統(每頁6人)</font></a></td>";
$echo_str .= "</tr>";
$echo_str .= "<tr>";
$echo_str .= "<td><a href=index5.htm target=_blank title='開票即時輪播系統(分割畫面)'><font style='font-size:20px;font-weight:900'>※ 開票即時輪播系統(分割畫面)</font></a></td>";
$echo_str .= "</tr>";
$echo_str .= "<tr>";
$echo_str .= "<td><a href=index6.htm target=_blank title='開票即時輪播系統(分區統計)'><font style='font-size:20px;font-weight:900'>※ 開票即時輪播系統(分區統計)</font></a></td>";
$echo_str .= "</tr>";
$echo_str .= "</table>\n";
//$echo_str .= "<font color=FFFFFF>".$Hits."</font>";
$echo_str .= "</center>\n";
$echo_str .= "</body></html>\n";
   
echo $echo_str;

?>