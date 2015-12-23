<?php

if (!defined('IN_DISCUZ')) {exit('Access Denied');}function pdnovelcache($cachename,$identifier = "") {global $_G;
$cachearray = array("pdnovelcategory","pdnovelcreditrule");
$cachename = in_array($cachename,$cachearray) ?$cachename : "";
if ($cachename == "pdnovelcategory") {
$data = array();
$query = DB::query("SELECT * FROM ".DB::table("pdnovel_category") ." ORDER BY displayorder,catid");
while ($value = DB::fetch($query)) {
$value['catname'] = dhtmlspecialchars($value['catname']);
$data[$value['catid']] = $value;
}
foreach($data as $k =>$v) {
if (!$v['catid']) {
continue;
}elseif ($v['upid'] >0) {
$data[$k]['level'] = 1;
continue;
}
foreach($data as $ks =>$vs) {
if ($vs['upid'] == $v['catid']) {
$data[$k]['children'][] = $vs['catid'];
$data[$k]['level'] = 0;
}
}
}
save_syscache("pdnovelcategory",$data);
}
}
function pdnovelcategoryrow($key,$level = 0,$last = "") {
global $_G;
loadcache("pdnovelcategory");
$value = $_G['cache']['pdnovelcategory'][$key];
$return = "";
if ($level == 1) {
$return = "<tr class=\"hover\" id=\"cat".$value['catid'] ."\"><td> </td><td class=\"td25\"><input type=\"text\" class=\"txt\" name=\"order[".$value['catid'] ."]\" value=\"".$value['displayorder'] ."\" /></td><td><div class=\"board\"><input type=\"text\" class=\"txt\" name=\"name[".$value['catid'] ."]\" value=\"".$value['catname'] ."\" /></div></td><td class=\"txt170\"><input type=\"text\" class=\"txt\" name=\"caption[".$value['catid'] ."]\" value=\"".$value['caption'] ."\" /></td><td class=\"txt170\"><input type=\"text\" class=\"txt\" name=\"keyword[".$value['catid'] ."]\" value=\"".$value['keyword'] ."\" /></td><td class=\"txt170\"><input type=\"text\" class=\"txt\" name=\"summary[".$value['catid'] ."]\" value=\"".$value['description'] ."\" /></td><td class=\"td28 lightfont\">catid:".$value['catid'] ."</td><td><a href=\"".ADMINSCRIPT ."?action=pdnovel&operation=category&do=delete&catid=".$value['catid'] ."\">".cplang("delete") ."</a></td></tr>";
return $return;
}
$childrennum = count($_G['cache']['pdnovelcategory'][$key]['children']);
$toggle = 25 <$childrennum ?" style=\"display:none\"": "";
$return = "<tbody><tr class=\"hover\" id=\"cat".$value['catid'] ."\"><td onclick=\"toggle_group('group_".$value['catid'] ."')\"><a id=\"a_group_".$value['catid'] ."\" href=\"javascript:;\">".($toggle ?"[+]": "[-]") ."</a></td><td class=\"td25\"><input type=\"text\" class=\"txt\" name=\"order[".$value['catid'] ."]\" value=\"".$value['displayorder'] ."\" /></td><td><div class=\"parentboard\"><input type=\"text\" class=\"txt\" name=\"name[".$value['catid'] ."]\" value=\"".$value['catname'] ."\" /></div></td><td class=\"txt170\"><input type=\"text\" class=\"txt\" name=\"caption[".$value['catid'] ."]\" value=\"".$value['caption'] ."\" /></td><td class=\"txt170\"><input type=\"text\" class=\"txt\" name=\"keyword[".$value['catid'] ."]\" value=\"".$value['keyword'] ."\" /></td><td class=\"txt170\"><input type=\"text\" class=\"txt\" name=\"summary[".$value['catid'] ."]\" value=\"".$value['description'] ."\" /></td><td class=\"td28 lightfont\">catid:".$value['catid'] ."</td><td><a href=\"".ADMINSCRIPT ."?action=pdnovel&operation=category&do=delete&catid=".$value['catid'] ."\">".cplang("delete") ."</a></td></tr></tbody><tbody id=\"group_".$value['catid'] ."\"".$toggle .">";
$i = 0;
$L = count($value['children']);
for (;$i <$L;++$i) {
$return .= pdnovelcategoryrow($value['children'][$i],1,"");
}
$return .= "</tdoby><tr><td> </td><td colspan=\"6\"><div class=\"lastboard\"><a href=\"###\" onclick=\"addrow(this, 1, ".$value['catid'] .")\" class=\"addtr\">".cplang("category_addsubcategory") ."</a></div></td></tr>";
return $return;
}
function category_showselect($name = "catid",$current = "") {
global $_G;
loadcache("pdnovelcategory");
$category = $_G['cache']['pdnovelcategory'];
$select = "<select id=\"".$name ."\" name=\"{$name}\" class=\"ps vm\">";
$select .= "<option value=\"\">".cplang("pdnovel_category_select") ."</option>";
foreach ($category as $value) {
if ($value['level'] == 0) {
$select .= "<option value=\"".$value['catid'] ."\" disabled=\"disabled\">{$value['catname']}</option>";
if (!$value['children']) {
}else {
foreach ($value['children'] as $catid) {
$disabled = $current &&$current == $catid ?"disabled=\"disabled\"": "";
$select .= "<option value=\"".$category[$catid][catid] ."\" {$disabled}>-- {$category[$catid][catname]}</option>";
}
}
}
}
$select .= "</select>";
return $select;
}
function renptyoveDir($dirName) {
if (!is_dir($dirName)) {
@unlink($dirName);
return false;
}
$handle = @opendir($dirName);
while (($file = @readdir($handle)) !== false) {
if (!($file != ".") &&!($file != "..")) {
$dir = $dirName ."/".$file;
is_dir($dir) ?removedir($dir) : unlink($dir);
}
}
closedir($handle);
return rmdir($dirName);
}
function show_input($value,$name) {
$select = "<ul onmouseover=\"altStyle(this);\"><li".($value == 0 ?" class=\"checked\"": "") ."><input class=\"radio\" type=\"radio\" name=\"".$name ."\" value=\"0\"".($value == 0 ?" checked": "") ."> ".cplang("pdnovel_collect_".$name ."_no") ."</li><li".($value == 1 ?" class=\"checked\"": "") ."><input class=\"radio\" type=\"radio\" name=\"".$name ."\" value=\"1\"".($value == 1 ?" checked": "") ."> ".cplang("pdnovel_collect_".$name ."_yes") ."</li>";
return $select;
}
function matchdomain($host) {
if (substr_count($host,".") == 1 ||(strpos($host,'.') === false)) {
$output = $host;
}elseif (substr_count($host,".") == 2) {
if (eregi("^[A-Za-z0-9\-]+\.(co|biz|com|edu|gov|info|mil|net|org)\.(ac|ad|ae|af|ag|ai|al|am|an|ao|aq|ar|as|at|au|aw|ax|az|ba|bb|bd|be|bf|bg|bh|bi|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|cr|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|ee|eg|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gg|gh|gi|gl|gm|gn|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|im|in|io|iq|ir|is|it|je|jm|jo|jp|ke|kg|kh|ki|km|kn|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|mv|mw|mx|my|mz|na|nc|ne|nf|ng|ni|nl|no|np|nr|nu|nz|om|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tl|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)$",$host)) {
$output = $host;
}else {
$host = explode(".",$host);
$output = $host[count($host)-2 ] .".".$host[count($host)-1 ];
}
}else {
if (eregi("^.*\.([A-Za-z0-9\-]+)\.(co|biz|com|edu|gov|info|mil|net|org)\.(ac|ad|ae|af|ag|ai|al|am|an|ao|aq|ar|as|at|au|aw|ax|az|ba|bb|bd|be|bf|bg|bh|bi|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|cr|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|ee|eg|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gg|gh|gi|gl|gm|gn|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|im|in|io|iq|ir|is|it|je|jm|jo|jp|ke|kg|kh|ki|km|kn|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|mv|mw|mx|my|mz|na|nc|ne|nf|ng|ni|nl|no|np|nr|nu|nz|om|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tl|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)$",$host,$output)) {
$host = explode(".",$host);
$output = $host[count($host)-3 ] .".".$host[count($host)-2 ] .".".$host[count($host)-1 ];
}else {
$output = $host;
}
}
return $output;
}
function get_contents($url,$cookie = '',$img = 0) {
global $pdnovel,$isimg,$getfun;
if (substr($url,0,4) != "http") {
$url = "http://".$url;
}while (empty($contents) &&$i <= 3) {
++$i;
if (!$getfun) {
$contents = dfsockopen($url,0,'',$cookie ,false,'',32);
}else {
eval('$contents = '.$getfun .'( $url );');
}
}
if ($img != 1 &&($pdnovel['utf8'] == '1')) {
if (function_exists('mb_convert_encoding')) {
$contents = mb_convert_encoding($contents,'GBK','UTF-8');
}else {
$contents = iconv('UTF-8','GBK',$contents);
}
}
return $contents;
}
function checkverify() {
global $_G;
$checkhost = parse_url($_G['siteurl']);
$host = $checkhost['host'];
$domainname = strtolower(matchdomain($host));
if (!defined('PDNOVEL_KEY')) {
include DISCUZ_ROOT .'./data/attachment/pdnovel/collect/pdnovel_key.php';
}
$key = PDNOVEL_KEY;
if ($key != md5('doItwell'.$domainname .'Author:Ludonghai')) {
cpmsg("pdnovel_index_keyerror","action=pdnovel&operation=index","error");
}
}
function get_matchone($rule,$source,$txt = 1) {
global $pdnovel;
if( $pdnovel['preg'] != 1 )
{
$i = strpos($rule,'[m]');
if ($i) {
$z = substr($rule,$i +3,1);
$rule = substr($rule,0,$i);
}
$str = array('[',']','.','$','+','|','^','/','{','}','(',')','<','>','*','?') ;
$replace = array('\[','\]','\.','\$','\+','\|','\^','\/','\{','\}','\(','\)','\<','\>','(.*)','.*');
$rule = str_replace($str,$replace,$rule) ;
$rule = '/'.$rule .'/i'.$z;
}
preg_match($rule,$source,$match);
if (!is_array($match)) {
return false;
}
if ($txt) {
$match[1] = trim(strip_tags($match[1]));
}
return $match[1];
}
function get_matchall($rule,$source) {
global $pdnovel;
if( $pdnovel['preg'] != 1 )
{
$i = strpos($rule,'[m]');
if ($i) {
$z = substr($rule,$i +3,1);
$rule = substr($rule,0,$i);
}
$str = array('[',']','.','$','+','|','^','/','{','}','(',')','<','>','*','?') ;
$replace = array('\[','\]','\.','\$','\+','\|','\^','\/','\{','\}','\(','\)','\<','\>','(.*)','.*');
$rule = str_replace($str,$replace,$rule) ;
$rule = '/'.$rule .'/i'.$z;
}
preg_match_all($rule,$source,$match,PREG_OFFSET_CAPTURE +PREG_SET_ORDER);
if (!is_array($match)) {
return false;
}
foreach ($match as $var) {
if (is_array($var)) {
$matchvar[] = $var[count($var) -1];
}else {
$matchvar[] = $var;
}
}
return $matchvar;
}
function get_txtvar($content) {
$pregstr = array("/(\r|\n)\\s+/","/\r|\n/","/\\<br[^\\>]*\\>/i","/\\<[\\s]*\\/p[\\s]*\\>/i","/\\<[\\s]*p[\\s]*\\>/i","/\\<script[^\\>]*\\>.*\\<\\/script\\>/is","/\\<[\\/\\!]*[^\\<\\>]*\\>/is","/&(quot|#34);/i","/&(amp|#38);/i","/&(lt|#60);/i","/&(gt|#62);/i","/&(nbsp|#160);/i","/&#(\\d+);/","/&([a-z]+);/i","/(\r\n){2,}/i");
$replacestr = array(" ","","\r\n","","\r\n","","","\"","&","<",">"," ","","","\r\n");
$content = preg_replace($pregstr,$replacestr,$content);
$content = strip_tags($content);
$content = str_replace("  "," ",$content);
$content = rtrim($content);
return $content;
}
function get_jsvar($content) {
$content = str_replace("\r\n","<br />",$content);
$content = str_replace("\r","<br />",$content);
$content = str_replace("\n","<br />",$content);
return $content;
}
function get_next($message,$url) {
$message = cplang($message);
$message = "<p class=\"ptop ptext\">".$message ."</p>";
$message .= "<p class=\"pbottom\"><a href=\"".$url ."\" class=\"lightlink\">".cplang("message_redirect") ."</a></p>";
$message .= "<script type=\"text/JavaScript\">setTimeout(\"redirect('".$url ."');\", 0);</script>";
echo "<script>var endcollect = 1;</script>";
echo $message;
exit();
}
function category_get_num($type,$catid) {
global $_G;
if (!in_array($type,array("pdnovel"))) {
return array();
}
loadcache($type ."category");
$category = $_G['cache'][$type ."category"];
$numkey = $type == "pdnovel"?"articles": "num";
if (!isset($_G[$type ."category_nums"])) {
$_G[$type ."category_nums"] = array();
$tables = array("pdnovel"=>"pdnovel_category");
$query = DB::query("SELECT catid, ".$numkey ." FROM ".DB::table($tables[$type]));
while ($value = DB::fetch($query)) {
$_G[$type ."category_nums"][$value['catid']] = intval($value[$numkey]);
}
}
$nums = $_G[$type ."category_nums"];
$num = intval($nums[$catid]);
if ($category[$catid]['children']) {
foreach ($category[$catid]['children'] as $id) {
$num += category_get_num($type,$id);
}
}
return $num;
}
function get_img($contents ,$fromurl) {
$froms = parse_url($fromurl);
$path = substr($fromurl,0 ,strrpos($froms['path'] ,'/'));
$pregstr = "/(<img\s*src\s*=\s*['|\"]{0,1}.+?['|\"]{0,1}.?>)/i";
preg_match_all($pregstr ,$contents ,$var);
for($i = 0 ;$i <count($var[1]) ;++$i) {
preg_match("/<img\s*src=['|\"]{0,1}.*([^\"'<>]+?)['|\"]{0,1}.*>/iU",$var[1][$i] ,$tmpimg);
$urs = parse_url($tmpimg[1]);
preg_match('/^(http:\/\/|ftp:\/\/|https:\/\/){0,1}([\w|-]+@){0,1}(([\w|-]*\.)*[\w|-]+\.\w{2,3}).*$/i',$tmpimg[1],$temp);
$host = $temp[3];
$url = $tmpimg[1];
if (!empty($urs['host']) ||substr($tmpimg[1],0,4) == 'http'||!empty($host)) {
$url = $tmpimg[1];
}elseif (substr($url,0,1) == "/") {
$url = $froms['host'] .$tmpimg[1];
}else {
$url = $tmpimg[1];
if (substr($fromurl ,-1 ,1) != "/") {
$tmpdir = dirname($fromurl);
}else {
$tmpdir = $fromurl;
}while (strpos($url,"../") === 0) {
$url = substr($url,3);
$tmpdir = dirname($tmpdir);
}
$url = $tmpdir ."/".$url;
}
$url = (substr($url,0,4) == 'http') ?$url : 'http://'.$url;
$contents = str_replace($tmpimg[0] ,'[img]'.$url .'[/img]',$contents);
}
return $contents;
}
function admincp_extent() {
global $_G;
$langpath = DISCUZ_ROOT .'./source/language/pdnovel/';
$cppath = DISCUZ_ROOT .'./source/module/pdnovel/';
$cpdir = dir($cppath);
while ($cpfile = $cpdir->read()) {
if (!in_array($cpfile,array('.','..')) &&preg_match("/^admincp\_([\w]+)\.php$/",$cpfile,$v) &&substr($cpfile,-4) == '.php'&&strlen($cpfile) <30 &&is_file($cppath .'/'.$cpfile) &&file_exists($langpath .'lang_'.$cpfile)) {
$v = $v[1];
include($langpath .'lang_'.$cpfile);
if (!empty($pdlang['menu']) &&substr(md5('pdvovel_extent:'.$pdlang['message']['name'] .$pdlang['message']['version']) ,8 ,16) == $pdlang['message']['key']) {
$pdlang['menu'][1] = empty($pdlang['menu'][1]) ?'pdnovel_'.$v : $pdlang['menu'][1];
$pdlang['operation'] = empty($pdlang['operation']) ?$v : $pdlang['operation'];
$extent[] = $pdlang;
$_G['lang']['admincp'] = array_merge($_G['lang']['admincp'],$pdlang['lang']);
}
}
}
return $extent;
}
function function_extent($function,$source) {
$temp = '';
if (function_exists($function)) {
eval('$temp = '.$function .'( $source );');
}
return empty($temp) ?$source : $temp;
}
?>