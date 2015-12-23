<?php
/*********************/
/*                   */
/*  Version : 5.1.0  */
/*  Author  : LiBaiWei     */
/*  Comment : 071223 */
/*                   */
/*********************/

if ( !defined( "IN_DISCUZ" ) )
{
		exit( "Access Denied" );
}
loadcache( "pdnovelcategory" );
$ncc = $_G['cache']['pdnovelcategory'];
$novelid = $_G['gp_novelid'];
$chapterid = $_G['gp_chapterid'] ? $_G['gp_chapterid'] : 0;
is_numeric( $novelid );
if(!is_numeric($chapterid)){
		showmessage( $lang['novelid_error'] );
}
$novel = DB::fetch_first("SELECT * FROM ".DB::table('pdnovel_view')." WHERE novelid=$novelid AND display=0 LIMIT 1");
if ( !$novel )
{
		showmessage( $lang['novel_error'] );
}
if ( $novel['vip'] == 0 )
{
		if ( !checkperm('pdnovelread') )
		{
				showmessage( "group_nopermission", NULL, array(
				"grouptitle" => $_G['group']['grouptitle']
				), array( "login" => 1 ) );
		}
		pdupdatecredit( 'pdnovelread', $lang['read_no_credit']);
}
else
{
		if ( !checkperm('pdnovelvipread') )
		{
				showmessage( "group_nopermission", NULL, array(
				"grouptitle" => $_G['group']['grouptitle']
				), array( "login" => 1 ) );
		}
		pdupdatecredit( 'pdnovelvipread', $lang['vipread_no_credit']);
}		
$catname = $ncc[$novel['catid']]['catname'];
$novel['upid'] = $ncc[$novel['catid']]['upid'];
$upname = $ncc[$novel['upid']]['catname'];
$novel['lastupdate'] = strftime( "%Y-%m-%d %X", $novel['lastupdate'] );
if( $chapterid){
	$read = DB::fetch_first( "SELECT * FROM ".DB::table( "pdnovel_chapter" ).( " WHERE chapterid=".$chapterid." LIMIT 1" ) );
}
else
{
		$read = DB::fetch_first( "SELECT * FROM ".DB::table( "pdnovel_chapter" ).( " WHERE novelid=".$novelid." LIMIT 1" ) );
}

if ( !$read )
{
		showmessage( $lang['novel_error'] );
}

$chapterid = $read['chapterid'];
$read['lastupdate'] = strftime( "%Y-%m-%d %X", $read['lastupdate'] );
$pre = DB::result_first( "SELECT chapterid FROM ".DB::table( "pdnovel_chapter" ).( " WHERE chapterid<".$chapterid.( " AND novelid=".$novelid." ORDER BY chapterid DESC LIMIT 1" ) ) );
$next = DB::result_first( "SELECT chapterid FROM ".DB::table( "pdnovel_chapter" ).( " WHERE chapterid>".$chapterid.( " AND novelid=".$novelid." ORDER BY chapterid ASC LIMIT 1" ) ) );
if ( $pdmodule['rewrite'] != 0 )
{
		$prepage = $pre ? "novel-read-".$novel['novelid'].( "-".$pre.".html" ) : "novel-chapter-".$novel['novelid'].".html";
		$nextpage = $next ? "novel-read-".$novel['novelid'].( "-".$next.".html" ) : "novel-chapter-".$novel['novelid'].".html";
}
else
{
		$prepage = $pre ? "pdnovel.php?mod=read&novelid=".$novel['novelid'].( "&chapterid=".$pre ) : "pdnovel.php?mod=chapter&novelid=".$novel['novelid'];
		$nextpage = $next ? "pdnovel.php?mod=read&novelid=".$novel['novelid'].( "&chapterid=".$next ) : "pdnovel.php?mod=chapter&novelid=".$novel['novelid'];
}
$navtitle = $read['chaptername']." - ".$novel['name']." - ".$catname." - ".$upname." - ".$navtitle;
$metakeywords = $read['chaptername'].",".$novel['name'].",".$novel['author'].",".$novel['keywords'];
pdupdateclick( $novelid, $novel['lastvisit'] );
loadcache( "diytemplatename" );
//$read['chaptercontent'] = file_get_contents( "data/attachment/pdnovel/chapter/".$read['chaptercontent'] );
$read['chaptercontent'] = DB::result_first('SELECT text FROM '.DB::table('pdnovel_text_'.$read['tableid'])." WHERE id=$chapterid");

//fix imgurl
if($_G['config']['tsdm']['novelurl']){
	$read['chaptercontent'] = fixImgurl_inread($read['chaptercontent'],$_G['config']['tsdm']['novelurl']);
}

if( defined( "IN_MOBILE" ) )
{
		$words = !empty($_G['gp_words']) ? $_G['gp_words'] : getcookie( 'words' );
		$words = !empty($words) ? $words : 1000;
		$page = !empty($_G['gp_page']) ? $_G['gp_page'] : 1;
		$wordstart = ( $page - 1 ) * $words;
		dsetcookie( 'words' , $words );
		$read['chaptercontent'] = str_replace( "document.write('", "", $read['chaptercontent'] );
		$read['chaptercontent'] = str_replace( "');", "", $read['chaptercontent'] );
		$read['chaptercontent'] = str_replace( "<br />", "\r\n", $read['chaptercontent'] );
		$multi = mobilemulti( mb_strlen($read['chaptercontent'],CHARSET), $words, $page, "qxs.php?mod=read&novelid=$novelid&chapterid=".$read['chapterid'] );
		$read['chaptercontent'] = mb_substr( $read['chaptercontent'], $wordstart, $words, CHARSET );
		$read['chaptercontent'] = str_replace( "\r\n", "</p><p class=\"pdp\">", $read['chaptercontent'] );
		$read['chaptercontent'] = str_replace( "[img]", "<img src='", $read['chaptercontent'] );
		$read['chaptercontent'] = str_replace( "[/img]", "'/>", $read['chaptercontent'] );
		include( template( "diy:pdnovel/read" ) );
}
$read['chaptercontent'] = str_replace( "document.write('", "", $read['chaptercontent'] );
$read['chaptercontent'] = str_replace( "');", "", $read['chaptercontent'] );
$read['chaptercontent'] = str_replace( "<br />", "\r\n", $read['chaptercontent'] );
$read['chaptercontent'] = str_replace( "\r\n", "</p><p class=\"pdp\">", $read['chaptercontent'] );
$read['chaptercontent'] = str_replace( "[img]", "<img src='", $read['chaptercontent'] );
$read['chaptercontent'] = str_replace( "[/img]", "' onload=\"if(this.width>850)this.width=850\" style=\"margin-bottom:20px\" onclick=\"zoom(this, this.src, 0, 0, 0)\" />", $read['chaptercontent'] );
if ( $pdmodule['rewrite'] != 0 )
{
		include( template( "diy:pdnovel/rewrite/read" ) );
}
else
{
		include( template( "diy:pdnovel/read" ) );
}


function fixImgurl_inread($str, $picstart){
	return str_replace('data/attachment/',$picstart.'/data/attachment/',$str);
}

?>