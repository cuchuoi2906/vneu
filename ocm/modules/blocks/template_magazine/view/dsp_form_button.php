<div class="greyBox">
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td>
                <?php if ($this->getPerm('admin,update')) { ?>
                    <a class="button_big btn_grey <?php echo $this->getPerm('admin,update,create') ? 'btnCreate' : ''; ?>" href="javascript:void(0)">Thêm mới</a>
                <?php }
                if ($this->getPerm('admin,update,publish')) { ?>
                    <a class="button_big btn_grey" href="javascript:btn_update_onclick(document.frm_dsp_all_item, '<?php html_link('ajax/'. $this->className() . '/act_update_order_template_magazine'); ?>', 'iframe_submit','Bạn có chắc chắn muốn lưu thay đổi?');">Lưu thay đổi</a>
                <?php }
                if ($this->getPerm('admin,delete')) { ?>
                    <a class="button_big btn_grey" href="javascript:btn_remove_onclick(document.frm_dsp_all_item, '<?php html_link('ajax/'. $this->className() . '/act_remove_magazine_template'); ?>', 'iframe_submit','Bạn có chắc chắn muốn xóa ?');">Xóa</a>
                <?php } ?>
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