<link rel="stylesheet" type="text/css" href="<?php html_link('css/magazine.css'); ?>?ver=20190805">
<script type="text/javascript" src="<?php html_link('js/magazine.js'); ?>?ver=20190801325"></script>
<script type="text/javascript" src="<?php html_js('editor/ckeditor/ckeditor.js?ver=2020042413'); ?>"></script>
<script type="text/javascript">
    CKEDITOR.stylesSet.add( 'custom_styles', [
        { name: 'In đậm', element: 'span', attributes: { 'class': 'mg-indam' } },
        { name: 'In nghiêng', element: 'span', attributes: { 'class': 'mg-innghieng' } }
    ] );
</script>
<?php
$v_count = count($v_arr_magazine_content);
?>
<style>
#up-0 {display: none;}
.chu_thich_anh_mg{
    text-align: center !important;
    color: #8d8d8d !important;
    font-size: 16px !important;
    line-height: 28px !important;
}
</style>
<div class="contentTitle m-tb-10">Cập nhật nội dung magazine</div>
<div class="line-dot m-tb-10"></div>
<form name="frm_update_data" method="post" action="<?php html_link('ajax/'.$this->className().'/act_update_magazine/'.$v_magazine_id); ?>" enctype="multipart/form-data" class="frm frm-aligned" id="frmUpdateMagazine">
    <input type="hidden" name="goback" value="<?php echo $v_goback; ?>" />
    <input type="hidden" name="magazine_id" id="magazine_id" value="<?php echo $v_magazine_id; ?>" />
    <div class="wrapper">
        <div class="row">
            <div class="col-1-2">
                <div class="control-group">
                    <div class="row">
                        <div class="col-1-4">
                            <label for="magazine_name" class="fluid" style="text-align: left;">Tên nội dung magazine <span class="redText">(*)</span></label>
                        </div>
                        <div class="col-3-4">
                            <input id="magazine_name" name="magazine_name" type="text" value="<?php echo $v_magazine['c_name'].$v_text_copy;?>" class="fluid">
                            <!-- <span class="pure-form-message-inline">This is a required field.</span> -->
                        </div>
                    </div>
                </div>
            </div>
            <?php
            if($v_magazine_id_new > 0){
                $v_magazine['c_bai_tham_khao'] = 0;
            }
            if ($this->getPerm('admin,add_bai_tham_khao')) { ?>
                <div class="col-1-6">
                    <input type="checkbox" id="chk_bai_tham_khao" name="chk_bai_tham_khao" value="1" <?php echo ($v_magazine['c_bai_tham_khao'] > 0) ? 'checked' : '' ?> /> Tích bài tham khảo
                </div>
            <?php 
            }
            $v_arr_font = _get_module_config('template_magazine', 'v_arr_font');
            ?>
            <div class="col-1-6">
                Chọn font: 
                <select name="magazine_font" style="width: 160px">
                    <?php
                        for($i=0, $s= sizeof($v_arr_font); $i<$s; $i++) {
                            $selected ='';
                            if(isset($v_magazine['c_font'])) {
                                if($v_arr_font[$i]['c_code_font'] == $v_magazine['c_font']) {
                                    $selected ='selected';
                                }
                            }
                    ?>
                        <option value="<?php echo $v_arr_font[$i]['c_code_font'];?>" <?php echo $selected; ?>><?php echo $v_arr_font[$i]['c_name'];?></option>
                        <?php
                    } ?>
                </select>
            </div>
        </div>
        <script type="text/javascript">
        <?php 
//        tuannt bo sung thong tin su dung template magazine
        foreach($v_arr_magazine_template as $key=>$items){
            $v_arr_magazine_template[$key]['c_name'] = $v_arr_magazine_template[$key]['pk_magazine_template'].'-'.$v_arr_magazine_template[$key]['c_name'];
        }        
//        tuannt bo sung thong tin su dung template magazine
        $v_arr_chuyen_muc = add_ascii_column($v_arr_magazine_template, 'c_name');// them cot ten khong dau
            echo _tao_file_js_suggestion($v_arr_chuyen_muc,'ds_template','pk_magazine_template','c_name_ascii','c_name'); // tao mang 
        ?>
        </script>
        <div class="magazine-content-detail-list" id="MagazineContentList">
            <?php if (empty($v_arr_magazine_content)) { 
                $v_add_content_html = $this->dsp_single_magazine_content(0);
                if (!$this->autoRender) {
                    echo $v_add_content_html;
                }
             } else {
				$v_count = count($v_arr_magazine_content);
                foreach ($v_arr_magazine_content as $k => $mzc) {
                   $v_add_content_html = $this->dsp_single_magazine_content($k, 0, $mzc,$v_count);
                   if (!$this->autoRender) {
                       echo $v_add_content_html;
                   }
                }
             }?>
        </div>
        <div class="line-dot" style="border-bottom: 3px dashed #ddd;margin: 10px 0;"></div>
        <div class="row">
            <div class="col-1-2">
                <a href="javascript:void(0)" id="AddNewContent" class="m-tb-10 add-answer" onclick="javascript:addNewContent(this);" data-next-stt="<?php echo $v_next_stt; ?>" data-base-url="<?php html_link('ajax/'.$this->className().'/dsp_single_magazine_content/'); ?>" style="font-size: 14px">
                    <img src="<?php html_image('images/add-icon.png'); ?>" />
                Thêm nội dung</a>
            </div>
        </div>
        
        <div class="row m-tb-10">
            <div class="col-1-2">
                <div class="row">
                    <div class="col-1-4">
                        <label class="fluid p-lr-6">Trạng thái xuất bản</label>
                    </div>
                    <div class="col-3-4">
                        <?php if ($this->getPerm('admin,publish')) { ?>
                        <select name="magazine_status" style="width: 160px" <?php echo !$this->getPerm('admin,update') ? 'disabled' : '' ?>>
                            <?php
                                for($i=0, $s= sizeof($v_arr_trang_thai); $i<$s; $i++) {
                                    $selected ='';
                                    if(isset($v_magazine['c_status'])) {
                                        if($v_arr_trang_thai[$i]['c_code'] == $v_magazine['c_status']) {
                                            $selected ='selected';
                                        }
                                    } else {
                                        if ($this->getPerm('admin,update')) {
                                            if($v_arr_trang_thai[$i]['c_code'] == 1) {
                                                $selected ='selected';
                                            }
                                        } else {
                                            if($v_arr_trang_thai[$i]['c_code'] == 0) {
                                                $selected ='selected';
                                            }
                                        }
                                    }
                            ?>
                                <option value="<?php echo $v_arr_trang_thai[$i]['c_code'];?>" <?php echo $selected; ?>><?php echo $v_arr_trang_thai[$i]['c_name'];?></option>
                                <?php
                            } ?>
                        </select>
                        <?php } else { ?>
                            <span><?php echo isset($v_magazine['c_status']) && $v_magazine['c_status'] > 0 ? 'Đã xuất bản' : 'Chưa xuất bản';?></span>
                            <input type="hidden" name="magazine_status" value="<?php isset($v_magazine['c_status']) ? $v_magazine['c_status'] : 0 ?>">
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="line-dot"></div>
        <div class="row">
            <div class="col-1-2">
                <div class="row">
                    <div class="col-1-4"></div>
                    <div class="col-3-4">
                        <?php
                            if ($this->getPerm('admin,update')) {
                                ?>
                                <a class="button_big btn_grey button_update" href="javascript:void(0);" onclick="updateMagazine(this)">Cập nhật</a>&nbsp;
                                <?php
                            }?>
                            <a class="button_big btn_grey" href="javascript:btn_back_onclick(document.frm_update_data,'<?php echo fw24h_base64_url_decode($_GET['goback'])?>','')">Thoát</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>
<script type="text/javascript">
    // Listen to message from child window
    bindEvent(window, 'message', function (e) {
        var v_list_id_temp_slide = '<?php echo $v_list_id_temp_slide; ?>';
        console.log(e.data);
        try {
            if (e.data) {
                var obj = JSON.parse(e.data);
                if (obj.msg_type == 'trigger_template_id_change') {
                    onMagazineTemplateChange(obj.target, obj.value);
                } else if (obj.msg_type == 'crop_image') {
                    $('#crop_x__'+obj.code + '__' + obj.stt).val(obj.x1);
                    $('#crop_y__'+obj.code + '__' + obj.stt).val(obj.y1);
                    $('#crop_w__'+obj.code + '__' + obj.stt).val(obj.w);
                    $('#crop_h__'+obj.code + '__' + obj.stt).val(obj.h);
                    $('#crop_image__'+obj.code + '__' + obj.stt).val(obj.data);

                    if (obj.target_elem) {
                        var that = $('#' + obj.target_elem);
                        var file_size = atob(obj.data.split(',')[1]).length;
                        that.parent().parent().find('.image-info').html('<span>Ảnh bạn cắt có dung lượng <strong>'+ bytesToSize(file_size) +'</strong>, chiều rộng: <strong>'+ obj.w +'</strong>, chiều cao: <strong>'+ obj.h +'</strong></span>');

                        var iframe_id = that.closest('.magazine-content-detail').find('.preview-iframe').attr('id');

                        var sendData = {};
                        sendData.type = that.attr('data-type');
                        sendData.code = that.attr('data-code');
                        sendData.data = obj.data;

                        sendMessageToIframe(iframe_id, sendData);
                    }
                }
            }
        } catch(err) {
            console.log(err);
        }
    });
	//    BEGIN tuannt bo sung chuc nang len xuong khoi noi dung magazine
	var v_url_modul_ajax = '<?php echo html_link('ajax/'.$this->className(),false);?>';
	// Tạo cờ để kiểm tra được phép thao tác move lên, xuống
    var v_allow_action_down_up = 1;
</script>
