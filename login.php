<?
include("config.inc.php");
// 若系統關閉則轉向
/*
if($System=='OFF'){
   header("LOCATION:./");
   exit();
}
*/
/**************************************************
   檔名：login.php3
   用途：密碼驗證(使用session)
   作者：甘之信
   說明：   
   	本檔須搭配 chkuser.inc.php 檔使用，
   	
   	請在須驗證的檔案最前方加入以下語法：
    
        <? require("chkuser.inc.php"); ?>
***************************************************/
$redir    = isset($_GET['redir'])   ?$_GET['redir']   :'';	// 外部傳來之轉向程式
$redir    = isset($_POST['redir'])  ?$_POST['redir']  :$redir;	// 外部傳來之轉向程式

/**************************************************
   驗證成功後要轉向的網頁
***************************************************/
$redirect_file="ins_team.php";

/**************************************************
   首次進入或驗證失敗的處理
***************************************************/
$action = isset($_POST['action'])?$_POST['action']:'';
if(!isset($action) or ($action<>'login')){
	
   $echo_str = head('style_c.css','◤線上報名身份認證◥');
   $echo_str .= '
<center><font color=blue size=4>'.$Title.'</font></center>
<form action='.$_SERVER['PHP_SELF'].' method="POST">
    <div align="center">
    <table cellspacing=0 cellpadding=3 border=1 style="border-collapse: collapse" bordercolor="#0080C0">
        <tr bgcolor="#0080C0">
            <td align="center" colspan=2>
            <table border=0 width=100% cellspacing=1 cellpadding=0><tr>
            <td nowrap align="left"><font color="#FFFFFF" size="4">◤</font></td>
            <td nowrap align="center"><font color="#FFFFFF" size="4"><b>使用者身份認證</b></font></td>
            <td nowrap align="right"><font color="#FFFFFF" size="4">◥</font></td>
            </tr></table>
            </td>
        <tr>
            <td align="center">身份：</td>
            <td align="center">
            <select name="ID_CATE" STYLE="FONT-SIZE:18px;HEIGHT:24px;COLOR:red;background-color:#ccffcc;FONT:BOLD">
            <option value="ID1"'.((isset($_GET['id_cate']) and ($_GET['id_cate']=="id1"))?" selected":"").'>系統管理員</option>
            <option value="ID2"'.((isset($_GET['id_cate']) and ($_GET['id_cate']=="id2"))?" selected":"").'>分區管理員</option>
            <option value="ID3"'.((isset($_GET['id_cate']) and ($_GET['id_cate']=="id3"))?" selected":"").'>投開票所管理員</option>
            </select>
            </td>
        </tr>
        <tr>
            <td align="center">帳號：</td>
            <td><input STYLE="FONT-SIZE:18px;HEIGHT:24px;COLOR:red;background-color:#ccffcc;FONT:BOLD" type="text" size="12"
            name=username></td>
        </tr>
        <tr>
            <td align="center">密碼：</td>
            <td><input STYLE="FONT-SIZE:18px;HEIGHT:24px;COLOR:red;background-color:#ccffcc;FONT:BOLD" type="password" size="12"
            name=password></td>
        </tr>
        <tr>
            <td align="center" colspan="2">
            <input type="hidden" name="action" value="login">
            <input type="submit" name="B1" value="確定">
            <input type="reset"  name="B2" value="重設">
            </td>
        </tr>
    </table>
    [<a href=index.php>回功能選單</a>]
    </div>
</form>
</body>
</html>';

   echo $echo_str;

/**************************************************
   登出(logout) 語法：login.php?action=out
   尚有問題，暫時使用logout.php
***************************************************/
}elseif($action=='out'){
	
   session_id()?'':session_start();
   session_unset();
   header("Location: ".$_SERVER['PHP_SELF']);
   exit();
   
/**************************************************
   驗證成功的處理
***************************************************/
}else{

   session_id()?'':session_start();
   session_register("super");
   session_register("login");
   session_register("fromip"); 	//設定fromip之值於session中
   session_register("locate"); 	//設定locate(以來源資料庫名稱為辨識名)之值於session中
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
            $num_ID2=mysql_num_rows($sqlqry) or die('權限不足！<br>Error:200');
            $id2 = mysql_fetch_object($sqlqry);
            // 有設定IP限定才做篩選
            if($id2->ZoneIP)
               if($id2->ZoneIP<>$from_ip) die('權限不足！<br>Error:201');
           break;
      case 'ID3':
            $sqlstr="Select * from $P_S_tbl where UCASE(ps_user)='".strtoupper($_POST['username'])."' and password='".$_POST['password']."'";
            $sqlqry=mysql_query($sqlstr);
            $num_ID3=mysql_num_rows($sqlqry) or die('權限不足！<br>Error:300');
            $id3 = mysql_fetch_object($sqlqry);
            // 有設定IP限定才做篩選
            if($id3->ps_ip)
               if($id3->ps_ip<>$from_ip) die('權限不足！<br>Error:301');
           break;
   }

   if ($num>=1) {
      session_register("orgid"); 	//設定orgid之值於session中
      session_register("orgno"); 	//單位資料庫中的排序
      session_register("orgar"); 	//設定orgar之值於session中(鄉鎮市區)
      session_register("orgname"); 	//設定orgname之值於session中
      session_register("username"); 	//設定username之值於session中
      session_register("password"); 	//設定password之值於session中
      session_register("admintitle"); 	//設定admintitle之值於session中
      session_register("adminname"); 	//設定adminname之值於session中
      session_register("admintel"); 	//設定admintel之值於session中
      session_register("adminid"); 	//設定adminid之值於session中
//      session_register("itemno"); 	//設定itemno之值於session中
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
   /* 系統管理員登入 */
   }elseif((($username==$AdminName['root']) and ($password==$AdminPwd['root'])) or ($num_ID1 >=1 )){
      session_register("username"); 	//設定username之值於session中
      session_register("password"); 	//設定password之值於session中
      session_register("root"); 	//設定root之值於session中
      $_SESSION['username'] =$AdminName['root'];
      $_SESSION['password'] =$AdminPwd['root'];
      $_SESSION['root']     ="sys_player";
      if($redir){ header("Location: ".$redir); exit; }		// 轉向程式:其他應用程式認證適用
      header("Location: admin.php?".(session_id()?SID:''));	// 內定之轉向程式
      exit();
   /* 分區管理員登入 */
   }elseif($num_ID2 >= 1){
      session_register("username"); 	//設定username之值於session中
      session_register("password"); 	//設定password之值於session中
      session_register("root"); 	//設定root之值於session中
      $_SESSION['username'] =$_POST['username'];
      $_SESSION['password'] =$_POST['password'];
      $_SESSION['root']     ="zone_player";
      if($redir){ header("Location: ".$redir); exit; }		// 轉向程式:其他應用程式認證適用
      header("Location: upd_vote.php?action=all&zone_no=".$id2->ZoneNum."&".(session_id()?SID:''));	// 內定之轉向程式
      exit();
   /* 投開票所管理員登入 */
   }elseif($num_ID3 >= 1){
      session_register("username"); 	//設定username之值於session中
      session_register("password"); 	//設定password之值於session中
      session_register("root"); 	//設定root之值於session中
      $_SESSION['username'] =$_POST['username'];
      $_SESSION['password'] =$_POST['password'];
      $_SESSION['root']     ="p_s_player";
      if($redir){ header("Location: ".$redir); exit; }		// 轉向程式:其他應用程式認證適用
      header("Location: upd_vote.php?action=single&mode=form&".(session_id()?SID:''));	// 內定之轉向程式
      exit();
   }else{
      if($redir){ header("Location: ".$redir); exit; }		// 轉向程式:其他應用程式認證適用
      session_unset();
      session_destroy();
      header("Location: index.php");
      exit();
   }
}
?>