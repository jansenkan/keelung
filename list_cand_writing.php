<?
include "config.inc.php";
$photo['女'] = "<img src=images/girl.gif>";
$photo['男'] = "<img src=images/boy.gif>";
$sql_select = "select * from $Candidate_tbl order by sno";
$result = mysql_query($sql_select);
$echo_str = "<html>\n<body>\n";
$echo_str .= "<center>\n";
$echo_str .= "<h1>大道國中模範生選舉候選人名錄</h1>\n";
$echo_str .= "<table border=0 style=\"border-collapse: collapse\" cellspacing=0>\n";
$echo_str .= "<tr>\n";
$num_circle = array('1' => 'j', '2' => 'k', '3' => 'l', '4' => 'm', '5' => 'n');
$cnt = 0;
while($o = mysql_fetch_object($result)){
   $sno     = $o->sno;
   $class   = $o->class;
   $name    = stripslashes($o->name);
   $sex     = $o->sex;
   $pic     = $o->pic;
   $history = stripslashes($o->history);
   $remark  = stripslashes($o->remark);
   $echo_str .= "<td width=33%>\n";
   $echo_str .= "<table border=2 style=\"border-collapse: collapse\" bordercolorlight=\"#111111\" bordercolordark=\"#111111\" cellspacing=0>\n";
   $echo_str .= "<tr><td valign=center align=center rowspan=6 style='width:240;height:320;'>".(empty($pic)?$photo[$sex]:"<img src=".$path_img_rela.$pic." width=235 height=315>")."</td>";
   $echo_str .= "<td align=center class=disp_cand_white>編號</td><td align=center class=disp_cand_orange>".$sno."</td></tr>\n";
   $echo_str .= "<tr><td align=center class=disp_cand_white>黨籍</td><td align=center class=disp_cand_green>".$class."</td></tr>\n";
   $echo_str .= "<tr><td align=center class=disp_cand_white>姓名</td><td align=center class=".(($sex=='男')?"disp_cand_blue":"disp_cand_red").">".$name."</td></tr>\n";
   $echo_str .= "<tr><td align=center class=disp_cand_white>性別</td><td align=center class=".(($sex=='男')?"disp_cand_blue":"disp_cand_red").">".$sex."</td></tr>\n";
   $echo_str .= "<tr><td colspan=2 align=center style='width:150' nowrap><font style='font-size:20px'>簡　明　政　見</font></td></tr>\n";
   $echo_str .= "<tr><td colspan=2 class=disp_cand_orange style='width:280;height:150;' background=".($remark?"electee.jpg":"").">".(empty($history)?"&nbsp;":$history)."</td></tr>\n";
   $echo_str .= "</table>\n";
   $echo_str .= "</td>\n";
   $cnt++;
   // 換列
   if($cnt==3){
      $echo_str .= "</tr>\n<tr>\n";
   }
   // 換頁
   if($cnt==6){
      $echo_str .= "</tr>\n</table>\n";
      $echo_str .= "<br /><br /><br />\n";
      $echo_str .= "<h1>大道國中模範生選舉候選人名錄</h1>\n";
      $echo_str .= "<table border=0 style=\"border-collapse: collapse\" cellspacing=0>\n";
      $echo_str .= "<tr>\n";
      $cnt = 0;
   }
}
$echo_str .= "</center>\n</body>\n</html>";

echo $echo_str;

/* 中文班號轉換 */
function chinese_class($class_num)
{
   $chinese_num = array('','一','二','三','四','五','六','七','八','九','十',
                        '十一','十二','十三','十四','十五','十六','十七','十八','十九','二十');
   $grade = substr($class_num,0,1);
   $class = substr($class_num,1,2);
   
   return $chinese_num[$grade]."年".$chinese_num[$class*1]."班";
}
?>