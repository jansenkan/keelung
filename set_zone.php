<?
/* session_name
$orgid       	=> ���N�X
$orgname       	=> ���W��
$username       => �n�J�b��
$password       => �n�J�K�X
$admintitle     => �޲z��¾��
$adminname      => �޲z�̩m�W
*/
//include("chkuser.inc.php");
include("config.inc.php");
session_id()?'':session_start();
/* �D�t�κ޲z�̡A�Ч�D�I */
if($_SESSION['root']<>"sys_player"){
   header("location:index.php");
   exit;
}

$action  = isset($_GET['action'])  ?$_GET['action']  :'';
$action  = isset($_POST['action']) ?$_POST['action'] :$action;
$confirm = isset($_GET['confirm']) ?$_GET['confirm'] :'';
$confirm = isset($_POST['confirm'])?$_POST['confirm']:$confirm;
$rid     = isset($_GET['rid'])     ?$_GET['rid']     :'';
$rid     = isset($_POST['rid'])    ?$_POST['rid']    :$rid;
$cnt     = isset($_GET['cnt'])     ?$_GET['cnt']     :'';
$cnt     = isset($_POST['cnt'])    ?$_POST['cnt']    :$cnt;

/* ���Y */
$echo_str = "<html>\n<meta http-equiv=\"Content-Type\" content=\"text/html; Charset=Big5\">\n";
$echo_str .= "<title>".$Title."</title>";
$echo_str .= "<Link Rel='stylesheet' Type='text/css' Href='style_c.css'>\n";
$echo_str .= '
   <script language="JavaScript">
   <!--
   function setBG(TheColor,TheObject) {TheObject.bgColor=TheColor}
    
   function CheckAnother()
   {
      for (var i=0;i<document.set_form.elements.length;i++)
      {
         var e = document.set_form.elements[i];
         if (e.name != "allbox")
               e.checked = !e.checked;
      }
   }
    
   function CheckAll(no)
   {
      if(no==1)
         var start = 14;
      else      
         var start = 16;
      for (var i=start;i<document.set_form.elements.length;i+=8)
      {
         var e = document.set_form.elements[i];
         if ((e.name != "allbox")
           &&(e.name != "zno_up")
           &&(e.name != "zna_up")
           &&(e.name != "zuser_up")
           &&(e.name != "zpass_up")
           &&(e.name != "zip_up"))
         {
            if(no==1)
               var condi = document.set_form.allbox.checked;
            else      
               var condi = document.set_form.allbox2.checked;
            if(condi)
               e.checked = true;
            else
               e.checked = false;
         }
      }
   }
   //-->
   </script>
    <center>';
   
/* �s�W�@�s���� */
if($action=="insert"){
   $sqlstr="Insert into ".$Zone_tbl." (ZoneNum) values ('')";
   mysql_query($sqlstr);
}

/* �R���Y�@���� */
if($action=="delete"){
   if($confirm=="yes"){
      $sqlstr="Delete from ".$Zone_tbl." where Rid='$rid'";
      mysql_query($sqlstr);
   }else{
      $sqlstr="Select * from ".$Zone_tbl." where Rid='$rid'";
      $result=mysql_query($sqlstr);
      $o=mysql_fetch_object($result);
      $echo_str .= "<table style=\"border-collapse: collapse;border:2px dashed\" bordercolor=\"#FF0000\" border=1 bgcolor=yellow>";
      $echo_str .= "<tr><td align=center><br>\n";
      $echo_str .= "<font color=red size=7><b>�M�Iĵ�i�I</b></font><br><br>\n";
      $echo_str .= "<center><font color=green size=5><b>�� �z�T�w�n�R���u".$o->ZoneName."�v�����ϸ�ƶܡH ��</b></font><br><br>\n";
      $echo_str .= "<font color=red>���@���R���A�h��ƱN�L�k���^�I</font><br><br>\n";
      $echo_str .= "<input type=\"button\" value=\"�ګܽT�w\" name=\"B1\" onclick=\"location='".$_SERVER['PHP_SELF']."?action=delete&confirm=yes&rid=".$rid."&".(session_id()?SID:'')."'\">&nbsp;&nbsp;&nbsp;&nbsp;";
      $echo_str .= "<input type=\"button\" value=\"���@�@��\" name=\"B1\" onclick=\"location='javascript:window.history.back()'\"><br></center>";
      $echo_str .= "<br><br>\n";
      $echo_str .= "</td></tr></table>\n";
      echo $echo_str;
      exit;
   }
}

/* �M�ũҦ��էO */
if($action=="delete_all"){
   if($confirm=="yes"){
      $sqlstr="Delete from ".$Zone_tbl;
      mysql_query($sqlstr);
   }else{
      $echo_str .= "<table style=\"border-collapse: collapse;border:2px dashed\" bordercolor=\"#FF0000\" border=1 bgcolor=yellow>";
      $echo_str .= "<tr><td align=center><br>\n";
      $echo_str .= "<font color=red size=7><b>�M�Iĵ�i�I</b></font><br><br>\n";
      $echo_str .= "<center><font color=green size=5><b>�� �z�T�w�n�R���Ҧ����ϸ�ƶܡH ��</b></font><br><br>\n";
      $echo_str .= "<font color=red>���@���R���A�h��ƱN�L�k���^�I</font><br><br>\n";
      $echo_str .= "<input type=\"button\" value=\"�ګܽT�w\" name=\"B1\" onclick=\"location='".$_SERVER['PHP_SELF']."?action=delete_all&confirm=yes&".(session_id()?SID:'')."'\">&nbsp;&nbsp;&nbsp;&nbsp;";
      $echo_str .= "<input type=\"button\" value=\"���@�@��\" name=\"B1\" onclick=\"location='javascript:window.history.back()'\"><br></center>";
      $echo_str .= "<br><br>\n";
      $echo_str .= "</td></tr></table>\n";
      echo $echo_str;
      exit;
   }
}

if(!isset($action) or ($action<>"update")){
   $bg_color1="#55CC55";
   $bg_color2="#0000FF";
   $bg_color3="#FFFFFF";
   $bg_color4="orange";
   $fnt_color1="#FFFFFF";
   $fnt_color2="red";
   $fnt_color3="yellow";
   
   $echo_str .= '
   <form name="set_form" method="POST" action="'.$_SERVER['PHP_SELF'].'" onsubmit="document.set_form.S1.disabled = true;">
   <font color=blue size=4>'.$Title.'���Ϫ�</font>'.($root==1?"�m<font color=darkpink size=2>�޲z</font>�n":"").'
    <input type="hidden" name="action" value="update">
    <table border=1 cellspacing=0 cellpadding=2 style="border-collapse: collapse" bordercolor=gray>
    <tr bgcolor=#009300 align=center>
    <td><font color="'.$fnt_color1.'">��</font></td>
    <td><font color="'.$fnt_color1.'">���ϥN��<br><input name="zno_up" type="checkbox" value="ON"><font size=-1 color="#FF8040">���\��s</font></font></td>
    <td><font color="'.$fnt_color1.'">���ϦW��<br><input name="zna_up" type="checkbox" value="ON"><font size=-1 color="#FF8040">���\��s</font></font></td>
    <td><font color="'.$fnt_color1.'">�޲z�b��<br><input name="zuser_up" type="checkbox" value="ON"><font size=-1 color="#FF8040">���\��s</font></font></td>
    <td><font color="'.$fnt_color1.'">�޲z�K�X<br><input name="zpass_up" type="checkbox" value="ON"><font size=-1 color="#FF8040">���\��s</font></font></td>
    <td><font color="'.$fnt_color1.'">IP���w<br><input name="zip_up" type="checkbox" value="ON"><font size=-1 color="#FF8040">���\��s</font></font></td>
    <td><font color="'.$fnt_color1.'" size=-1>��}���ҲM��</font></td>
    <td><font color="'.$fnt_color1.'"><font size=-1>�O�_��w</font><br><input name="allbox" type="checkbox" onClick="CheckAll(1);">����</font></td>
    <td><a href='.$_SERVER['PHP_SELF'].'?action=delete_all><img src=images/dele_all.gif border=0 title=�M�ũҦ����ϸ��></a><br><font color="'.$fnt_color1.'">'.$Zone_tbl.'</font></td>
    </tr>';

 $sqlstr="select * from ".$Zone_tbl." order by ZoneNum*1";
 $sqlqry=mysql_query($sqlstr);
 $i=0;
 while($o=mysql_fetch_object($sqlqry)){
   $rid  = $o->Rid;
   $z_no = $o->ZoneNum;
   if(($i % 2)==1) $bg_color="#A0FFA0"; else $bg_color="#E0FFE0";
   $echo_str .= "<tr bgcolor=$bg_color onMouseOver=setBG('#FFFF00',this) onMouseout=setBG('".$bg_color."',this)>";
   $echo_str .= "<td align=center>".($i+1).".</td>\n";
   $echo_str .= "<input type=hidden name=rid[".$i."] value=".$o->Rid.">\n";
   $echo_str .= "<td align=center><input type=text STYLE='border-width:0px;COLOR:brown;background-color:#F0FFE0' name=zno[".$i."] size=4 maxlength=4 value=".$o->ZoneNum."></td>\n";
   $echo_str .= "<td align=center><input type=text STYLE='border-width:0px;COLOR:brown;background-color:#F0FFE0' name=zna[".$i."] size=10 maxlength=10 value=".$o->ZoneName."></td>\n";
   $echo_str .= "<td align=center><input type=text STYLE='border-width:0px;COLOR:brown;background-color:#F0FFE0' name=zuser[".$i."] size=10 maxlength=10 value=".$o->ZoneUser."></td>\n";
   $echo_str .= "<td align=center><input type=password STYLE='border-width:0px;COLOR:brown;background-color:#F0FFE0' name=zpass[".$i."] size=10 value=".$o->ZonePass."></td>\n";
   $echo_str .= "<td align=center><input type=text STYLE='border-width:0px;COLOR:brown;background-color:#F0FFE0' name=zip[".$i."] size=11 maxlength=15 value=".$o->ZoneIP."></td>\n";
/*
   $echo_str .= "<td align=center><input type=text STYLE='COLOR:brown;background-color:#F0FFE0' name=org_dn_to1[".$i."] size=5 maxlength=5 value=".$o->OrgDownTo1."></td>\n";
   $echo_str .= "<td align=center><input type=text STYLE='COLOR:brown;background-color:#F0FFE0' name=org_up_to1[".$i."] size=5 maxlength=5 value=".$o->OrgUpTo1."></td>\n";
   $echo_str .= "<td align=center><input type=text STYLE='COLOR:brown;background-color:#F0FFE0' name=org_dn_to2[".$i."] size=5 maxlength=5 value=".$o->OrgDownTo2."></td>\n";
   $echo_str .= "<td align=center><input type=text STYLE='COLOR:brown;background-color:#F0FFE0' name=org_up_to2[".$i."] size=5 maxlength=5 value=".$o->OrgUpTo2."></td>\n";
   $echo_str .= "<td align=center>".select_option("org_dn_to1[".$i."]",$o->OrgDownTo1)."</td>\n";
   $echo_str .= "<td align=center>".select_option("org_up_to1[".$i."]",$o->OrgUpTo1)."</td>\n";
   $echo_str .= "<td align=center>".select_option("org_dn_to2[".$i."]",$o->OrgDownTo2)."</td>\n";
   $echo_str .= "<td align=center>".select_option("org_up_to2[".$i."]",$o->OrgUpTo2)."</td>\n";
*/
   $echo_str .= "<td><a href=ps_manage.php?zone_no=$z_no&zone_na=".$o->ZoneName."&".(session_id()?SID:'')." title=\"�s��<".$o->ZoneName.">���ϧ�}����\">
                 <img src=images/edit.png border=0></a>&nbsp;".select_option_ps($z_no,"reference")."</td>\n";
   $echo_str .= "<td align=center><input type=checkbox name=_lock[".$i."] value=ON ".(($o->_lock=='1')?'checked':'')."></td>\n";
   $echo_str .= "<td align=center><a href=".$_SERVER['PHP_SELF']."?action=delete&rid=".$rid."&".(session_id()?SID:'')." title=�R��".$o->ZoneNum."_".$o->ZoneName."><img src=images/delete.gif border=0></a></td>\n";
   $echo_str .= "</tr>";
   $i++;
 }
   $echo_str .= '
   <tr>
   <td colspan=13 bgcolor=#FFFFB4><font size=-1>
   �����G1.�Y����s��ƮɡA�ȥ��Ŀ�W�褧�u<font color="#FF8040">���\��s</font>�vCheckBox<br />
   </font>
   </td>
   </tr>
   </table>
    <input type=hidden name=cnt value='.$i.'>
    <input type="submit" name="S1" value="�x�s�]�w">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="button" name="b2" value="�s�W�@�s����" onclick="location=\''.$_SERVER['PHP_SELF'].'?action=insert&&'.(session_id()?SID:'').'\';this.disabled = true;">&nbsp;&nbsp;&nbsp;
   </form>';
   

$echo_str .= "</body>
</html>";

echo $echo_str;
//$n1=trim($n1);
}else{
   
   for($i=0;$i < $cnt;$i++){
      $id       = isset($_POST['rid'][$i])?$_POST['rid'][$i]:'';
      $zoneno   = isset($_POST['zno'][$i])?$_POST['zno'][$i]:'';
      $zonena   = addslashes(isset($_POST['zna'][$i])?$_POST['zna'][$i]:'');
      $zoneuser = isset($_POST['zuser'][$i])?$_POST['zuser'][$i]:'';
      $zonepass = isset($_POST['zpass'][$i])?$_POST['zpass'][$i]:'';
      $zoneip   = isset($_POST['zip'][$i])?$_POST['zip'][$i]:'';
      $lck      = (isset($_POST['_lock'][$i]) and ($_POST['_lock'][$i]=='ON'))?"1":"";
//      echo 'ply='.$ply."<br>";
      /* �P�_��s���� */
      $sqlstr = "Update ".$Zone_tbl." set _lock='$lck'";
      if(isset($_POST['zno_up']) and ($_POST['zno_up']=="ON"))   $sqlstr .= ",ZoneNum='$zoneno'";
      if(isset($_POST['zna_up']) and ($_POST['zna_up']=="ON"))   $sqlstr .= ",ZoneName='$zonena'";
      if(isset($_POST['zuser_up']) and ($_POST['zuser_up']=="ON")) $sqlstr .= ",ZoneUser='$zoneuser'";
      if(isset($_POST['zpass_up']) and ($_POST['zpass_up']=="ON")) $sqlstr .= ",ZonePass='$zonepass'";
      if(isset($_POST['zip_up']) and ($_POST['zip_up']=="ON"))   $sqlstr .= ",ZoneIP='$zoneip'";
      $sqlstr .= " where Rid='$id'";
//      echo $sqlstr;exit;
//      $sqlstr="Update ".$Sub_tbl." set play='$ply' where Rid='$id'";
      $sqlqry=mysql_query($sqlstr);
      }
   header("location:".$_SERVER['PHP_SELF']."?".(session_id()?SID:''));

}

/* ��ܳ��βէO��ƪ���� 
   $sel_name : �U�Ԧ����W��
   $prefix   : ����蠟��ƪ�r��
   $default  : �w�]����ƪ�W��
*/
function select_opts($sel_name,$prefix,$default)
{
   global $DbName;
   
   $sel_str = "<select size=1 STYLE='FONT-SIZE:13px;COLOR:#930000;background-color:F0FFDD;' name=$sel_name>\n";
   $result = mysql_list_tables ($DbName); 
   $i = 0; 
   while ($i < mysql_num_rows ($result)) { 
      $tb_name = mysql_tablename ($result, $i); 
      $tbn = substr($tb_name,0,strlen($prefix));
      if($tbn==$prefix)
         $sel_str .= '<option '.(($default==($tb_name))?"selected ":"").'value="'.$tb_name.'">'.$tb_name.'</option>'."\n";
      $i++;
   }
   $sel_str .= "</select>\n";
   
   return $sel_str;
}

/* ������� */
function select_option($name,$default=0)
{
   global   $Org_tbl;

   $script = "<select name=".$name." STYLE='COLOR:brown;background-color:F0FFDD;font-size:12px'>\n";
   $script .= "<option value=''>-----</option>\n";
   $sql_select="select * from $Org_tbl order by OrgId*1";
   $result = mysql_query($sql_select);
   while($o=mysql_fetch_object($result)){
       $orgid = $o->OrgId;
       $orgna = stripslashes($o->OrgName);
              
       $script .= "<option value=".$orgid.(($orgid==$default)?' selected':'').">".$orgid.$orgna."</option>\n";
   }
   $script .= "</select>\n";
   
   return $script;
}

/* ������� */
function select_option_ps($zone_no,$name,$default=0)
{
   global   $P_S_sift_tbl;

   $script = "<select name=".$name." STYLE='COLOR:brown;background-color:F0FFDD;font-size:12px'>\n";
//   $script .= "<option value=''>-----</option>\n";
   $sql_select="select * from $P_S_sift_tbl where ZoneNum='$zone_no' order by ps_no*1";
   $result = mysql_query($sql_select);
   while($o=mysql_fetch_object($result)){
       $psid = $o->ps_no;
       $psna = stripslashes($o->ps_name);
              
       $script .= "<option value=".$psid.(($psid==$default)?' selected':'').">".$psid.".".$psna."</option>\n";
   }
   $script .= "</select>\n";
   
   return $script;
}

/* ���ɶ��ؿ���� */
function select_option_sub($grp_no,$name,$default=0)
{
   global   $Sub_sift_tbl;

   $script = "<select name=".$name." STYLE='COLOR:brown;background-color:F0FFDD;font-size:12px'>\n";
//   $script .= "<option value=''>-----</option>\n";
   $sql_select="select * from $Sub_sift_tbl where Group_Num='$grp_no' order by SubNo*1";
   $result = mysql_query($sql_select);
   while($o=mysql_fetch_object($result)){
       $SubNo    = $o->SubNo;
       $SubName  = stripslashes($o->SubName);
              
       $script .= "<option value=".$SubNo.(($SubNo==$default)?' selected':'').">".$SubNo.".".$SubName."</option>\n";
   }
   $script .= "</select>\n";
   
   return $script;
}
?>