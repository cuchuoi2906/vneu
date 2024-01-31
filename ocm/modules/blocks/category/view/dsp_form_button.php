<div class="greyBox">    
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr class ="tr_button">
            <td>
                <?php                    
                if ($this->getPerm('admin,edit')) {                  
                    ?>
                    <a class="button_big btn_grey" href="javascript:btn_add_onclick(document.frm_dsp_all_item, '<?php html_link('category/dsp_single_category/0?goback='.$this->_getCurrentUri()); ?>', '');">Thêm mới</a>
                    <?php 
                }
                if ($this->getPerm('admin,delete')) {
                    ?>
                    <a class="button_big btn_grey button_update" href="javascript:btn_delete_onclick(document.frm_dsp_all_item, '<?php html_link('ajax/category/act_delete_category'); ?>', 'frm_submit', 'tr_button');">Xóa</a>
                    <?php 
                }
                if ($this->getPerm('admin,edit')) {                  
                    ?>
                    <a class="button_big btn_grey button_update" href="javascript:btn_update_onclick(document.frm_dsp_all_item, '<?php html_link('ajax/category/act_update_category_list'); ?>', 'frm_submit','Bạn có chắc chắn muốn lưu thay đổi?', 'tr_button');">Lưu thay đổi</a>
                    <?php 
                }                   
                ?>
            </td>
            <td align="right">
                Hiển thị <input class="textbox" type="text" style="width:20px" name="number_per_page" id="number_per_page" value="<?php echo $number_per_page; ?>" onchange="document.frm_dsp_filter.number_per_page.value = this.value; document.frm_dsp_filter.submit()"> chuyên mục/trang &nbsp;&nbsp;&nbsp;
                <?php
                // phan trang co su dung ajax
                echo _page_nav($phan_trang, $this->_getCurrentUri(false), 'div_dsp_all_item');
                // phan trang ko su dung ajax
                // echo _page_nav($phan_trang, $this->_getCurrentUri(false));
                ?>
            </td>
        </tr>
    </table>   
</div>