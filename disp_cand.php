<?
include "config.inc.php";
/* 變數初值設定 */
$sno     = isset($_GET['sno'])?$_GET['sno']:'';
$sno     = isset($_POST['sno'])?$_POST['sno']:$sno;
$class   = isset($_GET['class'])?$_GET['class']:'';
$class   = isset($_POST['class'])?$_POST['class']:$class;
$name    = isset($_GET['name'])?$_GET['name']:'';
$name    = isset($_POST['name'])?$_POST['name']:$name;
$sex     = isset($_GET['sex'])?$_GET['sex']:'';
$sex     = isset($_POST['sex'])?$_POST['sex']:$sex;
$history = isset($_GET['history'])?$_GET['history']:'';
$history = isset($_POST['history'])?$_POST['history']:$history;
$remark  = isset($_GET['remark'])?$_GET['remark']:'';
$remark  = isset($_POST['remark'])?$_POST['remark']:$remark;
$action  = isset($_GET['action'])?$_GET['action']:'';
$action  = isset($_POST['action'])?$_POST['action']:$action;
$rid     = isset($_GET['rid'])?$_GET['rid']:'';
$rid     = isset($_POST['rid'])?$_POST['rid']:$rid;
$mode    = isset($_GET['mode'])?$_GET['mode']:'';
$mode    = isset($_POST['mode'])?$_POST['mode']:$mode;
$sno_delay = isset($_GET['sno_delay'])?$_GET['sno_delay']:'';
$sno_delay = isset($_POST['sno_delay'])?$_POST['sno_delay']:$sno_delay;
$num_circle = array('1' => 'j', '2' => 'k', '3' => 'l', '4' => 'm', '5' => 'n');

/* 計數器用之函數 */
//include ("..\functions.php");
if(isset($_GET['hit'])) refresh_hits(); 	// 首次點選計數器才加1

/* 顏色表 */
$title_clr       ="#009380";
$title_bg_clr    ="#009380";
$title_txt_clr   ="#FFFFFF";
$content_txt_clr ="purple";
$total_clr       ="red";
$ending_clr  	 ="red";
$rank_clr        ="blue";
$mark_clr        ="blue";
$time_clr        ="green";
$number_clr      ="green";

if(!$sno_delay) $sno_delay=10;

if($sno<1){
   $sql_select = "select * from $Candidate_tbl order by sno DESC";
   $result = mysql_query($sql_select);
   $o = mysql_fetch_object($result);
   $sno=$o->sno;
}
$link = 0;
/* 最多測試跳5號 */
for($i=0;$i<5;$i++){
   $sql_select = "select * from $Candidate_tbl where sno='".$sno."'";
   $result = mysql_query($sql_select);
   $num = mysql_num_rows($result);
   if($mode=='back') $step_sno = $sno-1;
   else $step_sno = $sno+1;
   if($num>0){
      $link=1;
      break;
   } 
   if($mode=='back') $sno--;
   else $sno++;
}
/**/
if(!$link) {
   $sno=1;
   if($mode=='back') $step_sno = $sno-1;
   else $step_sno = $sno+1;
   header("location:".$_SERVER['PHP_SELF']."?mode=".$mode."&sno_delay=".$sno_delay."&sno=".$sno);
   exit;
}

$echo_str = "<html><head>\n";
$echo_str .= "<meta http-equiv=\"Content-Type\" content=\"text/html; Charset=Big5\">\n";
$echo_str .= "<META HTTP-EQUIV=REFRESH CONTENT='".$sno_delay.";URL=".$_SERVER['PHP_SELF']."?mode=".$mode."&sno_delay=".$sno_delay."&sno=".$step_sno."'>\n";
$echo_str .= "<Link Rel='stylesheet' Type='text/css' Href='style_c.css'>\n";
$echo_str .= "<title>".$Title."--候選人介紹輪播</title>\n";
//$echo_str .= "<bgsound src=\"Amsong55.mid\" loop=\"-1\">\n";
$echo_str .= "</head><body style='margin-top:2'>\n";
$echo_str .= "<center><form action=".$_SERVER['PHP_SELF']." method=POST>\n";
$echo_str .= "<font size=+1 color=$title_clr>".$Title."(候選人介紹)</font><br>\n";
   
$sql_select = "select * from $Candidate_tbl where sno='".$sno."'";
$result = mysql_query($sql_select);
$o = mysql_fetch_object($result);
$sno     = $o->sno;
$class   = $o->class;
$name    = stripslashes($o->name);
$sex     = $o->sex;
$pic     = $o->pic;
$history = stripslashes($o->history);
/* 讀取當選資料 */
$sql_select_cnt = "select * from $Cand_cnt_tbl where sno='".$sno."'";
$result_cnt = mysql_query($sql_select_cnt);
$o_cnt = @mysql_fetch_object($result_cnt);
$remark  = stripslashes($o_cnt->remark);
//
$photo['女'] = "<img src=images/girl.gif>";
$photo['男'] = "<img src=images/boy.gif>";
$echo_str .= "<table border=2 style=\"border-collapse: collapse\" bordercolor=\"#006400\" cellspacing=0>\n";
$echo_str .= "<tr><td valign=center align=center rowspan=6 style='width:240;height:320;background:#009380'>".(empty($pic)?$photo[$sex]:"<img src=".$path_img_rela.$pic." width=235 height=315>")."</td>";
$echo_str .= "<td align=center class=disp_cand_white>編號</td><td align=center class=disp_cand_orange style='font-size:36px;font-family:Wingdings 2;'>".$num_circle[$sno]."</td></tr>\n";
$echo_str .= "<tr><td align=center class=disp_cand_white>黨籍</td><td align=center class=disp_cand_green>".$class."</td></tr>\n";
$echo_str .= "<tr><td align=center class=disp_cand_white>姓名</td><td align=center class=".(($sex=='男')?"disp_cand_blue":"disp_cand_red").">".$name."</td></tr>\n";
$echo_str .= "<tr><td align=center class=disp_cand_white>性別</td><td align=center class=".(($sex=='男')?"disp_cand_blue":"disp_cand_red").">".$sex."</td></tr>\n";
$echo_str .= "<tr><td colspan=2 align=center bgcolor=".$title_bg_clr." style='width:240'><font color=".$title_txt_clr." style='font-size:20px'>簡　明　政　見</font></td></tr>\n";
$echo_str .= "<tr><td colspan=2 class=disp_cand_orange style='width:280;height:190;' ".($remark?"background=electee.jpg":"bgcolor=#EEFFEE").">".(empty($history)?"&nbsp;":$history)."</td></tr>\n";
$echo_str .= "</table>\n";
$echo_str .= "<input type=button name=b1 value=\"<<上一位\" onclick=\"location='".$_SERVER['PHP_SELF']."?mode=back&sno_delay=".$sno_delay."&sno=".($sno-1)."'\">\n";
$echo_str .= "&nbsp;&nbsp;&nbsp;(每 <font color=red>".select_option()."</font> 秒自動換頁)&nbsp;&nbsp;&nbsp;";
$echo_str .= "<input type=button name=b1 value=\"下一位>>\" onclick=\"location='".$_SERVER['PHP_SELF']."?sno_delay=".$sno_delay."&sno=".($sno+1)."'\">\n";
$echo_str .= "<br><br><font color=green>【說明】：點選「下一位」(內定)會順向換頁，點選「上一位」則會反向換頁。</font>\n";
$echo_str .= "<input type=hidden name=mode value=".$mode.">\n";
$echo_str .= "<input type=hidden name=sno value=".$sno.">\n";
$echo_str .= "<br><br><font color=#D0D0D0>".show_hits(5)."</font>"; 
$echo_str .= "</form></center>\n";
$echo_str .= "</body></html>\n";
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

/* 秒數選項表單(select) */
function select_option()
{
   global   $sno_delay;

   $sec = array('5','10','20','30');
   $script = "<select name=sno_delay style='color:red;font-size:9pt;' onchange=this.form.submit();>\n";
   foreach($sec as $k => $v)
      $script .= "<option value=".$v.(($sno_delay==$v)?' selected':'').">".$v."</option>\n";
   $script .= "</select>\n";
   
   return $script;
}
?>