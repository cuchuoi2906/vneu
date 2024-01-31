<?php

$template_fileupload_config = array(
    'allow_extensions' => array('jpg','jpeg','png','gif','html','htm','mp4','css','js','woff','woff2','otf','ttf','eot','svg','pjpeg', 'mp3'),
    'allow_mime_types' => array('image/jpeg','image/png','image/x-png','image/gif','text/html','video/mp4','text/css','text/javascript','application/javascript','application/x-javascript','font/woff','application/font-woff','font/woff2','font/otf','font/ttf','application/x-font-ttf','application/vnd.ms-fontobject','image/svg+xml', 'audio/mpeg', 'audio/mp3','application/octet-stream',' application/font-sfnt'),
    'max_file_size' => 3145782, // 3MB
    'upload_path' => 'upload/magazine/template_magazine/%s/%s/%s',
    'chmod' => 0777, // production: 755
);

$template_element_config = array(
    'image' => array(
        'data_type'             => 'file',
        'allow_extensions'      => array('jpg','jpeg','png','gif','svg','pjpeg'),
        'allow_mime_types'      => array('image/jpeg','image/png','image/gif','image/svg+xml','image/x-png'),
        'max_file_size'         => 5120000, // 5000KB
        'max_width'             => 1920,
        'max_height'            => 1080,
        'crop_ratio'            => '16:9'
    ),
    'title' => array(
        'data_type'             => 'text',
        'max_word_count'        => 30, // toi da 30 tu
    ),
    'paragraph' => array(
        'data_type'             => 'text',
        'max_word_count'        => 1000,
    ),
    'iframe' => array(
        'data_type'             => 'text',
    ),
    'audio' => array(
        'data_type'             => 'file',
        'allow_extensions'      => array('mp3'),
        'allow_mime_types'      => array('audio/mpeg', 'audio/mp3'),
        'max_file_size'         => 10485760, // 10MB
     ),
    'video' => array(
        'data_type'             => 'file',
        'allow_extensions'      => array('flv','mp4','3gp'),
        'allow_mime_types'      => array('video/mp4', 'video/x-flv', 'video/3gpp'),
        'max_file_size'         => 209715200, // 200MB
        'max_width'             => 1920,
        'max_height'            => 1080,
    ),
    'link' => array(
        'title'                 => 'Link',
        'data_type'             => 'text',
    ),
);
// cấu hình select hiệu ứng 
$v_arr_effect_image = array(
    '0' => array('c_code_effect_image'=>'fadeInLeft', 'c_name'=>'Chạy từ trái vào'),
    '1' => array('c_code_effect_image'=>'fadeInRight', 'c_name'=>'Chạy từ phải vào'),
    '2' => array('c_code_effect_image'=>'fadeInDown', 'c_name'=>'Chạy từ dưới lên'),
    '3' => array('c_code_effect_image'=>'fadeInUp', 'c_name'=>'Chạy từ trên xuống')
);
// cấu hình select font
$v_arr_font = array(
    '0' => array('c_code_font'=>'', 'c_name'=>'Chọn font'),
    '1' => array('c_code_font'=>'class_noto', 'c_name'=>'Font Noto'),
    '2' => array('c_code_font'=>'class_roboto', 'c_name'=>'Font Roboto')
);
// cấu hình select chọn vị trí text ghi chú
$v_arr_effect_text_note = array(
    '0' => array('c_code_effect_text_note'=>'to-left', 'c_name'=>'Vị trí trái ảnh'),
    '1' => array('c_code_effect_text_note'=>'to-right', 'c_name'=>'Vị trí phải ảnh')
);
