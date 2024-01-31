<?php

$v_list_form_title  = "Lịch sử sửa đổi chi tiết"; 
html_set_title($v_list_form_title);
?>
<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL . 'css/magazine.css'; ?>">
<script type="text/javascript" src="<?php html_link('js/magazine.js'); ?>"></script>
<div class="widget">
	<div class="widget_title">
        <h5><?php echo $v_list_form_title; ?></h5>
    </div>
    <div class="widget_body quiz" style="padding: 10px;">	
        <div id="err_common" class="redText"></div>

		<form method='post' id='frm_dsp_single_item' class="frm frm-stacked">
            <!-- <input type="hidden" name="goback_index" value="<?php echo $goback_index; ?>"> -->
            <div class="wrapper">
                <div class="row">
                    <div class="col-1-1">
                        <div class="control-group">
                            <p><strong>Tên nội dung magazine:</strong> <?php echo $v_history['c_name'];?></p>
                            <p><strong>Thao tác:</strong> <?php echo $v_history['c_action_type'] > 0 ? 'Thay đổi trạng thái Xuất bản' : 'Chỉnh sửa';?></p>
                            <p><strong>Người thao tác:</strong> <?php echo $v_history['Username'];?>   -  <strong>Thời gian thao tác:</strong> <?php echo $v_history['c_created_at'];?></p>
                            <hr>
                        </div>
                    </div>
                </div>
                <div class="magazine-content-detail-list" id="MagazineContentList">
                    <?php foreach ($v_arr_magazine_content as $v_stt => $v_magazine_content) { ?>
                        <div class="magazine-content-detail" id="MagazineContent<?php echo $v_stt;?>" data-stt="<?php echo $v_stt;?>" style="border-bottom: 1px solid #ddd;">
                            <div class="row">
                                <div class="col-1 control-group">
                                    <div class="row m-tb-10">
                                        <div class="col-1-12">
                                            <h3>Nội dung <?php echo $v_stt + 1; ?></h3>
                                        </div>
                                        <div class="col-11-12">
                                            <div class="row">
                                                <div class="col-1-1">
                                                    <p style="margin: 0">Trọng số: <?php echo $v_magazine_content['c_position']; ?></p>
                                                    <?php $v_template = be_get_magazine_template( $v_magazine_content['fk_magazine_template']); ?>
                                                    <p style="margin: 10px 0">Chọn template: <?php echo $v_template['c_name']; ?></p>
                                                </div>
                                            </div>
                                            <div class="magazine-content-load-zone">
                                                <?php 
                                                $v_html_map = json_decode($v_magazine_content['c_html_map'], true);
                                                if(check_array($v_html_map)) { ?>
                                                    <div class="row">                                        
                                                        <!-- Template Preview -->
                                                        <div class="col-1-1">
                                                            <div class="preview-iframe-wrapper preview-history" style="overflow: auto">
                                                                <iframe class="preview-iframe" src="<?php html_link('ajax/' . $this->className() . '/dsp_preview_magazine_content/' . $v_magazine_content['fk_magazine_template']); ?>" id="preview__<?php echo $v_stt; ?>" frameborder="0" width="100%" height="500" allowfullscreen="true" onload="PreviewIframe(this)" data-stt="<?php echo $v_stt; ?>" data-json="<?php echo htmlentities(json_encode($v_html_map)); ?>"></iframe>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                           
                        </div>
                    <?php } ?>
                </div>
                <!-- <hr class="m-tb-10"> -->
                <p class="m-tb-10"><strong>Trạng thái xuất bản:</strong> <?php echo $v_history['c_status'] > 0 ? 'Đã xuất bản' : 'Chưa xuất bản';?></p>
                
                <div class="row">
                    <div class="col-1-1">
                        <a class="btn btn-primary" href="<?php echo fw24h_base64_url_decode($_GET['goback'])?>" id="btnGoBack" style="margin-right:10px">Đóng</a>
                    </div>
                </div>
            </div>
		</form>
	</div>
</div>
