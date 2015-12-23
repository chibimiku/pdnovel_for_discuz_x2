<?php
/*********************/
/*                   */
/*  Version : 5.1.0  */
/*  Author  : RM     */
/*  Comment : 071223 */
/*                   */
/*********************/

define( 'APPTYPEID', 11 );
define( 'CURSCRIPT', 'pdnovel' );
require( './source/class/class_core.php' );
require( './source/discuz_version.php' );
$discuz =& discuz_core::instance( );
$cachelist = array( 'pdnovelcategory' );
$discuz->cachelist = $cachelist;
$discuz->init( );
if ( !empty( $_G['setting']['domain']['app']['pdnovel'] ) && $_SERVER['HTTP_HOST'] != $_G['setting']['domain']['app']['pdnovel'] )
{
		$siteurl = 'http://' . $_G['setting']['domain']['app']['pdnovel'] . $_SERVER['REQUEST_URI'];
		dheader('location:'.$siteurl);
}
$version = substr( DISCUZ_VERSION , 0 , 4 );
@include( DISCUZ_ROOT.'./source/language/'.CURSCRIPT.'/lang_template.php' );
require( DISCUZ_ROOT.'./source/function/function_'.CURSCRIPT.'.php' );
if ( empty( $_GET['mod'] ) || !in_array( $_GET['mod'], array( 'index', 'list', 'cat', 'ajax', 'view', 'misc', 'chapter', 'read', 'download', 'comment', 'novelcp', 'home', 'search', 'topic' , 'batch') ) )
{
		$GLOBALS['_GET']['mod'] = 'cat';
}
define( 'CURMODULE', $_GET['mod'] );
runhooks( );
$pdmodule = DB::fetch_first( 'SELECT * FROM '.DB::table( 'pdmodule_view' ).' WHERE moduleid='.APPTYPEID );
$query = DB::query( 'SELECT * FROM '.DB::table( 'pdmodule_power' ).' WHERE moduleid='.APPTYPEID );
while ( $pdpower = DB::fetch( $query ) )
{
		$grouppower = unserialize( $pdpower['power'] );
		$_G['group'][$pdpower['action']] = $grouppower[$_G['groupid']] ? 1 : 0;
}
$navtitle = $pdmodule['seotitle'];
$metakeywords = $pdmodule['seokeywords'];
$metadescription = $pdmodule['seodescription'];
$_G['setting']['seohead'] = $pdmodule['seohead'];
if ( $_GET['mod'] != 'search' )
{
		$_G['setting']['search'] = 0;
}
require_once( libfile( CURSCRIPT.'/'.$_GET['mod'], 'module' ) );
?>