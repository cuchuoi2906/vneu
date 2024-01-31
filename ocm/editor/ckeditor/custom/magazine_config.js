/**
 * @license Copyright (c) 2003-2013, 24H. All rights reserved.
 * @author hoangnv
 */

CKEDITOR.editorConfig = function( config ) {
    config.forceEnterMode = 1;
	config.language = 'vi';
    config.entities = false;
    config.resize_enabled = false;
    config.allowedContent = true;
    config.fillEmptyBlocks = false;
    config.forceSimpleAmpersand = true;
    config.autoParagraph = false;
    // config.forcePasteAsPlainText = true;
    config.removePlugins = 'elementspath';
    // config.toolbarCanCollapse = true;
    // begin 17-8-2018 BangND XLCYCMHENG_26387_bo_sung_chuc_nang_nhap_bai_magazine
    config.extraPlugins = 'templatetable,fakescript,iframe,wordcount,fix_loi_editor,magazine'; 
    // end 17-8-2018 BangND XLCYCMHENG_26387_bo_sung_chuc_nang_nhap_bai_magazine
    config.toolbar = 'default';
    // begin 17-8-2018 BangND XLCYCMHENG_26387_bo_sung_chuc_nang_nhap_bai_magazine
    config.toolbar_Simple = [
        [ 'Source' ],
        [ 'Cut', 'Copy', 'Paste', 'PasteText' ],
        [ 'Bold', 'Italic', 'Underline', '-', 'RemoveFormat' ],
        [ 'Link', 'Unlink' ],
        [ 'TextColor', 'BGColor' ]        
    ];
    config.toolbar_default = [
        [ 'Source' ],
        [ 'Undo', 'Redo' ],
        [ 'Cut', 'Copy', 'Paste', 'PasteText' ],
        [ 'Bold', 'Italic', 'Underline', '-', 'RemoveFormat' ],
        [ 'Link', 'Unlink' ],
        [ 'Styles' ],
        [ 'TextColor', 'BGColor' ],
        [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ],
        [ 'Image', 'uploadimage' ],
        [ 'Iframe' ],
        [ 'Maximize' ],
        [ 'magazine' ]
    ];

    config.toolbar_Basic = [
        [ 'Source' ],
        [ 'Cut', 'Copy', 'Paste', 'PasteText' ],
        [ 'Bold', 'Italic', 'Underline', '-', 'RemoveFormat' ],
        [ 'Link', 'Unlink' ],
        [ 'TextColor', 'BGColor' ],
        [ 'FontSize' ],
        [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ]
    ];
    // end 17-8-2018 BangND XLCYCMHENG_26387_bo_sung_chuc_nang_nhap_bai_magazine
    
    config.keystrokes = [
        [ CKEDITOR.CTRL + 69 /*E*/, 'justifycenter' ],
        [ CKEDITOR.CTRL + 76 /*L*/, 'justifyleft' ],
        [ CKEDITOR.CTRL + 82 /*R*/, 'justifyright' ]
    ];
};