<div class="greyBox">    
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td>
                <?php                    
                if ($this->getPerm('admin,edit')) {                  
                    ?>
                    <a class="button_big btn_grey" href="javascript:btn_add_onclick(document.frm_dsp_all_item, '<?php html_link('seo_chi_tiet_bai_viet/dsp_single_seo_chi_tiet/0?goback='.$this->_getCurrentUri()); ?>', '');">Thêm mới</a>
                    <?php 
                }
                if ($this->getPerm('admin,delete')) {
                    ?>
                    <a class="button_big btn_grey" href="javascript:btn_delete_onclick(document.frm_dsp_all_item, '<?php html_link('ajax/seo_chi_tiet_bai_viet/act_delete_seo_chi_tiet'); ?>', 'frm_submit');">Xóa</a>
                    <?php 
                }
                if ($this->getPerm('admin,publish')) {                  
                    ?>
                    <a class="button_big btn_grey" href="javascript:btn_update_onclick(document.frm_dsp_all_item, '<?php html_link('ajax/seo_chi_tiet_bai_viet/act_update_seo_chi_tiet_list'); ?>', 'frm_submit','Bạn có chắc chắn muốn lưu thay đổi?');">Lưu thay đổi</a>
                    <?php 
                }
                ?>
            </td>
            <td align="right">
                Hiển thị <input class="textbox" type="text" style="width:20px" name="number_per_page" id="number_per_page" value="<?php echo $number_per_page; ?>" onchange="document.frm_dsp_filter.number_per_page.value=this.value; document.frm_dsp_filter.submit()"> bản ghi/trang &nbsp;&nbsp;&nbsp;
                <?php
                // phan trang co su dung ajax
                echo _page_nav($phan_trang, $this->_getCurrentUri(false), 'div_dsp_all_item');
                ?>
            </td>
        </tr>
    </table>   
</div>