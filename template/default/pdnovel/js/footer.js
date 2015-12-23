var imgay = document.getElementsByTagName('img');
var coverpath = 'data/attachment/pdnovel/cover';
for(var i=0;i<imgay.length;++i){
	if( imgay[i].src.indexOf( coverpath ) != -1){
		imgay[i].onerror = function(){
			this.src='template/default/pdnovel/img/nocover.jpg';
		}
	}
}
