<div id="upload_result"></div>
<form name="frmUpload" method="post" action="<?php html_link($this->className().'/act_upload_for_mobile/'); ?>" enctype="multipart/form-data" class="padBot" target="frm_submit">
    <input type="hidden" name="hdn_chuyen_muc_duoc_chon" id="hdn_chuyen_muc_duoc_chon" value="" />
    <input type="hidden" name="tong_so_chuyen_muc" id="tong_so_chuyen_muc" value="<?php echo count($v_arr_category_by_select);?>"/>
    <table border="0" cellspacing="0" cellpadding="3">
        <tr>
            <td valign="top" width="350">
                <div>
                    <div style="float:left;width:130px;">File thường:</div>
                    <div style="height:30px"><input type="file" name="file_3gp" style="width:215px" /></div>
                <div>
                <div>
                    <div style="float:left;width:130px;">File chất lượng cao:</div>
                    <div><input type="file" name="file_3gp_hd" style="width:215px" /></div>
                <div>
                <div style="padding-top:10px">
                    <input type="submit" value="Upload file" />
                    <input type="button" value="Đóng cửa sổ" onclick="window.close()" />
                </div>
            </td>
            <?php /* Tam thoi khong dung chuyen muc khi chua co gioi han cho tung chuyen muc ?>
            <td valign="top">
                <div class="redBoldText padBot">Chọn chuyên mục kiểm tra giới hạn dung lượng video</div>
                <div style="width:180px" class="form_input">
                    <input type="text" name="txt_category" id="txt_category" value="Tìm nhanh" title="Tìm nhanh" class="auto-title" />
                </div>
                <div class="nganhhang" style="width:180px;height:120px">
                    <?php echo html_multi_radio_box('rad_category', $v_arr_category_by_select, 'ID', 'Name'); ?>
                </div>
            </td>
            <?php */ ?>
        </tr>
    </table>
</form>
<h3 style="font-weight:bold;color:red;height:30px;">Kích thước video thường cho phép: <?php echo MAX_WIDTH_VIDEO_MOBILE_THUONG.'x'.MAX_HEIGHT_VIDEO_MOBILE_THUONG;?></h3>
<h3 style="font-weight:bold;color:red;height:30px;">Kích thước video chất lượng cao cho phép: <?php echo MAX_WIDTH_VIDEO.'x'.MAX_HEIGHT_VIDEO;?></h3>
<table border="1" cellpadding="5" cellspacing="0" width="470" style="border-collapse:collapse;background-color:#ccffcc">
    <caption style="font-weight:bold;color:red;height:30px;">QUY ĐỊNH VỀ DUNG LƯỢNG 1 FILE VIDEO ĐƯỢC PHÉP UPLOAD THEO CHUYÊN MỤC</caption>
    <tr>
        <th align="center">Chuyên mục</th>
        <th align="center">Dung lượng tối đa</th>
    </tr>
    <tr>
        <td align="center">Thời trang</td>
        <td align="center">40MB</td>
    </tr>
    <tr>
        <td align="center">Ca nhạc</td>
        <td align="center">40MB</td>
    </tr>
    <tr>
        <td align="center">Phim</td>
        <td align="center">40MB</td>
    </tr>
    <tr>
        <td align="center">Bạn trẻ cuộc sống</td>
        <td align="center">40MB</td>
    </tr>
    <tr>
        <td align="center">Các mục khác</td>
        <td align="center">40MB</td>
    </tr>
</table>
<iframe name="frm_submit" class="iframe-form"></iframe>
<?php /* Tam thoi khong dung chuyen muc khi chua co gioi han cho tung chuyen muc ?>
<script type="text/javascript">
    <?php
    $v_arr_category_by_select = add_ascii_column($v_arr_category_by_select, 'Name');
    echo _tao_file_js_suggestion($v_arr_category_by_select,'ds_chuyen_muc','ID','Name_ascii','Name');
    ?>
	setAutoComplete('txt_category',ds_chuyen_muc,'hdn_chuyen_muc_duoc_chon',1,'chon_radiobuton_sau_khi_auto_complete("rad_category","hdn_chuyen_muc_duoc_chon","tong_so_chuyen_muc","txt_category",0,"frmUpload") ');
</script>
<?php */ ?>