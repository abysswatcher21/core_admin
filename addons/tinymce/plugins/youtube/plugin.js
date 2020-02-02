/**
 *
 * @author Josh Lobe
 * http://ultimatetinymcepro.com
 */
 
jQuery(document).ready(function($) {

    tinymce.PluginManager.add('youtube', function(editor, url) {
		
        editor.addButton('youtube', {
            image: url + '/images/icon_bw.png',
            tooltip: 'YouTube Video',
            onclick: open_youtube
        });
		
        function open_youtube() {
			
            editor.windowManager.open({
                title: 'Select YouTube Video',
                width: 900,
                height: 500,
                url: url+'/youtube.php'
            })
        }
		
    });
});