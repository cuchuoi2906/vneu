<?php
$urlHelper = &UrlHelper::getInstance();
$noidung = str_replace("\n", '<br />', $row_footer['noidung']);
$noidung = str_replace("&", '&amp;', $noidung);
/* Begin: Tytv - 13/4/2017 toi_uu_seo_ban_ipad_24h */
$v_array_domain_replace_pc_to_ipad = _get_module_config('cau_hinh_dung_chung', 'v_array_domain_replace_pc_to_ipad');
$linklogo = ($row_footer['linklogo'] != '')?str_replace($v_array_domain_replace_pc_to_ipad, BASE_URL_FOR_PUBLIC, $row_footer['linklogo']):BASE_URL_FOR_PUBLIC;
/* End: Tytv - 13/4/2017 toi_uu_seo_ban_ipad_24h */
$imglogo = ($row_footer['logo'] != '')?html_image($row_footer['logo'], false):html_image('/images/logo-chan-trang-24h.jpg', false); 

?>
<nav class="hdrRt">
    <?php 			
    $v_sub_menu = new box_menu_ngang_header_block();
    $p_category_id = ($cat_id<=0)?ID_TRANG_CHU:$cat_id;
    $v_sub_menu->setParam('p_is_ipad', 1);
    $v_sub_menu->setParam('v_device_global', $v_device_global);
    $v_sub_menu->index($p_category_id);
    ?>
</nav>
<div align="right" class="pdT5 pdB5 contnr" id="divfooterButton">
    <a class="btnTpDt" href="#">Lên đầu trang</a>
</div>
<footer id="footer">
    <?php
    $object = new box_text_link_footer_block();
    $object->setParam('v_device_global', $v_device_global);
    $object->index($cat_id);
    ?>
    <div class="ftPrt2">
        <?php // Begin 19/09/2016 TuyenNT fix_bug_trang_24hvn_link_bao_gia_sai 
        $v_link_bao_gia = _get_module_config('cau_hinh_dung_chung', 'v_link_bao_gia');
        $v_link_bao_gia_redirect = link_to_redirectout($v_link_bao_gia);
        ?>
        <ul><li><a rel="nofollow" target="_blank" href="<?php echo $v_link_bao_gia_redirect; ?>" title="Giới thiệu">Giới thiệu</a>|</li><li><a href="javascript:void(0)" onclick="window.open('/ajax/contact/index.php','replynews','menubar=1,resizable=1,width=524,height=420')" title="Góp ý">Góp ý</a>|<a href="#" title="Đầu trang">Đầu trang</a>|</li><li><a rel="nofollow" target="_blank" href="<?php echo $v_link_bao_gia_redirect; ?>" title="Liên hệ quảng cáo">&nbsp;LIÊN HỆ QUẢNG CÁO</a></li></ul>
        <?php // end 19/09/2016 TuyenNT fix_bug_trang_24hvn_link_bao_gia_sai ?>
    </div>
    <div class="ftCpRt"><?php echo $noidung?>
        <?php
        $v_gia_tri = xu_ly_on_off_text_link_duoi_footer($cat_id);
        if($v_gia_tri){
            $footer_link = $row_footer_link['c_noi_dung'];
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
            echo $footer_link;
        }
        ?>
    </div>
    <div class="ftLog"><a href="<?php echo $linklogo?>" title=""><img src="<?php echo $imglogo?>" width="156" height="121" alt="" title="" /></a></div>
    <div class="clr"></div>
</footer>
<?php /* begin 15/5/2017 TuyenNT nhung_nut_share_follow_zalo_vao_trang_24h */ ?>
<!--@script_sdk_zalo@-->
<?php /* end 15/5/2017 TuyenNT nhung_nut_share_follow_zalo_vao_trang_24h */?>
<script type="text/javascript">
    window.addEventListener('load', function(){
        displayBanner();
        setTimeout(function(){
            off_ad_zone_when_without_ads_delivery();
        },3000);
    });
</script>
<!--@begin_scroll_left_banner@-->
<script type="text/javascript">	
	var floorPos = 0;
    window.addEventListener('load', function(){
		window.onscroll = function() {				
			var fixPosRight = findYPos(document.getElementById('subRight'));						
			var rightParentID = 'right';
			doScroll("subRight", fixPosRight, rightParentID);
		}
    });
</script>
<!--@end_scroll_left_banner@-->
<!--@server_id@-->
<!--script src="http://thongke.24h.com.vn/24h-analytics/24h-analytics.js" type="text/javascript"></script--> 
<script type="text/javascript">
    try{v_url=location.href,"undefined"==typeof _SERVER&&(_SERVER=0),v_url.indexOf("?")>=0?v_url+="&server="+_SERVER:v_url+="?server="+_SERVER,"undefined"!=typeof _CAT&&(v_url+="&cat="+_CAT),v_get="","undefined"!=typeof tag_id&&(v_get="&tag_id="+tag_id),"undefined"!=typeof v_vung_mien_theo_user&&(v_url+="&region="+v_vung_mien_theo_user),"undefined"!=typeof v_device_global&&(v_url+="&device="+v_device_global),v_url=escape(v_url);var v_count=v_url.indexOf("24h.vn");v_domain=v_count>0?"24h.vn":"24h.com.vn",""!=v_url&&document.write("<img src='//thongke."+v_domain+"/24h-analytics/24h-analytics.php?rand="+Math.random()+v_get+"&amp;url_tracker="+v_url+"' height='0' width='0'>")}catch(e){}
</script>
<!--  comScore Tag -->
<noscript>
    <img src="http://b.scorecardresearch.com/p?c1=2&amp;c2=9634358&amp;cv=2.0&amp;cj=1" alt=""/>
</noscript>
<!--  comScore Tag -->
<!--js_player-->
<script>
    window.addEventListener('load', function(){
        xu_ly_anh_dai_dien_dang_gif();
        dong_danh_muc_khi_click_ra_ngoai();
    });
</script>

<script type="text/javascript">
    window.addEventListener('load', function(){
        if (window.addEventListener){
            addEventListener("message", expand_close_banner_html5, false);
        } else {
            attachEvent("onmessage", expand_close_banner_html5);
        }
    });
</script>
<script src="<?php echo BASE_URL_FOR_PUBLIC.'js/swiper.min.js?v='.JS_CSS_VERSION;?>"></script>
<script type="text/javascript">
    window.addEventListener('load', function(){
        if(typeof v_loai_slide_menu !== "undefined" && v_loai_slide_menu > 0){
            create_slide_with_page_number_auto('slide_menu', 'swpier_container_video_slide_anh','swiper_active_slide_video_slide_anh', 'swpier_wrapper_video_slide_anh', ' ',1,'/ajax/box_menu_theo_loai/index/'+v_loai_slide_menu+'?page=','swiper-wrapper swpier_wrapper_video_slide_anh', 'btnPrev-anh', 'btnNext-anh');
        }
        if(typeof v_slide_box_4t1_ipad !== "undefined" && v_slide_box_4t1_ipad > 0){
            create_box_tttt_slide('4t1','slide-box-4t1','vertical',<?php echo SO_ITEM_BAI_PR_2015_BOX4T1; ?>,<?php echo PR_AUTOPLAY_TIME; ?>,'4t1-top','4t1-bottom');
        }
    });
</script>
<?php
    $html_js    = '<script>'._read_file(WEB_ROOT.'js/lazyload.min.js');
    $html_js    .='</script>';
    echo $html_js;
?>
<script>
    window['myLazyLoad'] = new LazyLoad({
        data_src : 'original'	
    });
</script> 
<!--@@JQUERY_CODE@@-->
<!--@js@-->
<!--@js_quizz3_news@-->
<!--@js_twentytwenty@-->
<?php 
    $html_js_ads    = '<script>'._read_file(WEB_ROOT.'js/ads_common.min.js').'</script>';
    echo $html_js_ads;
?>
<script type='text/javascript'>
//<![CDATA[
    <!--js_quang_cao-->
//]]>
</script>
<!--@@SCRIPT_FOOTER@@-->
<!--END_OF_PAGE -->
</body>
</html>