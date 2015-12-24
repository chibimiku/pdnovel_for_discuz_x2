<?php

if(!defined('IN_DISCUZ') ||!defined('IN_ADMINCP')) {
exit('Access Denied');
}
shownav( 'pdnovel','index');
if ( $do == 'show')
{
@include_once( DISCUZ_ROOT.'./source/module/'.$action.'/pdnovel_version.php');
@include_once( DISCUZ_ROOT.'./data/attachment/pdnovel/collect/pdnovel_key.php');
showsubmenu( 'pdnovel_welcome',array(
array( 'system_detail','pdnovel&operation=index',1 ),
array( 'pdnovel_index_getkey','pdnovel&operation=index&do=getkey',0 )
) );
showtableheader( 'pdnovel_sys_info','fixpadding');
showtablerow( '',array(
'class="vtop td24 lineheight"',
'class="lineheight smallfont"'
),array(
cplang( 'pdnovel_version'),
'Pdnovel '.PDNOVEL_VERSION.' Release '.PDNOVEL_RELEASE.' '.PDNOVEL_CHARSET.' <script src="http://www.huiwei100.com/pdupdate.php?version='.PDNOVEL_VERSION.'&release='.PDNOVEL_RELEASE.'&charset='.PDNOVEL_CHARSET.'" type="text/javascript"></script>'
) );
showtablerow( '',array(
'class="vtop td24 lineheight"',
'class="lineheight smallfont"'
),array(
cplang( 'pdnovel_key'),
PDNOVEL_KEY
) );
showtablefooter( );
showtableheader( 'pdnovel_dev','fixpadding');
showtablerow( '',array(
'class="vtop td24 lineheight"'
),array(
cplang( 'dev_copyright'),
'<span class="bold"><a href="http://www.chnb.net" class="lightlink2" target="_blank">JUNK&AElig;&Ecirc;&para;&Egrave;&iquest;&AElig;&frac14;&frac14;</a></span>'
) );
showtablerow( '',array(
'class="vtop td24 lineheight"',
'class="lineheight smallfont team"'
),array(
cplang( 'dev_salesteam'),
'<span class="bold"><a href="http://www.chnb.net" class="lightlink2" target="_blank">JUNK&AElig;&Ecirc;&para;&Egrave;&iquest;&AElig;&frac14;&frac14;</a></span>'
) );
showtablerow( '',array(
'class="vtop td24 lineheight"',
'class="lineheight smallfont team"'
),array(
cplang( 'dev_oldteam'),
"BaiWei 'benwil' Liu"
) );
showtablerow( '',array(
'class="vtop td24 lineheight"',
'class="lineheight"'
),array(
cplang( 'dev_team'),
'<a href="http://www.cxapp.com" class="lightlink2" target="_blank">&acute;&acute;&ETH;&Acirc;&Oacute;&brvbar;&Oacute;&Atilde;</a>,<a href="http://t.qq.com/ss22219" class="lightlink2" target="_blank">&raquo;&Atilde;&Oacute;¡ãgool</a>'
) );
showtablefooter( );
}
else if ( $do == 'getkey')
{
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
else
{
$result = 1;
}
if ( strlen( $temp ) != 32 )
{
cpmsg( 'pdnovel_index_keyerror','action=pdnovel&operation=index','error');
}
elseif ( 0 <$result )
{
cpmsg( 'pdnovel_index_keysucceed','action=pdnovel&operation=index','succeed');
}
else
{
cpmsg( 'pdnovel_index_keynotwrite','action=pdnovel&operation=index','error');
}
}
?>