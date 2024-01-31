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
            <col width ="3%"/><col width ="3%"/>
            <col width ="20%"/><col width ="15%"/><col width ="25%"/><col width ="8%"/><col width ="8%"/>
            <tr>
                <td class="rowTitle"><input type="checkbox" name="chk_item_all" value="" onclick ="check_all(document.frm_dsp_all_item, this);"></td>               
                <td class="rowTitle">#</td>
                <td class="rowTitle">Tên nội dung bài magazine</td>     
                <td class="rowTitle">Template sử dụng (từ trên xuống)</td>
                <td class="rowTitle">ID bài viết magazine</td>
                <td class="rowTitle">Trạng thái tích chọn bài tham khảo</td>
                <td class="rowTitle">Chuyên mục</td>
                <td class="rowTitle">Trạng thái Xuất bản</td>            
                <td class="rowTitle">Thao tác</td>
            </tr>
            <?php   
            // Hien thi danh sach
            for ($i=0; $i < $v_record_count; $i++) {

                $v_magazine_id      = $v_magazines[$i]['pk_magazine'];
                $v_title            = $v_magazines[$i]['c_name'];
                $v_edited_by        = $v_magazines[$i]["c_edited_by"];
                $v_created_by       = $v_magazines[$i]["c_created_by"];
                $v_status           = $v_magazines[$i]["c_status"];
                $v_edited_at        = date_format(date_create($v_magazines[$i]["c_edited_at"]), 'd-m-Y H:i:s');
                $v_created_at       = date_format(date_create($v_magazines[$i]["c_created_at"]), 'd-m-Y H:i:s');
                $v_editor_name      = get_name_of_editor($v_edited_by);
                $v_creator_name     = get_name_of_editor($v_created_by);
                $v_bai_tham_khao           = $v_magazines[$i]["c_bai_tham_khao"];
               ?>
                <tr class="rowContent-g">
                    <td align="center">
                        <input type="checkbox" name="chk_item_id<?php echo $i; ?>" value="<?php echo $v_magazine_id; ?>">
                    </td>
                    <td align="center">
                        <a href="javascript:void(0)" onclick="window.open('<?php html_link($this->className() .'/act_preview_magazine/'.$v_magazine_id)?>', 'preview_magazine', 'width=1300, height=700,toolbar=no,status=yes,menubar=no,scrollbars=yes,resizable=yes')">
                            <img src="<?php html_image('images/imgpreview.gif');?>" width="16" height="16" />
                        </a>
                    </td>
                    <td class="title">
                        <b><a class="baiviet-tit" href="<?php html_link($this->className() .'/dsp_single_magazine/'.$v_magazine_id . '?goback=' . $goback); ?>"><?php echo strip_tags($v_title); ?></a></b>
                        <br/>Người tạo: <?php echo $v_creator_name; ?>; Người sửa: <?php echo $v_editor_name; ?>; 
                        <br/>Thời gian sửa cuối: <?php echo date('d/m/Y H:i:s', strtotime($v_edited_at)); ?>
                    </td>
                    <td>
                        <?php echo $v_magazines[$i]["info_mgz_used"]; ?>
                    </td>
                    <td>
                        <?php echo str_replace(',', ', ', $v_magazines[$i]["str_news_ids"]); ?>
                    </td>
                    <td class="title" align="center">
                        <?php
                            if ($this->getPerm('admin,add_bai_tham_khao')) {
                                echo html_select_box('sel_bai_tham_khao'.$i, $v_arr_trang_thai_bai_tham_khao, 'c_code', 'c_name', $v_bai_tham_khao, $extend='onchange="document.frm_dsp_all_item.chk_item_id'.$i.'.checked=true;"', $add_option=0); 
                            }else{
                                $v_html_bai_tham_khao = 'Chưa tích chọn';
                                $v_html_bai_tham_khao = ($v_bai_tham_khao == 1) ? 'Đã tích chọn' : $v_html_bai_tham_khao;
                                echo $v_html_bai_tham_khao;
                            }
                            ?>             
                    </td>
                    <td align="center"><?php echo str_replace(',', ', ', $v_magazines[$i]["str_cat_name"]); ?></td>
                    <td class="title" align="center">
                        <?php echo html_select_box('sel_publish_status'.$i, $v_arr_trang_thai, 'c_code', 'c_name', $v_status, $extend='onchange="document.frm_dsp_all_item.chk_item_id'.$i.'.checked=true;"', $add_option=0); ?>             
                    </td>
                    <td align ="center">
                        <a href="<?php html_link($this->className() . '/dsp_all_magazine_history/'.$v_magazine_id.'?title='.fw24h_base64_url_encode($v_title).'&goback='.$goback); ?>">Xem lịch sử sửa đổi</a>
                        </br>
                        <a href="<?php html_link($this->className() . '/dsp_single_magazine/?id_magazine='.$v_magazine_id.'&title='.fw24h_base64_url_encode($v_title).'&goback='.$goback); ?>">Nhân bản dữ liệu</a>
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
<script type="text/javascript">
    $('.preview_mzn_link').on('click', function (e) {
        var that = $(this);
        <?php if (!$this->getPerm('admin,update')) { ?>
            alert('Bạn không có quyền thực hiện chức năng này.'); return false;
        <?php } else { ?>
            var news_id = that.attr('data-news-id');

            if (parseInt(that.attr('data-news-status')) == 10 
                && parseInt(that.attr('data-news-category-status')) > 0) {
                window.open($(this).attr('data-href'), '_blank');
            } else {
                window.open('<?php html_link('news_common/dsp_news_preview_by_edit/'); ?>' + news_id,'newWindow','width=900,height=600','menubar=0,toolbar=0,status=0,scrollbars=1,resizable=1')
            }
        <?php } ?>
    });
</script>