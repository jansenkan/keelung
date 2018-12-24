<?
//-----------------------------------------------------------------------------
//  共用程式碼及函式庫 
//   by jansen since 2004.08
//-----------------------------------------------------------------------------
/* 程式版本 */
$Version = "2007.05.04 PM20:00";

session_id()?'':session_start();

   /* 用 session 記錄本程式的路徑名(以來源資料庫名稱為辨識名) */
   if(!isset($_SESSION['program'])){
      session_register("program");
      $_SESSION['program'] = $DbName;	
   }
   
/* 計數器 */
/* 定義記錄點閱時間的 session 變數 */
/*
if(!isset($_SESSION['click_time'])){ session_register('click_time');}
$Hits = $o->hits;
if(click_check($_SESSION['click_time'])){
   $Hits++;
   $sql_update = "update config set hits='".$Hits."'";
   mysql_query($sql_update);
}
*/
//
/*------------------------ 已  下  為  函  式  區 -----------------------------*/
/* 新增選手時，檢驗單項報名人數是否超過人數上限 $ItemMax (例外上限 $SpecialMax)
   若超過報名上限，則傳回 true，否則傳回 false        */
function is_over_players_ins($sub_na)
{
   global $Cus_tbl,$orgname,$grp_name,$grp_num;
   global $ItemMax,$SpecialMax;
   global $Compar1,$Group1,$Logic1,$Compar2,$Group2,$IsSpecialRange;

   unset($sub_array);
   $sqlstr="select * from $Cus_tbl where TeamNa LIKE '".$_SESSION['orgname']."%' and Group_Name='$grp_name' order by Rid";
   $sqlqry=mysql_query($sqlstr);
   
   while($o=mysql_fetch_object($sqlqry)){
      if(!empty($o->SubName)) $sub_array[$o->SubName]++;
      if(!empty($o->SubName1)) $sub_array[$o->SubName1]++;
      if(!empty($o->SubName2)) $sub_array[$o->SubName2]++;
      if(!empty($o->SubName3)) $sub_array[$o->SubName3]++;
      if(!empty($o->SubName4)) $sub_array[$o->SubName4]++;
   }
   /* 檢驗組別代碼是否位於例外範圍內(結果儲存於$IsSpecialRange內)，若是則可註冊不同之人數 */

   if ($IsSpecialRange){	/* 例外範圍 */
      if($sub_array[$sub_na]>=$SpecialMax)
         return true;
      else
         return false;
   }else{		/* 正常範圍 */
      if($sub_array[$sub_na]>=$ItemMax)
         return true;
      else
         return false;
   }
}

/* 更新選手時，檢驗單項報名人數是否超過人數上限 $ItemMax (例外上限 $SpecialMax)
   若超過報名上限，則傳回 true，否則傳回 false          */
function is_over_players_upd($sub_na)
{
   global $Cus_tbl,$orgname,$grp_name,$grp_num,$id;
   global $ItemMax,$SpecialMax;
   global $Compar1,$Group1,$Logic1,$Compar2,$Group2,$IsSpecialRange;

   unset($sub_array);
   $sqlstr="select * from $Cus_tbl where TeamNa LIKE '".$_SESSION['orgname']."%' and Group_Name='$grp_name' and Rid<>'$id' order by Rid";
   $sqlqry=mysql_query($sqlstr);
   
   while($o=mysql_fetch_object($sqlqry)){
      if(!empty($o->SubName)) $sub_array[$o->SubName]++;
      if(!empty($o->SubName1)) $sub_array[$o->SubName1]++;
      if(!empty($o->SubName2)) $sub_array[$o->SubName2]++;
      if(!empty($o->SubName3)) $sub_array[$o->SubName3]++;
      if(!empty($o->SubName4)) $sub_array[$o->SubName4]++;
   }
   /* 檢驗組別代碼是否位於例外範圍內(結果儲存於$IsSpecialRange內)，若是則可註冊不同之人數 */

   if ($IsSpecialRange){	/* 例外範圍 */
      if($sub_array[$sub_na]>=$SpecialMax)
         return true;
      else
         return false;
   }else{		/* 正常範圍 */
      if($sub_array[$sub_na]>=$ItemMax)
         return true;
      else
         return false;
   }
}

/* 檢查同一選手參賽項目報名是否重覆之函數    */
function check_item_repeat($a)
{
   $msg="";

   foreach($a as $k => $v) {
      if(!empty($k) and ($v>1)) $msg .= $k." ";
   }

   return $msg;
}

/* 將團體項目之代號轉換為名稱  */
function subteam_id_to_name($subno)
{
   global  $SubTeam_tbl;

   $sql_select="select * from $SubTeam_tbl where SubNo='".$subno."'";
   $result = mysql_query($sql_select);
   $o=mysql_fetch_object($result);

   return $o->SubName;
}

/*   網頁頁首
   參數 $css  : 為該程序所使用之 css 檔  
   參數 $note : 為該程序所使用標題之簡短註記  
*/
function head($css='style_c.css',$note='')
{
   global $Title;
   
   $echo_str  = "<html>\n";
   $echo_str .= "<head>\n";
   $echo_str .= "<meta http-equiv=\"Content-Type\" content=\"text/html; Charset=Big5\">\n";
   $echo_str .= "<title>".$Title."-".$note."</title>";
   $echo_str .= "<Link Rel='stylesheet' Type='text/css' Href='".$css."'>\n";
   /*
   $echo_str .= "<style type=\"text/css\">
   body {  background-image: url('images/tcctfmark.jpg'); background-repeat: no-repeat; background-position: right bottom; background-attachment: fixed;}
</style>\n";
*/
   $echo_str .= "</head>\n";
   $echo_str .= "<body>\n";
   
   return $echo_str;
}

/* 網頁頁尾    */
function foot()
{
   global $Version;
   
   $echo_str = "<br><center><font color=#E0E0E0 size=-1>程式版本：".$Version."</font></center>\n";
   $echo_str .= "</body>\n</html>\n";
   
   return $echo_str;
}

/* 防止灌水之點閱檢查 
   $click_time:記錄點閱時間的 session 陣列變數
   $id        :索引值
   傳回值     :0=>未達間隔標準 1=>已達間隔標準   */
function click_check(&$click_time)
{  
   $interval = 300;	/* 強制間隔秒數 */
   $now   = time();
   if($click_time==''){
      $click_time = $now;
      return 1;
   }else{
      $interv = $now - $click_time;
      if ($interv > $interval){
         $click_time = $now;
         return 1;
      }
   }
   return 0;
}

/*------------------------------------------------
   計數器相關函式
   作者：甘之信(jansen@ms13.url.com.tw)
   用法：1.於欲加計數器之程序檔檔頭加入如下語法
           <? 
              include ("..\functions.php");
              refresh_hits(); 
           ?>
         2.計數器顯示處加入如下語法：
           <? echo show_hits(5); ?>
------------------------------------------------*/
/* 累加一次點擊數至計數檔 */
function refresh_hits()
{
   $filename = hits_record_file_name();
   $handle   = @fopen($filename, "r");
   $hits     = @fgets($handle, 1000)*1;
   @fclose($handle);
   $hits++;
   $handle   = fopen($filename, "w");
   fwrite($handle, $hits);
   fclose($handle);
   return;
}

/* 顯示點擊數 ($figures:表位數) */
function show_hits($figures=0)
{
   $filename = hits_record_file_name();
   $handle   = fopen($filename, "r");
   $hits     = fgets($handle, 1000);
   fclose($handle);
   return sprintf("%0".$figures."d",$hits*1);
}

/* 記錄點擊人數的計數檔名 */
function hits_record_file_name()
{
//   $tmp   = explode('.',basename(__FILE__));
   $tmp   = explode('.',basename($_SERVER['SCRIPT_FILENAME']));
   $fname = ($tmp[0]?$tmp[0]:'index').'.hits';
   
   return $fname;
}

//------------------------------------------------------------
//  取得有「全能項目」之組別代碼及項目邏輯  並以陣列傳回
//------------------------------------------------------------
function get_arou_array(&$ar_grp,&$ar_logic) {
  global $Grp_tbl;
  
  $logic[1] = "SubNo>='51' and SubNo<='55'";	// 國女五項
  $logic[2] = "SubNo>='56' and SubNo<='60'";	// 國男五項
  $logic[3] = "SubNo>='71' and SubNo<='77'";	// 高、社女七項
  $logic[4] = "SubNo>='81' and SubNo<='90'";	// 高、社男十項
  $logic[5] = "SubNo>='61' and SubNo<='63'";	// 小女三項
  $logic[6] = "SubNo>='66' and SubNo<='68'";	// 小男三項
  
  $sql_select_s = "select * from sub_sift where SubName='全能項目' order by Group_Num";
  $result_s = mysql_query($sql_select_s);
  $tmp = array();
  while($os = mysql_fetch_object($result_s)){
     // 讀取全能項目之代號碼數以決定前導數字之碼數
     // 3碼 --> 前導1碼，   4碼 --> 前導2碼
     $prefix_len = array('3' => 1, '4' => 2);
     $sql_select = "select * from $Grp_tbl where Group_Name Like '%項%'";
     $result = mysql_query($sql_select);
     $o = mysql_fetch_object($result);
     $grp_num_length = strlen(trim($o->Group_Num));
     //
     $sql_select = "select * from $Grp_tbl where Group_Num='".$os->Group_Num."'";
     $result = mysql_query($sql_select);
     $o = mysql_fetch_object($result);
     // 組別名稱
     $grp_na = $o->Group_Name;
     // 組別代碼之前導數字
     $grp_prefix = substr($o->Group_Num,0,$prefix_len[$grp_num_length]);
     // 設定陣列值
     if(stristr($grp_na,"國女") or stristr($grp_na,"國中女")){
        $ar_grp[$o->Group_Num]   = $grp_prefix."05";
        $ar_logic[$o->Group_Num] = $logic[1];
     }elseif(stristr($grp_na,"國男") or stristr($grp_na,"國中男")){
        $ar_grp[$o->Group_Num]   = $grp_prefix."05";
        $ar_logic[$o->Group_Num] = $logic[2];
     }elseif(stristr($grp_na,"高女") or stristr($grp_na,"高中女")){
        $ar_grp[$o->Group_Num]   = $grp_prefix."07";
        $ar_logic[$o->Group_Num] = $logic[3];
     }elseif(stristr($grp_na,"高男") or stristr($grp_na,"高中男")){
        $ar_grp[$o->Group_Num]   = $grp_prefix."10";
        $ar_logic[$o->Group_Num] = $logic[4];
     }elseif(stristr($grp_na,"社女") or stristr($grp_na,"社會女")){
        $ar_grp[$o->Group_Num]   = $grp_prefix."07";
        $ar_logic[$o->Group_Num] = $logic[3];
     }elseif(stristr($grp_na,"社男") or stristr($grp_na,"社會男")){
        $ar_grp[$o->Group_Num]   = $grp_prefix."10";
        $ar_logic[$o->Group_Num] = $logic[4];
     }elseif(stristr($grp_na,"小女") or stristr($grp_na,"國小女")){
        $ar_grp[$o->Group_Num]   = $grp_prefix."03";
        $ar_logic[$o->Group_Num] = $logic[5];
     }elseif(stristr($grp_na,"小男") or stristr($grp_na,"國小男")){
        $ar_grp[$o->Group_Num]   = $grp_prefix."03";
        $ar_logic[$o->Group_Num] = $logic[6];
     }
  }
  return;
}

//------------------------------------------------------------
//  由編號取得侯選人姓名
//------------------------------------------------------------
function get_cand_name($sno) {
  global $Candidate_tbl;
  
  $sql_select_ca = "select * from $Candidate_tbl where sno='$sno'";
  $result_ca = mysql_query($sql_select_ca);
  $oc = mysql_fetch_object($result_ca);
  
  return stripslashes($oc->name);
}

//------------------------------------------------------------
//  由編號取得分區名稱
//------------------------------------------------------------
function get_zone_name_from_ps_no($ps_no) {
  global $Zone_tbl,$P_S_sift_tbl;
  
  $sql_select_ps = "select * from $P_S_sift_tbl where ps_no='$ps_no'";
  $result_ps = mysql_query($sql_select_ps);
  $op = mysql_fetch_object($result_ps);
  $zno = $op->ZoneNum;
  
  $sql_select_z = "select * from $Zone_tbl where ZoneNum='$zno'";
  $result_z = mysql_query($sql_select_z);
  $oz = mysql_fetch_object($result_z);
  
  
  return stripslashes($oz->ZoneName);
}

//------------------------------------------------------------
//  取得目前的全區統計資料
//------------------------------------------------------------
function get_count_all(&$count) {
  global $Vote_tbl;
  
  $count['voters']       = 0; // 總選舉人數
  $count['valid']        = 0; // 總投票數
  $count['invalid']      = 0; // 總無效票數
  $count['voter_in']     = 0; // 已開出票數(含未投票)統計
  $count['voter_not_in'] = 0; // 未開出票數統計
  $count['ps_in']        = 0; // 已開出投票所統計
  $count['ps_not_in']    = 0; // 未開出投票所統計
  $count['percentage']   = '0.0'; // 總投票率統計
  
   $sql_select="select * from $Vote_tbl order by ps_no";
   $result_vt=mysql_query($sql_select);
   // 統計
   while ($vt=mysql_fetch_object($result_vt)){
      $count['valid']   += $vt->total*1;
      $count['invalid'] += $vt->invalid*1;
      $count['voters']  += $vt->voters*1;
      if($vt->total*1 > 0){
      	 $count['ps_in']++;
         $count['voter_in']  += $vt->voters*1;
      }else{
         $count['ps_not_in']++;
         $count['voter_not_in']  += $vt->voters*1;
      }
   }
   // 百分率的處理
   if($count['voter_in'])
      $count['percentage'] = sprintf("%4.1f",(($count['valid']+$count['invalid'])*100) / $count['voter_in']);
      
  return;
}

/* 控制表單(text)輸入的 java script */
function java_script1($step)
{
   $script = "
<script >
var ss=0;
var is_change = false;
function set_default(first_element){
   ss=first_element;
   document.myform.elements[ss].focus();
}

function check_change(){
if(is_change){
   if (confirm('您已經更改資料是否要離開 ?'))
	window.close();
}else
   window.close();
}

function set_ower(thetext,ower) {
   ss=ower;
   thetext.style.background = '#FFBBFF';
   //thetext.select();
   return true;
}

function unset_ower(thetext) {
   thetext.style.background = '#FFFFFF';
   return true;
}

function reset_all() {
   for (var i=0;i<document.myform.elements.length;i++)
   {
	var e = document.myform.elements[i];
	if (e.type == 'text')
        	   e.value = '';
   }
   document.myform.elements[0].focus();
}

// handle keyboard events
if (navigator.appName == \"Mozilla\")
   document.addEventListener(\"keyup\",keypress,true);
else if (navigator.appName == \"Netscape\")
   document.captureEvents(Event.KEYPRESS);

if (navigator.appName != \"Mozilla\")
   document.onkeypress=keypress;

function keypress(e) {
	
   if (navigator.appName == \"Microsoft Internet Explorer\")
        tmp = window.event.keyCode;
   else if (navigator.appName == \"Navigator\")
	tmp = e.which;
   else if (navigator.appName == \"Navigator\"||navigator.appName == \"Netscape\")
        tmp = e.keyCode;
        
   if( document.myform.elements[ss].type != 'text'){
	return true;
   }else if (tmp == 13){ 
//	var tt = parseFloat(document.myform.elements[ss].value);
		
//	if (isNaN(tt) || tt >100 || tt < 0 ){			
//		alert('錯誤的分數!');
//		document.myform.elements[ss].value ='';
//		return false;
//	}else {			
		ss+=".$step.";
		document.myform.elements[ss].focus();
		is_change = true;
		return true;
//	}
   }else{    return true;}
}
</script>";

   return $script;
}

?>