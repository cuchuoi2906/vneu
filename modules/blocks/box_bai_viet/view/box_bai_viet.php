<?php 
$CategoryID = $row_news['CategoryID'];
$row_cat = fe_chuyen_muc_theo_id($CategoryID);
$url_cate = get_url_origin_of_category($row_cat);
preg_match_all('/<img[^>]+>/i',$row_news['Body'], $imgs);
if (sizeof($imgs[0]))
{
    foreach($imgs[0] as $img) {
        $newimg = str_replace( '<img', '<img class="news-image"', $img);
        $row_news['Body'] = str_replace($img, $newimg, $row_news['Body']);
    }
}
if(isset($_GET['test'])){
	$v_param_extension_video['v_is_trang_bai_viet'] = true;
	$v_param_extension_video['v_is_box_video_chon_loc'] = false;
	$v_param_extension_video['v_width_video']   = WIDTH_ZPLAYER_2021;
	$v_param_extension_video['v_height_video']  = HEIGHT_ZPLAYER_2021;
	$v_param_extension_video['v_type_video']    = 'flashWrite';
	$v_param_extension_video['v_type_quang_cao']    = TYPE_ADS_DEFAULT;
	$v_param_extension_video['v_ga_file']       = LINK_GA_VIDEO_MAC_DINH;
	$row_news['Body'] = _24h_player_xu_ly_video_body($row_news['Body'], $row_news, $row_cat, $v_param_extension_video);
}
?>
<div class="hero-area section">

    <!-- Backgound Image -->
    <div class="bg-image bg-parallax overlay" style="background-image:url(<?php echo html_image($row_news['TopnewsImg'], false); ?>)"></div>
    <!-- /Backgound Image -->

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1 text-center">
                <ul class="hero-area-tree">
                    <li><a href="<?php echo BASE_URL_FOR_PUBLIC; ?>">Trang chủ</a></li>
                    <li><?php echo $row_cat['Name']; ?></li>
                </ul>
                <h1 class="white-text"><?php echo $row_news['Title']; ?></h1>
                <ul class="blog-post-meta">
                    <li><?php echo date('d-m-Y H:i:s', strtotime($row_news['DatePublished'])); ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="section">

    <!-- container -->
    <div class="container">

        <!-- row -->
        <div class="row">

            <!-- main blog -->
            <div class="col-md-9">
                <!-- blog post -->
                <div class="blog-post">
                    <?php echo $row_news['Body']; ?>
                </div>
            </div>
            <!-- /main blog -->

            <!-- aside blog -->
            <div class="col-md-3">
                <!-- posts widget -->
                <div class="widget posts-widget">
                    <h3>Bài viết mới nhất</h3>

                    <!-- single posts -->
                    <?php 
                    $rs_cate_temp = [];
                    foreach($rs_items_moi as $row_news){
                        extract($row_news);
                        $v_arr_news_id[] = intval($ID);
                        $DatePublished = ($DatePublished != '') ? $DatePublished : $Date;
                        // Lấy chuyên mục được gắn vào bài viết
                        if(check_array($rs_cate_temp) && check_array($rs_cate_temp[$CategoryID])){
                            $v_row_cate = $rs_cate_temp[$CategoryID];
                        }else{
                            // Lấy chuyên mục chưa tồn tại trong mảng và đưa thêm vào mảng
                            $v_row_cate = fe_chuyen_muc_theo_id($CategoryID);
                            $rs_cate_temp[$CategoryID] = $v_row_cate;
                        }
                        $v_url_news = get_url_origin_of_news($row_news, $v_row_cate);
                        $Title  = fw24h_strip_tags($Title, true);
                        $Summary  = fw24h_strip_tags($Summary, true);
                        ?>
                        <div class="single-post">
                            <a class="single-post-img" href="<?php echo $v_url_news; ?>">
                                <img src="<?php echo html_image($row_news['SummaryImg_chu_nhat'], false); ?>" alt="">
                            </a>
                            <a href="<?php echo $v_url_news; ?>"><?php echo $Title; ?></a>
                            <p><small><?php echo date('d-m-Y H:i:s', strtotime($DatePublished)); ?></small></p>
                        </div>
                    <?php 
                    }?>

                </div>
                <!-- /posts widget -->

            </div>
            <!-- /aside blog -->

        </div>
        <!-- row -->

    </div>
    <!-- container -->

</div>