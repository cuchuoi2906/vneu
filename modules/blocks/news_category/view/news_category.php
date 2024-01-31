<?php 
//pre($row_cat);
?>
<div class="hero-area section">

    <!-- Backgound Image -->
    <div class="bg-image bg-parallax overlay" style="background-image:url(<?php echo html_image($row_cat['c_anh_dai_dien'], false); ?>)"></div>
    <!-- /Backgound Image -->

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1 text-center">
                <ul class="hero-area-tree">
                    <li><a href="<?php echo BASE_URL_FOR_PUBLIC; ?>">Trang chủ</a></li>
                </ul>
                <?php 
                $v_url_cate = get_url_origin_of_category($row_cat);
                ?>
                <h1 class="white-text"><?php echo ucfirst($row_cat['Name']); ?></h1>

            </div>
        </div>
    </div>

</div>
<!-- /Hero-area -->
<div class="section">
    <!-- container -->
    <div class="container">

        <!-- row -->
        <div class="row">

            <!-- main blog -->
            <div id="main" class="col-md-9">

                <!-- row -->
                <div class="row">
                    <?php 
                    $rs_cate_temp = [];
                    foreach($rs_items as $row_news){
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
                        <!-- single blog -->
                        <div class="col-md-6">
                            <div class="single-blog">
                                <div class="blog-img">
                                    <a href="<?php echo $v_url_news; ?>">
                                        <img src="<?php echo html_image($row_news['SummaryImg_chu_nhat'], false); ?>" alt="">
                                    </a>
                                </div>
                                <h4>
                                    <a href="<?php echo $v_url_news; ?>"><?php echo $Title; ?></a>
                                </h4>
                                <div class="blog-meta">
                                    <!--<span class="blog-meta-author">By: <a href="#">John Doe</a></span>-->
                                    <div class="pull-right">
                                        <span><?php echo date('d-m-Y H:i:s', strtotime($DatePublished)); ?></span>
                                        <!--<span class="blog-meta-comments"><a href="#"><i class="fa fa-comments"></i> 35</a></span>-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php 
                    }?>
                </div>
                <!-- /row -->

                <!-- row -->
                <div class="row">

                    <!-- pagination -->
                    <div class="col-md-12">
                        <div class="post-pagination">
                            <?php 
                            // Trang hiện tại
                            $current = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                            if($current > 1){
                            ?>
                                <a href="?page=<?php echo intval($current-1); ?>" class="pagination-back pull-left">Quay lại</a>
                            <?php 
                            }?>
                            <ul class="pages">
                                <?php
                                // Số trang tối đa bạn muốn hiển thị
                                $totalPages = ceil($v_tong_so_dong/$v_number_page); // Tổng số trang
                                $pagesToShow = 5; // Số trang muốn hiển thị

                                // Tính toán giới hạn cho số trang
                                $half = floor($pagesToShow / 2);

                                $lowerLimit = max($current - $half, 1);
                                $upperLimit = min($current + $half, $totalPages);

                                // Đảm bảo không bị vượt quá giới hạn trang
                                if ($lowerLimit === 1) {
                                    $upperLimit = min($pagesToShow, $totalPages);
                                }

                                // In ra các trang
                                for ($i = $lowerLimit; $i <= $upperLimit; $i++) {
                                    if (intval($i) === $current) {
                                        echo "<li class='active'>$i</li>";
                                    } else {
                                        echo "<li><a href='?page=$i'>$i</a></li>";
                                    }
                                }
                                ?>
                            </ul>
                            <?php 
                            if($current < $totalPages && $totalPages > 1){
                            ?>
                                <a href="?page=<?php echo intval($current+1); ?>" class="pagination-next pull-right">Tiếp theo</a>
                            <?php 
                            }?>
                        </div>
                    </div>
                    <!-- pagination -->

                </div>
                <!-- /row -->
            </div>
            <!-- /main blog -->

            <!-- aside blog -->
            <div id="aside" class="col-md-3">

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