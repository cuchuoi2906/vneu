<!-- filter -->
<div class="greyBox-light">
    <form action="" method="get" name="frm_search_template">
        <input type="hidden" name="stt" value="<?php echo $v_stt; ?>">
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td width="100" class="tbLabel" height="30">Tên template:</td>
                <td><input type="text" name="txt_template_name" value="<?php echo $this->_GET['txt_template_name']; ?>" style="width:300px" /></td>
                <td>
                    <input type="submit" value="Tìm"/>
                    <input type="button" value="Huỷ tìm kiếm" onclick="javascript:location.href='<?php html_link($this->className().'/dsp_choose_template_magazine?stt='.$v_stt.'&reset_filter=1'); ?>';" />
                </td>
            </tr>
        </table>
    </form>
</div>
<!-- top nav -->
<div style="padding:8px 0">
    <div style="float:left">
        <input type="image" src="<?php html_image('images/btn-chon.gif')?>" onclick="return chooseTemplate(document.frm_dsp_all_item);" />
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
        <col width ="3%"/><col width ="25%"/><col width ="25%"/><col width ="3%"/>
        
        <tr>
            <td class="rowTitle">#</td>
            <td class="rowTitle">Tên template</td>
            <td class="rowTitle">Ghi chú</td>          
            <td class="rowTitle">preview</td>            
        </tr>
        <?php   
        // Hien thi danh sach
        for ($i=0; $i < $v_record_count; $i++) {

            $v_template_id      = $v_templates[$i]['pk_magazine_template'];
            $v_title            = $v_templates[$i]['c_name'];
            $v_position         = $v_templates[$i]['c_position'];
            $v_edited_by        = $v_templates[$i]["c_edited_by"];
            $v_created_by       = $v_templates[$i]["c_created_by"];
            $v_description      = $v_templates[$i]["c_description"];
            $v_status           = $v_templates[$i]["c_status"];
            $v_use_status       = $v_templates[$i]["c_use_status"];
            $v_edited_at        = date_format(date_create($v_templates[$i]["c_edited_at"]), 'd-m-Y H:i:s');
            $v_created_at       = date_format(date_create($v_templates[$i]["c_created_at"]), 'd-m-Y H:i:s');
            $v_editor_name      = get_name_of_editor($v_edited_by);
            $v_creator_name     = get_name_of_editor($v_created_by);
           ?>
            <tr class="rowContent-g">
                <td align="center">
                    <input type="radio" name="rad_template_id" value="<?php echo $v_template_id; ?>">
                </td>
                <td class="title">
                    <b><a class="baiviet-tit" href="javascript:void(0)"><?php echo $v_template_id." - ".strip_tags($v_title); ?></a></b>
                </td>
                <td align="center">
                    <div style="max-height: 70px;overflow-y: auto">
                      <div>
                        <?php echo $v_description; ?>
                      </div>
                    </div>
                </td>
                <td align="center">
                    <a href="javascript:void(0)" onclick="window.open('<?php html_link('ajax/template_magazine/act_preview_template_magazine/' . $v_template_id); ?>', 'new_window_2', 'width=1000, height=700,toolbar=no,status=yes,menubar=no,scrollbars=yes,resizable=yes')">
                        <img src="<?php html_image('images/imgpreview.gif');?>" width="16" height="16" />
                    </a>
                </td>
            </tr>
    <?php
    } // for
    ?>
    </table>
</form>
<!-- bottom nav -->
<div style="padding-top:10px">
    <div style="float:left">
        <input type="image" src="<?php html_image('images/btn-chon.gif')?>" onclick="return chooseTemplate(document.frm_dsp_all_item);" />
        <input type="image" src="<?php html_image('images/btn-thoat.gif')?>" onclick="window.close();" />
    </div>
    <div style="float:right">
        <?php echo _page_nav($v_paging, $this->_getCurrentUri(false)); ?>
    </div>
    <div class="clear"></div>
</div>
<script type="text/javascript">
    function chooseTemplate() {
        var v_template_id = document.frm_dsp_all_item.rad_template_id.value;
        var v_stt = document.frm_search_template.stt.value;
        if (!v_template_id) {
            alert('Chưa có template nào được chọn!'); return false;
        }
        var target = 'sel_template_id' + v_stt;
        // window.opener.document.getElementById(target).value = v_template_id;
        // cac thong tin post sang parent window
        var obj = {};
        obj.msg_type = 'trigger_template_id_change';
        obj.target = '#' + target;
        obj.value = v_template_id;
        obj.stt = v_stt;
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