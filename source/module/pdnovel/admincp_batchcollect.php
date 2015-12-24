<?php

if(!defined('IN_DISCUZ') ||!defined('IN_ADMINCP')) {exit('Access Denied');
}
@include_once( DISCUZ_ROOT.'./data/attachment/pdnovel/collect/pdnovel_key.php');
$checkhost = parse_url($_G['siteurl']);
$checkdomain = strtolower(matchdomain($checkhost['host']));
$checkstr = substr( md5( $checkdomain.'checkpdkey2you7'),8,16 );
$keydomain = array( 'http://huan.poudu.net/pdgetkey.php?mod=pdnovel&domain='.$checkdomain.'&check='.$checkstr ,'http://www.huiwei100.com/pdgetkey.php?mod=pdnovel&domain='.$checkdomain.'&check='.$checkstr,'http://www.cxapp.com/pdgetkey.php?mod=pdnovel&domain='.$checkdomain.'&check='.$checkstr);
if( !DOMAIN ||strlen($temp = get_contents($keydomain[DOMAIN])) != 32 )
{
foreach( $keydomain as $k =>$v)
{
$temp = get_contents($v);
if ( strlen( $temp ) == 32 )
{
$result = @file_put_contents( 'data/attachment/pdnovel/collect/pdnovel_key.php','<?php'."\r\ndefine('PDNOVEL_KEY', '".$temp."');\r\ndefine('DOMAIN','".$k."');\r\n?>");
break;
}
}
}
if($temp != md5('doItwell'.$checkdomain.'Author:Ludonghai'))
{
cpmsg( 'pdnovel_index_keyerror','action=pdnovel&operation=index','error');
}
$key = substr( md5( $temp ) ,4 ,8);
if ( $do == 'show')
{
shownav( 'pdnovel','pdnovel_batchcollect');
echo "<form name=\"cpform\" method=\"post\" autocomplete=\"off\" action=\"pdnovel.php?mod=batch&do=pcollect&key=$key\" id=\"cpform\" ><table class=\"tb tb2 \">\r\n        ";
showtableheader( );
showtitle( "pdnovel_collect_page");
$optselect = "<select name=\"siteid\">";
$query = DB::query( "SELECT * FROM ".DB::table( "pdnovel_collect")." WHERE enable=1 ORDER BY displayorder");
while ( $pdnovelcollect = DB::fetch( $query ) )
{
$optselect .= "<option value=\"".$pdnovelcollect['siteid']."\">".$pdnovelcollect['sitename']."</option>";
}
$optselect .= "</select>";
showsetting( "pdnovel_collect_fromsite","","",$optselect );
showsetting( "pdnovel_collect_collectpage","collectpage","","text");
showsetting( "pdnovel_collect_startpage","startpage","","text");
showsetting( "pdnovel_collect_endpage","endpage","","text");
$pagenew = 0;
showsetting( "pdnovel_collect_method","","",show_input( $method,"method") );
showsubmit( "submit");
showtablefooter( );
showformfooter( );
echo "<form name=\"cpform\" method=\"post\" autocomplete=\"off\" action=\"pdnovel.php?mod=batch&do=ncollect&key=$key\" id=\"cpform\" ><table class=\"tb tb2 \">\r\n        ";
showtableheader( );
showtitle( "pdnovel_collect_number");
showsetting( "pdnovel_collect_fromsite","","",$optselect );
showsetting( "pdnovel_collect_startid","startid","","text");
showsetting( "pdnovel_collect_endid","endid","","text");
showsetting( "pdnovel_collect_method","","",show_input( $method,"method") );
showsubmit( "submit");
showtablefooter( );
showformfooter( );
echo "<form name=\"cpform\" method=\"post\" autocomplete=\"off\" action=\"pdnovel.php?mod=batch&do=localcollect&key=$key\" id=\"cpform\" ><table class=\"tb tb2 \">\r\n        ";
showtitle( "pdnovel_collect_local");
showsetting( "pdnovel_collect_fromsite","","",$optselect );
showsetting( "pdnovel_collect_startid","startid","","text");
showsetting( "pdnovel_collect_endid","endid","","text");
echo "<input type=\"hidden\" name=\"method\" value=\"1\" />";
showsubmit( "submit");
showtablefooter( );
showformfooter( );
echo "<form name=\"cpform\" method=\"post\" autocomplete=\"off\" action=\"pdnovel.php?mod=batch&do=lcollect&key=$key\" id=\"cpform\" ><table class=\"tb tb2 \">\r\n        ";
showtitle( "pdnovel_collect_list");
showsetting( "pdnovel_collect_fromsite","","",$optselect );
showsetting( "pdnovel_collect_batchids","batchids","","textarea");
showsetting( "pdnovel_collect_method","","",show_input( $method,"method") );
showsubmit( "submit");
showtablefooter( );
showformfooter( );
}
?>