<?
include "config.inc.php";

$echo_str = "<html><head>\n";
$echo_str .= "<meta http-equiv=\"Content-Type\" content=\"text/html; Charset=Big5\">\n";
$echo_str .= "<META HTTP-EQUIV=REFRESH CONTENT='".$delay.";URL=".$_SERVER['PHP_SELF']."'>\n";
$echo_str .= "<Link Rel='stylesheet' Type='text/css' Href='style_c.css'>\n";
$echo_str .= "<title>".$Title."--�}�������@�~</title>\n";
$echo_str .= "</head><body style='margin-top:2px;margin-left:2px'>\n";
$echo_str .= "<center>\n";
$echo_str .= "<h3>".$Title."--�}�������@�~</h3>\n";
$echo_str .= "<font size=2 color=blue>�@(���e���C&nbsp;<font color=red>".$delay."</font>&nbsp;�����۰ʽ����@��)</font>\n";
$echo_str .= "<font size=2> �Τ���I�� <a href=".$_SERVER['PHP_SELF'].">��s�n</a></font><br><br>\n";
$onlines = who_online($DbName);
$echo_str .= "<table border=0 cellspacing=0 cellpadding=0 style=\"border-collapse: collapse;font-family:�з���\" bordercolor=#009380>\n";
$echo_str .= "<tr style='font-size:30px;background:#666666;color:#FFFFFF;'><td align=right nowrap>�u�W�H�ơG</td><td><font color=red>".$onlines['all']."</font> �H</td></tr>";
$echo_str .= "<tr style='font-size:30px;background:#666666;color:#FFFFFF;'><td align=right nowrap>�@�@�|���G</td><td><font color=red>".$onlines['login']."</font> �H</td></tr>";
$echo_str .= "<tr style='font-size:30px;background:#666666;color:#FFFFFF;'><td align=right nowrap>�@�@�C�ȡG</td><td><font color=red>".($onlines['all']-$onlines['login'])."</font> �H</td></tr>";
$echo_str .= "</table>\n";
$echo_str .= "</center>\n";
$echo_str .= "</body></html>\n";
echo $echo_str;
   
/* �u�W�H�Ƥ��έp�D��� */
function who_online($program)
{  
   $sess_dir = session_save_path();
   $dhandle = opendir($sess_dir);
   $cnt['all']   = 0;
   $cnt['login'] = 0;
   while (false !== ($file = readdir($dhandle))) { 
      $f_tmp = explode('.',$file);	
      if(($file=='.') or ($file=='..') or (!$f_tmp[0])) continue;	/* �Y�O '.' �� '..' �� '.*' ���ɮ׸��L */
      $fname = $sess_dir."/".$file;
      $arr = @file($fname);
      $timestamp = filemtime($fname);		/* ���o�ɮ׳̫�ק�ɶ� */
      $seconds = time()-$timestamp;		/* �O�ɭp�� */
      if(isset($arr[0]) and (strlen($arr[0])==0)){ continue;}		/* session���e���׬�0�ɸ��L */
      $tmp0 = isset($arr[0])?explode(';',$arr[0]):'';
//   echo $arr[0]."<br>\n";
      /* �H$sess�}�C�x�ssession�ܼƭ� */
      $sess=array();
      if($tmp0)
      foreach($tmp0 as $key => $value){
         $tmp1 = explode('|',$value,2);		/* ���osession�ܼƦW�٤έ� */
         $tmp2 = isset($tmp1[1])?explode(':',$tmp1[1]):'';
         if(isset($tmp2[0]) and ($tmp2[0]=='i'))
            $sess[$tmp1[0]] = $tmp2[1];	  /* �Ysession�ܼƬ��������'i'�ɡA�����N�Ȧs�J�}�C$sess�� */
         else
            $sess[$tmp1[0]] = isset($tmp2[2])?substr($tmp2[2],1,strlen($tmp2[2])-2):'';	/* �Ysession�ܼƬ��������'s'�ɡA�R���r��e�ᤧ"�Ÿ��æs�J�}�C$sess�� */
      }
      /* �֭p�u�W�H�� */
      if(isset($sess['program']) and ($sess['program']==$program)){
         $cnt['all']++;
         /* �֭p�|���H�� */
         if(isset($sess['login']) and $sess['login']) $cnt['login']++;
      }
   }
   closedir($dhandle);

   return $cnt;
}

?>