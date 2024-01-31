<?php
$urlHelper = new UrlHelper();$urlHelper->getInstance();
$noidung = str_replace("\n", '<br />', $row_footer['noidung']);
$noidung = str_replace("&", '&amp;', $noidung);
?>
    <div class="mg-footer-top">			
        <div class="container-inner"><?php
            $rs_cat = get_sub_array_in_array($rs_cat, 'Activate', 1, false);
            $rs_cat_parent = get_sub_array_in_array($rs_cat, 'Parent', 0, false);// các chuyên mục cấp 1
            $v_so_cap_1_da_hien_thi = 0;
            if (check_array($rs_cat_parent)) {
                $v_count_parent = sizeof($rs_cat_parent);
                ?><ul class="mg-group-menu"><?php
                    for ($i = 0; $i < $v_count_parent; ++$i) {
                        $row_cat_parent = $rs_cat_parent[$i];
                        $v_id_parent = intval($row_cat_parent['ID']);
                        
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
                            <a href="<?php echo _get_link_server_seo($v_link_parent); ?>" title="<?php echo $v_name_parent;?>" <?php echo $v_target_parent; ?>><?php echo $v_name_parent; ?></a><?php
                            ?>
                                    </li><?php
                        ++$v_so_cap_1_da_hien_thi; // đếm tăng số cấp 1 đã hiển thị
                    }
                ?></ul><?php
            }
            ?><div class="clear"></div>
        </div>
    </div>
    <div class="footer-menu2-container mg-footer-menu2">
    	<div class="footer-menu2">
			<?php // Begin 19/09/2016 TuyenNT fix_bug_trang_24hvn_link_bao_gia_sai 
            $v_link_bao_gia = _get_module_config('cau_hinh_dung_chung', 'v_link_bao_gia');
            $v_link_bao_gia_redirect = link_to_redirectout($v_link_bao_gia);
            ?>
            <a rel="nofollow" target="_blank" href="<?php echo $v_link_bao_gia_redirect; ?>" title="Giới thiệu">Giới thiệu</a>|<a href="javascript:void(0)" onclick="window.open('/ajax/contact/index.php','replynews','menubar=1,resizable=1,width=524,height=420')" title="Góp ý">Góp ý</a>|<a href="#" title="Đầu trang">Đầu trang</a>|<a rel="nofollow" target="_blank" href="<?php echo $v_link_bao_gia_redirect; ?>" title="Liên hệ quảng cáo">&nbsp;LIÊN HỆ QUẢNG CÁO</a>
            <?php // end 19/09/2016 TuyenNT fix_bug_trang_24hvn_link_bao_gia_sai ?>
        </div>
    </div>
	<div class="mg-footer">
		<div class="container-inner">
			<?php echo $noidung?>
        </div>
    </div>
    <div id='slide_fullimage' style='display:none;'></div>
    <div class="clear"></div>
	<!--@server_id@-->
    <div class="thong_ke">
	<script src="//thongke.24h.com.vn/24h-analytics/24h-analytics.js" type="text/javascript"></script> 
    </div>
    <script type="text/javascript">
        var uProfileMapping = Object.create(uProfileMappingMb); // initiate tracking object
        uProfileMapping.uIdNamespace = uId24H;
		uProfileMapping.getIpUri = 'https://www.24h.com.vn/ip.php';
        uProfileMapping.init(function(data){
        }); 
    </script> 
	<!--  comScore Tag -->
	<noscript>
		<img src="http://b.scorecardresearch.com/p?c1=2&amp;c2=9634358&amp;cv=2.0&amp;cj=1" alt=""/>
	</noscript>
	<!--@js@-->
	<!--  comScore Tag -->
    <!--js_player-->
</div>
</body>
</html>