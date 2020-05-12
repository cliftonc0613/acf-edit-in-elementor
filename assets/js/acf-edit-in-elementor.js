// Page Settings Panel - onchange save and reload elementor window.
jQuery( function( $ ) {
    // if (typeof $e != "undefined" ){
	    elementor.channels.editor.on( 'RefreshPreview', function() {
		    // elementor.reloadPreview();
		  var url = window.location.href;    
if (url.indexOf('?') > -1){
   url += '&param=1'
}else{
   url += '?param=1'
}
window.location.href = url;
		 });
        // elementor.settings.page.addChangeCallback( 'first_content_header', function( newValue ) {
        //     // Here you can do as you wish with the newValue
		//  			
        //     try{
        //         //code that causes an error
        //         $e.run('document/save/auto', {
        //             force:true,
        //             onSuccess:function(){
        //                 elementor.reloadPreview();
        //                 elementor.once('preview:loaded',function(){
        //                     $e.route( 'panel/page-settings/settings' );
        //                 });
        //             }
        //         });
		// 
        //     }catch(e){
        //         console.log("Failed to update Page Settings.");
        //     }
        // });
	// }
});