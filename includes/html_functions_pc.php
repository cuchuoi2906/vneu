<?php
/*
 * hàm thực hiện đưa chuỗi url image về đúng link chuẩn
 * @param
 *  $p_ma_gia_tri   mã giá trị
 * return: html 
 **/
function html_image($url, $echo=true)
{
    if (USE_IMAGE_STATIC) {
        if (!preg_match('#^http://#', $url)) {
			//End 15-02-2017 : Thangnb tinh_chinh_giao_dien_trang_bai_viet_anh_24h_pc
            $url = preg_replace('#^[/]{0,1}upload#', IMAGE_NEWS.'upload', $url);
        }
    } else {
        if (preg_match('#^http://#', $url)) {
            $new_image = $url;
        } else if (preg_match('#^/#', $url)) {
            $new_image = $url;
        } else {
            $new_image = '/'.$url;
        }
        $url = $new_image;
    }
    if ($echo) {
        echo $url;
    }
    return $url;
}