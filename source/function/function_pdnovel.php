<?php
/*********************/
/*                   *//*  Version : 5.1.0  *//*  Author  : RM     */
/*  Last Modify  : gool  */
/*  Comment : 071223 */
/*                   */
/*********************/
if ( !defined( "IN_DISCUZ" ) )
{
		exit( "Access Denied" );
}
function multipage( $num, $perpage, $curpage, $mpurl )
{
		global $realpages;
		global $lang;
		$show = defined( "IN_MOBILE" ) ? 5 : 10;
		$realpages = 1;
		if ( $perpage < $num )
		{
				$realpages = @ceil( $num / $perpage );
				if ( $realpages < $show )
				{
						$from = 1;
						$to = $realpages;
				}
				else
				{
						$from = $curpage - floor( $show / 2 );
						$to = $from + $show - 1;
						if ( $from < 1 )
						{
								$to = $curpage + 1 - $from;
								$from = 1;
								if ( $to - $from < $show )
								{
										$to = $show;
								}
						}
						else if ( $realpages < $to )
						{
								$from = $realpages - $show - 1;
								$to = $realpages;
						}
				}
				$multipage = "<a href=\"".$mpurl."&page=1\">".$lang['list_index_page']."</a>|";
				$i = $from;
				for ( ;	$i <= $to; ++$i	)
				{
						if( $i == 0){continue;}
						$multipage .= $i == $curpage ? "<strong><a href=\"".$mpurl."&page=".$i."\" class=\"f_s\">".$i."</a></strong>" : "<a href=\"".$mpurl."&page=".$i."\" class=\"f_a\">".$i."</a>";
				}
				$multipage .= "|<a href=\"".$mpurl."&page=".$realpages."\">".$lang['list_end_page']."<form action=\"$mpurl\" method=\"GET\" style=\"margin:0px;display: inline\"><input type=\"text\" name=\"page\" size=\"2\"> ".$lang['list_page']." <input type=\"submit\" class=\"button\" value=\"GO\" /></form>&nbsp;".$curpage."/".$realpages."页";
				$multipage = "<div class=\"storelistbottom\">".$multipage."</div>";
		}
		return $multipage;
}

function mobilemulti( $num, $perpage, $curpage, $mpurl )
{
		global $realpages,$nextpage;
		global $lang;
		$show = 5;
		$realpages = 1;
		if ( $perpage < $num )
		{
				$realpages = @ceil( $num / $perpage );
				if ( $realpages < $show )
				{
						$from = 1;
						$to = $realpages;
				}
				else
				{
						$from = $curpage - floor( $show / 2 );
						$to = $from + $show - 1;
						if ( $from < 1 )
						{
								$to = $curpage + 1 - $from;
								$from = 1;
								if ( $to - $from < $show )
								{
										$to = $show;
								}
						}
						else if ( $realpages < $to )
						{
								$from = $realpages - $show - 1;
								$to = $realpages;
						}
				}
				$i = $from;
				for ( ;	$i <= $to;	++$i	)
				{
						if( $i == 0){continue;}
						$multipage .= $i == $curpage ? "<strong><a href=\"".$mpurl."&page=".$i."\" class=\"f_s\">".$i."</a></strong>" : "<a href=\"".$mpurl."&page=".$i."\" class=\"f_a\">".$i."</a>";
				}
				$multipage = "<div class=\"storelistbottom\">".$multipage;
				if ( $realpages >= ($curpage+1) )
				{
						$multipage .= "<a href=\"".$mpurl."&page=".($curpage+1)."\" class=\"f_a\">下一页</a>";
				}
				else
				{
						$multipage .= "<a href=\"$nextpage\" class=\"f_a\">下一章</a>";
				}
				$multipage .= "</div>";
		}
		return $multipage;
}
function pdupdateclick( $novelid, $lastvisit )
{
		$now = time( );
		$visitupdate = "lastvisit=".$now.",allvisit=allvisit+1";
		if ( date( "d", $lastvisit ) == date( "d", $now ) )
		{
				$visitupdate .= ",dayvisit = dayvisit+1";
		}
		else
		{
				$visitupdate .= ",dayvisit = 1";
		}
		if ( date( "W", $lastvisit ) == date( "W", $now ) )
		{
				$visitupdate .= ",weekvisit = weekvisit+1";
		}
		else
		{
				$visitupdate .= ",weekvisit = 1";
		}
		if ( date( "m", $lastvisit ) == date( "m", $now ) )
		{
				$visitupdate .= ",monthvisit = monthvisit+1";
		}
		else
		{
				$visitupdate .= ",monthvisit = 1";
		}
		DB::query( "UPDATE LOW_PRIORITY ".DB::table( "pdnovel_view" ).( " SET ".$visitupdate." WHERE novelid={$novelid}" ), "UNBUFFERED" );
}

function mysql2html( $message )
{
		$message = str_replace( " ", "&nbsp;", $message );
		$message = nl2br( $message );
		return $message;
}

function get_initial( $str )
{
		$str = str_replace( array( "《", "》", "<", ">" ), "", $str );
		$asc = ord( substr( $str, 0, 1 ) );
		if ( $asc < 160 )
		{
				if ( 48 <= $asc && $asc <= 57 )
				{
						return "1";
				}
				if ( 65 <= $asc && $asc <= 90 )
				{
						return chr( $asc );
				}
				if ( 97 <= $asc && $asc <= 122 )
				{
						return chr( $asc - 32 );
				}
				return "1";
		}
		$asc = $asc * 1000 + ord( substr( $str, 1, 1 ) );
		if ( 176161 <= $asc && $asc < 176197 )
		{
				return "A";
		}
		if ( 176197 <= $asc && $asc < 178193 )
		{
				return "B";
		}
		if ( 178193 <= $asc && $asc < 180238 )
		{
				return "C";
		}
		if ( 180238 <= $asc && $asc < 182234 )
		{
				return "D";
		}
		if ( 182234 <= $asc && $asc < 183162 )
		{
				return "E";
		}
		if ( 183162 <= $asc && $asc < 184193 )
		{
				return "F";
		}
		if ( 184193 <= $asc && $asc < 185254 )
		{
				return "G";
		}
		if ( 185254 <= $asc && $asc < 187247 )
		{
				return "H";
		}
		if ( 187247 <= $asc && $asc < 191166 )
		{
				return "J";
		}
		if ( 191166 <= $asc && $asc < 192172 )
		{
				return "K";
		}
		if ( 192172 <= $asc && $asc < 194232 )
		{
				return "L";
		}
		if ( 194232 <= $asc && $asc < 196195 )
		{
				return "M";
		}
		if ( 196195 <= $asc && $asc < 197182 )
		{
				return "N";
		}
		if ( 197182 <= $asc && $asc < 197190 )
		{
				return "O";
		}
		if ( 197190 <= $asc && $asc < 198218 )
		{
				return "P";
		}
		if ( 198218 <= $asc && $asc < 200187 )
		{
				return "Q";
		}
		if ( 200187 <= $asc && $asc < 200246 )
		{
				return "R";
		}
		if ( 200246 <= $asc && $asc < 203250 )
		{
				return "S";
		}
		if ( 203250 <= $asc && $asc < 205218 )
		{
				return "T";
		}
		if ( 205218 <= $asc && $asc < 206244 )
		{
				return "W";
		}
		if ( 206244 <= $asc && $asc < 209185 )
		{
				return "X";
		}
		if ( 209185 <= $asc && $asc < 212209 )
		{
				return "Y";
		}
		if ( 212209 <= $asc )
		{
				return "Z";
		}
		return "1";
}

function getstr( $string, $length, $in_slashes = 0, $out_slashes = 0, $bbcode = 0, $html = 0 )
{
		global $_G;
		$string = trim( $string );
		if ( $in_slashes )
		{
				$string = dstripslashes( $string );
		}
		if ( $html < 0 )
		{
				$string = preg_replace( "/(\\<[^\\<]*\\>|\r|\n|\\s|\\[.+?\\])/is", " ", $string );
		}
		else if ( $html == 0 )
		{
				$string = dhtmlspecialchars( $string );
		}
		if ( $length )
		{
				$string = cutstr( $string, $length );
		}
		if ( $bbcode )
		{
				require_once( DISCUZ_ROOT."./source/class/class_bbcode.php" );
				$bb =& bbcode::instance( );
				$string = $bb->bbcode2html( $string, $bbcode );
		}
		if ( $out_slashes )
		{
				$string = daddslashes( $string );
		}
		return trim( $string );
}
function pdupdatecredit( $action, $message='', $url='home.php?mod=spacecp&ac=credit&op=rule')
{
		global $_G;
		$credit = DB::fetch_first("SELECT * FROM " . DB::table("common_member_count") . " WHERE uid=" . $_G['uid']);
		$credits = DB::fetch_first("SELECT * FROM " . DB::table("common_credit_rule") . " WHERE action='$action'");
		$creditid = 1;
		$message = empty($message) ? lang('credit_shortage') : $message;
		while( $creditid <= 8 )
		{
				if( $credits['extcredits'.$creditid] < 0 && ( $credits['extcredits'.$creditid] + $credit['extcredits'.$creditid] ) < 0 )
				{
						showmessage( $message, $url);
				}
				$creditid++;
		}
		updatecreditbyaction( $action , $_G['uid'] );	
}
?>
