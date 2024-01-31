<?php

if ($row_news['Type'] == 2){
    $v_object = new header_magazine_block();
    $v_object->autoRender = false;
    $v_object->index($cat_id, $region_id);
    $v_header = $v_object->blockContent;
    $v_header = replace_template_header_footer($v_header,$rs_template);
    $v_header = replace_domain_static_images($v_header);
    
    $v_header = xu_ly_load_magazine_head_files($v_header, $v_arr_magazine_content);
    
    gnud_add_request_services_tier('header', '/ajax/header_magazine/index/'.$cat_id.'/0/pc/0/0', 'gnud_replace_with_services_tier_content', 'function', array('data'=>$v_header), 'filter_content');

    $v_object = new footer_magazine_block();
    $v_object->autoRender = false;
    $v_object->setParam('v_device_global', $v_device_global);
    $v_object->index($cat_id, 0);
    $v_footer = $v_object->blockContent;
    $v_footer = replace_template_header_footer($v_footer,$rs_template);
    $v_footer = replace_domain_static_images($v_footer);
    
    gnud_add_request_services_tier('footer', '/ajax/footer_magazine/index/'.$cat_id.'/pc/0/0', 'gnud_replace_with_services_tier_content', 'function', array('data'=>$v_footer), 'filter_content');
}else{

    // tao noi dung tu block
   $v_object = new header_block();
   $v_object->setParam('v_device_global', $v_device_global);
   $v_object->autoRender = false;
   $v_object->index($cat_id);
   $v_header = $v_object->blockContent;
   $v_header = replace_template_header_footer($v_header,$rs_template);
   // 
   //$v_header = html_load_header_css($v_header, CHEN_NOI_DUNG_CSS_VAO_HTML, MINIFY_JS_CSS,'home_092018','',$v_device_global);
   // Đưa js xuống dưới footer
   //$v_header = html_load_header_js($v_header, CHEN_NOI_DUNG_JS_VAO_HTML, MINIFY_JS_CSS, '', '', $v_header);

   gnud_add_request_services_tier('header', '/ajax/header/index/'.$cat_id.'/'.NAME_THIET_BI_PC.'/0/0', 'gnud_replace_with_services_tier_content', 'function', array('data'=>$v_header), 'filter_content');

   $v_object = new footer_block();
   $v_object->setParam('v_device_global', $v_device_global);
   $v_object->autoRender = false;
   $v_object->index($cat_id);
   $v_footer = $v_object->blockContent;
   $v_footer = replace_template_header_footer($v_footer,$rs_template);

   gnud_add_request_services_tier('footer', '/ajax/footer/index/'.$cat_id.'/'.NAME_THIET_BI_PC.'/0/0', 'gnud_replace_with_services_tier_content', 'function', array('data'=>$v_footer), 'filter_content');

}