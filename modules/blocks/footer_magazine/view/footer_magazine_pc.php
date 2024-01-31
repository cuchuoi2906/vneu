<?php
$urlHelper = new UrlHelper();$urlHelper->getInstance();
$noidung = str_replace("©", '&copy;', $row_footer['noidung']);
$noidung = str_replace("&", '&amp;', $noidung);
$noidung = str_replace("\n", '<br/>', $noidung);
$linklogo = ($row_footer['linklogo'] != '')?$row_footer['linklogo']:BASE_URL_FOR_PUBLIC;
$imglogo = ($row_footer['logo'] != '')?html_image($row_footer['logo'], false):html_image('/images/logo-eva-footer.jpg', false); 
// hiện thị banner footer
?>
		<div class="mg-footer-top">			
			<div class="container-inner"><?php
				$rs_cat = get_sub_array_in_array($rs_cat, 'Activate', 1, false);
				$rs_cat_parent = get_sub_array_in_array($rs_cat, 'Parent', 0, false);// các chuyên mục cấp 1
				$v_so_cap_1_da_hien_thi = 0;
				$rs_cat_parent = get_sub_array_in_array($rs_cat_parent, 'c_menu_ngang_footer', 1, false);// các chuyên mục cấp 1 được tích chọn hiển thị ở footer
				if (check_array($rs_cat_parent)) {
					$v_count_parent = sizeof($rs_cat_parent);
					?><ul class="mg-group-menu"><?php
						for ($i = 0; $i < $v_count_parent && $v_so_cap_1_da_hien_thi < MAX_FOOTER_CATEGORY_1; ++$i) {
							$row_cat_parent = $rs_cat_parent[$i];
							$v_id_parent = intval($row_cat_parent['ID']);
							
							$rs_cat_child = get_sub_array_in_array($rs_cat, 'Parent', $v_id_parent, false); // các chuyên mục con của chuyên mục cấp 1 đang hiển thị
							$rs_cat_child = get_sub_array_in_array($rs_cat_child, 'c_menu_ngang_footer', 1, false);// các chuyên mục cấp con được tích chọn hiển thị ở footer
							$v_count_child = 0;
							if (check_array($rs_cat_child)) {
								$v_count_child = sizeof($rs_cat_child);
							}
							if ($v_count_child <= 0) {
								continue;
							}
							
							$v_slug_parent = get_category_slug($row_cat_parent);
							$v_link_parent = $urlHelper->url_cate(array('ID'=>$v_id_parent, 'slug'=>$v_slug_parent));
							$v_target_parent = ($row_cat_parent['LinkType']==1) ? 'target="_blank"' : '';
							$v_link_parent = ($row_cat_parent['Link'] != '')? $row_cat_parent['Link']: $v_link_parent;
							$v_link_parent = htmlentities($v_link_parent);
							$v_name_parent = $row_cat_parent['Name'];
							if (!is_null($row_cat_parent['c_ten_menu_ngang_footer']) && $row_cat_parent['c_ten_menu_ngang_footer'] != '') {
								$v_name_parent = $row_cat_parent['c_ten_menu_ngang_footer'];
							}
							?><li>
								<a href="<?php echo _get_link_server_seo($v_link_parent); ?>" title="<?php echo $v_name_parent;?>" <?php echo $v_target_parent; ?>><b title="<?php echo $v_name_parent;?>"><?php echo $v_name_parent; ?></b></a><?php
								?><ul><?php
									for ($j = 0; $j < $v_count_child && $j < MAX_FOOTER_CATEGORY_2_ONPAGE; ++$j) {
										$row_cat_child = $rs_cat_child[$j];
										$v_id_child = intval($row_cat_child['ID']);
										$v_slug_child = get_category_slug($row_cat_child);
										$v_link_child = $urlHelper->url_cate(array('ID'=>$v_id_child, 'slug'=>$v_slug_child));
										$v_target_child = ($row_cat_child['LinkType']==1) ? 'target="_blank"' : '';
										$v_link_child = ($row_cat_child['Link'] != '')? $row_cat_child['Link']: $v_link_child;
										$v_link_child = htmlentities($v_link_child);
										$v_name_child = $row_cat_child['Name'];
										if (!is_null($row_cat_child['c_ten_menu_ngang_footer']) && $row_cat_child['c_ten_menu_ngang_footer'] != '') {
											$v_name_child = $row_cat_child['c_ten_menu_ngang_footer'];
										}
										?><li>
											<a href="<?php echo _get_link_server_seo($v_link_child); ?>" title="<?php echo $v_name_child;?>" <?php echo $v_target_child; ?>><?php echo $v_name_child; ?></a>
										</li><?php
									}
								?></ul><?php
							?></li><?php
							++$v_so_cap_1_da_hien_thi; // đếm tăng số cấp 1 đã hiển thị
						}
					?></ul><?php
				}
				?><div class="clear"></div>
			</div>
		</div>
		<div class="footer-menu2-container mg-footer-menu2">
			<div class="footer-menu2">
                _3DO_ Nền Tảng Kết Nối Nguồn Hàng Có Chọn Lọc.
			</div>
		</div>
		<div class="mg-footer">
			<div class="container-inner">
            	<div class="mg-footer-inner">
					<p>3DO là nền tảng cung cấp nguồn hàng có chọn lọc. Tại đây, mọi người bán đều tiếp cận được nguồn hàng chất lượng, đảm bảo được nguồn thu nhập bền vững từ việc kinh doanh.</p>
					<p>Địa chỉ: Biệt thự B17 - Richland Southern Xuân Thủy, Q.Cầu Giấy, Hà Nội</p>
					<p>Phone: 0981156520</p>
					<p>Email: 3do.com.vn@gmail.com</p>
                </div>
            </div>
        </div>
        <div id='slide_fullimage' style='display:none;'></div>
        <!-- Thangnb nang_cap_seo_19_05_2015 :Thangnb bo sung DMCA-->
        <center><script type="text/javascript"> (function () { var c = document.createElement('link'); c.type = 'text/css'; c.rel = 'stylesheet'; c.href = '//images.dmca.com/badges/dmca.css?ID=de3e0e0f-7a8b-49e2-8107-8e94320707f6'; var h = document.getElementsByTagName("head")[0]; h.appendChild(c); })();</script><div id="DMCA-badge"><div class="dm-1 dm-1-b" style="left: 0px; background-color: rgb(141, 198, 66);"><a href="http://www.dmca.com/" title="DMCA">DMCA</a></div><div class="dm-2 dm-2-b"><a href="http://www.dmca.com/Protection/Status.aspx?ID=de3e0e0f-7a8b-49e2-8107-8e94320707f6" title="DMCA">PROTECTED</a></div></div></center>
        <!--Thangnb nang_cap_seo_19_05_2015 :Thangnb bo sung DMCA-->
		<div class="footerSeo"><?php 
            // end 11/10/2016 TuyenNT bo_sung_cau_hinh_on_off_text_link_duoi_footer_24h
		?></div>
		</div>
		<!--@ga_code@-->
		<!--@server_id@-->
		<!--@category_id@-->
        <!--@back_to_page@-->
		<!--@js@-->
		<!-- comScore Tag -->
        <!--js_player-->
	</body>
</html>