<script type="text/javascript">var fileNumber = 0;</script>
<div id="upload_result"></div>
<?php /* Begin anhpt1 24/5/2016 chuc_nang_upload_video */ ?>
<form name="frm_Upload_image_video" method="post" action="" enctype="multipart/form-data" class="padBot" target="frm_submit">
    <div id="image_video" style="width: 570px;"></div>
</form>
<form name="frm_Upload_image_gif_video" method="post" action="" enctype="multipart/form-data" class="padBot" target="frm_submit">
    <div id="image_gif_video" style="width: 770px;"></div>
</form>
<?php /* End anhpt1 24/5/2016 chuc_nang_upload_video */ ?>
<form name="frmUpload" method="post" action="<?php html_link($this->className().'/act_upload/'); ?>" enctype="multipart/form-data" class="padBot" target="frm_submit">
    <input type="hidden" name="hdn_chuyen_muc_duoc_chon" id="hdn_chuyen_muc_duoc_chon" value="" />
    <input type="hidden" name="tong_so_chuyen_muc" id="tong_so_chuyen_muc" value="<?php echo count($v_arr_category_by_select);?>"/>
    <table border="0" cellspacing="0" cellpadding="3">        
        <tr>
            <td style="width: 60px;">Loại video</td>
            <td style="width: 80px;">
                <select size="5" style="width:2" id="sel_loai_video" name="sel_loai_video" onchange="load_div_loai_video_nivea(this.value);"><?php      
                    $sel_loai_video = $sel_loai_video==''? $rs_upload_video_conf['loai_video_mac_dinh_duoc_chon']:$sel_loai_video;
                    for($i=0, $s= sizeof($rs_loai_video); $i<$s; $i++) { 
                        $selected ='';
                        if($rs_loai_video[$i]['c_code'] == $sel_loai_video) {
                            $selected ='selected';
                        }?>
                        <option value='<?php echo $rs_loai_video[$i]['c_code'];?>' title="<?php echo $rs_loai_video[$i]['c_name'];?>" <?php echo $selected; ?>><?php echo $rs_loai_video[$i]['c_name'];?></option>
                        <?php 
                    } ?>	
                </select>
            </td>
            <td>
                <div id="div_loai_video_nivea" style="display:none;">
                    <table>
                        <tr><td style="width: 170px;">
                            Chọn giải đấu <span style="color:red;">*(Việc chọn giải đấu bắt buộc đối với loại "Chiến dịch đặc biệt" để phục vụ TTQC tracking số liệu video theo từng giải)</span></td>
                        </td>
                        <td>
                            <select size="5" id="sel_loai_giai_dau" name="sel_loai_giai_dau"><?php 
                                // Lấy loại giải đấu nivea
                                $v_arr_loai_giai_dau_nivea = get_arr_campaign();
                                $sel_loai_video = $sel_loai_video==''? $rs_upload_video_conf['loai_video_mac_dinh_duoc_chon']:$sel_loai_video;
                                for($i=0, $s= sizeof($v_arr_loai_giai_dau_nivea); $i<$s; $i++) { ?>
                                    <option value='<?php echo $v_arr_loai_giai_dau_nivea[$i]['c_code'];?>' title="<?php echo $v_arr_loai_giai_dau_nivea[$i]['c_name'];?>" ><?php echo $v_arr_loai_giai_dau_nivea[$i]['c_name'];?></option>
                                    <?php 
                                } ?>	
                            </select>
                        </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            <td valign="top" width="400" colspan=2>
                <div id="uploadContent" style="margin-left: 15px;"></div>
                    <?php  /*Begin 14-06-2014 trungcq tang_so_luong_upload_video*/?>
                    <div style="padding:5px" name="div_add_file" id="div_add_file">
                        <a href="javascript:;" onclick="addFile('uploadContent', 'file_video', 1)">
                            <img src="<?php html_image('images/add.png'); ?>" align="absmiddle" width="16" height="16" />
                            Thêm file
                        </a>
                    </div>
                    <?php  /*End 14-06-2014 trungcq tang_so_luong_upload_video*/?>
                <div style="padding-top:10px;padding-left: 65px;">
                    <input type="submit" value="Upload file" id="upload_file_video" />
                    <input type="button" value="Đóng cửa sổ" onclick="window.close()" />
                </div>
            </td>
            <?php /* Tam thoi khong dung chuyen muc khi chua co gioi han cho tung chuyen muc ?>
            <td width="10">&nbsp;</td>
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
<h3 style="font-weight:bold;color:red;height:30px;"></h3>
<h3 style="font-weight:bold;color:red;height:30px;">Kích thước video phải đúng tỉ lệ 16:9</h3>
<div id="upload_result_2" style="padding-left: 67px;"></div>
<table border="1" cellpadding="5" cellspacing="0" width="470" style="border-collapse:collapse;background-color:#ccffcc">
    <caption style="font-weight:bold;color:red;height:30px;">QUY ĐỊNH VỀ DUNG LƯỢNG 1 FILE VIDEO ĐƯỢC PHÉP UPLOAD THEO CHUYÊN MỤC</caption>
    <tr>
        <th align="center">Chuyên mục</th>
        <th align="center">Số lượng file video được upload tối đa 1 lần</th>
        <th align="center">Dung lượng tối đa</th>
    </tr>
    <?php  /*Begin 14-06-2014 trungcq tang_so_luong_upload_video*/?>
    <tr>
        <td align="center">Thời trang</td>
        <td align="center">5</td>
        <td align="center">200MB</td>
    </tr>
    <tr>
        <td align="center">Ca nhạc</td>
        <td align="center">5</td>
        <td align="center">200MB</td>
    </tr>
    <tr>
        <td align="center">Phim</td>
        <td align="center">5</td>
        <td align="center">200MB</td>
    </tr>
    <tr>
        <td align="center">Bạn trẻ cuộc sống</td>
        <td align="center">5</td>
        <td align="center">200MB</td>
    </tr>
    <tr>
        <td align="center">Các mục khác</td>
        <td align="center">5</td>
        <td align="center">200MB</td>
    </tr>
    <?php  /*End 14-06-2014 trungcq tang_so_luong_upload_video*/?>
</table>
<div style="margin-top: 10px;border: 1px solid black;background-color: #ccffcc;width:500px;"> 
<span class="note-upload-video" style="color:red;font-weight:700;font-size:15px;">NOTE:</span><br /><br />
<span class="title-note-upload-video" style="font-weight: 600;">- Chọn loại Video quảng cáo nivea: để code video sinh ra sẽ có mã "quangcaoWrite" </span><br /><br />
<span class="title-note-upload-video" style="font-weight: 600;">- Chọn loại video quảng cáo heineken để code video sinh ra sẽ có mã "heinekenWrite"</span><br /><br />
<span class="note-upload-video" style="color:red;font-weight:700;font-size:15px;">Chiến dịch Nivea</span><br /><br />
<span class="title-note-upload-video" style="font-weight: 600;">- Bài video highlight bàn thắng của giải Ngoại hạng anh</span><br /><br />
<span class="title-note-upload-video" style="font-weight: 600;">- Toàn bộ bài video có liên quan đội Real Madrid trong cúp C1</span><br /><br />
<span class="title-note-upload-video" style="font-weight: 600;">- Toàn bộ bài video có liên quan đội Real Madrid trong cúp LALIGA</span><br /><br />
<span class="note-upload-video" style="color:red;font-weight:700;font-size:15px;">Chiến dịch Heineken<span><br /><br />
<span class="title-note-upload-video" style="font-weight: 600;">- Bài video của giải C1 loại trừ các trận liên quan Real Madrid</span>
</div>
<iframe name="frm_submit" class="iframe-form"></iframe>
<?php  /*Begin 14-06-2014 trungcq tang_so_luong_upload_video*/?>
<script type="text/javascript">addFile('uploadContent', 'file_video', 1);</script>
<?php  /*End 14-06-2014 trungcq tang_so_luong_upload_video*/?>
<?php /* Tam thoi khong dung chuyen muc khi chua co gioi han cho tung chuyen muc ?>
<script type="text/javascript">
    <?php
    $v_arr_category_by_select = add_ascii_column($v_arr_category_by_select, 'Name');
    echo _tao_file_js_suggestion($v_arr_category_by_select,'ds_chuyen_muc','ID','Name_ascii','Name');
    ?>
	setAutoComplete('txt_category',ds_chuyen_muc,'hdn_chuyen_muc_duoc_chon',1,'chon_radiobuton_sau_khi_auto_complete("rad_category","hdn_chuyen_muc_duoc_chon","tong_so_chuyen_muc","txt_category",0,"frmUpload") ');
</script>
<?php */ ?>
<div id="loadding_page" class="none_load_page">
    <div class="container">
        <div class="center"></div>
        <div class="inner">
          <div class="inner__item" id="inner__item1"></div>
          <div class="inner__item" id="inner__item2"></div>
          <div class="inner__item" id="inner__item3"></div>
          <div class="inner__item" id="inner__item4"></div>
        </div>

        <div class="outer">
          <div class="outer__item" id="outer__item1"></div>
          <div class="outer__item" id="outer__item2"></div>
          <div class="outer__item" id="outer__item3"></div>
          <div class="outer__item" id="outer__item4"></div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
       $('#upload_file_video').click(function(){
           $('#loadding_page').addClass('show_load_page').removeClass('none_load_page');
       });
        $( ".target" ).change(function() {
            alert( "Handler for .change() called." );
        });
        v_max_size_video = <?php echo MAX_VIDEO_SIZE; ?>;
    });
</script>