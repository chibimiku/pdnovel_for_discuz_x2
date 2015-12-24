<?php

if(!defined('IN_DISCUZ') ||!defined('IN_ADMINCP')) {
exit('Access Denied');
}
$cachearray = array( 'pdnovelcategory');
foreach ( $cachearray as $cachename )
{
pdnovelcache( $cachename,'pdnovel');
}
cpmsg( 'cache_success','','succeed');
?>