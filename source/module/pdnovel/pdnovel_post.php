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
if ( empty( $_G['uid'] ) )
{
		showmessage( "to_login", NULL, array( ), array( "showmsg" => TRUE, "login" => 1 ) );
}
$ac = $_G['gp_ac'];
if ( $ac == "newnovel" )
{
		if ( !checkperm( "allownewnovel" ) )
		{
				showmessage( "group_nopermission", NULL, array(
						"grouptitle" => $_G['group']['grouptitle']
				), array( "login" => 1 ) );
		}
		if ( !submitcheck( "postsubmit" ) )
		{
				$query1 = DB::query( "SELECT catid,upid,catname FROM ".DB::table( "novel_category" )." WHERE upid=0 ORDER BY displayorder ASC" );
				$selectcat = "<option value=\"\">".$pdlang['post_cat']."</option>";
				while ( $upcat = DB::fetch( $query1 ) )
				{
						$selectcat .= "<option value=\"".$upcat['catid']."\" disabled=\"disabled\">&nbsp;".$upcat['catname']."</option>";
						$query2 = DB::query( "SELECT catid,upid,catname FROM ".DB::table( "novel_category" ).( " WHERE upid=".$upcat['catid']." ORDER BY displayorder ASC" ) );
						while ( $cat = DB::fetch( $query2 ) )
						{
								$selectcat .= "<option value=\"".$cat['catid']."\">&nbsp;&nbsp;&gt;".$cat['catname']."</option>";
						}
				}
		}
		else
		{
				if ( $_G['gp_type'] )
				{
						$author = addslashes( $_G['gp_author'] );
						$authorid = DB::result_first( "SELECT authorid FROM ".DB::table( "novel_author" ).( " WHERE author='".$author."';" ) );
						if ( !$authorid )
						{
								DB::insert( "novel_author", array(
										"author" => $author
								) );
								$authorid = DB::insert_id( );
						}
				}
				else
				{
						$author = $_G['username'];
						$authorid = $_G['uid'];
				}
				$novel_data = array(
						"catid" => $_G['gp_catid'],
						"name" => addslashes( $_G['gp_name'] ),
						"initial" => get_initial( $_G['gp_name'] ),
						"postdate" => $_G['timestamp'],
						"lastupdate" => $_G['timestamp'],
						"keyword" => addslashes( $_G['gp_keyword'] ),
						"author" => $author,
						"authorid" => $authorid,
						"poster" => $_G['uid'],
						"posterid" => $_G['username'],
						"admin" => $_G['uid'],
						"adminid" => $_G['username'],
						"cover" => $_G['gp_cover'],
						"full" => $_G['gp_full'],
						"permission" => $_G['gp_permission'],
						"first" => $_G['gp_first'],
						"intro" => addslashes( $_G['gp_intro'] ),
						"type" => $_G['gp_type'],
						"dayvisit" => 1,
						"weekvisit" => 1,
						"monthvisit" => 1,
						"allvisit" => 1
				);
				DB::insert( "novel_novel", $novel_data );
				$novelid = DB::insert_id( );
				$savepath = floor( $novelid / 1000 );
				if ( !file_exists( "./novel/".$savepath ) )
				{
						mkdir( "./novel/".$savepath );
				}
				if ( !file_exists( "./novel/".$savepath."/".$novelid ) )
				{
						mkdir( "./novel/".$savepath."/".$novelid );
				}
				if ( $_G['gp_cover'] )
				{
						$imagetype = strtolower( strrchr( $_G['gp_cover'], "." ) );
						$coversave = "novel/".$savepath."/".$novelid."/cover-".rand( 100, 999 ).$imagetype;
						rename( $_G['gp_cover'], $coversave );
						DB::update( "novel_novel", array(
								"cover" => $coversave
						), "novelid=".$novelid." LIMIT 1" );
				}
				updatecreditbyaction( "novelpost", $_G['uid'] );
				showmessage( "do_success", "pdnovel.php?mod=view&novelid=".$novelid );
		}
}
else
{
		if ( $ac == "editnovel" )
		{
				$novelid = $_G['gp_novelid'];
				$novel = DB::fetch_first( "SELECT * FROM ".DB::table( "novel_novel" ).( " WHERE novelid=".$novelid." AND display=0 LIMIT 1" ) );
				if ( $_G['uid'] != $novel['adminid'] && $_G['uid'] != $novel['postid'] && !checkperm( "allowmanagenovel" ) )
				{
						showmessage( "group_nopermission", NULL, array(
								"grouptitle" => $_G['group']['grouptitle']
						), array( "login" => 1 ) );
				}
				if ( !submitcheck( "postsubmit" ) )
				{
						$query1 = DB::query( "SELECT catid,upid,catname FROM ".DB::table( "novel_category" )." WHERE upid=0 ORDER BY displayorder ASC" );
						$selectcat = "<option value=\"\">".$pdlang['post_cat']."</option>";
						while ( $upcat = DB::fetch( $query1 ) )
						{
								$selectcat .= "<option value=\"".$upcat['catid']."\" disabled=\"disabled\">&nbsp;".$upcat['catname']."</option>";
								$query2 = DB::query( "SELECT catid,upid,catname FROM ".DB::table( "novel_category" ).( " WHERE upid=".$upcat['catid']." ORDER BY displayorder ASC" ) );
								while ( $cat = DB::fetch( $query2 ) )
								{
										$selectcat .= "<option value=\"".$cat['catid']."\"".( $cat['catid'] == $novel['catid'] ? " selected=\"selected\"" : "" ).">&nbsp;&nbsp;&gt;".$cat['catname']."</option>";
								}
						}
				}
				else
				{
						if ( $_G['gp_cover'] != $novel['cover'] )
						{
								rename( $_G['gp_cover'], $novel['cover'] );
						}
						$novel_data = array(
								"catid" => $_G['gp_catid'],
								"name" => addslashes( $_G['gp_name'] ),
								"initial" => get_initial( $_G['gp_name'] ),
								"keyword" => addslashes( $_G['gp_keyword'] ),
								"author" => addslashes( $_G['gp_author'] ),
								"poster" => $_G['username'],
								"posterid" => $_G['uid'],
								"admin" => $_G['username'],
								"adminid" => $_G['uid'],
								"cover" => $_G['gp_cover'],
								"full" => $_G['gp_full'],
								"permission" => $_G['gp_permission'],
								"first" => $_G['gp_first'],
								"intro" => addslashes( $_G['gp_intro'] )
						);
						DB::update( "novel_novel", $novel_data, "novelid=".$novelid." LIMIT 1" );
						showmessage( "do_success", "pdnovel.php?mod=view&novelid=".$novelid );
				}
		}
		else if ( $ac == "newchapter" )
		{
				$novelid = $_G['gp_novelid'];
				$novel = DB::fetch_first( "SELECT * FROM ".DB::table( "novel_novel" ).( " WHERE novelid=".$novelid." AND display=0 LIMIT 1" ) );
				if ( $_G['uid'] != $novel['adminid'] && $_G['uid'] != $novel['postid'] && !checkperm( "allowmanagenovel" ) )
				{
						showmessage( "group_nopermission", NULL, array(
								"grouptitle" => $_G['group']['grouptitle']
						), array( "login" => 1 ) );
				}
				if ( submitcheck( "submit" ) )
				{
						$chapterorder = $novel['chapters'] + 1;
						$content = $_G['gp_chaptercontent'];
						exit( $content );
				}
		}
		else if ( $ac == "editchapter" )
		{
				$novelid = $_G['gp_novelid'];
				$novel = DB::fetch_first( "SELECT * FROM ".DB::table( "novel_novel" ).( " WHERE novelid=".$novelid." AND display=0 LIMIT 1" ) );
				if ( $_G['uid'] != $novel['adminid'] && $_G['uid'] != $novel['postid'] && !checkperm( "allowmanagenovel" ) )
				{
						showmessage( "group_nopermission", NULL, array(
								"grouptitle" => $_G['group']['grouptitle']
						), array( "login" => 1 ) );
				}
				$chapterid = $_G['gp_chapterid'];
				$chapter = DB::fetch_first( "SELECT * FROM ".DB::table( "novel_chapter" ).( " WHERE novelid=".$novelid.( " AND chapterid=".$chapterid." LIMIT 1" ) ) );
				if ( !submitcheck( "submit" ) )
				{
						$chaptercontent = file_get_contents( $chapter['chaptercontent'] );
						$chaptercontent = str_replace( "document.write('", "", $chaptercontent );
						$chaptercontent = str_replace( "');", "", $chaptercontent );
						$chaptercontent = str_replace( "<br>", "\r\n", $chaptercontent );
				}
				else
				{
						if ( $chapter[chaptertype] == 0 )
						{
								$content = $_G['gp_chaptercontent'];
								$content = stripslashes( stripslashes( $content ) );
								$lastchaptercontent = mb_substr( strip_tags( $content ), 0, 300, "utf-8" );
								$content = str_replace( "\r\n", "<br>", $content );
								$chapterwords = ceil( strlen( $content ) / 2 );
								$chapter_data = array(
										"lastupdate" => $_G['timestamp'],
										"chaptername" => addslashes( $_G['gp_chaptername'] ),
										"chaptertype" => 0,
										"chapterwords" => $chapterwords
								);
								DB::update( "novel_chapter", $chapter_data, "novelid=".$novelid.( " AND chapterid=".$chapterid." LIMIT 1" ) );
								$fp = @fopen( $chapter['chaptercontent'], "wb" );
								@fwrite( $fp, $content );
								fclose( $fp );
								$words = $novel['words'] + $chapterwords - $chapter['chapterwords'];
								DB::update( "novel_novel", array(
										"words" => $words,
										"lastchaptercontent" => addslashes( $lastchaptercontent ),
										"lastchapter" => addslashes( $_G['gp_chaptername'] )
								), "novelid=".$novelid." LIMIT 1" );
						}
						else
						{
								DB::update( "novel_chapter", array(
										"chaptername" => addslashes( $_G['gp_chaptername'] )
								), "novelid=".$novelid.( " AND chapterid=".$chapterid." LIMIT 1" ) );
								DB::update( "novel_novel", array(
										"lastvolume" => addslashes( $_G['gp_chaptername'] )
								), "novelid=".$novelid." LIMIT 1" );
						}
						showmessage( "do_success", "pdnovel.php?mod=read&novelid=".$novelid.( "&chapterid=".$chapterid ) );
				}
		}
}
loadcache( "diytemplatename" );
include( template( "diy:novel/novel_post" ) );
?>