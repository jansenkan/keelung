<?
include("config.inc.php");
$week=array("日","一","二","三","四","五","六");
$echo_str  = "<html><head>\n";
$echo_str .= "<meta http-equiv=\"Content-Type\" content=\"text/html; Charset=Big5\">\n";
$echo_str .= "<script language=\"JavaScript\" src=\"clock.js\"></script>\n";
$echo_str .= "</head><body onload=\"startclock()\">\n";
$echo_str .= "<table border=0 cellspacing=0 cellpadding=0 style=\"border-collapse: collapse\" bordercolor=#009380 width=100%>\n";
$echo_str .= "<form name=\"clock\" onsubmit=\"0\"><tr>\n";
$echo_str .= "<td bgcolor=#D0D0D0 style='font-size:15px;color:#111111' width=1% nowrap>&nbsp;&nbsp;&nbsp;中華民國".(date("Y")-1911).date("年m月d日　禮拜").$week[date("w")]."&nbsp;&nbsp;&nbsp;</td>\n";
$echo_str .= "<td bgcolor=#666666 align=center style='font-size:24px;font-family:標楷體;color:#F0F0F0'>".$Title."</td>\n";
$echo_str .= "<td bgcolor=#D0D0D0 style='font-size:15px;color:#111111' width=1% nowrap>&nbsp;&nbsp;&nbsp;<input style='border:0;color:#111111;font-size:17px;background:#D0D0D0' type=\"text\" name=\"face\" size=\"10\" value>&nbsp;&nbsp;&nbsp;</td>\n";
$echo_str .= "</tr></form>\n";
$echo_str .= "</table>\n";
$echo_str .= "</body>\n</html>\n";
echo $echo_str;
?>