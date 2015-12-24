<?php

if(!defined('IN_DISCUZ') ||!defined('IN_ADMINCP')) {
exit('Access Denied');
}
ignore_user_abort(0);
@set_time_limit(0);
if ( $do == "show")
{
if ( !submitcheck( "collectsubmit") ){
	@include_once( DISCUZ_ROOT.'./data/attachment/pdnovel/collect/pdnovel_key.php');
	$checkhost = parse_url($_G['siteurl']);
	$checkdomain = strtolower(matchdomain($checkhost['host']));
	$checkstr = substr( md5( $checkdomain.'checkpdkey2you7'),8,16 );
	$keydomain = array( 'http://huan.poudu.net/pdgetkey.php?mod=pdnovel&domain='.$checkdomain.'&check='.$checkstr ,'http://www.huiwei100.com/pdgetkey.php?mod=pdnovel&domain='.$checkdomain.'&check='.$checkstr,'http://www.cxapp.com/pdgetkey.php?mod=pdnovel&domain='.$checkdomain.'&check='.$checkstr);
	if( !DOMAIN ||strlen($temp = get_contents($keydomain[DOMAIN])) != 32 ){
		foreach( $keydomain as $k =>$v){
			$temp = get_contents($v);
			if ( strlen( $temp ) == 32 ){
				$result = @file_put_contents( 'data/attachment/pdnovel/collect/pdnovel_key.php','<?php'."\r\ndefine('PDNOVEL_KEY', '".$temp."');\r\ndefine('DOMAIN','".$k."');\r\n?>");
			break;
			}
		}
	}
/*
if($temp != md5('doItwell'.$checkdomain.'Author:Ludonghai')){
	cpmsg( 'pdnovel_index_keyerror','action=pdnovel&operation=index','error');
}
*/
shownav( "pdnovel","pdnovel_collect");
showformheader( "pdnovel&operation=collect");
showtableheader( );
showtitle( "pdnovel_collect");
$optselect = "<select name=\"siteid\">";
$query = DB::query( "SELECT * FROM ".DB::table( "pdnovel_collect")." WHERE enable=1 ORDER BY displayorder");
while ( $pdnovelcollect = DB::fetch( $query ) ){
	$optselect .= "<option value=\"".$pdnovelcollect['siteid']."\">".$pdnovelcollect['sitename']."</option>";
}
$optselect .= "</select>";
showsetting( "pdnovel_collect_fromsite","","",$optselect );
showsetting( "pdnovel_collect_fromid","fromid","","text");
showsubmit( "collectsubmit");
showtablefooter( );
showformfooter( );
}
else
{
$siteid = $_G['gp_siteid'];
include_once( DISCUZ_ROOT."./data/attachment/pdnovel/collect/site_".$siteid.".php");
if ( $_G['gp_fromid'] )
{
$fromid = $_G['gp_fromid'];
$novel = DB::fetch_first( "SELECT * FROM ".DB::table( "pdnovel_view").( " WHERE siteid=".$siteid.( " AND fromid=".$fromid." LIMIT 1;") ) );
if ( $novel )
{
$novelid = $novel['novelid'];
$catid = $novel['catid'];
$name = $novel['name'];
$cover = $novel['cover'];
$keyword = $novel['keyword'];
$intro = $novel['intro'];
$notice = $novel['notice'];
$author = $novel['author'];
$premission = $novel['premission'];
$first = $novel['first'];
$full = $novel['full'];
if ( $full != 1 )
{
$url = str_replace( "<{novelid}>",$fromid,$pdnovel['url'] );
$url = str_replace( "<{subnovelid}>",$subnovelid,$url );
$source = get_contents( $url );
if ( $source )
{
$full = "";
$pregstr = $pdnovel['full'];
if ( !empty( $pregstr ) )
{
$matchvar = get_matchone( $pregstr,$source );
if ( !empty( $matchvar ) )
{
$full = $matchvar == $pdnovel['fullnovel'] ?1 : 0;
}
}
DB::update( "pdnovel_view",array(
"full"=>$full
),"novelid=".$novelid );
}
}
}
else
{
if ( empty( $pdnovel['name'] ) ||empty( $pdnovel['url'] ) )
{
cpmsg( "pdnovel_collect_error","","error");
}
$url = str_replace( "<{novelid}>",$fromid,$pdnovel['url'] );
if ( !empty( $pdnovel['subnovelid'] ) )
{
$subnovelid = 0;
$pdnovel['subnovelid'] = str_replace( "<{novelid}>",$fromid,$pdnovel['subnovelid'] );
eval( "\$subnovelid = ".$pdnovel['subnovelid'].";");
$url = str_replace( "<{subnovelid}>",$subnovelid,$url );
}
$source = get_contents( $url );
if ( $source )
{
$source = function_extent( 'viewstart',$source );
$name = "";
$pregstr = $pdnovel['name'];
if ( !empty( $pregstr ) )
{
$matchvar = get_matchone( $pregstr,$source );
if ( !empty( $matchvar ) )
{
$name = $matchvar;
}
}
$author = "";
$pregstr = $pdnovel['author'];
if ( !empty( $pregstr ) )
{
$matchvar = get_matchone( $pregstr,$source );
if ( !empty( $matchvar ) )
{
$author = $matchvar;
}
}
$cat = $catid = "";
$pregstr = $pdnovel['cat'];
if ( !empty( $pregstr ) )
{
$matchvar = get_matchone( $pregstr,$source );
if ( !empty( $matchvar ) )
{
$cat = $matchvar;
$catid = empty( $pdnovel['catid'][$cat] ) ?$pdnovel['catid']['default'] : $pdnovel['catid'][$cat];
}
}
$checkid = DB::result_first( "SELECT novelid FROM ".DB::table( "pdnovel_view").( " WHERE name='".$name.( "' AND author='".$author."'") ) );
if ( $checkid )
{
cpmsg( cplang( "pdnovel_collect_exist")."<br><br><a href=\"admin.php?action=pdnovel&operation=collect&do=update&novelid=".$novelid."\">".cplang( "pdnovel_collect_next")."</a>","","succeed");
}
$cover = "";
$pregstr = $pdnovel['cover'];
if ( substr( $pregstr,0,4 ) == "http")
{
$cover = str_replace( "<{novelid}>",$fromid,$pregstr );
if ( !empty( $pdnovel['subnovelid'] ) )
{
$cover = str_replace( "<{subnovelid}>",$subnovelid,$cover );
}
$pregstr = "";
}
if ( !empty( $pregstr ) )
{
$matchvar = get_matchone( $pregstr,$source );
if ( !empty( $matchvar ) )
{
$cover = $matchvar;
}
}
if ( !in_array( strrchr( strtolower( $cover ),"."),array( ".gif",".jpg",".jpeg",".bmp",".png") ) )
{
$cover = "";
}
if ( !empty( $cover ) &&!empty( $pdnovel['coverfilter'] ) )
{
$cover = str_ireplace( $pdnovel['coverfilter'],"",$cover );
}
if ( substr( $cover,0,4 ) !== "http")
{
if ( !empty( $pdnovel['coversite'] ) )
{
$cover = $pdnovel['coversite'].$cover;
}
else if ( substr( $cover,0,1 ) == "/")
{
$matches = array( );
preg_match( "/https?:\\/\\/[^\\/]+/is",$url,$matches );
if ( !empty( $matches[0] ) )
{
$cover = $matches[0].$cover;
}
else
{
$cover = $pdnovel['siteurl'].$cover;
}
}
else
{
$tmpdir = dirname( $url );
while ( strpos( $cover,"../") === 0 )
{
$tmpdir = dirname( $tmpdir );
$cover = substr( $cover,3 );
}
$cover = $tmpdir."/".$cover;
}
}
if ( strpos( $pdnovel['coverkeycancel'],$cover ) !== FALSE )
{
$cover = "";
}
$keyword = "";
$pregstr = $pdnovel['keyword'];
if ( !empty( $pregstr ) )
{
$matchvar = get_matchone( $pregstr,$source );
if ( !empty( $matchvar ) )
{
$keyword = $matchvar;
}
}
$intro = "";
$pregstr = $pdnovel['intro'];
if ( !empty( $pregstr ) )
{
$matchvar = get_matchone( $pregstr,$source,0 );
if ( !empty( $matchvar ) )
{
$intro = get_txtvar( $matchvar );
}
}
$notice = "";
$pregstr = $pdnovel['notice'];
if ( !empty( $pregstr ) )
{
$matchvar = get_matchone( $pregstr,$source );
if ( !empty( $matchvar ) )
{
$notice = $matchvar;
}
}
$full = "";
$pregstr = $pdnovel['full'];
if ( !empty( $pregstr ) )
{
$matchvar = get_matchone( $pregstr,$source );
if ( !empty( $matchvar ) )
{
$full = $matchvar == $pdnovel['fullnovel'] ?1 : 0;
}
}
$permission = $pdnovel['permission'];
$first = $pdnovel['first'];
$source = function_extent( 'viewend',$source );
}
else
{
cpmsg( "pdnovel_collect_infoerror","","error");
}
}
}
else
{
cpmsg( "pdnovel_collect_fromiderror","","error");
}
shownav( "pdnovel","pdnovel_collect");
showformheader( "pdnovel&operation=collect&do=collect");
showtableheader( );
showtitle( "pdnovel_collect_new");
$query = DB::query( "SELECT catid,catname,upid,displayorder FROM ".DB::table( "pdnovel_category")." ORDER BY displayorder ASC");
$catselect = "<select name=\"catid\">";
$novelcats = array( );
while ( $novelcat = DB::fetch( $query ) )
{
$novelcats[] = $novelcat;
}
if ( is_array( $novelcats ) )
{
foreach ( $novelcats as $cat1 )
{
if ( !$cat1['upid'] )
{
$selected = $cat1['catid'] == $catid ?"selected=\"selected\"": NULL;
$catselect .= "<option value=\"".$cat1['catid'].( "\" ".$selected.">{$cat1['catname']}</option>\n");
foreach ( $novelcats as $cat2 )
{
if ( $cat2['upid'] >0  &&$cat2['upid'] == $cat1['catid']  )
{
$selected = $cat2['catid'] == $catid ?"selected=\"selected\"": NULL;
$catselect .= "<option value=\"".$cat2['catid'].( "\" ".$selected.">  > {$cat2['catname']}</option>\n");
}
}
}
}
}
$catselect .= "</select>";
showhiddenfields( array(
"siteid"=>$_G['gp_siteid'],
"fromid"=>$_G['gp_fromid'],
"novelid"=>$novelid
) );
showsetting( "pdnovel_collect_catname","","",$catselect );
showsetting( "pdnovel_collect_name","name",$name,"text");
showsetting( "pdnovel_collect_initial","initial",get_initial( $name ),"text");
showsetting( "pdnovel_collect_author","author",$author,"text");
showsetting( "pdnovel_collect_cover","cover",$cover,"text");
showsetting( "pdnovel_collect_full","","",show_input( $full,"full") );
showsetting( "pdnovel_collect_permission","","",show_input( $permission,"permission") );
showsetting( "pdnovel_collect_first","","",show_input( $first,"first") );
showsetting( "pdnovel_collect_keyword","keyword",$keyword,"text");
showsetting( "pdnovel_collect_notice","notice",$notice,"text");
showsetting( "pdnovel_collect_intro","intro",$intro,"textarea");
showsubmit( "collectsubmit");
}
}
else if ( $do == "collect")
{
if ( $_G['gp_novelid'] )
{
$novel_data = array(
"catid"=>$_G['gp_catid'],
"name"=>addslashes( $_G['gp_name'] ),
"initial"=>$_G['gp_initial'],
"lastupdate"=>$_G['timestamp'],
"keyword"=>addslashes( $_G['gp_keyword'] ),
"notice"=>addslashes( $_G['gp_notice'] ),
"cover"=>$_G['gp_cover'],
"full"=>$_G['gp_full'],
"permission"=>$_G['gp_permission'],
"first"=>$_G['gp_first'],
"intro"=>addslashes( $_G['gp_intro'] )
);
$novelid = $_G['gp_novelid'];
DB::update( "pdnovel_view",$novel_data,"novelid=".$novelid." LIMIT 1");
}
else
{
$author = addslashes( $_G['gp_author'] );
$authorid = DB::result_first( "SELECT authorid FROM ".DB::table( "pdnovel_author").( " WHERE author='".$author."';") );
if ( !$authorid )
{
DB::insert( "pdnovel_author",array(
"author"=>$author
) );
$authorid = DB::insert_id( );
}
$novel_data = array(
"catid"=>$_G['gp_catid'],
"name"=>addslashes( $_G['gp_name'] ),
"initial"=>$_G['gp_initial'],
"postdate"=>$_G['timestamp'],
"lastupdate"=>$_G['timestamp'],
"keyword"=>addslashes( $_G['gp_keyword'] ),
"notice"=>addslashes( $_G['gp_notice'] ),
"author"=>$author,
"authorid"=>$authorid,
"poster"=>$_G['username'],
"posterid"=>$_G['uid'],
"admin"=>$_G['username'],
"adminid"=>$_G['uid'],
"cover"=>'',
"full"=>$_G['gp_full'],
"permission"=>$_G['gp_permission'],
"first"=>$_G['gp_first'],
"intro"=>addslashes( $_G['gp_intro'] ),
"type"=>1,
"siteid"=>$_G['gp_siteid'],
"fromid"=>$_G['gp_fromid']
);
DB::insert( "pdnovel_view",$novel_data );
$novelid = DB::insert_id( );
$subnovelid = floor( $novelid / 1000 );
$coverpath = DISCUZ_ROOT."./data/attachment/pdnovel/cover/";
dmkdir( $coverpath.$subnovelid );
$cover = $_G['gp_cover'];
if ( !empty( $cover ) )
{
$covercontent = get_contents( $cover,'',1 );
if ( !empty( $covercontent ) )
{
$coversave = $subnovelid."/".$novelid."-".rand( 100,999 ).".jpg";
@file_put_contents( $coverpath.$coversave,$covercontent );
DB::update( "pdnovel_view",array(
"cover"=>$coversave
),"novelid=".$novelid." LIMIT 1");
}
}
}
cpmsg( cplang( "pdnovel_collect_infosuccess")."<br><br><a href=\"admin.php?action=pdnovel&operation=collect&do=update&novelid=".$novelid."\">".cplang( "pdnovel_collect_next")."</a>","","succeed");
}
else if ( $do == "update")
{
echo "\r\n<script>\r\nvar thisurl = document.URL;\r\n</script>\r\n";
$pdmodule = DB::fetch_first( "select * from ".DB::table( "pdmodule_view") );
$novelid = $_G['gp_novelid'];
$novel = DB::fetch_first( "SELECT * FROM ".DB::table( "pdnovel_view").( " WHERE novelid=".$novelid.";") );
$fromid = $novel['fromid'];
$siteid = $novel['siteid'];
include_once( DISCUZ_ROOT."./data/attachment/pdnovel/collect/site_".$siteid.".php");
$chapterpath = DISCUZ_ROOT."./data/attachment/pdnovel/chapter/";
$name = $novel['name'];
$chapters = $novel['chapters'];
$volumeorder = $novel['volumes'];
ob_end_flush( );
$time = $_G['timestamp'];
echo "<h3>".cplang( "discuz_message")."</h3><style type=\"text/css\">p{background:#F2F9FD;line-height:22px;padding-left:20px;}.ptop{border-top:4px solid #DEEFFA;}.pbottom{border-bottom:4px solid #DEEEFA;}.ptext{line-height:30px;color:#009900;font-size:14px;font-weight:700;}</style>";
$url = str_replace( "<{novelid}>",$fromid,$pdnovel['chapterurl'] );
if ( !empty( $pdnovel['subnovelid'] ) )
{
$subnovelid = 0;
$pdnovel['subnovelid'] = str_replace( "<{novelid}>",$fromid,$pdnovel['subnovelid'] );
eval( "\$subnovelid = ".$pdnovel['subnovelid'].";");
$url = str_replace( "<{subnovelid}>",$subnovelid,$url );
}
$source = get_contents( $url );
$source = function_extent( 'liststart',$source );
if ( $novel['lcid'] )
{
$pregstr = str_replace( "<{lastid}>",$novel['lcid'],$pdnovel['lastchapter'] );
$source = get_matchone( $pregstr,$source,0 );
}
echo '<script type="text/javascript" src="static/js/pdnovelcp.js"></script>';
if ( $source )
{
$chapterary = array( );
$pregstr = $pdnovel['chapter'];
$matchvar = get_matchall( $pregstr,$source );
if ( $matchvar )
{
if ( is_array( $matchvar ) )
{
$chapterary = $matchvar;
}
$chapteridary = array( );
$pregstr = $pdnovel['chapterid'];
$matchvar = get_matchall( $pregstr,$source );
if ( is_array( $matchvar ) )
{
$chapteridary = $matchvar;
}
$volumeary = array( );
$pregstr = $pdnovel['volume'];
$matchvar = get_matchall( $pregstr,$source );
if ( is_array( $matchvar ) )
{
$volumeary = $matchvar;
}
else
{
if ( !$novel['lastchapterid'] )
{
$volumeary[] = array( 0 =>"&Otilde;&yacute;&Icirc;&Auml;",1 =>0 );
}
}
if ( !$novel['lastchapterid'] &&$chapterary[0][1] <$volumeary[0][1] )
{
$volumeary = array_merge( array(array( 0 =>"&Otilde;&yacute;&Icirc;&Auml;",1 =>0 )),$volumeary);
}
}
else
{
cpmsg( "pdnovel_collect_chaptererror","","error");
}
}
$fromrows = array( );
$i = 0;
$j = 0;
$k = 0;
$chapternum = count( $chapterary );
$volumenum = count( $volumeary );
$allnum = $chapternum +$volumenum;
echo cplang( "pdnovel_collect_leftname").$name.cplang( "pdnovel_collect_allchapter").$allnum.cplang( "pdnovel_collect_chapters");
ob_flush( );
flush( );
while ( $j <$chapternum )
{
++$i;
if ( $j <$chapternum )
{
$a = $chapterary[$j][1];
}
else
{
$a = 99999999;
}
if ( $k <$volumenum )
{
$b = $volumeary[$k][1];
}
else
{
$b = 99999999;
}
if ( $a <$b )
{
$tmpvar = trim( $chapterary[$j][0] );
if ( $tmpvar != "")
{
$chaptername = addslashes( $tmpvar );
$url = str_replace( "<{novelid}>",$fromid,$pdnovel['readurl'] );
$url = str_replace( "<{chapterid}>",$chapteridary[$j][0],$url );
if ( !empty( $pdnovel['subnovelid'] ) )
{
$url = str_replace( "<{subnovelid}>",$subnovelid,$url );
}
if ( !empty( $pdnovel['subchapterid'] ) )
{
$subchapterid = 0;
$chapterid = $chapteridary[$j][0];
$tmpstr = "\$subchapterid = ".$pdnovel['subchapterid'].";";
eval( $tmpstr );
$url = str_replace( "<{subchapterid}>",$subchapterid,$url );
}
$filetype = strtolower( strrchr( $url,".") );
$content = get_contents( $url );
$content = function_extent( 'readstart',$content );
$pregstr = $pdnovel['content'];
$content = get_matchone( $pregstr,$content,0 );
if ( !empty( $pdnovel['replace'] ) )
{
$content = str_replace( $pdnovel['replace'],$pdnovel['contentfile'],$content );
}
if ( !empty( $pdnovel['pregreplace'] ) )
{
$content = preg_replace( $pdnovel['pregreplace'],$pdnovel['pregcontentfilter'],$content );
}
$lastchaptercontent = addslashes( cutstr( strip_tags( get_txtvar( $content ) ),600 ) );
$chapterwords = ceil( strlen( $content ) / 2 );
if ( !$startvolume )
{
$start = DB::fetch_first( "SELECT volumeid,chapterorder FROM ".DB::table( "pdnovel_chapter").( " WHERE chapterid=".$novel['lastchapterid'] ) );
$volumeid = $start['volumeid'];
$chapterorder = $start['chapterorder'];
}
$chapterorder += 1;
$chapter_data = array(
"novelid"=>$novelid,
"volumeid"=>$volumeid,
"poster"=>$_G['username'],
"posterid"=>$_G['uid'],
"postdate"=>$time,
"lastupdate"=>$time,
"chaptername"=>$chaptername,
"chapterorder"=>$chapterorder,
"chapterwords"=>$chapterwords
);
DB::insert( "pdnovel_chapter",$chapter_data );
$chapterid = DB::insert_id( );
$content = function_extent( 'imgstart',$content );
if ( $pdmodule['icollect'] == 1 )
{
$content = get_img( $content,$url );
}
if ( $pdmodule['localimg'] == 1 )
{
$imgay = get_matchall( "[img]*[/img][m]U",$content );
foreach ( $imgay as $ik =>$var )
{
DB::insert( "pdnovel_image",array(
"novelid"=>$novelid,
"chapterid"=>$chapterid
) );
$imgid = DB::insert_id( );
$subimgid = floor( $imgid / 1000 );
$subsubimgid = floor( $subimgid / 1000 );
$imgpath = "data/attachment/pdnovel/img/".$novelid."/".$subsubimgid."/".$subimgid."/".$imgid.".jpg";
dmkdir( dirname( $imgpath ) );
$data = get_contents( $var[0] ,'',1 );
file_put_contents( $imgpath ,$data );
$content = str_replace( "[img]".$var[0]."[/img]","[img]".$imgpath."[/img]",$content );
echo '<p>      &Iacute;¨º&sup3;&Eacute;&micro;&Uacute;'.($ik+1).'&cedil;&ouml;&Iacute;&frac14;&AElig;&not;&sup2;&Eacute;&frac14;&macr;</p>';
ob_flush( );
flush( );
}
}
$content = get_txtvar( $content );
$content = get_jsvar( $content );
$content .= $pdnovel['addcontents'];
$subchapterid = floor( $chapterid / 1000 );
$subsubchapterid = floor( $subchapterid / 1000 );
$content = function_extent( 'writestart',$content );
dmkdir( $chapterpath.$subsubchapterid."/".$subchapterid );
$chaptercontent = $subsubchapterid."/".$subchapterid."/".$chapterid."-".rand( 100,999 ).".txt";
@file_put_contents( $chapterpath.$chaptercontent,$content );
$lcid = $chapteridary[$j][0];
DB::update( "pdnovel_chapter",array(
"chaptercontent"=>$chaptercontent
),"chapterid=".$chapterid );
DB::query( "UPDATE ".DB::table( "pdnovel_volume").( " SET volumechapters=volumechapters+1, volumewords=volumewords+".$chapterwords.( " WHERE volumeid=".$volumeid ) ) );
DB::query( "UPDATE ".DB::table( "pdnovel_view").( " SET chapters=chapters+1, words=words+".$chapterwords.( ", lastupdate=".$time.", lastchapter='{$chaptername}', lastchapterid={$chapterid}, lastchaptercontent='{$lastchaptercontent}', lcid={$lcid} WHERE novelid={$novelid}") ) );
}
++$j;
echo cplang( "pdnovel_collect_doneleft").$i.cplang( "pdnovel_collect_doneright").$novelid."&chapterid=".$chapterid."\" target=\"_blank\">".$chaptername."</a></p>";
ob_flush( );
flush( );
}
else
{
$tmpvar = trim( $volumeary[$k][0] );
$startvolume = 1;
if ( $tmpvar != "")
{
$volumeorder += 1;
$volumename = addslashes( $tmpvar );
$volume_data = array(
"novelid"=>$novelid,
"volumename"=>$volumename,
"volumeorder"=>$volumeorder
);
$volumeid = DB::insert( "pdnovel_volume",$volume_data,1 );
$chapterorder = 0;
DB::query( "UPDATE ".DB::table( "pdnovel_view").( " SET volumes=volumes+1, lastvolume='".$volumename.( "', lastvolumeid=".$volumeid." WHERE novelid={$novelid}") ) );
}
++$k;
echo cplang( "pdnovel_collect_doneleft").$i.cplang( "pdnovel_collect_doneright").$novelid."&chapterid=".$chapterid."\" target=\"_blank\">".$volumename."</a></p>";
ob_flush( );
flush( );
}
}
echo cplang( "pdnovel_collect_alldoneleft").$name.cplang( "pdnovel_collect_alldoneright");
ob_flush( );
flush( );
ob_end_flush( );
}
?>