<?
include "config.inc.php";
$photo['�k'] = "<img src=images/girl.gif>";
$photo['�k'] = "<img src=images/boy.gif>";
$sql_select = "select * from $Candidate_tbl order by sno";
$result = mysql_query($sql_select);
$echo_str = "<html>\n<body>\n";
$echo_str .= "<center>\n";
$echo_str .= "<h1>�j�D�ꤤ�ҽd�Ϳ��|�Կ�H�W��</h1>\n";
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
   $echo_str .= "<td align=center class=disp_cand_white>�s��</td><td align=center class=disp_cand_orange>".$sno."</td></tr>\n";
   $echo_str .= "<tr><td align=center class=disp_cand_white>���y</td><td align=center class=disp_cand_green>".$class."</td></tr>\n";
   $echo_str .= "<tr><td align=center class=disp_cand_white>�m�W</td><td align=center class=".(($sex=='�k')?"disp_cand_blue":"disp_cand_red").">".$name."</td></tr>\n";
   $echo_str .= "<tr><td align=center class=disp_cand_white>�ʧO</td><td align=center class=".(($sex=='�k')?"disp_cand_blue":"disp_cand_red").">".$sex."</td></tr>\n";
   $echo_str .= "<tr><td colspan=2 align=center style='width:150' nowrap><font style='font-size:20px'>²�@���@�F�@��</font></td></tr>\n";
   $echo_str .= "<tr><td colspan=2 class=disp_cand_orange style='width:280;height:150;' background=".($remark?"electee.jpg":"").">".(empty($history)?"&nbsp;":$history)."</td></tr>\n";
   $echo_str .= "</table>\n";
   $echo_str .= "</td>\n";
   $cnt++;
   // ���C
   if($cnt==3){
      $echo_str .= "</tr>\n<tr>\n";
   }
   // ����
   if($cnt==6){
      $echo_str .= "</tr>\n</table>\n";
      $echo_str .= "<br /><br /><br />\n";
      $echo_str .= "<h1>�j�D�ꤤ�ҽd�Ϳ��|�Կ�H�W��</h1>\n";
      $echo_str .= "<table border=0 style=\"border-collapse: collapse\" cellspacing=0>\n";
      $echo_str .= "<tr>\n";
      $cnt = 0;
   }
}
$echo_str .= "</center>\n</body>\n</html>";

echo $echo_str;

/* ����Z���ഫ */
function chinese_class($class_num)
{
   $chinese_num = array('','�@','�G','�T','�|','��','��','�C','�K','�E','�Q',
                        '�Q�@','�Q�G','�Q�T','�Q�|','�Q��','�Q��','�Q�C','�Q�K','�Q�E','�G�Q');
   $grade = substr($class_num,0,1);
   $class = substr($class_num,1,2);
   
   return $chinese_num[$grade]."�~".$chinese_num[$class*1]."�Z";
}
?>