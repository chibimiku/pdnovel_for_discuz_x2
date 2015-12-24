//common
function pdinitSearchmenu(searchform) {
	var searchtxt = $(searchform + '_txt');
	if(!searchtxt) {
		searchtxt = $(searchform);
	}
	var tclass = searchtxt.className;
	searchtxt.className = tclass + ' xg1';
	searchtxt.onfocus = function () {
		if(searchtxt.value == '«Î ‰»ÎÀ—À˜ƒ⁄»›') {
			searchtxt.value = '';
			searchtxt.className = tclass;
		}
	};
	searchtxt.onblur = function () {
		if(searchtxt.value == '' ) {
			searchtxt.value = '«Î ‰»ÎÀ—À˜ƒ⁄»›';
			searchtxt.className = tclass + ' xg1';
		}
	};
	var o = $(searchform + '_type');

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
