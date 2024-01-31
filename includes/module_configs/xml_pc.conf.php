<?php
//begin: sua_doi_he_thong_sitemap_24h
$v_arr_header_footer = array(
	// mặc định. cho file hàng ngày
	'mac_dinh_web'=>array(
		'header'=>'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" >',
		'footer'=>'</urlset>',
	),
	'mac_dinh_mobile'=>array(
		'header'=>'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns:mobile="http://www.google.com/schemas/sitemap-mobile/1.0">',
		'footer'=>'</urlset>',
	),
	
	// index
	'index_web'=>array(
		'header'=>'<sitemapindex xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/siteindex.xsd" >',
		'footer'=>'</sitemapindex>',
	),
	'index_mobile'=>array(
		'header'=>'<sitemapindex xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/siteindex.xsd" xmlns:mobile="http://www.google.com/schemas/sitemap-mobile/1.0">',
		'footer'=>'</sitemapindex>',
	),
	
	// news
	'news_web'=>array(
		'header'=>'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd http://www.google.com/schemas/sitemap-news/0.9 http://www.google.com/schemas/sitemap-news/0.9/sitemap-news.xsd" >',
		'footer'=>'</urlset>',
	),
	'news_mobile'=>array(
		'header'=>'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd http://www.google.com/schemas/sitemap-news/0.9 http://www.google.com/schemas/sitemap-news/0.9/sitemap-news.xsd" xmlns:mobile="http://www.google.com/schemas/sitemap-mobile/1.0">',
		'footer'=>'</urlset>',
	),
	
	// article
	'article_web'=>array(
		'header'=>'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" >',
		'footer'=>'</urlset>',
	),
	'article_mobile'=>array(
		'header'=>'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns:mobile="http://www.google.com/schemas/sitemap-mobile/1.0">',
		'footer'=>'</urlset>',
	),
	
	// category
	'category_web'=>array(
		'header'=>'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" >',
		'footer'=>'</urlset>',
	),
	'category_mobile'=>array(
		'header'=>'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns:mobile="http://www.google.com/schemas/sitemap-mobile/1.0">',
		'footer'=>'</urlset>',
	),
	
	// category
	'event_web'=>array(
		'header'=>'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" >',
		'footer'=>'</urlset>',
	),
	'event_mobile'=>array(
		'header'=>'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns:mobile="http://www.google.com/schemas/sitemap-mobile/1.0">',
		'footer'=>'</urlset>',
	),
	
	// video
	//Begin 17-12-2015 : Thangnb doi_sitemap_urlset_thanh_sitemapindex
	//Begin 06-01-2016 : Thangnb chinh_sua_sitemap_video_image_tag
	'video_web1'=>array(
		'header'=>'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1" >',
		'footer'=>'</urlset>',
	),
	'video_mobile1'=>array(
		'header'=>'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1" xmlns:mobile="http://www.google.com/schemas/sitemap-mobile/1.0">',
		'footer'=>'</urlset>',
	),
	'video_web'=>array(
		'header'=>'<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1">',
		'footer'=>'</sitemapindex>',
	),
	'video_mobile'=>array(
		'header'=>'<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1">',
		'footer'=>'</sitemapindex>',
	),
	//End 17-12-2015 : Thangnb doi_sitemap_urlset_thanh_sitemapindex
	
	// image
	//Begin 17-12-2015 : Thangnb doi_sitemap_urlset_thanh_sitemapindex
	//Begin 06-01-2016 : Thangnb chinh_sua_sitemap_video_image_tag
	'image_web1'=>array(
		'header'=>'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" >',
		'footer'=>'</urlset>',
	),
	'image_mobile1'=>array(
		'header'=>'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:mobile="http://www.google.com/schemas/sitemap-mobile/1.0">',
		'footer'=>'</urlset>',
	),
	'image_web'=>array(
		'header'=>'<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">',
		'footer'=>'</sitemapindex>',
	),
	'image_mobile'=>array(
		'header'=>'<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">',
		'footer'=>'</sitemapindex>',
	),
	
	// tag
	//Begin 17-12-2015 : Thangnb doi_sitemap_urlset_thanh_sitemapindex
	//Begin 06-01-2016 : Thangnb chinh_sua_sitemap_video_image_tag
	'tags_web1'=>array(
		'header'=>'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">',
		'footer'=>'</urlset>',
	),
	'tags_mobile1'=>array(
		'header'=>'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns:mobile="http://www.google.com/schemas/sitemap-mobile/1.0">',
		'footer'=>'</urlset>',
	),
	'tags_web'=>array(
		'header'=>'<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">',
		'footer'=>'</sitemapindex>',
	),
	'tags_mobile'=>array(
		'header'=>'<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">',
		'footer'=>'</sitemapindex>',
	),
	//End 17-12-2015 : Thangnb doi_sitemap_urlset_thanh_sitemapindex
	// eventsbeta
	'eventsbeta_web'=>array(
		'header'=>'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">',
		'footer'=>'</urlset>',
	),
	'eventsbeta_mobile'=>array(
		'header'=>'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns:mobile="http://www.google.com/schemas/sitemap-mobile/1.0">',
		'footer'=>'</urlset>',
	),
	
	// articlebeta
	'articlebeta_web'=>array(
		'header'=>'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">',
		'footer'=>'</urlset>',
	),
	'articlebeta_mobile'=>array(
		'header'=>'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns:mobile="http://www.google.com/schemas/sitemap-mobile/1.0">',
		'footer'=>'</urlset>',
	),
);
//end: sua_doi_he_thong_sitemap_24h
// danh sách file player trên từng phiên bản
$v_arr_player = array(
	'web'=>'js/player.swf',
	'mobile'=>'js/player.swf',
);