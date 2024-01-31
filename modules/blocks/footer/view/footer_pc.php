		<footer class="section">

			<!-- container -->
			<div class="container">

				<!-- row -->
				<div class="row">

					<!-- footer logo -->
					<div class="col-md-6">
						<div class="footer-logo">
							<a class="logo" href="<?php echo BASE_URL_FOR_PUBLIC; ?>">
								<img src="<?php echo html_image(IMAGE_NEWS.'/images/vneu.png',false); ?>" alt="logo">
							</a>
						</div>
					</div>
					<!-- footer logo -->

					<!-- footer nav -->
					<div class="col-md-6">
						<ul class="footer-nav">
                            
							<li><a href="<?php echo BASE_URL_FOR_PUBLIC; ?>">Trang chủ</a></li>
                            <?php 
                            $rs_cate = fe_danh_sach_chuyen_muc();
                            $rs_cate_cap2 = [];
                            $rs_cate_cap1 = [];
                            if(check_array($rs_cate)){
                                foreach($rs_cate as $items){
                                    if(intval($items['Parent']) == 0){
                                        $rs_cate_cap1[] = $items;
                                        continue;
                                    }
                                    $rs_cate_cap2[$items['Parent']][] = $items;
                                }
                            }
                            if(check_array($rs_cate_cap1)){
                                foreach($rs_cate_cap1 as $items){
                                    $name = $items['Name'];
                                    $v_url_cate = get_url_origin_of_category($items);
                                    $classToggle = '';
                                    if(isset($rs_cate_cap2[$items['ID']]) && check_array($rs_cate_cap2)){
                                        $classToggle = 'dropdown-toggle';
                                    }
                                ?>
                                <li>
                                    <a href="<?php echo $v_url_cate; ?>">
                                    <?php echo $name; ?></a>
                                </li>
                            <?php 
                                }
                            }?>
						</ul>
					</div>
					<!-- /footer nav -->

				</div>
				<!-- /row -->

				<!-- row -->
				<div class="row">

					<!-- social -->
					<div class="col-md-4 col-md-push-8">
						<ul class="footer-social" style="display: flex;">
							<li><a href="https://www.facebook.com/vneu.duhocduc/" target="_blank" class="facebook"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="https://zalo.me/0362963389" target="_blank" class="twitter" data-replace-href="https://zalo.me/0362963389">zalo</a></li>
							<li><a style="background: none;margin-top: -4px;" href="https://www.tiktok.com/@cuocsong_oduc?_t=8eltKA5ZrJX&amp;_r=1" target="_blank" ><svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="50" height="50" viewBox="0 0 50 50">
                                <path d="M41,4H9C6.243,4,4,6.243,4,9v32c0,2.757,2.243,5,5,5h32c2.757,0,5-2.243,5-5V9C46,6.243,43.757,4,41,4z M37.006,22.323 c-0.227,0.021-0.457,0.035-0.69,0.035c-2.623,0-4.928-1.349-6.269-3.388c0,5.349,0,11.435,0,11.537c0,4.709-3.818,8.527-8.527,8.527 s-8.527-3.818-8.527-8.527s3.818-8.527,8.527-8.527c0.178,0,0.352,0.016,0.527,0.027v4.202c-0.175-0.021-0.347-0.053-0.527-0.053 c-2.404,0-4.352,1.948-4.352,4.352s1.948,4.352,4.352,4.352s4.527-1.894,4.527-4.298c0-0.095,0.042-19.594,0.042-19.594h4.016 c0.378,3.591,3.277,6.425,6.901,6.685V22.323z"></path>
                            </svg></a></li>
							<!-- <li><a href="#" class="instagram"><i class="fa fa-instagram"></i></a></li> -->
							<!-- <li><a href="#" class="youtube"><i class="fa fa-youtube"></i></a></li> -->
							<!-- <li><a href="#" class="linkedin"><i class="fa fa-linkedin"></i></a></li> -->
						</ul>
					</div>
					<!-- /social -->

					<!-- copyright -->
					<div class="col-md-4 col-md-pull-4">
                        <h2>VĂN PHÒNG TẠI VIỆT NAM</h2>
                        <address>
                            <strong>Địa chỉ:</strong><br>
                            <i class="fa fa-home" aria-hidden="true"></i> Số 34, đường Cựu Chiến Binh, xã An Khánh, huyện Hoài Đức, Thành phố Hà Nội
                        </address>
                        <br>
                        <p>
                            <strong>Hotline:</strong><br>
                            <i class="fa fa-phone" aria-hidden="true"></i> 036.296.3389
                        </p>
                        <br>
                        <p>
                            <strong>Website:</strong><br>
                            <i class="fa fa-globe" aria-hidden="true"></i> <a href="http://www.example.com">www.vneu.vn</a>
                        </p>
					</div>
                    <div class="col-md-4 col-md-pull-4">
                        <h2>VĂN PHÒNG TẠI ĐỨC</h2>
                        <address>
                            <strong>Địa chỉ:</strong><br>
                            <i class="fa fa-home" aria-hidden="true"></i> Käthe kollwitz 1. 99634. Straußfurt.
                        </address>
                        <br>
                        <p>
                            <strong>Hotline:</strong><br>
                            <i class="fa fa-phone" aria-hidden="true"></i> +4917682260908
                        </p>
					</div>
					<!-- /copyright -->

				</div>
				<!-- row -->

			</div>
			<!-- /container -->

		</footer>
		<!-- /Footer -->
		<!-- jQuery Plugins -->
		<script type="text/javascript" src="<?php echo BASE_URL_FOR_PUBLIC; ?>js/jquery.min.js"></script>
		<script type="text/javascript" src="<?php echo BASE_URL_FOR_PUBLIC; ?>js/bootstrap3.7.min.js"></script>
		<script type="text/javascript" src="<?php echo BASE_URL_FOR_PUBLIC; ?>js/main.js"></script>
		<script async src="//imasdk.googleapis.com/js/sdkloader/ima3.js?v=1234"></script>
		<script async src="<?php echo BASE_URL_FOR_PUBLIC; ?>js/videojs-ie8.min.js?v=1234"></script>';
		<script async src="<?php echo BASE_URL_FOR_PUBLIC; ?>js/24hplayer-drm.min.js?default=1&v=1234"></script>
	</body>
</html>