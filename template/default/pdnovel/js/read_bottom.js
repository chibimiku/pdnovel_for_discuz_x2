var currentpos=1;
var timer;
var novelbgcolor=document.getElementById('novelbgcolor');
var txtcolor=document.getElementById('txtcolor');
var fonttype=document.getElementById('fonttype');
var scrollspeed=document.getElementById('scrollspeed');
function setSpeed()
{
	speed = parseInt(scrollspeed.value);
	beginScroll();	
}

function stopScroll()
{
	clearInterval(timer);
}

function beginScroll()
{
	if (speed != 0)
	{
		timer=setInterval("scrolling()",200/speed);
	}
}

function scrolling()
{
	var currentpos = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;
	window.scroll(0, ++currentpos);
	var nowpos = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;
	if(currentpos != nowpos) clearInterval(timer);
}

function saveSet()
{
	setcookie("novelbgcolor",novelbgcolor.options[novelbgcolor.selectedIndex].value,9999999);
	setcookie("txtcolor",txtcolor.options[txtcolor.selectedIndex].value,9999999);
	setcookie("fonttype",fonttype.options[fonttype.selectedIndex].value,9999999);
	setcookie("scrollspeed",scrollspeed.value,9999999);
	beginScroll();
}
function loadSet()
{
	var tmpstr;
	tmpstr = getcookie("novelbgcolor");
	novelbgcolor.selectedIndex = 0;
	if (tmpstr != "")
	{
	    for (var i=0;i<novelbgcolor.length;i++)
		{
			if (novelbgcolor.options[i].value == tmpstr)
			{
				novelbgcolor.selectedIndex = i;
				break;
			}
		}
	}
	tmpstr = getcookie("txtcolor");
	txtcolor.selectedIndex = 0;
	if (tmpstr != "")
	{
		for (var i=0;i<txtcolor.length;i++)
		{
			if (txtcolor.options[i].value == tmpstr)
			{
				txtcolor.selectedIndex = i;
				break;
			}
		}
	}
	tmpstr = getcookie("fonttype");
	fonttype.selectedIndex = 0;
	if (tmpstr != "")
	{
		for (var i=0;i<fonttype.length;i++)
		{
			if (fonttype.options[i].value == tmpstr)
			{
				fonttype.selectedIndex = i;
				break;
			}
		}
	}
	
	tmpstr = getcookie("scrollspeed");
	if (tmpstr=='') tmpstr=0;
	scrollspeed.value=tmpstr;
	setSpeed();
	readcontentobj=document.getElementById('readcontent');
	if(readcontentobj){
		readcontentobj.style.backgroundColor=novelbgcolor.options[novelbgcolor.selectedIndex].value;
		readcontentobj.style.fontSize=fonttype.options[fonttype.selectedIndex].value;
		readcontentobj.style.color=txtcolor.options[txtcolor.selectedIndex].value;
	}
}
document.onmousedown=stopScroll;
loadSet();