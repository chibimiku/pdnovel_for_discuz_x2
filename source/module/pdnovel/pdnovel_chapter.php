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
if ( !is_numeric( $novelid ) )
{
		showmessage( $lang['pdnovelid_error'] );
}
$novel = DB::fetch_first( "SELECT * FROM ".DB::table( "pdnovel_view" ).( " WHERE novelid=".$novelid." AND display=0" ) );
if ( !$novel )
{
		showmessage( $lang['pdnovel_error'] );
}
$catname = $ncc[$novel['catid']]['catname'];
$novel['upid'] = $ncc[$novel['catid']]['upid'];
$upname = $ncc[$novel['upid']]['catname'];
$novel['lastupdate'] = strftime( "%Y-%m-%d %X", $novel['lastupdate'] );
$volumechapter = "";
$volumenum = 0;
$vquery = DB::query( "SELECT volumeid,volumename,volumeorder FROM ".DB::table( "pdnovel_volume" ).( " WHERE novelid=".$novelid." ORDER BY volumeorder ASC,volumeid ASC" ) );
if( defined( "IN_MOBILE" ) )
{
		$page = $_G['gp_page'] ? $_G['gp_page'] : 1;
		$perpage = 20;
		$limit_start = $perpage * ( $page - 1 );
		$chaptercount = DB::result_first( "SELECT count(*) FROM ".DB::table( "pdnovel_chapter" )." WHERE novelid=$novelid;" );
		while ( $volume = DB::fetch( $vquery ) )
		{
				$cquery = DB::query( "SELECT chapterid,chaptername,chapterorder FROM ".DB::table( "pdnovel_chapter" ).( " WHERE novelid=".$novelid.( " AND volumeid=".$volume['volumeid']." ORDER BY chapterorder ASC,chapterid ASC" ) ) );
				while ( $chapter = DB::fetch( $cquery ) )
				{
						$tempay[] = $chapter;
				}
		}
		for( $i = $limit_start; $i < $perpage+$limit_start && $i < count($tempay); ++$i)
		{
				$chapteray[] = $tempay[$i];
		}
		unset($tempay);
		$multi = multipage( $chaptercount, $perpage, $page, "qxs.php?mod=chapter&novelid=$novelid" );
		include( template( "diy:pdnovel/chapter" ) );
}
while ( $volume = DB::fetch( $vquery ) )
{
		++$volumenum;
		$volumechapter .= "<div class=\"contenttitle\"><h2><span>".$volumenum."</span>¡¶".$novel[name]."¡·".$volume[volumename]."</h2></div><div class=\"contentlist\"><ul>";
		$cquery = DB::query( "SELECT chapterid,chaptername,chapterorder FROM ".DB::table( "pdnovel_chapter" ).( " WHERE novelid=".$novelid.( " AND volumeid=".$volume['volumeid']." ORDER BY chapterorder ASC,chapterid ASC" ) ) );
		while ( $chapter = DB::fetch( $cquery ) )
		{
				$chapter['lastupdate'] = strftime( "%Y-%m-%d %X", $chapter['lastupdate'] );
				if ( $pdmodule['rewrite'] != 0 )
				{
						$volumechapter .= "<li style=\"width:25%;\"><a href=\"novel-read-".$novelid."-".$chapter[chapterid].".html\" title=\"".$chapter[chaptername]."  ".$pdlang[chapter_words].$chapter[chapterwords]."  ".$pdlang[chapter_updatetime].$chapter[lastupdate]."\">".$chapter[chaptername]."</a></li>";
				}
				else
				{
						$volumechapter .= "<li style=\"width:25%;\"><a href=\"pdnovel.php?mod=read&novelid=".$novelid."&chapterid=".$chapter[chapterid]."\" title=\"".$chapter[chaptername]."  ".$pdlang[chapter_words].$chapter[chapterwords]."  ".$pdlang[chapter_updatetime].$chapter[lastupdate]."\">".$chapter[chaptername]."</a></li>";
				}
		}
		$volumechapter .= "</ul></div>";
}
$navtitle = $novel['name']." - ".$catname." - ".$upname." - ".$navtitle;
$metakeywords = $novel['name'].",".$novel['author'].",".$novel['keywords'];
$metadescription = substr( $novel['intro'], 0, 200 );
pdupdateclick( $novelid, $novel['lastvisit'] );
include( template( "diy:pdnovel/chapter" ) );
?>