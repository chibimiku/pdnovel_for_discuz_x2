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
$coverpath = "data/attachment/pdnovel/cover/";
$page = $_G['gp_page'] ? $_G['gp_page'] : 1;
$perpage = 10;
$limit_start = $perpage * ( $page - 1 );
$ac = empty( $_G['gp_ac'] ) ? ( !empty( $_G['gp_author'] ) ? 'author' : 'name') : $_G['gp_ac'];
$srchtxt = $keyword = trim( $_G['gp_srchtxt'] );
if ( empty( $keyword ) && submitcheck( "searchsubmit", 1 ) )
{
		dheader( "Location: pdnovel.php?mod=search".( empty( $ac ) ? "" : "&ac=".$ac ) );
}
if ( $ac == "author" )
{
		$query = DB::query( "SELECT * FROM ".DB::table( "pdnovel_view" ).( " WHERE display=0 AND author like '%".$keyword."%' ORDER BY lastupdate DESC LIMIT {$limit_start},{$perpage}" ) );
		$num = DB::result_first( "SELECT count(novelid) FROM ".DB::table( "pdnovel_view" ).( " WHERE display=0 AND author like '%".$keyword."%'" ) );
}
else if ( $ac == "name" )
{
		$query = DB::query( "SELECT * FROM ".DB::table( "pdnovel_view" ).( " WHERE display=0 AND name like '%".$keyword."%' ORDER BY lastupdate DESC LIMIT {$limit_start},{$perpage}" ) );
		$num = DB::result_first( "SELECT count(novelid) FROM ".DB::table( "pdnovel_view" ).( " WHERE display=0 AND name like '%".$keyword."%'" ) );
}
else if ( $ac == "authorid" )
{
		$query = DB::query( "SELECT * FROM ".DB::table( "pdnovel_view" ).( " WHERE display=0 AND authorid='".$keyword."' ORDER BY lastupdate DESC LIMIT {$limit_start},{$perpage}" ) );
		$num = DB::result_first( "SELECT count(novelid) FROM ".DB::table( "pdnovel_view" ).( " WHERE display=0 AND authorid='".$keyword."'" ) );
}
$novellist = array( );
while ( $novel = DB::fetch( $query ) )
{
		$novel['full'] = $novel['full'] == 1 ? $lang['full'] : $lang['nofull'];
		$novel['catname'] = $ncc[$novel['catid']]['catname'];
		$novel['lastupdate'] = strftime( "%Y-%m-%d %X", $novel['lastupdate'] );
		$novellist[] = $novel;
}
$mpurl = "pdnovel.php?mod=search&ac=".$ac."&srchtxt=".urlencode( $keyword ) ."&searchsubmit=yes";
$multi = defined( "IN_MOBILE" ) ? multipage( $num, $perpage, $page, $mpurl ) : $multi = multi( $num, $perpage, $page, $mpurl );
include_once( template( "pdnovel/search" ) );
?>