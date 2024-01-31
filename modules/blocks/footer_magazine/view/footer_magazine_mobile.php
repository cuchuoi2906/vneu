<?php
$urlHelper = new UrlHelper();$urlHelper->getInstance();
$v_object = new box_menu_chan_trang_block();
$v_object->setParam('loai_trang','magazine');
$v_object->index($cat_id);
?>
<div class="mg-footer">
    <div class="container-inner">
    	<div class="mg-footer-inner">
			<?php		
			if ($row_footer_link['pk_seo_text_link']) {
				$footer_link = $row_footer_link['c_noi_dung'];
			} else {
				$footer_link = $row_footer['website'];
			}
			$tag_footer = '';
			if (TAG_FOOTER) {
				if ($row_tag_category_ctrl['status']) {
					$v_parent_cat = ($row_cat['Parent'] > 0)?$row_cat['Parent']:$cat_id;
					$tag_footer = Gnud_Db_read_get_key('box_tag_footer_201403-'.$v_parent_cat, _CACHE_TABLE_COMMON);
				}
				echo "<br />".$tag_footer;
			} 
            //Begin: Tytv - 12/12/2016 - gan_nofollow_link_ra_ngoai_trang_thoi_tiet_gia_vang
            if(!empty($footer_link)){
                preg_match_all('#href="([^\"]*)"#ism', $footer_link,$v_arr_temp);
                if(check_array($v_arr_temp[1])){
                    foreach ($v_arr_temp[1] as $key => $v_link_tmp) {
                        if(!empty($v_link_tmp)){
                            $v_nofollow = (check_link_sub_domain_or_redirectout($v_link_tmp))?'rel="nofollow"':'';
                            $v_str_replace = 'href="'.$v_link_tmp.'" '.$v_nofollow;
                            $footer_link = str_replace('href="'.$v_link_tmp.'"', $v_str_replace, $footer_link);
                        }
                    }
                }
            }
            //End: Tytv - 12/12/2016 - gan_nofollow_link_ra_ngoai_trang_thoi_tiet_gia_vang
			echo $row_footer['noidung'];
			?> 
        </div>  	
    </div>
    <div id='fw24h_trackPageview' style='display:none;'></div>	
</div>
<div id='slide_fullimage' style='display:none;'></div>
<?php //Begin 11-04-2016 : Thangnb nang_cap_box_binh_luan ?>
<div class="arrowPageUp" id="arrowPageUp" style="display:none;z-index:9999999;">
	<a href="#" title="Về đầu trang"><img src="<?php html_image('images/m2014/images/arrowPageUp.png'); ?>" width="44" height="44" alt="" /></a>
</div>
<!--@sticky_toolbar@-->
<script>
    if (typeof(f_filterResults) != 'function'){
        function f_filterResults( n_win, n_docel, n_body) {
            var n_result = n_win ? n_win : 0;
            if (n_docel && (!n_result || (n_result > n_docel)))
                n_result = n_docel;
            return n_body && (!n_result || (n_result > n_body)) ? n_body : n_result;

        }
    }
    if (typeof(f_scrollTop) != 'function'){
        function f_scrollTop() {
            return f_filterResults (
                window.pageYOffset ? window.pageYOffset : 0,
                document.documentElement ? document.documentElement.scrollTop : 0,
                document.body ? document.body.scrollTop : 0
            );
        }
    }
    if (typeof(f_clientHeight) != 'function'){
        function f_clientHeight() {
            return f_filterResults (
                window.innerHeight ? window.innerHeight : 0,
                document.documentElement ? document.documentElement.clientHeight : 0,
                document.body ? document.body.clientHeight : 0
            );
        }
    }
    if (typeof(sticky_toolbar_onscroll) != 'function'){
        // 20210825 xử lý ẩn hiện sticky_toolbar khi scroll màn hình
        function sticky_toolbar_onscroll(){
            if (!document.getElementById('box_sticky_toolbar')){
                return false;
            }

            if (document.getElementById("box_sticky_toolbar").style.display == 'none'){
                if (document.getElementById("box_sticky_toolbar__end_body")){
                    document.getElementById("box_sticky_toolbar__end_body").style.display = "none";
                }

                return false;
            }

            // ẩn box_sticky_toolbar khi có banner sticky hiển thị trên trang
            if (document.getElementById("ADS_166_15s_container")){
                // 20210826 check có banner là ẩn toolbar
                if (
                    document.getElementById("ADS_166_15s_container").style.display != 'none'
                    &&
                    document.getElementById("ADS_166_15s_container").offsetHeight > 0
                ){
                    document.getElementById('box_sticky_toolbar').style.transform = 'translateY(100%)';
                    // 20210829 off luôn nếu phát hiện có banner
                    setTimeout(function(){document.getElementById("box_sticky_toolbar").style.display = "none";}, 1000);
                    return false;
                } else {
                    // check lại thêm sau 0.1s cho trường hợp js xử lý ẩn hiện banner sticky chưa chạy tới
                    setTimeout(function(){
                        if (
                            document.getElementById("ADS_166_15s_container").style.display != 'none'
                            &&
                            document.getElementById("ADS_166_15s_container").offsetHeight > 0
                        ){
                            document.getElementById('box_sticky_toolbar').style.transform = 'translateY(100%)';
                            // 20210829 off luôn nếu phát hiện có banner
                            setTimeout(function(){document.getElementById("box_sticky_toolbar").style.display = "none";}, 1000);
                            return false;
                        }
                    }, 100);
                }
            }

            // ẩn box_sticky_toolbar khi kéo trang lên màn hình đầu tiên
            var view_height = f_clientHeight();// lấy chiều cao màn hình hiển thị trình duyệt
            var view_scroll = f_scrollTop();// lấy vị trí scroll trang hiện tại

            if (view_height <= 0 || view_scroll <= view_height){
                document.getElementById('box_sticky_toolbar').style.transform = 'translateY(100%)';
                return false;
            }

            // 20210826 chèn thêm khoảng trắng cuối trang để scroll qua
            if (
                document.getElementById("box_sticky_toolbar").style.display != 'none'
                &&
                document.getElementById("box_sticky_toolbar").offsetHeight > 0
            ){
                if (!document.getElementById("box_sticky_toolbar__end_body")){
                    var divObj = document.createElement('div');
                    divObj.id = 'box_sticky_toolbar__end_body';
                    divObj.style.cssText = 'width:100%;height:'+document.getElementById("box_sticky_toolbar").offsetHeight+'px';
                    document.body.appendChild(divObj);
                } else if (document.getElementById("box_sticky_toolbar__end_body").style.display == "none"){
                    document.getElementById("box_sticky_toolbar__end_body").style.display = "";
                }
            }

            document.getElementById('box_sticky_toolbar').style.visibility = 'visible';
            document.getElementById('box_sticky_toolbar').style.transform = 'translateY(0)';
            return true;
        }
    }
</script>
<script type="text/javascript">
    var uProfileMapping = Object.create(uProfileMappingMb); // initiate tracking object
    uProfileMapping.uIdNamespace = uId24H;
	uProfileMapping.getIpUri = 'https://www.24h.com.vn/ip.php';
    uProfileMapping.init(function(data){
    }); 
</script>
<script type="text/javascript">
	function mo_menu_trai_trang_chu()
	{
		show_hide_block('mg-menu-mobile');
		
		var height = window.innerHeight;
		var menu = document.getElementById('mnRight');
		if (menu != '' && menu != null && menu != undefined) {
			document.getElementById('mnRight').style.height = (height-150) + 'px';
		}
	}
	$(window).resize(function(){
		var height = window.innerHeight;
		var menu = document.getElementById('mnRight');
		if (menu != '' && menu != null && menu != undefined) {
			document.getElementById('mnRight').style.height = (height-150) + 'px';
		}	
	});
	function dong_menu_trai_trang_chu()
	{
		show_hide_block('mg-menu-mobile');
	}

	function check_div_con(div_con, parent) {
		if (div_con.parentNode === parent) {
		  return true;
		} else if (div_con.parentNode === null) {
		  return false;
		} else {
		  return check_div_con(div_con.parentNode, parent);
		}
	}
	
	function dong_danh_muc_khi_click_ra_ngoai(){
		window.addEventListener('click', function(e){
			if(document.getElementById('mg-menu-mobile') && document.getElementById('mg-menu-mobile').style.display == 'block') {
					var mouse_inside = false;
					menu = document.getElementById('mg-menu-mobile');
					mnDanhMuc = document.getElementById('show_menu_button');
					var target = e.target || e.srcElement;
					if (target != menu && !check_div_con(target, menu) && target != mnDanhMuc && !check_div_con(target, mnDanhMuc)){
						mouse_inside = false;
					}else {
						mouse_inside = true;	
					}
					if(!mouse_inside) {
						document.getElementById('mg-menu-mobile').style.display = 'none';
						e.preventDefault();
						e.stopPropagation();
					}
			}
		})
	}
	hien_thi_icon_header_khi_cuon_trang();
	dong_danh_muc_khi_click_ra_ngoai();
</script>
<!--@server_id@-->
<!--@category_id@-->
<noscript>
    <?php // begin 15/11/2016 TuyenNT xu_ly_loi_w3c_us_m_24h ?>
    <img src="http://b.scorecardresearch.com/p?c1=2&amp;c2=9634358&amp;cv=2.0&amp;cj=1" alt="" />
    <?php // end 15/11/2016 TuyenNT xu_ly_loi_w3c_us_m_24h ?>
</noscript>
<!--@js@-->
<!-- comScore Tag -->
<!--js_player-->
</div>
<div class="thong_ke">
	<script type="text/javascript" src="//thongke.24h.com.vn/24h-analytics/24h-analytics.js"></script>
</div>
</body>
</html>
