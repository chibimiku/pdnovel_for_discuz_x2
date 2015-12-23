var contents = document.getElementById('cpcontainer').innerHTML;
var length= document.getElementById('cpcontainer').innerHTML.length;
setInterval("scrolling()",25000);
function document.onreadystatechange()
{
	if(document.readyState == 'complete' && contents.indexOf("全部小说采集成功") == -1 && contents.indexOf("如果你的浏览器没有自动跳转") == -1 ){
		window.location.href=thisurl;
	}
}
function scrolling(){
	if( document.getElementById('cpcontainer').innerHTML.length == length ){
	 	 window.location.href=thisurl;
	}else{
	 	 length = document.getElementById('cpcontainer').innerHTML.length;
	}
}
