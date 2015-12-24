<?php

if(!defined('IN_DISCUZ') ||!defined('IN_ADMINCP')) {
exit('Access Denied');
}
shownav( 'pdnovel','power');
if ( !submitcheck( 'powersubmit') )
{
showsubmenu( 'power');
showformheader( 'pdnovel&operation=power');
showtableheader( );
$query = DB::query( 'SELECT groupid, grouptitle FROM '.DB::table( 'common_usergroup').' ORDER BY groupid');
while ( $ugroup = DB::fetch( $query ) )
{
$ugrouparr[] = array(
$ugroup['groupid'],
$ugroup['grouptitle'],
'1'
);
}
$query = DB::query( 'SELECT * FROM '.DB::table( 'pdmodule_power').' WHERE moduleid = 11');
while ( $power = DB::fetch( $query ) )
{
$power['power'] = $power['power'] ?unserialize( $power['power'] ) : array( );
$powerarr = array(
'power['.$power['action'].']',
$ugrouparr,
'isfloat'
);
showtitle( $power['name'] );
showsetting( '',$powerarr,$power['power'],'omcheckbox');
}
showtablefooter( );
showsubmit( 'powersubmit');
showformfooter( );
}
else
{
$query = DB::query( 'SELECT * FROM '.DB::table( 'pdmodule_power').' WHERE moduleid = 11');
while ( $power = DB::fetch( $query ) )
{
DB::update( 'pdmodule_power',array(
'power'=>serialize( $_G['gp_power'][$power['action']] )
),"action='".$power['action'] ."'");
}
cpmsg( 'threadtype_infotypes_option_succeed','action=pdnovel&operation=power','succeed');
}
?>