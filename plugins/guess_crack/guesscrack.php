<?php  
function ecwr($str,$ok = 0) {
$res->ok=$ok;
$res->str=$str;
echo json_decode(str_replace("\\\\u","\\u",json_encode(json_encode($res))));
exit();
}
require_once dirname(dirname(dirname(__FILE__))).'/system/common.inc.php';
if(getSetting('guesscrack-run')!=1) ecwr('功能未开启');
$t1=(int)($_GET['t']);
$m1=(int)($_GET['m']);
if ($t1<100) {
if(!(($t1>0)&&($t1<9)&&($m1>0)&&($m1<17))) ecwr('输入信息(类别号/关卡号)未按要求填写!');
if(($t1==5)&&($m1>12)) ecwr('第5类只有1-12关哦!');
}else{
if(!(($t1>99)&&($t1<103))) ecwr('输入信息(类别号/关卡号)未按要求填写!');
if(($m1>6)||($m1<1)) ecwr('圣诞类只有1-6关哦!');
}
if($uid==0) ecwr('用户尚未登录');
if($formhash!=$_GET['formhash']) ecwr('来源不可信');
$cc=DB::result_first("SELECT v FROM guesscrack WHERE 1");
$data=json_decode(json_decode(str_replace("\\u","\\\\u",json_encode('{"'.str_replace(',','","',str_replace(':','":"',$cc)).'"}'))),true);
$cookie = get_cookie($uid);
if(!$cookie) ecwr('找不到该账号的百度Cookie');
$url = 'http://www.baidu.com/ur/show/uhguesshome' ;  
$ch = curl_init() ;  
curl_setopt($ch, CURLOPT_URL,$url) ;  
curl_setopt($ch, CURLOPT_COOKIE, $cookie);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
$result = curl_exec($ch);  
curl_close($ch) ;
preg_match('/<input type=hidden id=bsToken value="(.*?)"/',$result,$sth);
$bstoken=$sth[1];
$i=0;
while (true) {
$url = 'http://www.baidu.com/ur/submit/uhguessstart' ;  
$fields = array(  
'themeid'=>$_GET['t'] ,  
'missionid'=>$_GET['m'] ,   
'bsToken'=>$bstoken,
'_req_seqid'=>'',
);  
$ch = curl_init() ;  
curl_setopt($ch, CURLOPT_URL,$url) ;  
curl_setopt($ch, CURLOPT_COOKIE, $cookie);
curl_setopt($ch, CURLOPT_POST,count($fields)) ;
curl_setopt($ch, CURLOPT_POSTFIELDS,$fields);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
$result = curl_exec($ch);  
curl_close($ch) ;
$rr=json_decode(str_replace('\\&quot;','\\"',$result))->problems;
foreach ($rr as $aa) {
$t=0;$i++;
if (!empty($data[$aa->pquery])) {$tk=$data[$aa->pquery];}else{
$tk='';
foreach ($aa->pans as $c) {
if (($c->des==$aa->pquery)or(!(strpos($c->des,$aa->pquery)===false))or(!(strpos($aa->pquery,$c->des)===false))) {$tk=$c->index;break;}
}
if ($tk==''){
$data[$aa->pquery]='a';
$tk='a';}
}
$url = 'http://www.baidu.com/ur/submit/uhguess' ;  
$fields = array(  
'themeid'=>$_GET['t'] ,  
'missionid'=>$_GET['m'] ,   
'bsToken'=>$bstoken,
'_req_seqid'=>'',
'pid'=>($aa->pid),
'pans'=>($tk),
);  
$ch = curl_init() ;  
curl_setopt($ch, CURLOPT_URL,$url) ;  
curl_setopt($ch, CURLOPT_COOKIE, $cookie);
curl_setopt($ch, CURLOPT_POST,count($fields)) ;
curl_setopt($ch, CURLOPT_POSTFIELDS,$fields);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
$result = curl_exec($ch);  
if (json_decode($result)->answer->correct_flag==0) {
$tt=$data[$aa->pquery];
if (empty($tt)) {$data[$aa->pquery]='a';} else {$tt++;if ($tt=='e') $tt='a';$data[$aa->pquery]=$tt;}
$t=1;break;
}
curl_close($ch) ; 
}
if ($t==1) continue;
break;
}
$v=substr(str_replace('"','',json_decode(str_replace("\\\\u","\\u",json_encode(json_encode($data))))),1,-1);
DB::query("UPDATE `guesscrack` SET `v`='{$v}' WHERE 1");
if(((string)(json_decode($result)->answer->gametime))===''){
ecwr('题目获取出错,可能为该关不存在或该关未解锁或JSON解码失败(如果自行尝试可以玩,请重试)');
}else{
ecwr(json_decode($result)->answer->gametime,1);
}
?>