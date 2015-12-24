<?php

if(!defined('IN_DISCUZ') ||!defined('IN_ADMINCP')) {
exit('Access Denied');
}
shownav( 'pdnovel','setting');
if ( $do == 'show')
{
if ( !submitcheck( 'settingsubmit') )
{
showsubmenu( 'setting');
showformheader( 'pdnovel&operation=setting','enctype');
showtableheader( );
$setting = DB::fetch_first( 'SELECT * FROM '.DB::table( 'pdmodule_view')." WHERE name='pdnovel'");
showtitle( 'setting_status');
showsetting( 'setting_open','status',$setting['status'],'radio');
showsetting( 'setting_rewrite','rewrite',$setting['rewrite'],'radio');
showsetting( 'setting_icollect','icollect',$setting['icollect'],'radio');
showsetting( 'setting_localimg','localimg',$setting['localimg'],'radio');
showtitle( 'setting_seo');
showsetting( 'setting_seo_seotitle','seotitle',$setting['seotitle'],'text');
showsetting( 'setting_seo_siteurl','siteurl',$_G['setting']['domain']['app']['pdnovel'],'text');
showsetting( 'setting_seo_seokeywords','seokeywords',$setting['seokeywords'],'text');
showsetting( 'setting_seo_seodescription','seodescription',$setting['seodescription'],'text');
showsetting( 'setting_seo_seohead','seohead',$setting['seohead'],'textarea');
showtablefooter( );
showsubmit( 'settingsubmit');
showformfooter( );
}
else
{
if(preg_match('/^((http|https|ftp):\/\/|\.)|(\/|\.)$/i',$_G['gp_siteurl'])) {
cpmsg('setting_domain_http_error','','error');
}
$_G['setting']['domain']['app']['pdnovel'] = $_G['gp_siteurl'];
DB::insert('common_setting',array('skey'=>'domain','svalue'=>addslashes(serialize($_G['setting']['domain']))),false,true);
$_G['gp_seotitle'] = !empty( $_G['gp_seotitle'] ) ?dhtmlspecialchars( trim( $_G['gp_seotitle'] ) ) : '';
$_G['gp_seokeywords'] = !empty( $_G['gp_seokeywords'] ) ?dhtmlspecialchars( trim( $_G['gp_seokeywords'] ) ) : '';
$_G['gp_seodescription'] = !empty( $_G['gp_seodescription'] ) ?dhtmlspecialchars( trim( $_G['gp_seodescription'] ) ) : '';
$_G['gp_seohead'] = !empty( $_G['gp_seohead'] ) ?dhtmlspecialchars( trim( $_G['gp_seohead'] ) ) : '';
DB::update( 'pdmodule_view',array(
'status'=>intval( $_G['gp_status'] ),
'seotitle'=>$_G['gp_seotitle'],
'seokeywords'=>$_G['gp_seokeywords'],
'seodescription'=>$_G['gp_seodescription'],
'seohead'=>$_G['gp_seohead'],
'rewrite'=>$_G['gp_rewrite'],
'icollect'=>$_G['gp_icollect'],
'localimg'=>$_G['gp_localimg']
),"name='pdnovel'");
updatecache('setting');
cpmsg( 'setting_succeed','action=pdnovel&operation=setting','succeed');
}
}
?>