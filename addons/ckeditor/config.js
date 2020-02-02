/**
 * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'id';
	// config.uiColor = '#AADC6E';
	// config.skin = 'Moonocolor'; //options: Moono, Moonocolor
        config.extraPlugins = 'youtube,wenzgmap'; //options: youtube, wordcount
        config.allowedContent = true;
        
//        config.wordcount = {
//            showWordCount: false, //whether or not you want to show the Word Count
//            showCharCount: true, //whether or not you want to show the Char Count
//            countHTML: false, //whether or not to include Html chars in the Char Count
//            charLimit: 'unlimited', //chars limitation
//            wordLimit: 'unlimited' //words limitation
//        };
        
        config.toolbar_PageGenerator =
        [
            { name: 'document', items : [ 'Source','-','Preview' ] },
            { name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
            { name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ] },
            { name: 'insert', items : [ 'File','Image','Flash','Youtube','wenzgmap' ] },
            { name: 'insert', items : [ 'Table','HorizontalRule','Smiley','SpecialChar','PageBreak' ] },
            '/',
            { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
            { name: 'links', items : [ 'Link','Unlink' ] },
            { name: 'styles', items : [ 'Format','Font','FontSize' ] },
            { name: 'colors', items : [ 'TextColor','BGColor' ] },
            { name: 'tools', items : [ 'Maximize', 'ShowBlocks' ] }
        ];
        
        config.toolbar_Standard =
        [
            { name: 'document', items : [ 'Source','-','Preview' ] },
            { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike' ] },
            { name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
            { name: 'links', items : [ 'Link','Unlink' ] },
            { name: 'insert', items : [ 'Image','Table','HorizontalRule','SpecialChar' ] },
            { name: 'styles', items : [ 'Format' ] },
            { name: 'tools', items : [ 'Maximize', 'ShowBlocks' ] }
        ];
        
        config.toolbar_Basic =
        [
            { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike' ] },
            { name: 'clipboard', items : [ 'Cut','Copy','Paste' ] },
        ];
};
