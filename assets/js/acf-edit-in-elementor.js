// Page Settings Panel - onchange save and reload elementor window.
jQuery( function( $ ) {
	elementor.channels.editor.on( 'RefreshPreview', function() {
		var url = window.location.href;
		if (url.indexOf('tab') == -1) {
			if (url.indexOf('?') > -1){
	  			url += '&tab=settings'
			} else {
	 			url += '?tab=settings'
			}
		}
		window.location.href = url;
	});
});

// Re-open "Page Settings" tab if URL contains "tab=settings"
jQuery( window ).load(function() {
	var url = window.location.href;
	if (url.indexOf('tab=settings') > -1){
		jQuery( function( $ ) {
			$e.route( 'panel/page-settings/settings' );
		});
	}
});
