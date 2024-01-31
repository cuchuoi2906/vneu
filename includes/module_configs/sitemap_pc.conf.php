<?php
// Cấu hình các chu ky để dựng cờ cho gen_24h_sitemap
$v_chu_ky_chay_sitemap_all = 0;
$v_chu_ky_chay_sitemap_daily_article = 40;
$v_chu_ky_chay_sitemap_daily_event = 40;
$v_chu_ky_chay_sitemap_article_all = 30;
$v_chu_ky_chay_sitemap_all_event = 30;
$v_chu_ky_chay_sitemap_article_by_menu = 30;
$v_chu_ky_chay_sitemap_menu = 30;
// Cấu hình các chu kỳ sitemap image
$v_chu_ky_chay_sitemap_image = 30;
$v_chu_ky_chay_sitemap_image_all = 30;
// Cấu hình các chu kỳ sitemap news
$v_chu_ky_chay_sitemap_news = 40;
// Cấu hình các chu kỳ sitemap video
$v_chu_ky_chay_sitemap_video = 40;
$v_chu_ky_chay_sitemap_video_all = 30;
// Cấu hình cho all articel
$v_so_thang_chay_sitemap_article = 1; // sẽ chạy lại toàn bộ file sitemap article cho số tháng cấu hình. kể từ tháng hiện tại.
$v_co_chay_sitemap_article_thang_cu = true; // True: vẫn chạy cho tháng cũ, False: không chạy cho tháng cũ
// Cấu hình sitemap-video
$v_so_thang_chay_sitemap_video = 1;// sẽ chạy lại toàn bộ file sitemap video cho số tháng cấu hình. kể từ tháng hiện tại.
$v_co_chay_sitemap_video_thang_cu = false; // True: vẫn chạy cho tháng cũ, False: không chạy cho tháng cũ
$v_so_ban_ghi_tao_sitemap_video = 500;
// Cấu hình sitemap-image
$v_so_thang_chay_sitemap_images = 1;// sẽ chạy lại toàn bộ file sitemap images cho số tháng cấu hình. kể từ tháng hiện tại.
$v_co_chay_sitemap_images_thang_cu = false; // True: vẫn chạy cho tháng cũ, False: không chạy cho tháng cũ
$v_so_ban_ghi_tao_sitemap_images = 500;								   
// Cấu hình các sitemap không đọc từ key file mà đọc trực tiếp từ Mysql
$v_date = date('Y-m-d'); // Lấy ngày hiện tại
// begin 6/2/2018 Tytv chinh_ten_sitemap_theo_ngay
$v_arr_sitemap_khong_doc_tu_key_file= array('sitemap-news','sitemap-article-daily','sitemap-event-daily','sitemap-image-daily','sitemap-news-https','sitemap-article-daily-https','sitemap-event-daily-https','sitemap-image-daily-https','sitemap-index-https');
// end 6/2/2018 Tytv chinh_ten_sitemap_theo_ngay

$v_create_sitemap_protocol_by_type = 1; // 1: chỉ tạo sitemap cho giao thức http, 2, Chỉ tạo sitemap cho giao thức https, 3: tạo cả http,https