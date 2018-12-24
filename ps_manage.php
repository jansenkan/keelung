<?
/* 比賽項目設定
   ps_manage.php */
//include("chkuser.inc.php");
include("config.inc.php");
session_start();
/*  資料表名稱 */ 
$Source_tbl = $P_S_tbl;
$Target_tbl = $P_S_sift_tbl;
$action  = isset($_GET['action'])   ?$_GET['action']   :'';
$action  = isset($_POST['action'])   ?$_POST['action']   :$action;
$confirm = isset($_GET['confirm'])  ?$_GET['confirm']  :'';
$confirm = isset($_POST['confirm'])  ?$_POST['confirm']  :$confirm;
$source  = isset($_POST['source'])  ?$_POST['source']  :'';
$target  = isset($_POST['target'])  ?$_POST['target']  :'';
$zone_no  = isset($_GET['zone_no'])?$_GET['zone_no']:'';
$zone_no  = isset($_POST['zone_no'])?$_POST['zone_no']:$zone_no;
$zone_na  = isset($_GET['zone_na'])?$_GET['zone_na']:'';
$zone_na  = isset($_POST['zone_na'])?$_POST['zone_na']:$zone_na;
/**/
$echo_str = "<html>\n";
$echo_str .= "<head>\n";
$echo_str .= "<meta http-equiv=\"Content-Type\" content=\"text/html; Charset=Big5\">\n";
$echo_str .= "<title>".$Title."</title>";
$echo_str .= "<Link Rel='stylesheet' Type='text/css' Href='style_c.css'>\n";
$echo_str .= '
<script language="JavaScript">
<!--
function setBG(TheColor,TheObject) {TheObject.bgColor=TheColor}
//-->
</script>
</head>
<body style="margin-top:5px">
<center>';
$echo_str .= "<font style='font-size:16pt;color:green'>各分區投開票所管理</font><br>";

/* 刪除所有組別之競賽項目 */
if($action=='del_all'){
   if($confirm=='yes'){
      $sql_delete = "delete from $Target_tbl";
      mysql_query($sql_delete);
   }else{
      $echo_str .= "<center><br>";
      $echo_str .= "<table style=\"border-collapse: collapse\" bordercolor=\"#FF0000\" border=1 bgcolor=yellow>\n";
      $echo_str .= "<tr><td>\n";
      $echo_str .= "<center><br>";
      $echo_str .= "<font color=red size=7><b>危險警告！</b></font><br><br><br>";
      $echo_str .= "<font color=green size=5>您確定要刪除各分區所有投開票所單位資料</font><br><br>";
      $echo_str .= "<font color=red>※一旦刪除則所有投開票所單位資料將必須重新設定！</font><br><br>";
      $echo_str .= "<input type=button name=b1 value=我很確定 onclick=\"location='".$_SERVER['PHP_SELF']."?action=del_all&confirm=yes&zone_no=$zone_no&".(session_id()?SID:'')."'\">&nbsp;&nbsp;&nbsp;&nbsp;";
      $echo_str .= "<input type=button name=b2 value=取　　消 onclick=\"location='javascript:window.history.back()'\">";
      $echo_str .= "<br><br>";
      $echo_str .= "</td></tr>\n";
      $echo_str .= "</table>\n";
      echo $echo_str;
      exit;
   }
   header("location:".$_SERVER['PHP_SELF']."?zone_no=$zone_no&".(session_id()?SID:''));
   exit;
}

/* 組別代碼初值設定 */
if(!isset($zone_no) or ($zone_no=='')){
   $sqlstr="select * from ".$Zone_tbl." order by ZoneNum";
   $result=mysql_query($sqlstr);
   $o=mysql_fetch_object($result);
   $zone_no = $o->ZoneNum;
   $zone_na = $o->ZoneName;
}

/* Insert */
if($action=='insert'){
   if(sizeof($source)>0)
//   print_r($source);
   foreach($source as $k => $v){
      $sqlstr="select * from ".$Source_tbl." where rid='$v'";
      $result_s=mysql_query($sqlstr);
      while($row=mysql_fetch_array($result_s)){
         $sqlstr="select * from ".$Target_tbl." where ps_no='".$row['ps_no']."' and ZoneNum='".$zone_no."'";
         $result_t=mysql_query($sqlstr);
         $num = mysql_num_rows($result_t);
         /* 目的資料表不存在相同資料時，才插入 */
         if($num==0){
            /* 先丟棄Rid欄位訊息() */
            $of = mysql_fetch_field($result_t);
   
            /* 讀取mysql欄位，並製作insert sql語法之前段並存於 $sql_inse 中 
               插入值存於 $ins_value 中                */
            $of = mysql_fetch_field($result_t);
            $sql_inse = "insert into ".$Target_tbl." (";
            $ins_value = "";
            $sql_inse .= $of->name;
            $ins_value .= "'".$zone_no."'";
            while($of = mysql_fetch_field($result_t)){
           	$sql_inse .= ",".$of->name;
           	$ins_value .= ",'".addslashes($row[$of->name])."'";
            }
            $sql_inse .= ") values (".$ins_value.")";
//            echo $sql_inse."<br>";exit;
            mysql_query($sql_inse);
         }
      }
   }

}
/* Remove */
if($action=='remove'){
   if(sizeof($target)>0)
   foreach($target as $k => $v){
      $sql_delete = "delete from $Target_tbl where Rid='$v'";
      mysql_query($sql_delete);
   }
}
/**/
$echo_str .= "<table style=\"border-collapse: collapse\" border=1>\n";
$echo_str .= "<tr><td>\n";
$echo_str .= "<table style=\"border-collapse: collapse\" bordercolor=\"#888888\" border=1>\n";
$echo_str .= "<tr>\n";
$sqlstr="select * from ".$Zone_tbl." order by ZoneNum*1";
$result=mysql_query($sqlstr);
while($o=mysql_fetch_object($result)){
   $zno = $o->ZoneNum;
   $zna = $o->ZoneName;
   if($zno==$zone_no){
      $echo_str .= "<td bgcolor=yellow>";
      $echo_str .= "<img src=images/79.gif>&nbsp;<font color=red style='font-size:9pt'>".$zno."<br>".$zna."</font>";
   }else{
      $echo_str .= "<td bgcolor=#E0E0E0 onMouseOver=setBG('#FFFFFF',this) onMouseout=setBG('#E0E0E0',this)>";
      $echo_str .= "<img src=images/79.gif>&nbsp;<a href=".$_SERVER['PHP_SELF']."?zone_no=$zno&zone_na=$zna&".(session_id()?SID:'')." style='font-size:9pt'>".$zno."<br>".$zna."</a>";
   }
   $echo_str .= "</td>\n";
}
$echo_str .= "<td bgcolor=#E0E0E0 onMouseOver=setBG('#FFFFFF',this) onMouseout=setBG('#E0E0E0',this)>";
$echo_str .= "<img src=images/delete.gif><br><a href=".$_SERVER['PHP_SELF']."?action=del_all&".(session_id()?SID:'')." style='font-size:9pt'>清空</a></td>";
$echo_str .= "</tr></table>\n";
$echo_str .= "</td></tr>\n";

/* 讀取所選取分區之內含競賽項目 */
$sqlstr="select * from ".$Target_tbl." where ZoneNum='".$zone_no."' order by ps_no*1";
$result=mysql_query($sqlstr);
$num_t = mysql_num_rows($result);
$opt = array();		/* 記錄已選取項目之陣列 */
$selected = array();	/* 記錄已選取項目的旗標之陣列 */
while($o=mysql_fetch_object($result)){
   $s_Rid    = $o->Rid;
   $PSNo    = $o->ps_no;
   $PSName  = $o->ps_name;
   $opt[$s_Rid] = $PSNo."_".$PSName;
   $selected[$PSNo] = 1;
}
/* 讀取所有分區之內含競賽項目 */
$sqlstr="select * from ".$Target_tbl." order by ps_no*1";
$result=mysql_query($sqlstr);
$num_t_all = mysql_num_rows($result);
$opt_all = array();		/* 記錄已選取項目之陣列 */
$selected_all = array();	/* 記錄已選取項目的旗標之陣列 */
while($o=mysql_fetch_object($result)){
   $s_Rid   = $o->Rid;
   $PSNo    = $o->ps_no;
   $PSName  = $o->ps_name;
   $opt_all[$s_Rid] = $PSNo."_".$PSName;
   $selected_all[$PSNo] = 1;
}
/* 項目選單內容 */
$echo_str .= "<tr><td align=center bgcolor=#F4F4F4>\n";
$echo_str .= "<table style=\"border-collapse: collapse\" bordercolor=\"#888888\" border=0 width=100%>\n";
$echo_str .= "<tr><td align=right>\n";
$echo_str .= "<form name=form_s action=".$_SERVER['PHP_SELF']." method=post>\n";
$echo_str .= "<input type=hidden name=action value=insert>\n";
$echo_str .= "<input type=hidden name=zone_no value=$zone_no>\n";
$echo_str .= "<input type=hidden name=zone_na value=$zone_na>\n";
$echo_str .= "<img src=images/22.gif><font color=green>&nbsp;所有投開票所</font>&nbsp;<img src=images/22.gif><br>";
$echo_str .= "<select name=source[] size=15 multiple style='background:#F0FFE0;color:green;width=128px'>\n";
$sqlstr="select * from ".$Source_tbl." order by ps_no*1";
$result=mysql_query($sqlstr);
$not_selected = 0;
while($o=mysql_fetch_object($result)){
   $s_Rid  = $o->rid;
   $PSNo   = $o->ps_no;
   $PSName = $o->ps_name;
   $value  = $PSNo."_".$PSName;
   /* 未被選取才顯示 */
   if(!isset($selected_all[$PSNo]) or !$selected_all[$PSNo]){
      $echo_str .= "<option value=$s_Rid>$value</option>\n";
      $not_selected++;
   }
}
if(!$not_selected) $echo_str .= "<option>- 所有投開票所都已被選取 -</option>\n";
$echo_str .= "</select>\n";
$echo_str .= "</form>\n";
$echo_str .= "</td><td align=center>\n";
$echo_str .= "<input type=button name=b1 value='加入->' onclick=\"document.form_s.submit()\"><br><br><br><br><br>\n";
$echo_str .= "<input type=button name=b2 value='<-移出' onclick=\"document.form_t.submit()\">\n";
$echo_str .= "</td><td>\n";
$echo_str .= "<form name=form_t action=".$_SERVER['PHP_SELF']." method=post>\n";
$echo_str .= "<input type=hidden name=action value=remove>\n";
$echo_str .= "<input type=hidden name=zone_no value=$zone_no>\n";
$echo_str .= "<input type=hidden name=zone_na value=$zone_na>\n";
$echo_str .= "<img src=images/22.gif><font color=green>&nbsp;".$zone_no."_".$zone_na."</font>&nbsp;<img src=images/22.gif><br>";
$echo_str .= "<select name=target[] size=15 multiple style='background:#FFCCCC;color:purple;width=128px'>\n";
/* 若未選取任何項目時 */
if($num_t==0)
   $echo_str .= "<option>- 尚無任何投開票單位 -</option>\n";
   
/* 顯示已選取項目 */
foreach($opt as $k => $v)
   $echo_str .= "<option value=$k>$v</option>\n";
   
$echo_str .= "</select>\n";
$echo_str .= "</form>\n";
$echo_str .= "</td></tr>\n";
$echo_str .= "</table>
<img src=images/22.gif>&nbsp;<font color=red>配合鍵盤上之 'Ctrl' 及 'Shift' 按鍵或直接拖曳可複選</font>";
$echo_str .= "</td></tr>\n";
$echo_str .= "</table>
<input type=button name=b1 value=\"返　　回\" onclick=\"location='set_zone.php?".(session_id()?SID:'')."'\">
</center>";
$echo_str .= foot();

echo $echo_str;

?>