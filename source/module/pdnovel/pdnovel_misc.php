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
define( "NOROBOT", TRUE );
if ( $_G['gp_ac'] == "vote" )
{
		$novelid = $_G['gp_novelid'];
		if ( !checkperm('pdnovelvote') )
		{
				showmessage( "group_nopermission", NULL, array(
				"grouptitle" => $_G['group']['grouptitle']
				), array( "login" => 1 ) );
		}
		$novel = DB::fetch_first( "SELECT * FROM ".DB::table( "pdnovel_view" ).( " WHERE novelid=".$novelid." AND display=0 LIMIT 1" ) );
		if ( !$novel )
		{
				showmessage( $lang['pdnovel_error'] );
		}
		$find = DB::result_first( "SELECT uid FROM ".DB::table( "pdnovel_vote" ).( " WHERE uid='".$_G['uid']."' AND novelid='{$novelid}'" ) );
		if ( $find )
		{
				showmessage( $lang['vote_haven'] );
		}
		$setarr = array(
				"novelid" => $novelid,
				"uid" => $_G['uid'],
				"dateline" => $_G['timestamp']
		);
		DB::insert( "pdnovel_vote", $setarr );
		$now = time( );
		$voteupdate = "lastvote=".$now.",allvote=allvote+1";
		pdupdatecredit( 'pdnovelvote', $lang['credit_shortage'], '');
		if ( date( "d", $novel['lastvote'] ) == date( "d", $now ) )
		{
				$voteupdate .= ",dayvote = dayvote+1";
		}
		else
		{
				$voteupdate .= ",dayvote = 1";
		}
		if ( date( "W", $novel['lastvote'] ) == date( "W", $now ) )
		{
				$voteupdate .= ",weekvote = weekvote+1";
		}
		else
		{
				$voteupdate .= ",weekvote = 1";
		}
		if ( date( "m", $novel['lastvote'] ) == date( "m", $now ) )
		{
				$voteupdate .= ",monthvote = monthvote+1";
		}
		else
		{
				$voteupdate .= ",monthvote = 1";
		}
		DB::query( "UPDATE LOW_PRIORITY ".DB::table( "pdnovel_view" ).( " SET ".$voteupdate." WHERE novelid={$novelid}" ), "UNBUFFERED" );
		showmessage( $lang['vote_succeed'] );
}
else if ( $_G['gp_ac'] == "mark" )
{
		if ( !checkperm('pdnovelmark') )
		{
				showmessage( "group_nopermission", NULL, array(
				"grouptitle" => $_G['group']['grouptitle']
				), array( "login" => 1 ) );
		}
		$novelid = $_G['gp_novelid'];
		$chapterid = $_G['gp_chapterid'] ? $_G['gp_chapterid'] : 0;
		$novel = DB::fetch_first( "SELECT * FROM ".DB::table( "pdnovel_view" ).( " WHERE novelid=".$novelid." AND display=0 LIMIT 1" ) );
		if ( !$novel )
		{
				showmessage( $lang['pdnovel_error'] );
		}
		$find = DB::result_first( "SELECT uid FROM ".DB::table( "pdnovel_mark" ).( " WHERE uid='".$_G['uid']."' AND novelid='{$novelid}'" ) );
		if ( $find )
		{
				if ( $chapterid )
				{
						DB::query( "UPDATE ".DB::table( "pdnovel_mark" ).( " SET chapterid=".$chapterid." WHERE uid='{$_G['uid']}' AND novelid='{$novelid}'" ) );
				}
				else
				{
						showmessage( $lang['mark_haven'] );
				}
		}
		else
		{
				pdupdatecredit( 'pdnovelmark', $lang['credit_shortage'], '');
				$setarr = array(
						"novelid" => $novelid,
						"uid" => $_G['uid'],
						"chapterid" => $chapterid,
						"dateline" => $_G['timestamp']
				);
				DB::insert( "pdnovel_mark", $setarr );
				DB::query( "UPDATE LOW_PRIORITY ".DB::table( "pdnovel_view" ).( " SET allmark=allmark+1 WHERE novelid=".$novelid ), "UNBUFFERED" );
		}
		showmessage( $lang['mark_succeed'] );
}
else if ( $_G['gp_ac'] == "rate" )
{
		$balance = getuserprofile( "extcredits".$_G['setting']['creditstransextra'][1] );
		if ( !submitcheck( "ratesubmit" ) )
		{
				$novelid = $_G['gp_novelid'];
				if ( $balance == 0 )
				{
						showmessage( $lang['rate_no_credit'] );
				}
				$leftbalance = $balance - 1;
				include_once( template( "pdnovel/rate" ) );
		}
		else
		{
				$credits = $_G['gp_credits'];
				if ( $balance < $credits )
				{
						showmessage( $lang['credit_shortage'] );
				}
				$novelid = $_G['gp_novelid'];
				$novel = DB::fetch_first( "SELECT * FROM ".DB::table( "pdnovel_view" ).( " WHERE novelid=".$novelid." AND display=0;" ) );
				if ( !$novel )
				{
						showmessage( "undefined_action", NULL );
				}
				else if ( $novel['posterid'] == $_G['uid'] )
				{
						showmessage( "thread_rate_member_invalid", NULL );
				}
				DB::query( "INSERT INTO ".DB::table( "pdnovel_rate" ).( " (novelid, uid, username, credits, dateline) VALUES (".$novelid.", {$_G['uid']}, '{$_G['username']}', {$credits}, {$_G['timestamp']})" ), "UNBUFFERED" );
				DB::query( "UPDATE LOW_PRIORITY ".DB::table( "pdnovel_view" ).( " SET rate=rate+".$credits." WHERE novelid={$novelid}" ), "UNBUFFERED" );
				updatemembercount( $_G['uid'], array(
						$_G['setting']['creditstransextra'][1] => 0 - $credits
				), 1, "BAC", $novelid );
				updatemembercount( $novel['posterid'], array(
						$_G['setting']['creditstransextra'][1] => $credits
				), 1, "BAC", $novelid );
				if ( $_POST['message'] )
				{
						$message = getstr( $_POST['message'], 600, 1, 1, 1, 0 );
						$message = "<font color=red>".$message."</font>";
						$message = censor( $message );
						if ( censormod( $message ) )
						{
								$comment_status = 1;
						}
						else
						{
								$comment_status = 0;
						}
						$setarr = array(
								"uid" => $_G['uid'],
								"username" => $_G['username'],
								"novelid" => $novelid,
								"postip" => $_G['onlineip'],
								"dateline" => $_G['timestamp'],
								"status" => $comment_status,
								"message" => $message
						);
						DB::insert( "pdnovel_comment", $setarr );
						DB::query( "UPDATE ".DB::table( "pdnovel_view" ).( " SET comments=comments+1 WHERE novelid=".$novelid ) );
						DB::update( "common_member_status", array(
								"lastpost" => $_G['timestamp']
						), array(
								"uid" => $_G['uid']
						) );
				}
				showmessage( "do_success", "pdnovel.php?mod=view&novelid=".$novelid );
		}
}
else if ( $_G['gp_ac'] == "star" )
{

		$novelid = $_G['gp_novelid'];
		$novel = DB::fetch_first( "SELECT * FROM ".DB::table( "pdnovel_view" ).( " WHERE novelid=".$novelid." AND display=0 LIMIT 1" ) );
		if ( !$novel )
		{
				showmessage( $lang['pdnovel_error'] );
		}
		if ( !checkperm('pdnovelstar') )
		{
				showmessage( "group_nopermission", NULL, array(
				"grouptitle" => $_G['group']['grouptitle']
				), array( "login" => 1 ) );
		}
		if ( $novel['posterid'] == $_G['uid'] )
		{
				showmessage( "click_no_self" );
		}
		if ( $_G['gp_op'] == "add" )
		{
				$find = DB::result_first( "SELECT uid FROM ".DB::table( "pdnovel_star" ).( " WHERE uid='".$_G['uid']."' AND novelid='{$novelid}'" ) );
				if ( $find )
				{
						showmessage( $lang['star_haven'] );
				}
				pdupdatecredit( 'pdnovelstar', $lang['credit_shortage'], '');
				$clickid = $_G['gp_clickid'];
				$setarr = array(
						"novelid" => $novelid,
						"clickid" => $clickid,
						"uid" => $_G['uid'],
						"dateline" => $_G['timestamp']
				);
				DB::insert( "pdnovel_star", $setarr );
				DB::query( "UPDATE ".DB::table( "pdnovel_view" ).( " SET click=click+1, click".$clickid."=click{$clickid}+1 WHERE novelid={$novelid}" ) );
				showmessage( $lang['star_succeed'] );
		}
		else if ( $_G['gp_op'] == "show" )
		{
				$percentage = $width = array( );
				$i = 1;
				for ( ;	$i < 6;	++$i	)
				{
						$percentage[$i] = round( $novel["click".$i] * 100 / $novel[click], 1 );
						$width[$i] = ceil( $percentage[$i] * 0.7 ) + 1;
						$sum_score += $novel["click".$i] * $i;
				}
				$novel_score = round( $sum_score * 2 / $novel[click], 1 );
				$current_rating = $novel_score * 10;
				$current_i = ceil( $novel_score / 2 );
				$current_text = array(
						$lang['star_0'],
						$lang['star_2'],
						$lang['star_4'],
						$lang['star_6'],
						$lang['star_8'],
						$lang['star_10']
				);
				include_once( template( "common/header_ajax" ) );
				include_once( template( "pdnovel/ajax_star" ) );
				include_once( template( "common/footer_ajax" ) );
		}
}
else if ( $_G['gp_ac'] == "upload" )
{
	
		if ( $_G['gp_inajax'] != "yes" )
		{
				$imgexts = "jpg, jpeg, gif, png, bmp";
				include( template( "pdnovel/upload" ) );
		}
		else
		{
				if ( !in_array(strrchr(strtolower($_FILES['file']['name']), "."), array(".gif", ".jpg", ".jpeg", ".bmp", ".png")) )
				{
						novel_upload_error( $upload->error( ) );
				}
				if( $version == 'X2.5')
				{
						require_once( "source/class/discuz/discuz_upload.php" );
				}
				elseif( $version == 'X2')
				{
						require_once( "source/class/class_upload.php");
				}
				$upload = new discuz_upload( );
				$upload->init( $_FILES['file'] );
				$attach = $upload->attach;
				if ( !$upload->error( ) )
				{
						$upload->save( );
				}
				if ( $upload->error( ) )
				{
						novel_upload_error( $upload->error( ) );
				}
				if ( $attach )
				{
						echo "data/attachment/temp/".$attach['attachment'];
				}
		}
}
?>