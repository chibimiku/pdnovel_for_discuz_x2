var contents = document.getElementById('cpcontainer').innerHTML;
var length= document.getElementById('cpcontainer').innerHTML.length;
setInterval("scrolling()",25000);
function document.onreadystatechange()
{
	if(document.readyState == 'complete' && contents.indexOf("ȫ��С˵�ɼ��ɹ�") == -1 && contents.indexOf("�����������û���Զ���ת") == -1 ){
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
