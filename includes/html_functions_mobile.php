<?php
/*
 * Đường dẫn tạo link html image
 * @pram
 *   $url       url cần tạo đường dẫn
 *   $echo      có hiển thị luôn dữ liệu trong hàm
 * return string
 **/
function html_image($url, $echo=true)
{
    if (USE_IMAGE_STATIC) {
        if (!preg_match('#^http://#', $url)) {
            $url = preg_replace('#^[/]{0,1}images#', IMAGE_STATIC.'images', $url);
            $url = preg_replace('#^[/]{0,1}upload#', IMAGE_NEWS.'upload', $url);
			$v_arr_image_stored_folder = explode(',', IMAGE_STORED_FOLDER);
			for ($i = 0, $s = sizeof($v_arr_image_stored_folder); $i < $s; ++$i) {
				$url = str_replace(IMAGE_NEWS.$v_arr_image_stored_folder[$i], IMAGE_STORED_URL.$v_arr_image_stored_folder[$i], $url);
				$url = str_replace(str_replace(':8008', '', IMAGE_NEWS).$v_arr_image_stored_folder[$i], IMAGE_STORED_URL.$v_arr_image_stored_folder[$i], $url);
			}
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