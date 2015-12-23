<?php
//----基本设置start
$pdnovel['utf8'] = '';
$pdnovel['cookie'] = '';
$pdnovel['subnovelid'] = 'floor(<{novelid}>/1000)'; //小说子序号运算方法
$pdnovel['subchapterid'] = ''; //小说章节子序号运算方法
//----基本设置end

//----小说信息页面采集规则start'
$pdnovel['url'] = ''; //小说信息页地址
$pdnovel['name'] = ''; //小说名称采集规则
$pdnovel['author'] = ''; //小说作者采集规则
$pdnovel['cat'] = ''; //小说分类采集规则
$pdnovel['cover'] = ''; //小说封面采集规则
$pdnovel['coverkeycancel'] = ''; //过滤的封面图片
$pdnovel['coverfilter'] = '';//过滤字符
$pdnovel['coversite'] = '';//封面地址前缀
$pdnovel['keyword'] = ''; //小说关键字采集规则
$pdnovel['intro'] = ''; //小说简介采集规则
$pdnovel['notice'] = ''; //作者公告采集规则
$pdnovel['full'] = ''; //连载状态采集规则
$pdnovel['fullnovel'] = ''; //完本标志
//分类替换
$pdnovel['catid'][''] = '';
$pdnovel['permission'] = '0'; //小说授权
$pdnovel['first'] = '0'; //小说首发
//----小说信息页面采集规则end

//----小说目录页面采集规则start
$pdnovel['chapterurl'] = ''; //小说目录页地址
$pdnovel['lastchapter'] = ''; //最后章节
$pdnovel['chapter'] = ''; //章节名称采集规则
$pdnovel['chapterid'] = ''; //章节ID采集规则
$pdnovel['volume'] = ''; //卷名称采集规则
//----小说目录页面采集规则end

//----章节内容页面采集规则start
$pdnovel['readurl'] = ''; //章节阅读地址
$pdnovel['content'] = ''; 

//章节内容采集规则
$pdnovel['replace'] = ''; //章节内容过滤
$pdnovel['contentfilter'] = ''; //章节过滤内容替换为
$pdnovel['pregreplace'] = ''; //章节内容正则过滤
$pdnovel['pregcontentfilter'] = ''; //正则过滤内容替换为
$pdnovel['addcontents'] =''; //章节内容添加
//----章节内容页面采集规则end

//----页面列表格式start
$pdnovel['pageurl'] = ''; //默认列表页采集地址
$pdnovel['page'] = ''; //列表页采集规则

//----页面列表格式end

//----采集事件start

//信息页处理开始
function viewstart( $source ){
	return $source;
}

//信息页处理结束
function viewend( $source ){
	return $source;
}

//章节列表页处理开始
function liststart( $source ){
	return $source;
}

//阅读页处理开始
function readstart( $source ){
	return $source;
}

//准备处理图片数据
function imgstart( $source ){
	return $source;
}

//准备写入章节内容
function writestart( $source ){
	return $source;
}

//页面列表处理开始
function pagestart( $source ){
	return $source;
}

//----采集事件end
?>
