<?php
// begin 17-8-2018 BangND XLCYCMHENG_26387_bo_sung_chuc_nang_nhap_bai_magazine
function loadEditor($inputName, $value='', $width='560', $height='325', $editor='default', $clearData=true, $config = 'standard')
{
    // 15-04-2021 DanNC begin bổ sung phân quyền chức năng editor
    $quyen_add_giai_dau = '';
    if($editor == 'edit_bong_da_truc_tiep') {
        $editor = 'default';
        $quyen_add_giai_dau = 'edit_bong_da_truc_tiep';
    } else {
        $editor='default';
    }
    // 15-04-2021 DanNC end bổ sung phân quyền chức năng editor
    $v_editor_path = 'editor/ckeditor/';
    check_editor_loaded($v_editor_path);
	?>
    <textarea id="<?php echo $inputName; ?>" name="<?php echo $inputName; ?>" autocomplete="off"><?php echo $value; ?></textarea>
    <script>
        CKEDITOR.replace( '<?php echo $inputName; ?>', {
            baseHref: '<?php echo BASE_URL; ?>',
            filebrowserImageUploadUrl: '<?php echo BASE_URL.$v_editor_path; ?>uploader/upload.php',
            customConfig: '<?php echo BASE_URL.$v_editor_path; ?>custom/<?php echo $config; ?>_config.js?v=20220107',
            width: <?php echo $width; ?>,
            height: <?php echo $height; ?>,
            toolbar: '<?php echo $editor; ?>'
        });
        // 15-04-2021 DanNC begin bổ sung phân quyền chức năng editor
        <?php if($quyen_add_giai_dau == 'edit_bong_da_truc_tiep') {?>
            CKEDITOR.config.removeButtons = 'giaidau';
        <?php }?>
        // 15-04-2021 DanNC end bổ sung phân quyền chức năng editor
        <?php
        if ($clearData) {
            ?>
            CKEDITOR.on('instanceReady', function(ev){
                // if there is a paste event
                ev.editor.on('paste', function(evt) {
                    var editor = evt.editor;
                    // Begin TungVN 28-06-2017 - fix_loi_up_anh_editor
                    var dataValue = evt.data.dataValue;
                    var base64ImageRegex = /<img([^>]*)src="(data:image\/(bmp|dds|gif|jpg|jpeg|png|psd|pspimage|tga|thm|tif|tiff|yuv|ai|eps|ps|svg);base64,.*?)"([^>]*)>/gi;
                    if (dataValue.match(base64ImageRegex)) {
                        var msg = 'Ảnh trong nội dung bài không hợp lệ.\nBạn vui lòng đổi thao tác chèn ảnh vào nội dung bằng cách kéo/thả hoặc copy đường dẫn của ảnh.';
                        alert(msg);
                        return false;
                    }
                    // End TungVN 28-06-2017 - fix_loi_up_anh_editor
                    evt.stop(); // we don't let editor to paste data, only for current event
                    // show loader that blocks editor changes
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo BASE_URL.$v_editor_path; ?>/clear_data.php',
                        data: { paste_content: evt.data.dataValue },
                        success: function(result) {
                            editor.insertHtml( result );
                        },
                    });
                }, null, null, 9);

            });
            <?php
        }
		//Begin 27-03-2017 : Thangnb chon_bai_lien_quan_trong_noi_dung_bai_viet
		if ($inputName == 'txt_bai_lien_quan_noi_dung_bai_viet_24h') {
        ?>
			CKEDITOR.instances.<?php echo $inputName; ?>.config.readOnly = true;
		<?php
		//End 27-03-2017 : Thangnb chon_bai_lien_quan_trong_noi_dung_bai_viet
		} ?>
    </script>
    <?php
}
// end 17-8-2018 BangND XLCYCMHENG_26387_bo_sung_chuc_nang_nhap_bai_magazine
function check_editor_loaded()
{
    if (!defined('CKEDITOR_LOADED')) {
        define('CKEDITOR_LOADED', 'ok');
	// begin 17-8-2018 BangND XLCYCMHENG_26387_bo_sung_chuc_nang_nhap_bai_magazine
        ?>
        <script type="text/javascript" src="<?php html_js('editor/ckeditor/ckeditor.js?ver=20220107'); ?>"></script>
        <?php // end 17-8-2018 BangND XLCYCMHENG_26387_bo_sung_chuc_nang_nhap_bai_magazine
    }
}

/* Tytv - 08/11/2016 - quan_ly_quiz */
function htmlLoadEditor($inputName, $value='', $width='560', $height='325', $editor='default', $clearData=true){
    $v_html = '';
    $v_editor_path = 'editor/ckeditor/';
    if (!defined('CKEDITOR_LOADED')) {
        define('CKEDITOR_LOADED', 'ok');
	// begin 17-8-2018 BangND XLCYCMHENG_26387_bo_sung_chuc_nang_nhap_bai_magazine
        $v_html .= '<script type="text/javascript" src="'.html_js('editor/ckeditor/ckeditor.js?ver=20220107',false).'"></script>';
    	// end 17-8-2018 BangND XLCYCMHENG_26387_bo_sung_chuc_nang_nhap_bai_magazine
    }
    $v_html .='<textarea id="'.$inputName.'" name="'.$inputName.'" autocomplete="off">'.$value.'</textarea>';
    $v_html .=' <script>';
    $v_html .=' CKEDITOR.replace( "'.$inputName.'", {';
    $v_html .='baseHref: "'.BASE_URL.'",';
    $v_html .='filebrowserImageUploadUrl: "'.BASE_URL.$v_editor_path.'uploader/upload.php",';
    $v_html .='customConfig: "'.BASE_URL.$v_editor_path.'custom/standard_config.js?v=20190709312",';
    $v_html .='width:\''.$width.'\',';
    $v_html .='height:\''.$height.'\',';
    $v_html .='toolbar: "'.$editor.'"';
    $v_html .='});';
    if ($clearData) {
        $v_html .='CKEDITOR.on("instanceReady", function(ev){';
        $v_html .='ev.editor.on("paste", function(evt) {';
        $v_html .='var editor = evt.editor;';
        $v_html .='evt.stop();';
        $v_html .='$.ajax({';
        $v_html .='type: "POST",';
        $v_html .='url: "'.BASE_URL.$v_editor_path.'clear_data.php",';
        $v_html .='data: { paste_content: evt.data.dataValue },';
        $v_html .='success: function(result) {';
        $v_html .='editor.insertHtml( result )';
        $v_html .='},';
        $v_html .='});';
        $v_html .='}, null, null, 9);';
        $v_html .='});';
    }
    $v_html .='</script> ';
    return $v_html;
}
/* Tytv - 08/11/2016 - quan_ly_quiz */
