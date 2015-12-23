//common
function pdinitSearchmenu(searchform) {
	var searchtxt = $(searchform + '_txt');
	if(!searchtxt) {
		searchtxt = $(searchform);
	}
	var tclass = searchtxt.className;
	searchtxt.className = tclass + ' xg1';
	searchtxt.onfocus = function () {
		if(searchtxt.value == 'ÇëÊäÈëËÑË÷ÄÚÈÝ') {
			searchtxt.value = '';
			searchtxt.className = tclass;
		}
	};
	searchtxt.onblur = function () {
		if(searchtxt.value == '' ) {
			searchtxt.value = 'ÇëÊäÈëËÑË÷ÄÚÈÝ';
			searchtxt.className = tclass + ' xg1';
		}
	};
	var o = $(searchform + '_type');
	var a = $(searchform + '_type_menu').getElementsByTagName('a');
	for(var i=0; i<a.length; i++){
		if(a[i].className == 'curtype'){
			o.innerHTML = a[i].innerHTML;
			$(searchform + '_mod').value = a[i].rel;
		}
		a[i].onclick = function(){
			o.innerHTML = this.innerHTML;
			$(searchform + '_mod').value = this.rel;
		};
	}

}
function errorcover(v) {
	v.src='template/default/pdnovel/img/nocover.jpg';
}
//cat
function catmainxsphhover(t,order) {
	var id = $(t+'row'+order).attributes['pdtag'].value;
	var x = new Ajax();
	x.get('pdnovel.php?mod=ajax&do=rank&novelid='+id+'&t='+t, function(s){
		ajaxinnerhtml($(t+'pdbookshow'), s);
	});
	for (var i = 1; i < 11; i++) {
		if(i==order){
			$(t+'row'+i).className = "listrow hover";
		}else{
			$(t+'row'+i).className = "listrow";
		}
	}
};
