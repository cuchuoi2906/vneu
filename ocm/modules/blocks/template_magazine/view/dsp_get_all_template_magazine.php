<link rel="stylesheet" type="text/css" href="<?php html_link('css/magazine.css'); ?>">
<div id="div_dsp_all_item">
<?php
$v_filter_box_on_top = $this->dsp_filter_box_top();
if (!$this->autoRender) {
    echo $v_filter_box_on_top;
}
?>
    <form action="" method="POST" name="frm_dsp_all_item" target="iframe_submit">
        <input type="hidden" name="goback" value="<?php echo $goback; ?>">
        <input type="hidden" id="hdn_record_count" name="hdn_record_count" value="<?php echo $v_record_count; ?>">
        <table width="100%" cellpadding="1" cellspacing="1" border="0" class="listing">
            <col width ="3%"/><col width ="3%"/><col width ="5%"/>
            <col width ="25%"/><col width ="25%"/><col width ="10%"/><col width ="8%"/><col width ="8%"/>
            <tr>
                <td class="rowTitle"><input type="checkbox" name="chk_item_all" value="" onclick ="check_all(document.frm_dsp_all_item, this);"></td>               
                <td class="rowTitle">#</td>
                <td class="rowTitle">Trọng số</td>                     
                <td class="rowTitle">Tên template magazine</td>      
                <td class="rowTitle">Ghi chú</td>          
                <td class="rowTitle">Trạng thái Xuất bản</td>            
                <!-- <td class="rowTitle">Trạng thái sử dụng</td> -->
                <td class="rowTitle">Lịch sử sửa đổi</td>
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
                        <input type="checkbox" name="chk_item_id<?php echo $i; ?>" value="<?php echo $v_template_id; ?>">
                    </td>
                    <td align="center">
                        <a target="_blank" href="javascript:void(0)" onclick="window.open('<?php html_link('ajax/' . $this->className().'/act_preview_template_magazine/' . $v_template_id); ?>', 'new_window', 'width=1000, height=700,toolbar=no,status=yes,menubar=no,scrollbars=yes,resizable=yes')">
                            <img src="<?php html_image('images/imgpreview.gif');?>" width="16" height="16" />
                        </a>
                    </td>
                    <td align="center">
                        <input type="textbox" name="txt_order<?php echo $i; ?>" value="<?php echo $v_position; ?>" onchange ="document.frm_dsp_all_item.chk_item_id<?php echo $i; ?>.checked=true;" style="width:30px"/>
                    </td>
                    <td class="title">
                        <b><a class="baiviet-tit" href="<?php echo $this->getPerm('admin,update') ? html_link($this->className() .'/dsp_magazine_template/'.$v_template_id . '?goback=' . $goback, false) : 'javascript:void(0)'; ?>"><?php echo $v_template_id." - ".strip_tags($v_title); ?></a></b>
                        <br/>Người tạo: <?php echo $v_creator_name; ?>; Người sửa: <?php echo $v_editor_name; ?>; 
                        <br/>Sửa lần cuối: <?php echo $v_edited_at; ?>
                    </td>
                    <td align="center">
                        <div style="max-height: 70px;overflow-y: auto">
                          <div>
                            <?php echo $v_description; ?>
                          </div>
                        </div>
                    </td>
                    <td class="title" align="center">
                        <?php echo html_select_box(($this->getPerm('admin,publish') ? 'sel_publish_status'.$i : 'c_status_'.$i), $v_arr_trang_thai, 'c_code', 'c_name', $v_status, $extend='onchange="document.frm_dsp_all_item.chk_item_id'.$i.'.checked=true;"'. ($this->getPerm('admin,publish') ? '' : ' disabled'), $add_option=0); ?>
                        <?php if(!$this->getPerm('admin,publish')) { ?>
                          <input type="hidden" name="<?php echo 'sel_publish_status'.$i; ?>" value="<?php echo $v_status; ?>">
                        <?php } ?>
                    </td>
                    <!-- <td align ="center"><?php echo $v_use_status > 0 ? 'Đã sử dụng' : 'Chưa sử dụng'; ?></td> -->
                    <td align="center">
                        <a href="<?php html_link($this->className() . '/dsp_all_magazine_template_history/'.$v_template_id.'?title='.fw24h_base64_url_encode($v_title).'&goback='.$goback); ?>">Xem lịch sử sửa đổi</a>
                    </td>
                </tr>
        <?php
        } // for
        ?>
        </table>
        <?php
        html_no_data($v_record_count);
        ?>
        <iframe name="iframe_submit" class="iframe-form"></iframe>
    </form><?php
    $v_html = $this->dsp_form_button(false);
    if (!$this->autoRender) {
        echo $v_html;
    }?>
</div> 
<!-- modal -->
<div id="dialog-form" title="Tạo template"> 
  <form action='<?php html_link('ajax/'. $this->className().'/act_create_magazine_template'); ?>' method='post' id='frmCreateTemplate' class="frm frm-stacked">
 <div class="wrapper">
         <div class="row">
             <div class="col-1-1">
                <input type="hidden" name="goback" value="<?php echo $goback; ?>">
                 <div class="control-group">
                     <label for="c_name">Tên template <span class="redText">(*)</span></label>
                     <input id="c_name" name="c_name" type="text" value="" class="fluid ui-input">
                     <small id="c_name_error" class="redText"></small>
                 </div>
             </div>
         </div>
     </div>
   </form>
 </div>
 <script type="text/javascript">
    var form = $("#frmCreateTemplate");
    function createTemplate() {
      var ten_template = $('#c_name').val();
      if(!ten_template) {
        $('#c_name_error').html('Vui lòng nhập tên template');
      } else {
         $.ajax({
             type : 'POST',
             url : form.attr('action'),
             data : form.serialize(),
             success: function(res) {
               console.log(res);
               var obj = JSON.parse(res);

               if(!obj.error) {
                  dialog.dialog( "close" );
                  window.location.href = obj.data.redirect_link;
               } else {
                  $('#c_name_error').html(obj.msg);
               }
               
            }
        });
      }
    }
    form.submit(function () {
      return false;
    });
   var dialog = $( "#dialog-form" ).dialog({
           resizable: false,
           autoOpen: false,
           height: "auto",
           width: 500,
           modal: true,
           buttons: {
               "Tạo": createTemplate,
               "Hủy": function() {
                 dialog.dialog( "close" );
               }
           },
           close: function() {
               // form.reset();
           }
       });
       $( ".btnCreate" ).on( "click", function() {
         dialog.dialog( "open" );
       });
</script>