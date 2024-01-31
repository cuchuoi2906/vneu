<link type="text/css" href="<?php html_css('css/jquery.Jcrop.css'); ?>" rel="stylesheet" />
<script type="text/javascript" src="<?php html_js('js/jquery.Jcrop.js'); ?>"></script>
<div class="padTop">
    <?php // begin 10/03/2016 tuyennt: bo_sung_chuc_nang_crop_anh_cho_cac_chu_nang_ocm_24h
    if($v_txt_field_name == 'c_anh_cover'){
        $v_max_width = 'max-width:1004px';
    // begin 14/11/2016 TuyenNT bo_sung_chuc_nang_crop_anh_chia_se_mxh_24h_dv
    }elseif($v_field_name == 'file_chia_se_mxh' || $v_field_name == 'file_anh_mxh'){
        $v_max_width = 'max-width:1200px';
    }else{
    // end 14/11/2016 TuyenNT bo_sung_chuc_nang_crop_anh_chia_se_mxh_24h_dv
        $v_max_width = 'max-width:640px';

        if ($v_rq_max_width > 0){# 20220105 hỗ trợ max width từ param
            $v_max_width = 'max-width:'.$v_rq_max_width.'px';
        }
    }
    ?>
    <img src="<?php echo $v_image; ?>" id="target" style="<?php echo $v_max_width; ?>" />
    <div id="jcrop-preview-pane" style="margin-left:40px">
        <div class="jcrop-preview-container" style="<?php echo $v_max_width; ?>;width:<?php echo $v_width.'px';?>;height:<?php echo $v_height.'px'; ?>">
    <?php // end 10/03/2016 tuyennt: bo_sung_chuc_nang_crop_anh_cho_cac_chu_nang_ocm_24h ?>
            <img src="<?php echo $v_image; ?>" class="jcrop-preview" alt="Preview" />
        </div>
    </div>
</div>

<form  name="frm_crop_image" action="" method="post" style="position: relative;">
    <input type="hidden" name="hdn_image" value="<?php echo $v_image; ?>" />
    <input type="hidden" name="hdn_block" value="<?php echo $v_block; ?>" />
    <input type="hidden" name="hdn_type" value="<?php echo $v_type; ?>" />
    <input type="hidden" name="hdn_extend" value="<?php echo $v_extend; ?>" />
    <input type="hidden" id="x" name="x" />
    <input type="hidden" id="y" name="y" />
    <input type="hidden" id="w" name="w" />
    <input type="hidden" id="h" name="h" />
    <div style="position:absolute;left: 400px;top: 10px;">
        <a href="javascript:;" onclick="if (parseInt($('#w').val())) frm_submit(document.frm_crop_image, '<?php html_link($this->className().'/act_crop_image/'.$v_field_name.'/'.$v_txt_field_name.'/'.$v_width.'/'.$v_height); ?>', 'iframe_submit'); else alert('Bạn chưa chọn vùng để cắt');" class="button_big btn_grey">Cắt ảnh</a>
        <a href="javascript:;" onclick="window.close()" class="button_big btn_grey">Đóng cửa sổ</a>
    </div>
</form>
<?php /* Tytv - bo_xung_hien_thi_kich_thuoc_cat_anh */?>
<div id="info_crop" style="margin-top:10px">
	Độ rộng <input type="text" name="crop_width" id="crop_width" style="width:100px" disabled />px
    &nbsp;&nbsp;&nbsp;&nbsp;
    Độ cao <input type="text" name="crop_height" id="crop_height" style="width:100px" disabled />px
</div>
<?php /* Tytv - bo_xung_hien_thi_kich_thuoc_cat_anh */?>
<iframe name="iframe_submit" class="iframe-form" ></iframe>

<script type="text/javascript">
$(function(){
    // Create variables (in this scope) to hold the API and image size
    var jcrop_api, boundx, boundy,

    // Grab some information about the preview pane
    $preview = $('#jcrop-preview-pane'),
    $pcnt = $('#jcrop-preview-pane .jcrop-preview-container'),
    $pimg = $('#jcrop-preview-pane .jcrop-preview-container img'),

    xsize = $pcnt.width(),
    ysize = $pcnt.height(),
    xrealsize = $pimg.width(),
    yrealsize = $pimg.height();

    $('#target').Jcrop({
        onChange: updatePreview,
        onSelect: updatePreview,
        aspectRatio: <?php echo 'xsize / ysize'; ?>
    },function(){
        // Use the API to get the real image size
        var bounds = this.getBounds();
        boundx = bounds[0];
        boundy = bounds[1];
        // Store the API in the jcrop_api variable
        jcrop_api = this;

        // Move the preview into the jcrop container for css positioning
        $preview.appendTo(jcrop_api.ui.holder);
    });

    function updatePreview(c) {
        if (parseInt(c.w) > 0) {
            if($('#crop_width')){
                $('#crop_width').val(c.w);
            }
            if($('#crop_height')){
                $('#crop_height').val(c.h);
            }
            var rx = xsize / c.w;
            var ry = ysize / c.h;
            var rxreal = xrealsize / boundx;
            var ryreal = yrealsize / boundy;

            $pimg.css({
                width: Math.round(rx * boundx) + 'px',
                height: Math.round(ry * boundy) + 'px',
                marginLeft: '-' + Math.round(rx * c.x) + 'px',
                marginTop: '-' + Math.round(ry * c.y) + 'px'
            });

            $('#x').val(Math.round(rxreal * c.x));
            $('#y').val(Math.round(rxreal * c.y));
            $('#w').val(Math.round(rxreal * c.w));
            $('#h').val(Math.round(ryreal * c.h));
        }
    };
});
</script>
