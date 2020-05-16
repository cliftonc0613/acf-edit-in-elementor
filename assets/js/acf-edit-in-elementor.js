// Page Settings Panel - onchange save and reload elementor window.
jQuery( function( $ ) {
	elementor.channels.editor.on( 'RefreshPreview', function() {
		var url = window.location.href;    
		if (url.indexOf('?') > -1){
	  		url += '&param=1'
		} else {
	 		url += '?param=1'
		}
		window.location.href = url;
	});
});