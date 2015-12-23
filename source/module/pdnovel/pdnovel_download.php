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
if ( !checkperm('pdnoveldown') )
{
		showmessage( "group_nopermission", NULL, array(
		"grouptitle" => $_G['group']['grouptitle']
		), array( "login" => 1 ) );
}
include_once('source/class/class_zip.php') ;
$zip = new zipfile();
$novelid = $_G['gp_novelid'];
$novel = DB::fetch_first( "SELECT name,author,lastupdate,intro FROM ".DB::table( "pdnovel_view" ).( " WHERE novelid=".$novelid."  AND display=0  LIMIT 1" ) );

if ( !$novel )
{
	showmessage( $lang['novel_error'] );
}
loadcache( "diytemplatename" );
if( in_array( $_G['gp_type'] , array( 'txt' , 'zip' , 'chapter' ) ) )
{
		pdupdatecredit( 'pdnoveldown', $lang['download_no_credit'] );
}
else
{
		include( template( "diy:pdnovel/donwload" ) );
		exit();
}
$chapterpath = "data/attachment/pdnovel/chapter/";
if($_G['gp_type'] != 'chapter')
{
		$query = DB::query( "SELECT * FROM ".DB::table( "pdnovel_chapter" ).( " WHERE novelid=".$novelid ) );
		$contents = "《".$novel[name]."》作者:".$novel['author']."\r\n\r\n简介:\r\n\r\n"  . $novel['intro']. "\r\n\r\n";
		while ( $chapter = DB::fetch( $query ) )
		{
				$chapter['lastupdate'] = strftime( "%Y-%m-%d %X", $chapter['lastupdate'] );
				$content = file_get_contents( $chapterpath.$chapter['chaptercontent'] );
				$content = str_replace( "document.write('", "", $content );
				$content = str_replace( "');", "", $content );
				$content = str_replace( "<br>", "\r\n", $content );
				$content = str_replace( "<br />", "\r\n", $content );
				$content = $novel['name']." ".$chapter['chaptername']."\r\n".$lang['chapter_updatetime'].$chapter['lastupdate']." ".$lang['chapter_words'].$chapter['chapterwords']."\r\n\r\n".$content."\r\n\r\n";
				$contents .= $content;
		}
}
if($_G['gp_type']=='zip')
{
		$zip->addFile($contents,$novel['name'] . '.txt');
		$type  = 'zip';
}
elseif($_G['gp_type']=='chapter')
{
		$query = DB::query( "SELECT * FROM ".DB::table( "pdnovel_chapter" ).( " WHERE novelid=".$novelid ) );
		$zip->addFile("《".$novel[name]."》作者:".$novel['author'] ."\r\n\r\n简介:\r\n\r\n" . $novel['intro'],'作品相关.txt');
		while ( $chapter = DB::fetch( $query ) )
		{
				++$i;   
				$chapter['lastupdate'] = strftime( "%Y-%m-%d %X", $chapter['lastupdate'] );
				$content = file_get_contents( $chapterpath.$chapter['chaptercontent'] );
				$content = str_replace( "document.write('", "", $content );
				$content = str_replace( "');", "", $content );
				$content = str_replace( "<br>", "\r\n", $content );
				$content = str_replace( "<br />", "\r\n", $content );
				$content = $novel['name']." ".$chapter['chaptername']."\r\n".$lang['chapter_updatetime'].$chapter['lastupdate']." ".$lang['chapter_words'].$chapter['chapterwords']."\r\n\r\n".$content."\r\n\r\n";
				$zip->addFile($content,'第'.$i .'章.txt');
		}
		$type  = 'zip';
}
else
{ 
		$type  = 'txt';
}
$type = $_G['gp_type'] == 'chapter' ? 'zip' : $_G['gp_type'];
ob_end_clean( );
$readmod = getglobal( "config/download/readmod" );
$readmod = 0 < $readmod && $readmod < 5 ? $readmod : 2;
$name = "\"".( strtolower( CHARSET ) == "utf-8" && strexists( $_SERVER['HTTP_USER_AGENT'], "MSIE" ) ? urlencode( $novel['name'].'.' .$type ) : $name )."\"";
dheader( "Date: ".gmdate( "D, d M Y H:i:s", $novel['lastupdate'] )." GMT" );
dheader( "Last-Modified: ".gmdate( "D, d M Y H:i:s", $novel['lastupdate'] )." GMT" );
dheader( "Content-Encoding: none" );
dheader( "Content-Disposition: attachment; filename=" .$novel['name'].'.' .$type );
dheader( "Content-Type: application/pdnovel-donwload" );
if($type == 'zip)
{
		echo $zip->file();
}
else
{
		dheader( "Content-Length: ".strlen($contents) );
		echo $contents;
}
?>
