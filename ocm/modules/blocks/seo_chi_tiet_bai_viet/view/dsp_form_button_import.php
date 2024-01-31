<?php                    
if ($this->getPerm('admin,edit')) {                  
    ?>
    <a class="button_big btn_grey button_update" href="javascript:btn_update_onclick(document.frm_dsp_all_item, '<?php html_link('ajax/'.$this->className().'/act_update_seo_chi_tiet_bai_viet_import'); ?>', 'frm_submit','Bạn có chắc chắn muốn import dữ liệu đã chọn?');">Cập nhật Dữ liệu đã chọn</a>					
    <?php 
}           
if ($this->getPerm('admin,edit')) {                  
   ?>
   <a class="button_big btn_grey button_update" href="javascript:if (confirm('Bạn có thực sự cập nhật tất cả các keyword link?')) {frm_submit(document.frm_dsp_all_item, '<?php html_link('ajax/'.$this->className().'/act_update_seo_chi_tiet_bai_viet_import/1') ?>', 'iframe_submit')};">Cập nhật Dữ liệu tất cả</a>					
   <?php 
}                     
?>