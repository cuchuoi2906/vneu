<div class="greyBox">
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td>
                <?php 
                // Nếu màn hình danh sách bài tham khảo
                if($_SERVER['REQUEST_URI'] == '/ocm/magazine/dsp_danh_sach_bai_tham_khao'){
                    if ($this->getPerm('admin,view')) { ?>
                        <a class="button_big btn_grey" href="<?php html_link($this->className() . '/index'); ?>">Quay lại màn hình QTND magazine</a>
                    <?php }
                    if ($this->getPerm('admin,update')) { ?>
                        <a class="button_big btn_grey" href="javascript:btn_update_onclick(document.frm_dsp_all_item, '<?php html_link('ajax/'. $this->className() . '/act_update_status_magazine'); ?>', 'iframe_submit','Bạn có chắc chắn muốn lưu thay đổi?');">Lưu thay đổi</a>
                    <?php }
                }else{
                    if ($this->getPerm('admin,update')) { ?>
                        <a class="button_big btn_grey" href="<?php html_link($this->className() . '/dsp_single_magazine/0?goback=' . $goback); ?>">Thêm mới</a>
                    <?php }
                    if ($this->getPerm('admin,update')) { ?>
                        <a class="button_big btn_grey" href="javascript:btn_update_onclick(document.frm_dsp_all_item, '<?php html_link('ajax/'. $this->className() . '/act_update_status_magazine'); ?>', 'iframe_submit','Bạn có chắc chắn muốn lưu thay đổi?');">Lưu thay đổi</a>
                    <?php }
                    if ($this->getPerm('admin,delete')) { ?>
                        <a class="button_big btn_grey" href="javascript:btn_remove_onclick(document.frm_dsp_all_item, '<?php html_link('ajax/'. $this->className() . '/act_delete_magazine'); ?>', 'iframe_submit','Bạn có chắc chắn muốn xóa ?');">Xóa</a>
                    <?php } 
                    if ($this->getPerm('admin,view_bai_tham_khao')) { ?>
                        <a class="button_big btn_grey" href="<?php html_link($this->className() . '/dsp_danh_sach_bai_tham_khao'); ?>">Xem danh sách bài magazine tham khảo</a>
                    <?php } 
                }
                ?>
            </td>
            <td align="right">
                Hiển thị 
                <input class="textbox" type="text" style="width:20px" name="number_per_page" id="number_per_page" value="<?php echo $number_per_page; ?>" onchange="<?php if ($p_first ==false) { echo 'document.frm_dsp_filter.number_per_page.value=this.value;';}?>document.frm_dsp_filter.submit()"> bài viết/trang &nbsp;&nbsp;&nbsp;<?php
                // phan trang co su dung ajax
                echo _page_nav($phan_trang, $this->_getCurrentUri(false), 'div_dsp_all_item');
                // phan trang ko su dung ajax
                // echo _page_nav($phan_trang, $this->_getCurrentUri(false));
                ?>
            </td>
        </tr>
    </table>
</div>