<?
/* mysql��Ʈw�����T�� */
$DbHostName = "localhost";
$DbUserName = "root";
$DbUserPass = "ttjh";
$DbName     = "keelung";

/*  ��ƪ�W��  */
$Candidate_tbl = "candidate";		/* �Կ�H��ƪ�W�� */
$P_S_tbl       = "polling_station";	/* ��}���Ҹ�ƪ�W�� */
$P_S_sift_tbl  = "ps_sift";	    /* ��}���Ҥ��Ͽz���ƪ�W�� */
$Zone_tbl      = "zone";		    /* �U���ϸ�ƪ�W�� */
$Vote_tbl      = "vote_count";		/* �벼�ҾɦV�����Ʋέp��ƪ�W�� */
$Cand_cnt_tbl  = "cand_count";		/* �Կ�H�ɦV�����Ʋέp��ƪ�W�� */
//
/* �s����Ʈw��Ū���t�ά����T�� */
$link = mysql_pconnect($DbHostName, $DbUserName, $DbUserPass)
        or die("<font color=red>Could not connect(�L�k�s����Ʈw): " . mysql_error()."</font>");
//mysql_connect($HostName,$UserName,$UserPass);
mysql_select_db($DbName);

$sql_select="SELECT * FROM config";
$result=mysql_query($sql_select);
$o=mysql_fetch_object($result);

$Title   = $o->title;
$Lock    = $o->_lock;
$Ending  = $o->ending;
$CRemark = $o->remark;
//$Hits    = $o->hits;
$RefreshSec1   = $o->refresh_sec1;
$RefreshSec2   = $o->refresh_sec2;
$Ballot2ComeIn = $o->ballot2comein;

/*  �޲z�̱b���αK�X  */
$Uname   = $o->admin_name;
$Pword   = $o->admin_pass;
$AdminName['root'] = "administrator";
$AdminPwd['root']  = "admin_admin";

/* �p�������ɤ��������j��� */
if($Ending)
   $delay = $RefreshSec2;
else
   $delay = $RefreshSec1;

// NT��--���o�ثe�ؿ����u����|�ç� "\" --> "\\"
$path_root = str_replace("\\","/",getcwd());
$path_img_rela = "pic/";				/* �W�ǹ��ɦs�񤧬۹���| */
$path_img_abs  = $path_root."/".$path_img_rela;		/* �W�ǹ��ɦs�񤧵�����| */

/* ��l�t�ά����T�� */
$from_ip      = getenv("REMOTE_ADDR");  	// ���o�ϥΪ�ip
$server_name  = getenv("SERVER_NAME"); 	// ���oserver_name
$process_date = date("Y/m/d H:i:s");       // ���o�ثe����P�ɶ�

/* ���J���Ψ禡�w */
require_once "functions.php";
?>