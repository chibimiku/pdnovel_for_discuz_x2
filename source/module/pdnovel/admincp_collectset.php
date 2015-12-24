<?php

if ( !defined( "IN_DISCUZ") )
{
exit( "Access Denied");
}
if ( $do == "show")
{
if ( !submitcheck( "sitesubmit") )
{
shownav( "pdnovel","pdnovel_collectset");
showsubmenu( "pdnovel_collectset",array(
array( "list","pdnovel&operation=collectset",1 )
) );
showformheader( "pdnovel&operation=collectset");
showtableheader( "",""," style=\"min-width:910px; _width:910px;\"");
showtitle( "pdnovel_collectset_rule");
showsubtitle( array( "pdnovel_collectset_order","pdnovel_collectset_siteid","pdnovel_collectset_use","pdnovel_collectset_sitename","pdnovel_collectset_siteurl","pdnovel_collectset_file","pdnovel_collectset_operation") );
$query = DB::query( "SELECT * FROM ".DB::table( "pdnovel_collect")." ORDER BY displayorder");
while ( $pdnovelcollect = DB::fetch( $query ) )
{
$pdenable = $pdnovelcollect['enable'];
echo "<tbody><tr><td class=\"td25\"><input type=\"text\" class=\"txt\" name=\"order[".$pdnovelcollect['siteid']."]\" value=\"".$pdnovelcollect['displayorder']."\" /></td><td class=\"td25\">".$pdnovelcollect['siteid']."</td><td><input type=\"radio\" name=\"enable[".$pdnovelcollect['siteid']."]\" value=\"1\" ".( $pdenable == 1 ?"checked=\"checked\"": "").">&#x662F;<input type=\"radio\" name=\"enable[".$pdnovelcollect['siteid']."]\" value=\"0\" ".( $pdenable == 0 ?"checked=\"checked\"": "").">&#x5426;</td><td class=\"td31\"><a href=\"".$pdnovelcollect['siteurl']."\" target=\"_blank\">".$pdnovelcollect['sitename']."</a></td><td class=\"td40\">".$pdnovelcollect['siteurl']."</td><td>data/attachment/pdnovel/collect/site_".$pdnovelcollect['siteid'].".php</td><td><a href=\"".ADMINSCRIPT."?action=pdnovel&operation=collectset&do=edit&siteid=".$pdnovelcollect['siteid']."\">".cplang( "edit")."</a>&nbsp;<a href=\"".ADMINSCRIPT."?action=pdnovel&operation=collectset&do=dele&siteid=".$pdnovelcollect['siteid']."\">".cplang( "delete")."</a></td></tr></tbody>";
}
echo "<tbody><tr><td colspan=\"6\"><div><a href=\"###\" onclick=\"addrow(this, 0)\" class=\"addtr\">".cplang( "pdnovel_collectset_addsite")."</a></div></td></tr></tbody>";
showsubmit( "sitesubmit");
showtablefooter( );
showformfooter( );
echo "<script type=\"text/Javascript\">\r\nvar rowtypedata = [\r\n[[1,'<input type=\"text\" class=\"txt\" name=\"neworder[]\" value=\"0\" />', 'td25'], [1,'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', 'td25'], [1,'&#x662F;', ''], [1,'<input type=\"text\" class=\"txt\" name=\"newname[]\" value=\"\"/></div>','td29'], [1,'<input type=\"text\" class=\"txt\" name=\"newurl[]\" value=\"http://\"/></div>','td29'], [1,'',''], [1,'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;','td40']],\r\n];\r\n</script>";
}
else
{
if ( is_array( $_G['gp_order'] ) )
{
foreach ( $_G['gp_order'] as $siteid =>$value )
{
DB::update( "pdnovel_collect",array(
"displayorder"=>$_G['gp_order'][$siteid],
"enable"=>$_G['gp_enable'][$siteid]
),"siteid='".$siteid."'");
}
}
if ( is_array( $_G['gp_neworder'] ) )
{
foreach ( $_G['gp_neworder'] as $siteid =>$value )
{
$name = $_G['gp_newname'][$siteid];
$checkid = DB::result_first( "SELECT siteid FROM ".DB::table( "pdnovel_collect").( " WHERE sitename='".$name."'") );
if ( $checkid )
{
cpmsg( "pdnovel_collectset_update_error","action=pdnovel&operation=collectset","error");
}
else
{
DB::insert( "pdnovel_collect",array(
"sitename"=>$name,
"siteurl"=>$_G['gp_newurl'][$siteid],
"enable"=>1,
"displayorder"=>$_G['gp_neworder'][$siteid]
),1 );
$id = DB::insert_id();
copy( "source/module/pdnovel/include/site_default.php","data/attachment/pdnovel/collect/site_".$id.".php");
}
}}
cpmsg( "pdnovel_collectset_update_succeed","action=pdnovel&operation=collectset","succeed");
}
}
else if ( $do == "edit")
{
$siteid = $_G['gp_siteid'];
if ( !submitcheck( "submit") )
{
shownav( "pdnovel","pdnovel_collectset");
showsubmenu( "pdnovel_collectset",array(
array( "list","pdnovel&operation=collectset",0 ),
array( "edit","pdnovel&operation=collectset",1 )
) );
showformheader( "pdnovel&operation=collectset&do=edit&siteid=".$siteid,"enctype");
showtips( "pdnovel_collectset_tips");
showtableheader( );
$content = @file_get_contents( "data/attachment/pdnovel/collect/site_".$_G['gp_siteid'].".php");
showtitle( "pdnovel_collectset_editrule");
echo "<tr><td><textarea rows=\"12\" name=\"content\" style=\"width:100%; height:250px; line-height:22px\">".$content."</textarea></td></tr>";
showtablefooter( );
showsubmit( "submit");
showformfooter( );
}
else
{
set_magic_quotes_runtime( 1 );
$content = $_POST['content'];
$save = "data/attachment/pdnovel/collect/site_".$siteid.".php";
@file_put_contents( $save,$content );
cpmsg( "pdnovel_collectset_editsucceed","action=pdnovel&operation=collectset","succeed");
}
}
else if ( $do == "dele")
{
shownav( "pdnovel","pdnovel_collectset");
if ( !submitcheck( "confirmed") )
{
cpmsg( "pdnovel_collectset_deleconfirm","action=pdnovel&operation=collectset&do=dele&siteid=".$_G['gp_siteid'],"form");
}
else
{
DB::query( "DELETE FROM ".DB::table( "pdnovel_collect")." WHERE siteid=".$_G['gp_siteid'].";");
unlink( "data/attachment/pdnovel/collect/site_".$_G['gp_siteid'].".php");
cpmsg( "pdnovel_collectset_delesucceed","action=pdnovel&operation=collectset","succeed");
}
}
?>