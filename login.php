<?
include("config.inc.php");
// �Y�t�������h��V
/*
if($System=='OFF'){
   header("LOCATION:./");
   exit();
}
*/
/**************************************************
   �ɦW�Glogin.php3
   �γ~�G�K�X����(�ϥ�session)
   �@�̡G�̤��H
   �����G   
   	���ɶ��f�t chkuser.inc.php �ɨϥΡA
   	
   	�Цb�����Ҫ��ɮ׳̫e��[�J�H�U�y�k�G
    
        <? require("chkuser.inc.php"); ?>
***************************************************/
$redir    = isset($_GET['redir'])   ?$_GET['redir']   :'';	// �~���ǨӤ���V�{��
$redir    = isset($_POST['redir'])  ?$_POST['redir']  :$redir;	// �~���ǨӤ���V�{��

/**************************************************
   ���Ҧ��\��n��V������
***************************************************/
$redirect_file="ins_team.php";

/**************************************************
   �����i�J�����ҥ��Ѫ��B�z
***************************************************/
$action = isset($_POST['action'])?$_POST['action']:'';
if(!isset($action) or ($action<>'login')){
	
   $echo_str = head('style_c.css','���u�W���W�����{�Ң�');
   $echo_str .= '
<center><font color=blue size=4>'.$Title.'</font></center>
<form action='.$_SERVER['PHP_SELF'].' method="POST">
    <div align="center">
    <table cellspacing=0 cellpadding=3 border=1 style="border-collapse: collapse" bordercolor="#0080C0">
        <tr bgcolor="#0080C0">
            <td align="center" colspan=2>
            <table border=0 width=100% cellspacing=1 cellpadding=0><tr>
            <td nowrap align="left"><font color="#FFFFFF" size="4">��</font></td>
            <td nowrap align="center"><font color="#FFFFFF" size="4"><b>�ϥΪ̨����{��</b></font></td>
            <td nowrap align="right"><font color="#FFFFFF" size="4">��</font></td>
            </tr></table>
            </td>
        <tr>
            <td align="center">�����G</td>
            <td align="center">
            <select name="ID_CATE" STYLE="FONT-SIZE:18px;HEIGHT:24px;COLOR:red;background-color:#ccffcc;FONT:BOLD">
            <option value="ID1"'.((isset($_GET['id_cate']) and ($_GET['id_cate']=="id1"))?" selected":"").'>�t�κ޲z��</option>
            <option value="ID2"'.((isset($_GET['id_cate']) and ($_GET['id_cate']=="id2"))?" selected":"").'>���Ϻ޲z��</option>
            <option value="ID3"'.((isset($_GET['id_cate']) and ($_GET['id_cate']=="id3"))?" selected":"").'>��}���Һ޲z��</option>
            </select>
            </td>
        </tr>
        <tr>
            <td align="center">�b���G</td>
            <td><input STYLE="FONT-SIZE:18px;HEIGHT:24px;COLOR:red;background-color:#ccffcc;FONT:BOLD" type="text" size="12"
            name=username></td>
        </tr>
        <tr>
            <td align="center">�K�X�G</td>
            <td><input STYLE="FONT-SIZE:18px;HEIGHT:24px;COLOR:red;background-color:#ccffcc;FONT:BOLD" type="password" size="12"
            name=password></td>
        </tr>
        <tr>
            <td align="center" colspan="2">
            <input type="hidden" name="action" value="login">
            <input type="submit" name="B1" value="�T�w">
            <input type="reset"  name="B2" value="���]">
            </td>
        </tr>
    </table>
    [<a href=index.php>�^�\����</a>]
    </div>
</form>
</body>
</html>';

   echo $echo_str;

/**************************************************
   �n�X(logout) �y�k�Glogin.php?action=out
   �|�����D�A�Ȯɨϥ�logout.php
***************************************************/
}elseif($action=='out'){
	
   session_id()?'':session_start();
   session_unset();
   header("Location: ".$_SERVER['PHP_SELF']);
   exit();
   
/**************************************************
   ���Ҧ��\���B�z
***************************************************/
}else{

   session_id()?'':session_start();
   session_register("super");
   session_register("login");
   session_register("fromip"); 	//�]�wfromip���ȩ�session��
   session_register("locate"); 	//�]�wlocate(�H�ӷ���Ʈw�W�٬����ѦW)���ȩ�session��
   $_SESSION['super']  = "yes";
   $_SESSION['login']  = 1;
   $_SESSION['fromip'] = $from_ip;
   $_SESSION['locate'] = $DbName;
//   mysql_connect($DbHostName,$DbUserName,$DbUserPass);
//   mysql_select_db($DbName);
   $num     = 0;
   $num_ID1 = 0;
   $num_ID2 = 0;
   $num_ID3 = 0;
   switch($_POST['ID_CATE']){
      case 'ID1':
            $sqlstr="Select * from config where UCASE(admin_name)='".strtoupper($_POST['username'])."' and admin_pass='".$_POST['password']."'";
//            echo $sqlstr;exit;
            $sqlqry=mysql_query($sqlstr);
            $num_ID1=mysql_num_rows($sqlqry);
           break;
      case 'ID2':
            $sqlstr="Select * from $Zone_tbl where UCASE(ZoneUser)='".strtoupper($_POST['username'])."' and ZonePass='".$_POST['password']."'";
            $sqlqry=mysql_query($sqlstr);
            $num_ID2=mysql_num_rows($sqlqry) or die('�v�������I<br>Error:200');
            $id2 = mysql_fetch_object($sqlqry);
            // ���]�wIP���w�~���z��
            if($id2->ZoneIP)
               if($id2->ZoneIP<>$from_ip) die('�v�������I<br>Error:201');
           break;
      case 'ID3':
            $sqlstr="Select * from $P_S_tbl where UCASE(ps_user)='".strtoupper($_POST['username'])."' and password='".$_POST['password']."'";
            $sqlqry=mysql_query($sqlstr);
            $num_ID3=mysql_num_rows($sqlqry) or die('�v�������I<br>Error:300');
            $id3 = mysql_fetch_object($sqlqry);
            // ���]�wIP���w�~���z��
            if($id3->ps_ip)
               if($id3->ps_ip<>$from_ip) die('�v�������I<br>Error:301');
           break;
   }

   if ($num>=1) {
      session_register("orgid"); 	//�]�worgid���ȩ�session��
      session_register("orgno"); 	//����Ʈw�����Ƨ�
      session_register("orgar"); 	//�]�worgar���ȩ�session��(�m����)
      session_register("orgname"); 	//�]�worgname���ȩ�session��
      session_register("username"); 	//�]�wusername���ȩ�session��
      session_register("password"); 	//�]�wpassword���ȩ�session��
      session_register("admintitle"); 	//�]�wadmintitle���ȩ�session��
      session_register("adminname"); 	//�]�wadminname���ȩ�session��
      session_register("admintel"); 	//�]�wadmintel���ȩ�session��
      session_register("adminid"); 	//�]�wadminid���ȩ�session��
//      session_register("itemno"); 	//�]�witemno���ȩ�session��
      $o=mysql_fetch_object($sqlqry);
      $_SESSION['orgid']      = $o->OrgId;
      $_SESSION['orgno']      = $o->Rid;
      $_SESSION['orgar']      = stripslashes($o->OrgArea);
      $_SESSION['orgname']    = stripslashes($o->OrgName);
      $_SESSION['username']   = $o->UserName;
      $_SESSION['password']   = $o->Password;
      $_SESSION['admintitle'] = stripslashes($o->AdminTitle);
      $_SESSION['adminname']  = stripslashes($o->AdminName);
      $_SESSION['admintel']   = $o->AdminTel;
      $_SESSION['adminid']    = $o->AdminId;
      header("Location: $redirect_file"."&".(session_id()?SID:''));
      exit();
   /* �t�κ޲z���n�J */
   }elseif((($username==$AdminName['root']) and ($password==$AdminPwd['root'])) or ($num_ID1 >=1 )){
      session_register("username"); 	//�]�wusername���ȩ�session��
      session_register("password"); 	//�]�wpassword���ȩ�session��
      session_register("root"); 	//�]�wroot���ȩ�session��
      $_SESSION['username'] =$AdminName['root'];
      $_SESSION['password'] =$AdminPwd['root'];
      $_SESSION['root']     ="sys_player";
      if($redir){ header("Location: ".$redir); exit; }		// ��V�{��:��L���ε{���{�ҾA��
      header("Location: admin.php?".(session_id()?SID:''));	// ���w����V�{��
      exit();
   /* ���Ϻ޲z���n�J */
   }elseif($num_ID2 >= 1){
      session_register("username"); 	//�]�wusername���ȩ�session��
      session_register("password"); 	//�]�wpassword���ȩ�session��
      session_register("root"); 	//�]�wroot���ȩ�session��
      $_SESSION['username'] =$_POST['username'];
      $_SESSION['password'] =$_POST['password'];
      $_SESSION['root']     ="zone_player";
      if($redir){ header("Location: ".$redir); exit; }		// ��V�{��:��L���ε{���{�ҾA��
      header("Location: upd_vote.php?action=all&zone_no=".$id2->ZoneNum."&".(session_id()?SID:''));	// ���w����V�{��
      exit();
   /* ��}���Һ޲z���n�J */
   }elseif($num_ID3 >= 1){
      session_register("username"); 	//�]�wusername���ȩ�session��
      session_register("password"); 	//�]�wpassword���ȩ�session��
      session_register("root"); 	//�]�wroot���ȩ�session��
      $_SESSION['username'] =$_POST['username'];
      $_SESSION['password'] =$_POST['password'];
      $_SESSION['root']     ="p_s_player";
      if($redir){ header("Location: ".$redir); exit; }		// ��V�{��:��L���ε{���{�ҾA��
      header("Location: upd_vote.php?action=single&mode=form&".(session_id()?SID:''));	// ���w����V�{��
      exit();
   }else{
      if($redir){ header("Location: ".$redir); exit; }		// ��V�{��:��L���ε{���{�ҾA��
      session_unset();
      session_destroy();
      header("Location: index.php");
      exit();
   }
}
?>