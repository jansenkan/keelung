<?
//-----------------------------------------------------------------------------
//  �@�ε{���X�Ψ禡�w 
//   by jansen since 2004.08
//-----------------------------------------------------------------------------
/* �{������ */
$Version = "2007.05.04 PM20:00";

session_id()?'':session_start();

   /* �� session �O�����{�������|�W(�H�ӷ���Ʈw�W�٬����ѦW) */
   if(!isset($_SESSION['program'])){
      session_register("program");
      $_SESSION['program'] = $DbName;	
   }
   
/* �p�ƾ� */
/* �w�q�O���I�\�ɶ��� session �ܼ� */
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
/*------------------------ �w  �U  ��  ��  ��  �� -----------------------------*/
/* �s�W���ɡA����涵���W�H�ƬO�_�W�L�H�ƤW�� $ItemMax (�ҥ~�W�� $SpecialMax)
   �Y�W�L���W�W���A�h�Ǧ^ true�A�_�h�Ǧ^ false        */
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
   /* ����էO�N�X�O�_���ҥ~�d��(���G�x�s��$IsSpecialRange��)�A�Y�O�h�i���U���P���H�� */

   if ($IsSpecialRange){	/* �ҥ~�d�� */
      if($sub_array[$sub_na]>=$SpecialMax)
         return true;
      else
         return false;
   }else{		/* ���`�d�� */
      if($sub_array[$sub_na]>=$ItemMax)
         return true;
      else
         return false;
   }
}

/* ��s���ɡA����涵���W�H�ƬO�_�W�L�H�ƤW�� $ItemMax (�ҥ~�W�� $SpecialMax)
   �Y�W�L���W�W���A�h�Ǧ^ true�A�_�h�Ǧ^ false          */
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
   /* ����էO�N�X�O�_���ҥ~�d��(���G�x�s��$IsSpecialRange��)�A�Y�O�h�i���U���P���H�� */

   if ($IsSpecialRange){	/* �ҥ~�d�� */
      if($sub_array[$sub_na]>=$SpecialMax)
         return true;
      else
         return false;
   }else{		/* ���`�d�� */
      if($sub_array[$sub_na]>=$ItemMax)
         return true;
      else
         return false;
   }
}

/* �ˬd�P�@�����ɶ��س��W�O�_���Ф����    */
function check_item_repeat($a)
{
   $msg="";

   foreach($a as $k => $v) {
      if(!empty($k) and ($v>1)) $msg .= $k." ";
   }

   return $msg;
}

/* �N���鶵�ؤ��N���ഫ���W��  */
function subteam_id_to_name($subno)
{
   global  $SubTeam_tbl;

   $sql_select="select * from $SubTeam_tbl where SubNo='".$subno."'";
   $result = mysql_query($sql_select);
   $o=mysql_fetch_object($result);

   return $o->SubName;
}

/*   ��������
   �Ѽ� $css  : ���ӵ{�ǩҨϥΤ� css ��  
   �Ѽ� $note : ���ӵ{�ǩҨϥμ��D��²�u���O  
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

/* ��������    */
function foot()
{
   global $Version;
   
   $echo_str = "<br><center><font color=#E0E0E0 size=-1>�{�������G".$Version."</font></center>\n";
   $echo_str .= "</body>\n</html>\n";
   
   return $echo_str;
}

/* ����������I�\�ˬd 
   $click_time:�O���I�\�ɶ��� session �}�C�ܼ�
   $id        :���ޭ�
   �Ǧ^��     :0=>���F���j�з� 1=>�w�F���j�з�   */
function click_check(&$click_time)
{  
   $interval = 300;	/* �j��j��� */
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
   �p�ƾ������禡
   �@�̡G�̤��H(jansen@ms13.url.com.tw)
   �Ϊk�G1.����[�p�ƾ����{�������Y�[�J�p�U�y�k
           <? 
              include ("..\functions.php");
              refresh_hits(); 
           ?>
         2.�p�ƾ���ܳB�[�J�p�U�y�k�G
           <? echo show_hits(5); ?>
------------------------------------------------*/
/* �֥[�@���I���Ʀܭp���� */
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

/* ����I���� ($figures:����) */
function show_hits($figures=0)
{
   $filename = hits_record_file_name();
   $handle   = fopen($filename, "r");
   $hits     = fgets($handle, 1000);
   fclose($handle);
   return sprintf("%0".$figures."d",$hits*1);
}

/* �O���I���H�ƪ��p���ɦW */
function hits_record_file_name()
{
//   $tmp   = explode('.',basename(__FILE__));
   $tmp   = explode('.',basename($_SERVER['SCRIPT_FILENAME']));
   $fname = ($tmp[0]?$tmp[0]:'index').'.hits';
   
   return $fname;
}

//------------------------------------------------------------
//  ���o���u���ඵ�ءv���էO�N�X�ζ����޿�  �åH�}�C�Ǧ^
//------------------------------------------------------------
function get_arou_array(&$ar_grp,&$ar_logic) {
  global $Grp_tbl;
  
  $logic[1] = "SubNo>='51' and SubNo<='55'";	// ��k����
  $logic[2] = "SubNo>='56' and SubNo<='60'";	// ��k����
  $logic[3] = "SubNo>='71' and SubNo<='77'";	// ���B���k�C��
  $logic[4] = "SubNo>='81' and SubNo<='90'";	// ���B���k�Q��
  $logic[5] = "SubNo>='61' and SubNo<='63'";	// �p�k�T��
  $logic[6] = "SubNo>='66' and SubNo<='68'";	// �p�k�T��
  
  $sql_select_s = "select * from sub_sift where SubName='���ඵ��' order by Group_Num";
  $result_s = mysql_query($sql_select_s);
  $tmp = array();
  while($os = mysql_fetch_object($result_s)){
     // Ū�����ඵ�ؤ��N���X�ƥH�M�w�e�ɼƦr���X��
     // 3�X --> �e��1�X�A   4�X --> �e��2�X
     $prefix_len = array('3' => 1, '4' => 2);
     $sql_select = "select * from $Grp_tbl where Group_Name Like '%��%'";
     $result = mysql_query($sql_select);
     $o = mysql_fetch_object($result);
     $grp_num_length = strlen(trim($o->Group_Num));
     //
     $sql_select = "select * from $Grp_tbl where Group_Num='".$os->Group_Num."'";
     $result = mysql_query($sql_select);
     $o = mysql_fetch_object($result);
     // �էO�W��
     $grp_na = $o->Group_Name;
     // �էO�N�X���e�ɼƦr
     $grp_prefix = substr($o->Group_Num,0,$prefix_len[$grp_num_length]);
     // �]�w�}�C��
     if(stristr($grp_na,"��k") or stristr($grp_na,"�ꤤ�k")){
        $ar_grp[$o->Group_Num]   = $grp_prefix."05";
        $ar_logic[$o->Group_Num] = $logic[1];
     }elseif(stristr($grp_na,"��k") or stristr($grp_na,"�ꤤ�k")){
        $ar_grp[$o->Group_Num]   = $grp_prefix."05";
        $ar_logic[$o->Group_Num] = $logic[2];
     }elseif(stristr($grp_na,"���k") or stristr($grp_na,"�����k")){
        $ar_grp[$o->Group_Num]   = $grp_prefix."07";
        $ar_logic[$o->Group_Num] = $logic[3];
     }elseif(stristr($grp_na,"���k") or stristr($grp_na,"�����k")){
        $ar_grp[$o->Group_Num]   = $grp_prefix."10";
        $ar_logic[$o->Group_Num] = $logic[4];
     }elseif(stristr($grp_na,"���k") or stristr($grp_na,"���|�k")){
        $ar_grp[$o->Group_Num]   = $grp_prefix."07";
        $ar_logic[$o->Group_Num] = $logic[3];
     }elseif(stristr($grp_na,"���k") or stristr($grp_na,"���|�k")){
        $ar_grp[$o->Group_Num]   = $grp_prefix."10";
        $ar_logic[$o->Group_Num] = $logic[4];
     }elseif(stristr($grp_na,"�p�k") or stristr($grp_na,"��p�k")){
        $ar_grp[$o->Group_Num]   = $grp_prefix."03";
        $ar_logic[$o->Group_Num] = $logic[5];
     }elseif(stristr($grp_na,"�p�k") or stristr($grp_na,"��p�k")){
        $ar_grp[$o->Group_Num]   = $grp_prefix."03";
        $ar_logic[$o->Group_Num] = $logic[6];
     }
  }
  return;
}

//------------------------------------------------------------
//  �ѽs�����o�J��H�m�W
//------------------------------------------------------------
function get_cand_name($sno) {
  global $Candidate_tbl;
  
  $sql_select_ca = "select * from $Candidate_tbl where sno='$sno'";
  $result_ca = mysql_query($sql_select_ca);
  $oc = mysql_fetch_object($result_ca);
  
  return stripslashes($oc->name);
}

//------------------------------------------------------------
//  �ѽs�����o���ϦW��
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
//  ���o�ثe�����ϲέp���
//------------------------------------------------------------
function get_count_all(&$count) {
  global $Vote_tbl;
  
  $count['voters']       = 0; // �`���|�H��
  $count['valid']        = 0; // �`�벼��
  $count['invalid']      = 0; // �`�L�Ĳ���
  $count['voter_in']     = 0; // �w�}�X����(�t���벼)�έp
  $count['voter_not_in'] = 0; // ���}�X���Ʋέp
  $count['ps_in']        = 0; // �w�}�X�벼�Ҳέp
  $count['ps_not_in']    = 0; // ���}�X�벼�Ҳέp
  $count['percentage']   = '0.0'; // �`�벼�v�έp
  
   $sql_select="select * from $Vote_tbl order by ps_no";
   $result_vt=mysql_query($sql_select);
   // �έp
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
   // �ʤ��v���B�z
   if($count['voter_in'])
      $count['percentage'] = sprintf("%4.1f",(($count['valid']+$count['invalid'])*100) / $count['voter_in']);
      
  return;
}

/* ������(text)��J�� java script */
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
   if (confirm('�z�w�g����ƬO�_�n���} ?'))
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
//		alert('���~������!');
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