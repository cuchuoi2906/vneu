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
    config.autoParagraph = true;
    // config.forcePasteAsPlainText = true;
    config.removePlugins = 'elementspath';
    // config.toolbarCanCollapse = true;
	//Begin 09-06-2016 : Thangnb upload_anh_so_sanh
    config.extraPlugins = 'templatetable,poll,giaidau,splitcontent,uploadimage,uploadvideo,uploadvideomobile,fakescript,iframe,wordcount,codefacebook,anhsosanh,quiz,fix_loi_editor,toc,remove_toc,textlink_box,minigame,hoidap,delete_bailienquan,chinhta,bangphuctap,addon_pr,uploadaudio,upload_chum_anh'; // Tytv - 25/10/2016 - quan_ly_quiz
    config.toolbar = 'default';
    config.toolbar_default = [
        [ 'Source' ],
        [ 'Undo', 'Redo' ],
        [ 'Cut', 'Copy', 'Paste', 'PasteText' ],
        [ 'Bold', 'Italic', 'Underline', '-', 'RemoveFormat' ],
        [ 'Link', 'Unlink' ],
        [ 'Styles' ],
        /* Begin 06-08-2018 : Trungcq XLCYCMHENG_32140_bo_sung_muc_luc_bai_viet */
		[ 'Format' ],
		/* End 06-08-2018 : Trungcq XLCYCMHENG_32140_bo_sung_muc_luc_bai_viet */
        [ 'TextColor', 'BGColor' ],
        [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ],
        [ 'Outdent', 'Indent' ],
        [ 'Subscript', 'Superscript' ],
        [ 'Image', 'Table', 'Blockquote' ], // TungVN - 07/03/2017 - ocm_add_blockquote_editor
        [ 'templatetable', 'poll', 'giaidau', 'splitcontent', 'uploadimage',  ],
        [ 'uploadvideo', 'uploadvideomobile' ],
		[ 'Iframe' ],
        [ 'Maximize' ],
        [ 'bailienquan' ],
		[ 'anhsosanh' ],
        [ 'quiz' ] // Tytv - 25/10/2016 - quan_ly_quiz
        /* Begin 06-08-2018 : Trungcq XLCYCMHENG_32140_bo_sung_muc_luc_bai_viet */
		,['toc']
		,['remove_toc']
		/* End 06-08-2018 : Trungcq XLCYCMHENG_32140_bo_sung_muc_luc_bai_viet */
        ,['textlink_box']
		//BEGIN 6/8/2019 tuannt XLCYCMHENG-35421 bo sung chuc nang chon game vao bai viet
        ,['minigame']
        // END 6/8/2019 tuannt XLCYCMHENG-35421 bo sung chuc nang chon game vao bai viet
        ,['hoidap']
        /* Begin 17-04-2020 : AnhTT bo_sung_xoa_bai_lien_quan */
        ,['delete_bailienquan']
        /* End 17-04-2020 : AnhTT bo_sung_xoa_bai_lien_quan */
        ,['chinhta']
        ,['bangphuctap']
        ,['addon_pr']// XLCYCMHENG-38119 box add-on-pr
        ,[ 'uploadaudio' ]
        ,[ 'upload_chum_anh' ] //XLCYCMHENG-37759 bo_sung_chuc_nang_upload_chum_anh
    ];
	//End 09-06-2016 : Thangnb upload_anh_so_sanh
    config.toolbar_Basic = [
        [ 'Source' ],
        [ 'Cut', 'Copy', 'Paste', 'PasteText' ],
        [ 'Bold', 'Italic', 'Underline', '-', 'RemoveFormat' ],
        [ 'Link', 'Unlink' ],
        [ 'TextColor', 'BGColor' ],
        [ 'Image', 'Table' ],
        [ 'uploadimage' ],
        [ 'FontSize' ],
        [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ]
    ];
    // Tytv - Begin - 25/10/2016 - quan_ly_quiz
    /* 02-07-2015 : Thangnb them loại basic nhưng có nút upload video */
    config.toolbar_Basicvideo = [
        [ 'Source' ],
        [ 'Cut', 'Copy', 'Paste', 'PasteText' ],
        [ 'Bold', 'Italic', 'Underline', '-', 'RemoveFormat' ],
        [ 'Link', 'Unlink' ],
        [ 'TextColor', 'BGColor' ],
        [ 'Image', 'Table' ],
        [ 'uploadimage' ],
        [ 'FontSize' ],
        [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ],
        [ 'uploadvideo', 'uploadvideomobile']
    ];
    config.toolbar_Simple = [
        [ 'Source' ],
        [ 'Cut', 'Copy', 'Paste', 'PasteText' ],
        [ 'Bold', 'Italic', 'Underline', '-', 'RemoveFormat' ],
        [ 'Link', 'Unlink' ],
        [ 'TextColor', 'BGColor' ]
    ];
    // Tytv - End - 25/10/2016 - quan_ly_quiz
	/* 02-07-2015 : Thangnb them loại basic nhưng có nút upload video */
    config.toolbar_Basicvideo = [
        [ 'Source' ],
        [ 'Cut', 'Copy', 'Paste', 'PasteText' ],
        [ 'Bold', 'Italic', 'Underline', '-', 'RemoveFormat' ],
        [ 'Link', 'Unlink' ],
        [ 'TextColor', 'BGColor' ],
        [ 'Image', 'Table' ],
        [ 'uploadimage' ],
        [ 'FontSize' ],
        [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ],
        [ 'uploadvideo', 'uploadvideomobile']
    ];
	//Begin 27-03-2017 : Thangnb chon_bai_lien_quan_trong_noi_dung_bai_viet
    // AnhTT - Begin - 21/5/2020 - them_upload_video_tuong_thuat
    config.toolbar_SimpleVideo = [
        [ 'Source' ],
        [ 'Cut', 'Copy', 'Paste', 'PasteText' ],
        [ 'Bold', 'Italic', 'Underline', '-', 'RemoveFormat' ],
        [ 'Link', 'Unlink' ],
        [ 'TextColor', 'BGColor' ],
        [ 'Image', 'Table' ],
        [ 'uploadimage' ],
        [ 'FontSize' ],
        [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ],
        [ 'uploadvideo']
    ];
    // AnhTT - Begin - 21/5/2020 - them_upload_video_tuong_thuat
    config.toolbar_OnlyTextBox = [];
	//End 27-03-2017 : Thangnb chon_bai_lien_quan_trong_noi_dung_bai_viet
    config.toolbar_full = [
        { name: 'document', items : [ 'Source','-','Save','NewPage','DocProps','Preview','Print','-','Templates' ] },
        { name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
        { name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ] },
        { name: 'forms', items : [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton',
            'HiddenField' ] },
        '/',
        { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
        { name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv',
        '-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
        { name: 'links', items : [ 'Link','Unlink','Anchor' ] },
        { name: 'insert', items : [ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe' ] },
        '/',
        { name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
        { name: 'colors', items : [ 'TextColor','BGColor' ] },
        { name: 'tools', items : [ 'Maximize', 'ShowBlocks','-','About' ] }
    ];
    config.keystrokes = [
        [ CKEDITOR.CTRL + 69 /*E*/, 'justifycenter' ],
        [ CKEDITOR.CTRL + 76 /*L*/, 'justifyleft' ],
        [ CKEDITOR.CTRL + 82 /*R*/, 'justifyright' ]
    ];
    /* Begin 06-08-2018 : Trungcq XLCYCMHENG_32140_bo_sung_muc_luc_bai_viet */
	config.format_tags = 'p;h2;h3;h4;h5;h6';
	/* End 06-08-2018 : Trungcq XLCYCMHENG_32140_bo_sung_muc_luc_bai_viet */
    /* Begin: 06-02-2020 TuyenNT dieu_chinh_dat_heading_tag_tren_24h */
    config.format_h2 = { element: 'h2', attributes: { 'class': 'tuht_show' } };
    config.format_h3 = { element: 'h3', attributes: { 'class': 'tuht_show' } };
    config.format_h4 = { element: 'h4', attributes: { 'class': 'tuht_show' } };
    config.format_h5 = { element: 'h5', attributes: { 'class': 'tuht_show' } };
    config.format_h6 = { element: 'h6', attributes: { 'class': 'tuht_show' } };
    /* End: 06-02-2020 TuyenNT dieu_chinh_dat_heading_tag_tren_24h */
};
// 18-07-2017 trungcq XLCYCMHENG_24046_fix_loi_tu_dong_tao_khoang_trang - add chú thích ảnh
CKEDITOR.stylesSet.add('default',[{ name:'Chú thích ảnh', element: 'p', attributes: { 'class': 'img_chu_thich_0407' }}]);
