<?
include "config.inc.php";

$echo_str = "<html><head>\n";
$echo_str .= "<meta http-equiv=\"Content-Type\" content=\"text/html; Charset=Big5\">\n";
$echo_str .= "<META HTTP-EQUIV=REFRESH CONTENT='".$delay.";URL=".$_SERVER['PHP_SELF']."'>\n";
$echo_str .= "<Link Rel='stylesheet' Type='text/css' Href='style_c.css'>\n";
$echo_str .= "<title>".$Title."--開票輪播作業</title>\n";
$echo_str .= "</head><body style='margin-top:2px;margin-left:2px'>\n";
$echo_str .= "<center>\n";
$echo_str .= "<h3>".$Title."--開票輪播作業</h3>\n";
$echo_str .= "<font size=2 color=blue>　(本畫面每&nbsp;<font color=red>".$delay."</font>&nbsp;秒鐘自動輪播一次)</font>\n";
$echo_str .= "<font size=2> 或手動點選 <a href=".$_SERVER['PHP_SELF'].">更新》</a></font><br><br>\n";
$onlines = who_online($DbName);
$echo_str .= "<table border=0 cellspacing=0 cellpadding=0 style=\"border-collapse: collapse;font-family:標楷體\" bordercolor=#009380>\n";
$echo_str .= "<tr style='font-size:30px;background:#666666;color:#FFFFFF;'><td align=right nowrap>線上人數：</td><td><font color=red>".$onlines['all']."</font> 人</td></tr>";
$echo_str .= "<tr style='font-size:30px;background:#666666;color:#FFFFFF;'><td align=right nowrap>　　會員：</td><td><font color=red>".$onlines['login']."</font> 人</td></tr>";
$echo_str .= "<tr style='font-size:30px;background:#666666;color:#FFFFFF;'><td align=right nowrap>　　遊客：</td><td><font color=red>".($onlines['all']-$onlines['login'])."</font> 人</td></tr>";
$echo_str .= "</table>\n";
$echo_str .= "</center>\n";
$echo_str .= "</body></html>\n";
echo $echo_str;
   
/* 線上人數之統計主函數 */
function who_online($program)
{  
   $sess_dir = session_save_path();
   $dhandle = opendir($sess_dir);
   $cnt['all']   = 0;
   $cnt['login'] = 0;
   while (false !== ($file = readdir($dhandle))) { 
      $f_tmp = explode('.',$file);	
      if(($file=='.') or ($file=='..') or (!$f_tmp[0])) continue;	/* 若是 '.' 或 '..' 或 '.*' 等檔案跳過 */
      $fname = $sess_dir."/".$file;
      $arr = @file($fname);
      $timestamp = filemtime($fname);		/* 取得檔案最後修改時間 */
      $seconds = time()-$timestamp;		/* 逾時計算 */
      if(isset($arr[0]) and (strlen($arr[0])==0)){ continue;}		/* session內容長度為0時跳過 */
      $tmp0 = isset($arr[0])?explode(';',$arr[0]):'';
//   echo $arr[0]."<br>\n";
      /* 以$sess陣列儲存session變數值 */
      $sess=array();
      if($tmp0)
      foreach($tmp0 as $key => $value){
         $tmp1 = explode('|',$value,2);		/* 取得session變數名稱及值 */
         $tmp2 = isset($tmp1[1])?explode(':',$tmp1[1]):'';
         if(isset($tmp2[0]) and ($tmp2[0]=='i'))
            $sess[$tmp1[0]] = $tmp2[1];	  /* 若session變數為整數類型'i'時，直接將值存入陣列$sess中 */
         else
            $sess[$tmp1[0]] = isset($tmp2[2])?substr($tmp2[2],1,strlen($tmp2[2])-2):'';	/* 若session變數為整數類型's'時，刪除字串前後之"符號並存入陣列$sess中 */
      }
      /* 累計線上人數 */
      if(isset($sess['program']) and ($sess['program']==$program)){
         $cnt['all']++;
         /* 累計會員人數 */
         if(isset($sess['login']) and $sess['login']) $cnt['login']++;
      }
   }
   closedir($dhandle);

   return $cnt;
}

?>