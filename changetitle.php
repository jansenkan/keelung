<?
//---------------------------------------------
//    �����ɼ��D�Ψt�ΰѼ� changetitle.php
//---------------------------------------------
//include("chkuser.inc.php");
include("config.inc.php");
/* ���C�J��椧���~��� */
//$except_org = array("org_sift","org_list");
//$org_table = ($System=='ON')?$Org_tbl:$_POST['org_table'];
//$grp_table = ($System=='ON')?$Grp_tbl:$_POST['grp_table'];

// �����ɼ��D���
if (!isset($_POST['action'])){
   $echo_str = head();
   $echo_str .="
<center>
<form action=".$_SERVER['PHP_SELF']." method=post>
<font color=blue size=4>".$Title."</font>
<input type=hidden name=action value=go>
<table cellspacing=0 cellpadding=5 border=1 style=\"border-collapse: collapse\" bordercolor=cryan>
<tr><td colspan=2 bgcolor=cryan align=center>
        <font size=4 color=white>�t�ΰѼƳ]�w</font></td></tr>
<tr><td align=right>�쬡�ʼ��D�G</td>
    <td><label style='background:#F0F000;'>&nbsp;".$Title."&nbsp;</label></td></tr>
<tr><td align=right>�s���ʼ��D�G</td>
    <td><input type=text STYLE='FONT-SIZE:16px;COLOR:#0080C0;background-color:#FEEBD8;' name=titlen size=60 value=".$Title."></td></tr>
<tr><td align=right>�t�κ޲z�̱b���G</td>
  <td><input type=text STYLE='FONT-SIZE:16px;COLOR:#0080C0;background-color:#FEEBD8;' name=user_name value=".$Uname."></td></tr>
<tr><td align=right>�t�κ޲z�̱K�X�G</td>
  <td><input type=password STYLE='FONT-SIZE:16px;COLOR:#0080C0;background-color:#FEEBD8;' name=user_pass value=".$Pword."></td></tr>
<tr><td align=right>�e����s���1�G</td>
  <td><input type=text STYLE='FONT-SIZE:16px;COLOR:#0080C0;background-color:#FEEBD8;' name=refresh_sec1 size=3 maxlength=3 value=".$RefreshSec1.">&nbsp;�ȡG1~999&nbsp;<font color=cryan size=-1>(�}���i�椤����s���)</font></td></tr>
<tr><td align=right>�e����s���2�G</td>
  <td><input type=text STYLE='FONT-SIZE:16px;COLOR:#0080C0;background-color:#FEEBD8;' name=refresh_sec2 size=3 maxlength=3 value=".$RefreshSec2.">&nbsp;�ȡG1~999&nbsp;<font color=cryan size=-1>(�}�������ᤧ��s��ơA�H��t�έt��)</font></td></tr>
<tr><td align=right>�w�w��ﲼ�ơG</td>
    <td><input type=text STYLE='FONT-SIZE:16px;COLOR:#0080C0;background-color:#FEEBD8;' name=ballot2comein size=6 maxlength=6 value=".$Ballot2ComeIn.">&nbsp;�ȡG1~999999&nbsp;<font color=cryan size=-1>(�F�ЮɡA�ӫJ��H�i�w��ŧG��蠟����)</font></td></tr>
<tr><td align=right>�������ʪ��p�G</td>
    <td><select name=ending STYLE='FONT-SIZE:16px;COLOR:#0080C0;background-color:#FEEBD8;'>
        <option value='0'".(($Ending=='0')?" selected":"").">�i�椤�K</option>
        <option value='1'".(($Ending=='1')?" selected":"").">�����F�I</option>
        </select>&nbsp;<font color=cryan size=-1>(���]�w�v�T�t�ΰѼƪ�����)</font></td></tr>
<tr><td colspan=2 bgcolor=cryan align=center>
        <input type=submit name=B1 value=�x�s��s></td></tr>
</table>            
</form>
</center>";
   $echo_str .= foot();
   echo $echo_str;
   exit();
}


// �ˬd�K�X�O�_�X�k
if ($_POST['action']=="go"){
   
   $echo_str = head()."<center>\n";
   /*
   if (($_SESSION['username']<>$AdminName['root']) or ($_SESSION['password']<>$AdminPwd['root'])){
      $echo_str .="<label style='background:#FFFF00'><font color=red size=5>���\�୭�޲z�̨ϥΡI�I<br>(�G��ƥ����)</font></label><br><br>";
      $echo_str .="�U<a href='javascript:window.close()'>����������</a>�U";
      echo $echo_str."</center>".foot();
      exit();
   }
   */
   $titlen=addslashes($_POST['titlen']);

   $sql_update= "update config set title='$titlen',admin_name='".$_POST['user_name']."',admin_pass='".$_POST['user_pass']."',
                                   refresh_sec1='".$_POST['refresh_sec1']."',refresh_sec2='".$_POST['refresh_sec2']."',
                                   ballot2comein='".$_POST['ballot2comein']."',ending='".$_POST['ending']."'";
   $result=mysql_query($sql_update);
   
   $echo_str .="<label style='background:#FFFF00'><font color=red size=5>���ʼ��D�Ψt�ΰѼƧ�s�����I</font></label><br><br>";
   $echo_str .="�U<a href='javascript:window.close()'>����������</a>";
   $echo_str .="�U<a href='".$_SERVER['PHP_SELF']."'>�^�]�w�e��</a>";
   $echo_str .="�U<a href=logout.php>�n�X</a>�U";
   echo $echo_str."</center>".foot();
}

/* ��ܤ���Ÿ�(>,<,=)����� */
function compar_mode($cm,$no)
{
   $cmode = array(">",">=","==","<=","<");
   $cmodeW= array("�j��","�j�󵥩�","����","�p�󵥩�","�p��");
   $cnt = sizeof($cmode);
   $sel_str = "<select size=1 STYLE='FONT-SIZE:13px;COLOR:#009300;background-color:F0FFDD;' name=compar".$no.">\n";
   for($i=0;$i<$cnt;$i++){
      $sel_str .= "<option ".(($cm==$cmode[$i])?"selected ":"")."value='".$cmode[$i]."'>".$cmodeW[$i]."</option>\n";
   }
   $sel_str .= "</select>\n";
   
   return $sel_str;
}

/* ��ܰ��ɲէO����� */
function grp_mode($gm,$no)
{
   global $Grp_tbl;
   
   $sel_str = "<select size=1 STYLE='FONT-SIZE:13px;COLOR:#009300;background-color:F0FFDD;' name=group".$no.">\n";
   $sqlstr="select * from $Grp_tbl";
   $sqlqry=mysql_query($sqlstr);
   while($o=mysql_fetch_object($sqlqry)){
      $sel_str .= '<option '.(($gm==($o->Group_Num*1))?"selected ":"").'value="'.$o->Group_Num.'">'.$o->Group_Num.$o->Group_Name.'��</option>\n';
   }    
   $sel_str .= "</select>\n";
   
   return $sel_str;
}

/* ����޿�Ÿ�(and,or)����� */
function logic_mode($lm,$no)
{
   $lmode = array("and","or");
   $lmodeW = array("�B","��");
   $cnt = sizeof($lmode);
   $sel_str = "<select size=1 STYLE='FONT-SIZE:16px;COLOR:red;background-color:F0FFDD;' name=logic".$no.">\n";
   for($i=0;$i<$cnt;$i++){
      $sel_str .= "<option ".(($lm==$lmode[$i])?"selected ":"")."value=".$lmode[$i].">".$lmodeW[$i]."</option>\n";
   }
   $sel_str .= "</select>\n";
   
   return $sel_str;
}

/* ��ܳ��βէO��ƪ���� 
   $sel_name : �U�Ԧ����W��
   $prefix   : ����蠟��ƪ�r��
   $default  : �w�]����ƪ�W��
   $system   : �t�Ϊ��A(ON or OFF)
   $except   : ���C�J��椧���~�ﶵ�}�C
*/
function select_opts($sel_name,$prefix,$default,$system,$except=array())
{
   global $DbName;
   
   $sel_str = "<select size=1 STYLE='FONT-SIZE:16px;COLOR:#009300;background-color:F0FFDD;' name=$sel_name".(($system=='ON')?" disabled":"").">\n";
   $result = mysql_list_tables ($DbName); 
   $i = 0; 
   while ($i < mysql_num_rows ($result)) { 
      $tb_name = mysql_tablename ($result, $i); 
      $tbn = substr($tb_name,0,strlen($prefix));
      if(($tbn==$prefix) and !in_array($tb_name,$except))
         $sel_str .= '<option '.(($default==($tb_name))?"selected ":"").'value="'.$tb_name.'">'.$tb_name.'</option>\n';
      $i++;
   }
   $sel_str .= "</select>\n";
   
   return $sel_str;
}
?>