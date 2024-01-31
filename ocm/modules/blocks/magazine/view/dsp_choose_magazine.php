<!-- filter -->
<div class="greyBox-light">
    <form action="" method="get" name="frm_search_magazine">
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td width="120" class="tbLabel" height="30" style="text-align: left;">Tên bài magazine:</td>
                <td><input type="text" name="txt_magazine_name" value="<?php echo $this->_GET['txt_magazine_name']; ?>" style="width:480px" /></td>
                <td>
                    <input type="submit" value="Tìm"/>
                    <input type="button" value="Huỷ tìm kiếm" onclick="javascript:location.href='<?php html_link($this->className().'/dsp_choose_magazine?reset_filter=1'); ?>';" />
                </td>
            </tr>
        </table>
    </form>
</div>
<!-- top nav -->
<div style="padding:8px 0">
    <div style="float:left">
        <input type="image" src="<?php html_image('images/btn-chon.gif')?>" onclick="return chooseMagazine(document.frm_dsp_all_item);" />
        <input type="image" src="<?php html_image('images/btn-thoat.gif')?>" onclick="window.close();" />
    </div>
    <div style="float:right">
        <?php echo _page_nav($v_paging, $this->_getCurrentUri(false)); ?>
    </div>
    <div class="clear"></div>
</div>
<!-- main content -->
<form action="" method="POST" name="frm_dsp_all_item">
    <input type="hidden" id="hdn_record_count" name="hdn_record_count" value="<?php echo $v_record_count; ?>">
    <table width="100%" cellpadding="1" cellspacing="1" border="0" class="listing">
        <col width ="3%"/><col width ="3%"/><col width ="50%"/><col width ="20%"/>
        
        <tr>
            <td class="rowTitle">#</td>
            <td class="rowTitle">Preview</td>
            <td class="rowTitle">Danh sách nội dung bài magazine</td>          
            <td class="rowTitle">Người tạo</td>            
        </tr>
        <?php   
        // Hien thi danh sach
        for ($i=0; $i < $v_record_count; $i++) {

            $v_magazine_id      = $v_magazines[$i]['pk_magazine'];
            $v_title            = $v_magazines[$i]['c_name'];
            $v_created_by       = $v_magazines[$i]["c_created_by"];
            $v_created_at       = date_format(date_create($v_magazines[$i]["c_created_at"]), 'H:i:s d/m/Y ');
            $v_creator_name     = get_name_of_editor($v_created_by);
           ?>
            <tr class="rowContent-g">
                <td align="center">
                    <input type="radio" name="rad_magazine_id" value="<?php echo $v_magazine_id; ?>">
                </td>
                <td align="center">
                    <a href="javascript:void(0)" onclick="window.open('<?php html_link('ajax/magazine/act_preview_magazine/' . $v_magazine_id); ?>', 'new_window_2', 'width=1300, height=700,toolbar=no,status=yes,menubar=no,scrollbars=yes,resizable=yes')">
                        <img src="<?php html_image('images/imgpreview.gif');?>" width="16" height="16" />
                    </a>
                </td>
                <td class="title">
                    <b><a class="baiviet-tit" href="javascript:void(0)"><?php echo strip_tags($v_title); ?></a></b>
                </td>
                <td align="center">
                    <div style="max-height: 70px;overflow-y: auto">
                      <div>
                        <?php echo $v_creator_name; ?><br>
                        <?php echo $v_created_at; ?>
                      </div>
                    </div>
                </td>
            </tr>
    <?php
    } // for
    ?>
    </table>
    <?php if ($v_record_count <= 0) { ?>
        <p style="color:red;text-align: center;">KO CÓ DỮ LIỆU, QUAY LẠI CÁC TRANG TRƯỚC ĐỂ XEM
        <br>(Đây ko phải là lỗi của OCM)</p>
    <?php } ?>
</form>
<!-- bottom nav -->
<div style="padding-top:10px">
    <div style="float:left">
        <input type="image" src="<?php html_image('images/btn-chon.gif')?>" onclick="return chooseMagazine(document.frm_dsp_all_item);" />
        <input type="image" src="<?php html_image('images/btn-thoat.gif')?>" onclick="window.close();" />
    </div>
    <div style="float:right">
        <?php echo _page_nav($v_paging, $this->_getCurrentUri(false)); ?>
    </div>
    <div class="clear"></div>
</div>
<script type="text/javascript">
    function chooseMagazine() {
        var v_magazine_id = document.frm_dsp_all_item.rad_magazine_id.value;
        if (!v_magazine_id) {
            alert('Chưa có đối tượng nào được chọn.'); return false;
        }
        // cac thong tin post sang parent window
        var obj = {};
        obj.msg_type = 'choose_magazine';
        obj.content = '<div class="data-embed-code-magazine">[[@magazine_' + v_magazine_id + '#]]</div>';
        obj.value = v_magazine_id;
        // gui data sang parent window
        window.close();
        sendMessage(obj);
        return false;
    }
    var sendMessage = function (obj) {
        // convert data sang json
        var msg = JSON.stringify(obj);
        window.opener.postMessage(msg, '*');
    };
</script>