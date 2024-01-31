<?php
$v_list_form_title = 'Quản trị Title,des,key,slug cho bài viết';
html_set_title($v_list_form_title);
$v_obj = new seo_chi_tiet_bai_viet_block();
$v_obj->index();
