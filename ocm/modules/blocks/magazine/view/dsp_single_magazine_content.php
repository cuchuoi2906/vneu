<script type="text/javascript" src="<?php html_js('js/jscolor/jscolor.js'); ?>"></script>
<div class="magazine-content-detail" id="MagazineContent<?php echo $v_stt;?>" data-stt="<?php echo $v_stt;?>" style="border-top: 3px dashed #ccc">
    <div class="row">
         <input  name="magazine_content[<?php echo $v_stt; ?>][content_id]" type="hidden" value="<?php echo !empty($v_magazine_content['pk_magazine_content']) ? $v_magazine_content['pk_magazine_content'] : -100; ?>">
        <div class="col-1-2">
            <div class="row m-tb-10">
                <div class="col-1-6">
                    <h3 style="font-size: 14px">Nội dung <span id="getid<?php echo $v_stt; ?>"><?php echo $v_stt + 1; ?></span></h3>
                </div>
                <div class="col-5-6">
                    <a href="javascript:void(0)" class="close-answer" onclick="javascript:removeMagazineContentDetail(this);" style="margin: 0"><img src="<?php html_image('images/close-icon.png'); ?>" /> Xóa nội dung</a>
					<?php // BEGIN 23/7/2019 tuannt so sng chuc nang di chuyen len xuong khoi noi dung magazine
                            ?>
                    <a onclick="no2down('MagazineContent<?php echo $v_stt;?>',<?php echo $v_stt;?>);" class="<?php echo $v_stt.' '.$v_magazine_content['pk_magazine_content']; ?>" id="down-<?php echo $v_stt; ?>" style="margin-left:30px;cursor: pointer;">
                        <img src="<?php html_image('images/download-symbol.png'); ?>" alt="Chuyển xuống" height="20" width="20">
                        <span>Chuyển xuống</span>
                    </a>
                                <a onclick="no2up('MagazineContent<?php echo $v_stt;?>',<?php echo $v_stt;?>);" class="<?php echo $v_stt.' '.$v_magazine_content['pk_magazine_content']; ?>" id="up-<?php echo $v_stt; ?>" style="margin-left:30px;cursor: pointer;">
                                    <img src="<?php html_image('images/download-symbol.png'); ?>" alt="Chuyển xuống" height="20" width="20" style="transform: rotate(-180deg);">
                                    <span>Chuyển lên</span>
                                </a>
                            <?php
                        // BEGIN 23/7/2019 tuannt so sng chuc nang di chuyen len xuong khoi noi dung magazine
                    ?>
                </div>
            </div>
            <div class="row m-tb-10">
                <div class="col-1-6">
                    <label class="fluid p-lr-6">Trọng số</label>
                </div>
                <div class="col-5-6">
                    <?php //Begin AnhTT 22/4/2020 toi_uu_magazine ?>
                    <input id="position__<?php echo $v_stt; ?>" name="magazine_content[<?php echo $v_stt; ?>][position]" type="number" onkeyup="changePositionStt(this)" value="<?php echo !empty($v_magazine_content['c_position']) ? $v_magazine_content['c_position'] : intval($v_stt) +1; ?>" class="fluid magazine-content-number">
                    <?php //Begin AnhTT 22/4/2020 toi_uu_magazine ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-1-2">
            <div class="row">
                <div class="col-1-6">
                    <?php if ($v_magazine_id){ ?>
                        <label class="fluid p-lr-6">Chọn template</label>
                    <?php }  else { ?>
                        <a href="javascript:void(0)" class="fluid p-lr-6" onclick="window.open('<?php html_link($this->className().'/dsp_choose_template_magazine?stt=' . $v_stt); ?>', 'new_window', 'width=700, height=700,toolbar=no,status=yes,menubar=no,scrollbars=yes,resizable=yes')">Chọn template</a>
                    <?php } ?>
                    
                </div>
                <div class="col-5-6">
						<?php //Begin AnhTT 22/4/2020 toi_uu_magazine ?>
                        <?php if ($v_is_update){ ?>
                            <label><?php echo $v_template['pk_magazine_template'].' - '.$v_template['c_name'];?></label>
                        <?php }?>
						<?php //Begin AnhTT 22/4/2020 toi_uu_magazine ?>
                        <input type="text" style="width:140px;" name="txt_template_id<?php echo $v_stt; ?>" id="txt_template_id<?php echo $v_stt; ?>" value="" placeholder="Nhập tên template" class="auto-title fluid" onKeyPress="if(event.keyCode == 13 || event.keyCode == '13'){return(false);}"/>
                        <!-- bien hidden luu gia tri duoc chon trong hop thoai suggestion-->
                        <input type="hidden" name="hdn_template_id<?php echo $v_stt; ?>" id="hdn_template_id<?php echo $v_stt; ?>" value=""/>
                        <select size="5" onchange="javascript:onMagazineTemplateChange(this);" class="fluid" id="sel_template_id<?php echo $v_stt; ?>" name="sel_template_id<?php echo $v_stt; ?>" style="height: 130px" data-base-url="<?php html_link('ajax/'.$this->className().'/dsp_single_magazine_content/'); ?>" data-stt="<?php echo $v_stt; ?>" data-seleted-value="<?php echo $v_template_id; ?>">
                            <?php
                                for($i=0, $s= sizeof($v_arr_magazine_template); $i<$s; $i++) {
                                    $selected ='';
                                    if($v_arr_magazine_template[$i]['pk_magazine_template'] == $v_template_id) {
                                        $selected ='selected';
                                    }
                            ?>
                                <option value='<?php echo $v_arr_magazine_template[$i]['pk_magazine_template'];?>' title="<?php echo _utf8_to_ascii($v_arr_magazine_template[$i]['c_name']).' - '.$v_arr_magazine_template[$i]['c_name'];?>" <?php echo $selected; ?>><?php echo $v_arr_magazine_template[$i]['pk_magazine_template'].' - '.$v_arr_magazine_template[$i]['c_name'];?></option>
                                <?php
                            } ?>
                        </select>
                        <script type="text/javascript">
                            // Tao suggestion cho o tim kiem chuyen muc
                            setAutoComplete('txt_template_id<?php echo $v_stt; ?>',ds_template,'hdn_template_id<?php echo $v_stt; ?>', 1, 'selectTemplate("sel_template_id<?php echo $v_stt; ?>","hdn_template_id<?php echo $v_stt; ?>","txt_template_id<?php echo $v_stt; ?>")');
                        </script>
                    
                </div>
            </div>
        </div>
        <div class="col-1-2 p-lr-6">
            <div style="margin-top: 0">                
                <?php if (!empty($v_template)) { ?>              
                    <div class="view_desc_quiz" style="width: auto;height: auto"> 
                        <img src="<?php html_image('images/help.png');?>" align="absmiddle" width="16" height="16">
                        <a target="_blank" href="javascript:void(0)" onclick="window.open('<?php html_link('ajax/template_magazine/act_preview_template_magazine/' . $v_template_id); ?>', 'new_window', 'width=1200, height=600,toolbar=no,status=yes,menubar=no,scrollbars=yes,resizable=yes')" style="display: inline-block;position: relative;top: 3px">
                            <img src="<?php html_image('images/imgpreview.gif');?>" width="16" height="16" />
                        </a>
                        <div id="list_desc_quiz" style="display: none;text-align:left;left:0">
                            <?php echo (empty($v_template['c_description']) ? 'Không có ghi chú' : $v_template['c_description']); ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="magazine-content-load-zone">
	    <?php if(check_array($v_html_map)) { ?>
	        <input type="hidden" name="magazine_content[<?php echo $v_stt; ?>][template_id]" id="template_id__<?php echo $v_stt; ?>" value="<?php echo $v_template_id; ?>">
	        <div class="row">
	            <!-- Map -->
	            <div class="col-1-2 p-lr-6" id="LoadZoneLeft_<?php echo $v_stt; ?>">
	                <?php 
                        global $v_index_chu_thich_anh;
                        $v_index_chu_thich_anh = 0;
                        mzt_loop_through_map($v_html_map, function ($element_data, $element_index, $map_kind) use ($v_stt) {
                            echo mzGetElementFormInput( $element_data, $element_index, $v_stt );
                        }, 'defined', $v_template_id, $v_html_body);
                        // Lấy cấu hình list template cho phép chọn vị trí ghi chú + màu text ghi chú
                        $v_list_id_temp = '227';
                        if($v_list_id_temp != ''){
                            $v_arr_list_id_temp = explode(',', $v_list_id_temp);
                            // nếu temp thuộc list cấu hình thì hiện thị chọn vị trí text note
                            if(check_array($v_arr_list_id_temp) && in_array($v_template_id, $v_arr_list_id_temp)){
                                ?>
                                <div class="row m-tb-5" style="border-top:1px dotted #ddd;padding: 5px 0 0 0">
                                    <div class="col-1-6">
                                        <label class="fluid p-lr-6">Vị trí ghi chú </label>
                                    </div>   
                                    <div class="col-5-6">
                                        <?php
                                        $v_arr_effect_text_note = _get_module_config('template_magazine', 'v_arr_effect_text_note');
                                        ?>
                                        <select name="effect_text_note_<?php echo $v_stt; ?>" style="width: 150px;margin-left: 5px;">
                                            <?php
                                            $v_list_class = '';
                                            preg_match_all('/<div class=\"item-slide-text (.*)\"/msU', $v_html_body, $v_arr_class);
                                            if(check_array($v_arr_class[1]) && $v_arr_class[1][0] != ''){
                                                $v_list_class = $v_arr_class[1][0];
                                            }
                                            foreach($v_arr_effect_text_note as $v_effect_note){
                                                $v_selected = ($v_list_class == $v_effect_note['c_code_effect_text_note']) ? 'selected' : '';
                                                ?>
                                                <option <?php echo $v_selected; ?> value="<?php echo $v_effect_note['c_code_effect_text_note']; ?>"><?php echo $v_effect_note['c_name']; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>    
                                <div class="row m-tb-5" style="border-top:1px dotted #ddd;padding: 5px 0 0 0">
                                    <div class="col-1-6">
                                        <label class="fluid p-lr-6">Màu nền ghi chú </label>
                                    </div>   
                                    <div class="col-5-6">
                                        <?php
                                        $v_value_0 = '#8e9eab';
                                        $v_value_1 = '#eef2f3';
                                        preg_match_all('/<div class=\"style_color\">(.*)<\/div>/msU', $v_html_body, $v_arr_color);
                                        if(check_array($v_arr_color[0]) && $v_arr_color[0][0] != ''){
                                            $v_string_color = $v_arr_color[1][0];
                                            $v_arr_color_add = explode('@@', $v_string_color);
                                            $v_value_0 = '#'.$v_arr_color_add[0];
                                            $v_value_1 = '#'.$v_arr_color_add[1];
                                        } 
                                        ?>
                                        <input type = "text" id="txt_effect_text_note_0_<?php echo $v_stt; ?>" name="txt_effect_text_note_0_<?php echo $v_stt; ?>" value="<?php echo $v_value_0; ?>" style="width:15%" class="color" />
                                        Đến
                                        <input type = "text" id="txt_effect_text_note_1_<?php echo $v_stt; ?>" name="txt_effect_text_note_1_<?php echo $v_stt; ?>" value="<?php echo $v_value_1; ?>" style="width:15%" class="color" />
                                    </div>
                                </div>
                                <?php
                            }
                        }
	                ?>
	            </div>
	            <!-- Template Preview -->
	            <div class="col-1-2 m-tb-10">
                    <div class="preview-iframe-wrapper" id="scroll_area__<?php echo $v_stt; ?>">
                    	<?php if (empty($v_magazine_content)) { ?>
                    		<iframe class="preview-iframe" src="<?php html_link('ajax/' . $this->className() . '/dsp_preview_magazine_content/' . $v_template_id); ?>" id="preview__<?php echo $v_stt; ?>" frameborder="0" width="1200" height="auto" allowfullscreen="true" onload="resizeIframe(this, 'LoadZoneLeft_<?php echo $v_stt; ?>')"></iframe>
                    	<?php } else { ?>
                    		<iframe class="preview-iframe" src="<?php html_link('ajax/' . $this->className() . '/dsp_preview_magazine_content/' . $v_template_id . '/' . $v_magazine_content['pk_magazine_content'] . '?v=' . time()); ?>" id="preview__<?php echo $v_stt; ?>" frameborder="0" width="1200" allowfullscreen="true" onload="resizeIframe(this, 'LoadZoneLeft_<?php echo $v_stt; ?>')"></iframe>
                    	<?php } ?>
                    	
                    	<span class="btn btn-primary close-preview-iframe" style="display: none;">Đóng</span>
                    	
                    </div>
	            </div>
	        </div>
	    <?php } ?>
    </div>
</div>
<?php 
$v_list_id_temp_slide = '227,228';
$v_value_slide = 0;
$v_arr_id_slide = explode(',', $v_list_id_temp_slide);
if(in_array($v_template_id, $v_arr_id_slide)){
    $v_value_slide = 1;
}
?>
<script type =text/javascript>
    var arrTempOrderObj = new Array;
    var v_is_temp_slide = '<?php echo $v_value_slide; ?>';
    var v_id_temp_slide_current = '<?php echo $v_template_id; ?>';
    <?php echo mz_read_file(WEB_ROOT.'js/jscolor/jscolor.min.js'); ?>;
    var arrTempOrderValue = new Array;
</script>