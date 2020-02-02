/**
 * @license MIT 
 *
 * all right I have no idea about MIT license, but put it on seems cool. :P  Enjoy!
 */
CKEDITOR.plugins.add( 'wenzgmap', {
    icons: 'wenzgmap_bw',
    init: function( editor ) {
        editor.addCommand('wenzgmapDialog', new CKEDITOR.dialogCommand('wenzgmapDialog'));
        editor.ui.addButton('wenzgmap', {
            label: 'Insert a google map',
            command: 'wenzgmapDialog',
            toolbar: 'paragraph',
            icon: 'wenzgmap_bw'
        });

        CKEDITOR.dialog.add('wenzgmapDialog', this.path + 'dialogs/wenzgmap.js');
    }
});