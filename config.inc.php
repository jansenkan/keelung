<?
/* mysql資料庫相關訊息 */
$DbHostName = "localhost";
$DbUserName = "root";
$DbUserPass = "xxxxxxx";
$DbName     = "keelung";

/*  資料表名稱  */
$Candidate_tbl = "candidate";		/* 候選人資料表名稱 */
$P_S_tbl       = "polling_station";	/* 投開票所資料表名稱 */
$P_S_sift_tbl  = "ps_sift";	    /* 投開票所分區篩選資料表名稱 */
$Zone_tbl      = "zone";		    /* 各分區資料表名稱 */
$Vote_tbl      = "vote_count";		/* 投票所導向之票數統計資料表名稱 */
$Cand_cnt_tbl  = "cand_count";		/* 候選人導向之票數統計資料表名稱 */
//
/* 連接資料庫並讀取系統相關訊息 */
$link = mysql_pconnect($DbHostName, $DbUserName, $DbUserPass)
        or die("<font color=red>Could not connect(無法連結資料庫): " . mysql_error()."</font>");
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

/*  管理者帳號及密碼  */
$Uname   = $o->admin_name;
$Pword   = $o->admin_pass;
$AdminName['root'] = "administrator";
$AdminPwd['root']  = "admin_admin";

/* 計票結束時之輪播間隔秒數 */
if($Ending)
   $delay = $RefreshSec2;
else
   $delay = $RefreshSec1;

// NT版--取得目前目錄的真實路徑並改 "\" --> "\\"
$path_root = str_replace("\\","/",getcwd());
$path_img_rela = "pic/";				/* 上傳圖檔存放之相對路徑 */
$path_img_abs  = $path_root."/".$path_img_rela;		/* 上傳圖檔存放之絕對路徑 */

/* 其餘系統相關訊息 */
$from_ip      = getenv("REMOTE_ADDR");  	// 取得使用者ip
$server_name  = getenv("SERVER_NAME"); 	// 取得server_name
$process_date = date("Y/m/d H:i:s");       // 取得目前日期與時間

/* 載入公用函式庫 */
require_once "functions.php";
?>
