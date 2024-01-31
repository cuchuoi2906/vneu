<?php
$v_list_form_title = 'Trang nhập tin bài thường';
if (isset($_GET['type'])) {
	if ($_GET['type'] == 'video') {
		$v_list_form_title = 'Trang nhập tin bài video';
	} elseif ($_GET['type'] == 'album') {
		$v_list_form_title = 'Trang nhập tin bài ảnh';
	// begin 31/08/2016 TuyenNT trang_bai_viet_tong_hop_su_kien_infographic_backend
    }elseif($_GET['type'] == 'infograpfic') {
        $v_list_form_title = 'Trang nhập tin bài infographic';
    }
    // end 31/08/2016 TuyenNT trang_bai_viet_tong_hop_su_kien_infographic_backend
}
html_set_title($v_list_form_title);
$v_obj = new news_block();
?>
<div><span class="contentTitle"><?php echo $v_list_form_title; ?></span></div>
<div class="line-dot"></div>
<?php
$v_obj->index();