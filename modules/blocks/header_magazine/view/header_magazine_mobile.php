<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php /* Begin 10-6-2019 TuyenNT bo_sung_1_so_thong_tin_ho_tro_seo */ ?>
<html lang="vi" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset = "UTF-8">
<meta http-equiv="Content-Language" itemprop="inLanguage" content="vi"/>
<link rel="icon" href="/favicon.gif" type="image/gif" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes" />
<?php /* End 10-6-2019 TuyenNT bo_sung_1_so_thong_tin_ho_tro_seo */ ?>
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<link rel="apple-touch-startup-image" href="<?php html_image('images/m2014/images/logo-24h_bookmarks.png'); ?>" />
<link rel="apple-touch-icon" href="<?php html_image('images/m2014/images/logo-24h_bookmarks.png'); ?>"/>
<link rel="apple-touch-icon-precomposed" sizes="160x90" href="<?php html_image('images/m2014/images/images/logo-24h_bookmarks.png'); ?>" />
<meta name="mobile-web-app-capable" content="yes" />
<link rel="shortcut icon" sizes="160x90" href="<?php html_image('images/m2014/images/logo-24h_bookmarks.png'); ?>" />
<!--@title@-->
<!--@description@-->
<!--@keywords@-->
<!--@social_network_meta@-->
<!--@canonical@-->
<!--@robots_index@-->
<!--27052015 Thangnb metagooglebot-->
<!--Snippets Video Google-->
<!--meta_googlebot-->
<?php /* Begin 10-6-2019 TuyenNT bo_sung_1_so_thong_tin_ho_tro_seo */ ?>
<!--@meta_alternate@-->
<?php /* End 10-6-2019 TuyenNT bo_sung_1_so_thong_tin_ho_tro_seo */ ?>
<meta http-equiv="cleartype" content="on" />
<meta name="MobileOptimized" content="width" />
<meta name="HandheldFriendly" content="true" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<meta name="copyright" content="Copyright © 2013 by 24H.COM.VN" />
<meta name="abstract" content="24H.COM.VN Website tin tức số 1 Việt Nam" />
<meta name="distribution" content="Global" />
<?php // begin 23/05/2016 TuyenNT fix_loi_va_bo_sung_snippet_ipad_mobile_24h ?>
<?php /*Begin 23-11-2017 trungcq XLCYCMHENG_27919_bo_sung_author_lang_header*/?>
<meta name="author" content="Tin Tức 24h" />
<?php /*End 23-11-2017 trungcq XLCYCMHENG_27919_bo_sung_author_lang_header*/?>
<?php // end 23/05/2016 TuyenNT fix_loi_va_bo_sung_snippet_ipad_mobile_24h ?>
<meta http-equiv="refresh" content="1200" />
<meta name="REVISIT-AFTER" content="1 DAYS" />
<meta name="RATING" content="GENERAL" />
<meta name="format-detection" content="telephone=no" />
<?php
    echo '<script src="//cdn.24h.com.vn/js/24hgatracking/fe/prod/24huidmbutil.min.js"></script>';
?>
<script type="text/javascript">
    var uId24H = 'profile24hUid';
    var gauID24h_dimension31 = '';
    function get24hUidData(name) {
      const value = `; ${document.cookie}`;
      const parts = value.split(`; ${name}=`);
      if (parts.length === 2) return parts.pop().split(';').shift();
    }
    gauID24h_dimension31 = get24hUidData(uId24H);
    if(gauID24h_dimension31 === ''){
        gauID24h_dimension31 = 'None';
    }
</script>
<!--@@end_code_prebid-->
<!--@@start_code_criteo-->
<!--@@js_code_criteo-->
<!--@@end_code_criteo-->
<!--@@script_code_slot_dfp-->								   
<!-- Begin comScore Tag -->
<script type="text/javascript">
//<![CDATA[
	  var _comscore = _comscore || [];
	  _comscore.push({ c1: "2", c2: "9634358" });
	  (function() {
		var s = document.createElement("script"), el = document.getElementsByTagName("script")[0]; s.async = true;
		s.src = (document.location.protocol == "https:" ? "https://sb" : "http://b") + ".scorecardresearch.com/beacon.js";
		el.parentNode.insertBefore(s, el);
	  })();
//]]>
</script>
<!--@css@-->			   
<?php // begin 17-8-2018 BangND XLCYCMHENG_26387_bo_sung_chuc_nang_nhap_bai_magazine ?>
<!--@css_magazine@-->
<?php // end 17-8-2018 BangND XLCYCMHENG_26387_bo_sung_chuc_nang_nhap_bai_magazine ?>
<!--@@JQUERY_CODE@@-->
<!--@js@-->
<?php // begin 17-8-2018 BangND XLCYCMHENG_26387_bo_sung_chuc_nang_nhap_bai_magazine ?>
<!--@js_magazine@-->
<?php // end 17-8-2018 BangND XLCYCMHENG_26387_bo_sung_chuc_nang_nhap_bai_magazine ?>
<?php
//End 12-07-2017 : Thangnb xu_ly_load_jquery_tren_header
?>
<!--@css_magazine@-->
<!--@js_magazine@-->
<!-- LIVESCORE_CONST -->
</head>
<body>
<!--@@SCRIPT_GTM_BODY@@-->
<script type="text/javascript">
    jQuery(document).ready(function() {
        var mqsmall = "(min-device-width:320px)";
        var mqbig   = "(min-device-width:1024px)";
        function imageresize() {
            if(window.matchMedia(mqbig).matches) {
                jQuery('img[src]').each(function () {
                    jQuery(this).attr('src',jQuery(this).attr('src'));
                });
            }
            else if(window.matchMedia(mqsmall).matches) {
                jQuery('img[data-src-767px]').each(function () {
                    jQuery(this).attr('src',jQuery(this).attr('data-src-767px'));
                });
            }
        }
        imageresize();
        jQuery(window).bind("resize", function() {
                imageresize();
        });
    });
    // begin AnhTT 22/5/2020 Thay_doi_duong_dan_icon_magazine
	function go_back_site() {
        history.go(-1);
	}
    // begin AnhTT 22/5/2020 Thay_doi_duong_dan_icon_magazine
	function js_connect_facebook(p_public_domain) {
		window.fbAsyncInit = function() {
			FB.init({
				//Begin 14-07-2016 : Thangnb thay_doi_domain_chia_se_fb
				appId : '292166911133889', // App ID
				status : true, // check login status
				cookie : true, // enable cookies to allow the server to access the session
				oauth : true, // enable OAuth 2.0
				xfbml : true, // parse XFBML
				/* Begin: Tytv - 29/07/2016 - nang_cap_api_facebook */
				version    : 'v2.6'
				/* End: Tytv - 29/07/2016 - nang_cap_api_facebook */
			});
			if(typeof(loadonload) != "undefined" && loadonload == true){
				 var is_callback = false;
				 FB.getLoginStatus(function(response) {
					 //Begin 11-04-2016 : Thangnb nang_cap_box_binh_luan
					 statusChangeCallback(response, 0, 1);
					 is_callback = true;
					 //End 11-04-2016 : Thangnb nang_cap_box_binh_luan
				});
				if (is_callback == false) {
					statusChangeCallback('',0,1);
				}
			}          
		};
		(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			/* Begin: Tytv - 29/07/2016 - nang_cap_api_facebook */
			js.src = "//connect.facebook.net/vi_VN/sdk.js#version=v2.12&xfbml=1";
			/* End: Tytv - 29/07/2016 - nang_cap_api_facebook */
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
	}
	function hien_thi_icon_header_khi_cuon_trang()
	{
		var currentScroll = 0;
		document.addEventListener('touchmove', function(){
			var scrollTop = document.body.scrollTop || window.pageYOffset;
			if (scrollTop == 0) {
				document.getElementById('arrowPageUp').style.display = 'none';
			}
			if (scrollTop > currentScroll) {
				document.getElementById('arrowPageUp').style.display = 'block';
			}		
			currentScroll = scrollTop;		
		}, false);
		document.addEventListener('scroll', function(){
			var scrollTop = document.body.scrollTop || window.pageYOffset;
			if (scrollTop == 0) {
				document.getElementById('arrowPageUp').style.display = 'none';
			}
			if (scrollTop > currentScroll + 5) {
				document.getElementById('arrowPageUp').style.display = 'block';
			}
			currentScroll = scrollTop;		
		}, false);
	}
	/*
	 * Ham ẩn/hiện 1 block
	 * @param: p_object_id : ID cua object
	 */
	function show_hide_block(p_object_id) {
		var block = document.getElementById(p_object_id);
		if (block != '' && block != undefined) {
			if(document.getElementById(p_object_id).style.display=='none') {
			  document.getElementById(p_object_id).style.display='block';
			}else{
			  document.getElementById(p_object_id).style.display='none';
			}
		}
	}
	
	/*
	* @author:Thangnb - Ham thay doi giua 2 class cua 1 object
	* @params:
		p_object_id: ID cua object
		p_class_1: class thu nhat de chuyen doi
		p_class_2: class thu 2 de chuyen doi
	*/
	
	function change_class_onclick(p_object_id, p_class_1, p_class_2) {
		if (document.getElementById(p_object_id).getAttribute('class') == p_class_1) {
			document.getElementById(p_object_id).setAttribute('class', p_class_2);
		} else{
	
			document.getElementById(p_object_id).setAttribute('class', p_class_1);	
		}
	}
    function share_mobile() {
        if (navigator.share) {
            navigator.share({
                url: window.location.href
            });
        }
    }
</script>
<!--begin_css_inline_magazine-->										
<style type="text/css">
/*Start đoạn chung cả header và footer*/
@media (min-width: 1025px) {
    img[data-src-desktop] {
        content: attr([data-src-desktop], url);
    }
}
* {
    margin: 0 auto;
}
.mg-header *,.mg-footer-top *,.footer-menu2-container *,.mg-footer *,.footerSeo * {
	font-family: Helvetica,Arial,sans-serif;	
}
*, *:before, *:after {
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    -ms-box-sizing: border-box;
    box-sizing: border-box;
}
body {
    ms-text-size-adjust: 100%;
    -webkit-text-size-adjust: 100%;
}
.mg-header {
    z-index: 99999;
    background: rgba(0, 0, 0, 0.5);
    text-align: center;
    transition: all .3s;
    -webkit-transition: all .3s;
    -moz-transition: all .3s;
    position: fixed;
    width: 100%;
    background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.3));
}
.heading-site a{
    font-size: 44px;
    text-decoration: none;
    color: #fff;
    height: 68px;
    line-height: 68px;
    display: block;
    float: left;
}
.back-site {
    position: absolute;
    float: left;
    left: 0px;
    top: 0px;
}
.heading-site {
    display: inline-block;
}
.back-site a {
    display: block;
    width: 100%;
    height: 100%;
    line-height: 68px;
}
.back-site a img {
    vertical-align: middle;
}
.heading-site a img {
    //margin-top: 7px;
}
.mg-menu ul{
    display: none;
    list-style: none;
    position: absolute;
}
.mg-menu > a { 
    display: block;
    width: 24px;
    height: 20px;
    position: absolute;
    left: 0;
    background-image: url(<?php html_image('/images/m2014/images/icon-mg-menu.png'); ?>);
    top: 10px;
}
.mg-menu:hover > ul {
    display: block;
    position: absolute;
    left: -108px;
    top: 68px;
    width: 204px;
}
.mg-menu ul li.sub-li {
    max-width: 204px;
    position: relative;
    width: 100%;
    display: inline-block;
}
.mg-menu ul li a {
    width: 180px;
    margin-right: -181px;
    background: #000;
    float: left;
    color: #fff;
    font-weight: 400;
    font-size: 14px;
    text-align: left;
    line-height: 16px;
    padding: 3px 12px;
    text-decoration: none;
}
.mg-menu ul li.sub-li > a:hover {
    font-weight: 700;
    transition: .3s;
}
.mg-menu ul.fly li a {
    background-color: #363636;
}
.mg-menu ul.fly li a:hover {
    text-decoration: underline;
}
.mg-menu ul.fly {
    left: 204px;
    top: 0;
    padding: 0;
}
.mg-menu li:hover > a {
    background-color: #363636;
    top: 26px;
}
.mg-menu .sub-li:hover ul {
    display: block;
}
.mg-menu ul.fly:hover {
    clear: left;
}
.mg-user-profile {
    float: right;
    position: absolute;
    top: 15px;
    right: 20px;
}
.mg-user-profile a{
    margin-right: 10px
}
img {
    border: 0;
}
.mg-menu {
    position: relative;
    float: left;
    left: 68px;
    top: 0px;
    text-align: left;
    width: 68px;
    height: 68px;
}
.mg-menu a img {
    position: absolute;
    left: 17px;
    top: 23px;
}
.mg-search {
    position: absolute;
    float: left;
    left: 125px;
    top: 17px;
}
.mg-search .form-control {
    display: block;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.42857143;
    color: #fff;
    background-color: #363636;
    background-image: none;
    border: 1px solid #363636;
    border-radius: 25px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
    -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    position: relative;
    z-index: 2;
    float: left;
    width: 90%;
    margin-bottom: 0;
}
.mg-search input:focus {
    outline:none !important;
}
.input-group-addon {
    position: absolute;
    right: 5px;
    z-index: 3;
    top: 5px;
}
.input-group-addon button {
    background-color: transparent;
    background-image: none;
    border: 0;
    border-radius: 4px;
    padding: 0;
    cursor: pointer;
}
/*End header*/

/*Start footer*/
.mg-footer a {
    text-decoration: none;
    float: left;
    display: block;
    height: 78px;
    line-height: 100px;
    margin-right: 10px;
}
.mg-group-menu {
    clear: both;
}
.mg-group-menu > li ul {
    padding: 0;
}
.mg-group-menu > li ul li {
    list-style: none;
    padding-left: 15px;
    background: url(<?php html_image('/images/m2014/images/sprites.png'); ?>) 2px -302px no-repeat;
}
.mg-group-menu > li {
    width: 25%;
    float: left;
    list-style: none;
    margin: 10px 0;
}
.mg-footer-top {
    display: inline-block;
    width: 100%;
    background: #363636;
    padding: 20px 0;
}
.mg-footer-top li a {
    color: #fff;
    text-decoration: none;
    line-height: 1.5;
}
.footer-menu2-container {
    background: #78b43d;
    padding: 5px 30px;
    text-align: center;
    color: #000;
    font-weight: bold;
	margin-top: -4px;
}
.footer-menu2 {
    text-align: center;
    color: #000;
    font-weight: bold;
}
.footer-menu2 a {
    font-weight: bold;
    color: #000;
    margin: 0 15px;
    text-decoration: none;
    font-size: 14px;
}
.footer-menu2 a:hover {
    text-decoration: underline;
}
.mg-footer h1 {
    font-size: 16px;
    color: #464646;
    margin-bottom: 15px;
    font-weight: 400;
}
.mg-footer-inner {
    display: inline-block;
}
.mg-footer-inner p, .mg-footer-inner * {
    color: #7d7d7d;
    font-size: 12px;
	text-align:center;
	line-height: 1.5;
	margin:0;
}
.mg-footer {
    display: inline-block;
    width: 100%;
    padding: 15px 0;
    background: #f2f2f2;
}
.mg-footer .container-inner {
    text-align: center;
	float:left;
}
/*Start footer*/

@media only screen and (max-width: 767px) {
    /*Start menu*/
    .mg-header .mg-menu {
        left: 50px;
        position: absolute;
        float: left;
    }
    .mg-header .mg-menu a img {
        position: absolute;
        left: 0;
        top: -19px;
    }
    .heading-site a {
        font-size: 18px;
        height: 40px;
    }
    .mg-header .mg-user-profile {
        top: 8px;
        right: 5px;
    }
    .mg-header .mg-user-profile a {
        margin-right: 0;
    }
    .mg-menu-mobile .menuRight {
        left: -50px;
        top: 40px;
        position: relative;
        background: #000;
        width: 320px;
    }
    /*End menu*/

    /*Start footer*/
    .mg-footer-inner {
        width: 100%;
    }
    .mg-footer a, .mg-footer a h1 {
        font-size: 16px;
        line-height: 1.5;
        display: inherit;
        height: inherit;
        float: none;
    }
    .mg-footer-common {
        padding: 0 10px;
    }
    .mg-group-menu {
        padding: 0 10px;
    }
    .mg-group-menu > li {
        width: 100%;
        margin: 0;
    }
    .mg-footer-top li a b, .mg-footer-menu2 {
        display: none;
    }
    .mg-footer-top li a {
        color: #fff;
        text-decoration: none;
        line-height: 2.5;
        font-size: 14px;
    }
    .mg-group-menu > li ul li {
        background: url(<?php html_image('/images/m2014/images/sprites.png'); ?>) 2px -302px no-repeat;
    }
    .mg-footer-top {
        padding: 10px 0;
    }
    /*End footer*/
}

@media only screen and (min-width: 768px) and (max-width: 1024px) {
    /*Start header*/
    .mg-menu-mobile .menuRight {
        left: -68px;
        top: 68px;
        position: relative;
        background: #000;
        width: 320px;
    }
    .mg-header {
        height: 68px;
    }
    /*End header*/

    /*Start footer*/
    .mg-footer-inner {
        width: 100%;
    }
    .mg-menu > a {
        top: 24px;
        left: 24px;
    }
    /*End footer*/
}
@media only screen and (width: 768px) {
    .mg-header {
        height: 40px;
    }
    .mg-menu a img {
        position: absolute;
        left: 0;
        top: 10px;
    }
    .mg-menu-mobile .menuRight {
        top: 40px;
    }
    .mg-user-profile {
        top: 7px;
    }
    .mg-menu > a {
        top: 10px;
        left: 0px;
    }
}
@media only screen and (max-width: 1024px) {
    .mg-menu:hover .mg-menu-destop {
        display: none;
    }
    .mg-menu .mg-menu-mobile {
        display: none;
    }
    .mg-menu:hover .mg-menu-mobile {
        display: block;
    }
    .mg-menu-mobile ul {
        display: block;
        padding: 0;
        width: 320px;
        position: inherit;
    }
    .mg-header .mg-search {
        display: none;
    }
    .mg-menu-mobile ul li a {
        width: 100%;
        display: inline-block;
        font-size: 16px;
        float: inherit;
        margin-right: 0;
        line-height: 36px;
        height: 36px;
        background: none;
    }
    .mnRight {
        display: block;
    }
    .mnRight li ul {
        background-color: #363636;
    }
    .mnRight li ul li {
        padding-left: 20px;
    }
    .mg-menu-mobile ul li a.down, .mg-menu-mobile ul li a.up {
        position: absolute;
        top: 0px;
        width: 17px;
		right: 10px;
    }
    .mg-menu-mobile ul.mnRight li ul {
        position: inherit;
    }
    .mg-menu-mobile ul.mnRight > li {
        position: relative;
        display: inline-block;
        width: 100%;
    }
    .mg-header .searchBox .mg-search {
        display: inline-block;
        float: none;
        width: 85%;
        margin-top: 20px;
        position: relative;
        left: 20px;
        top: 0;
    }
    .searchBox .mg-search .form-control {
        width: 85%;
    }
    .mg-menu-mobile ul li a.active {
        font-weight: 700;
    }
}
@media only screen and (min-width: 1025px) {
    .mg-menu > a {
        display: block;
        left: 15px;
        top: 23px;
    }
    .mg-menu-mobile {
        display: none;
    }
}
@media only screen and (min-width: 768px) {
    .mg-share-social {
        display: none;
    }
}
ul.mnRight li a.down {
    background: url(<?php html_image('/images/m2014/images/sprites-small.png'); ?>) no-repeat right -754px;
}
ul.mnRight li a.up {
    background: url(<?php html_image('/images/m2014/images/sprites-small.png'); ?>) no-repeat right -665px;
}
.arrowPageUp {
    position: fixed;
    bottom: 60px;
    right: 0px;
}
.mnRight-close {
    background: #000000;
    padding: 15px;
    font-size: 14px;
    color: #fff;
    display: block;
    text-align: center;
}
.mnRight-close span.icon {
    width: 20px;
    height: 20px;
    display: inline-block;
    background: url(<?php html_image('/images/m2014/images/sprites-small.png'); ?>) no-repeat 0px -880px;
    vertical-align: middle;
    padding-left: 5px;
}
.thong_ke img {
	width: 0px !important;
	height: 0px !important;
}
.mg-duroi {
    margin: 20px auto !important;
    text-align: right;
	padding: 0px 10px;
}
.atclRdSb * {
    font-family: Helvetica,Arial,sans-serif;
}
.atclRdSb {
    margin-top: 5px;
	font-size: 16px;
	padding-top: 5px;
    text-align: left;
    margin: 10px 0 0 0;
	padding: 0px 10px;
}
.atclRdSbIn  {
    display: inline-block;
    width: 100%;
}
.atclRdSbIn  span {
    line-height: 130%;
}
.mrT10 {
    margin-top: 10px;
}
.mrB10 {
    margin-bottom: 10px;
}
.imgFlt {
    float: left;
    margin: 0 2% 0px 0;
    display: block;
    position: relative;
}
@media only screen and (max-width: 599px) {
	.imgFlt {
		margin-right: 10px;
	}
}
@media only screen and (max-width: 568px) and (min-width: 321px) {
	.atclRdSb a,.nwsTit {
		font-size: 18px;	
	}
}
.atclRdSbIn a {
    font-weight: 700;
    padding-bottom: 7px;
    color: #000000;
    text-decoration:none;
}
.lind {
    border-bottom: 1px dotted #78b43d;
}
.atclRdSbIn span {
    width: 110px;
}
.atclRdSbIn img {
	border: none;
	display: inline-block;
    vertical-align: middle;
	max-width: 100%;
    height: auto;	
}
.atclRdSb .atclRdSbIn .nwsTit {
    padding: 0;
    width: inherit;
    margin-left: 0px;
    float: inherit;
	padding-left: 0px;
}
.nwsTit {
    display: block;
    font-weight: bold;
    color: #000;
    padding: 0 0 5px 0;
    margin: 0;
}
.pic-icon {
	position: absolute;	
	bottom: 5px !important;
	left: 5px !important;
    background: url(http://static.24h.com.vn/images/m2014/images-small/iconPic2-small.png) no-repeat 0 0;
    width: 20px;
    height: 20px;
}
.video-icon {
    bottom: 5px !important;
    left: 5px !important;
    background: url(http://static.24h.com.vn/images/m2014/images-small/iconVideo2-small.png) no-repeat 0 0;
    width: 20px;
    height: 20px;
    position: absolute;
    display: block;
}
.baiviet-bailienquan .blq-border-none {
    margin-bottom : 10px;
}
.mg-header{
	background: #535353;
}
.heading-site a{
	height: 60px;
}
.back-site{
	left: 15px;
    top: 50%;
    margin-top: -17px;
}
.icoBack{
	display: inline-block;
	width: 24px;
	height: 24px;
	background: url('//image.24h.com.vn/images/m2014/magazine_icon/arrow_back_36px.png') no-repeat;
    margin: 5px 0;
}
.mg-menu > a{
	background-image: url('//image.24h.com.vn/images/m2014/magazine_icon/magazine_menu.png');
	height: 19px;
	top: 50%;
    margin-top: -14px;
}
.mg-menu-mobile .menuRight{
	top: 60px;
}
.mg-header .mg-user-profile{
	display: block;
	padding: 0;
    top: 50%;
    margin-top: -15px; /* Minhdt 31/12/2021 Fix-logo-icon-share-24h */
    right: 10px;
    padding: 0;
}
.icoFb{
	display: inline-block;
	background: url('//image.24h.com.vn/images/m2014/magazine_icon/ficon_magazine.png') no-repeat;
	width: 36px;
	height: 36px;
    margin: 5px 0;
}
.heading-site a img{
    width: auto;
    height: 65px;
    position: absolute;
    top: 50%;
    left: 50%;
    margin-top: -28px;
    margin-left: -84px;
}
.content{
	padding-top: 60px;
}
.atclRdSb .tit, .atclRdSb>header{
    font-size: 20px;
    text-transform: uppercase;
    font-weight: 600;
    color: #7ab245;
    padding-top: 10px;
    border-top: 5px solid #7ab245;
    margin-bottom: 10px;
}
.atclRdSb .mrB10.mrT10{
	margin-top: 17px;
	margin-bottom: 17px;
}
.atclRdSbIn span.imgFlt{
	width: 140px;
	height: 105px;
}

@media screen and (max-width: 320px) {
	.heading-site a img{
		margin-left: -70px;
	}
}
/* Minhdt 22/12/2021 Fix-Text-Phone-Footer-Bi-Nhay-Dong-Tren-Firefox */
@media screen and (max-width: 480px) {
    .mg-footer-inner p, .mg-footer-inner *{
        display: inline-block;
        font-size: 14px !important;
    }
}
/* End Minhdt 22/12/2021 Fix-Text-Phone-Footer-Bi-Nhay-Dong-Tren-Firefox */
/*End đoạn chung cả header và footer*/

/*== Box Sticky Menu Bottom ==*/
.menu24h-sticky-b{--bg-menu:#fff;--box-shadow-menu:0 1px 10px #999}.menu24h-sticky-b *{margin:0;padding:0;box-sizing:border-box;font-family:Arial,Helvetica,sans-serif}.menu24h-sticky-b{position:fixed;bottom:0;left:0;right:0;background:var(--bg-menu);box-shadow:var(--box-shadow-menu);z-index:9999;transition:transform 400ms ease;-webkit-transition:transform 400ms ease;-moz-transition:transform 400ms ease;-o-transition:transform 400ms ease}.menu24h-sticky-b ul{display:flex;align-items:center;justify-content:center;padding:15px 5px}.menu24h-sticky-b ul li{list-style:none}.menu24h-sticky-b ul li a{display:flex;align-items:center;text-align:center;flex-direction:column;padding:0 5px;font-size:12px;color:#888;text-decoration:none}.menu24h-sticky-b ul li a img{margin:0 0 8px;width:18px;height:18px;object-fit:scale-down}@media screen and (min-width: 736px){.menu24h-sticky-b ul li a{font-size:16px}}@media screen and (max-width: 667px){.menu24h-sticky-b ul li a{font-size:14px}}@media screen and (max-width: 568px){.menu24h-sticky-b ul li a{font-size:12px}}@media screen and (min-width: 380px) and (max-width: 480px){.menu24h-sticky-b ul{padding:15px 5px}}@media screen and (max-width: 414px){.menu24h-sticky-b ul li a{font-size:14px}}@media screen and (max-width: 375px){.menu24h-sticky-b ul li a{font-size:12px}}@media screen and (max-width: 320px){.menu24h-sticky-b ul li a{font-size:10px}}
/*== End Box Sticky Menu Bottom ==*/
/*== Tin Lien Quan ==*/
	.atclRdSb .tmPst{
	    display: block;
	    padding: 0 0 0 150px;
	    margin:8px 0 0;
	    color: #757575;
	    font-weight: 400;
	}
	/*== Hoz ==*/
	    @media screen and (min-width: 736px){
	        .atclRdSb .tmPst {
	            font-size:18px;
	        }
	    }
	    @media screen and (max-width: 667px){
	        .atclRdSb .tmPst {
	            font-size:16px;
	        }
	    }
	    @media screen and (max-width: 568px){
	        .atclRdSb .tmPst {
	            font-size:14px;
	        }
	    }
	/*== End Hoz ==*/

	/*== Vertical ==*/
	    @media screen and (max-width: 480px){
	        .atclRdSb .tmPst {
	            font-size:16px;
	        }
	    }
	    @media screen and (max-width: 375px){ 
	        .atclRdSb .tmPst {
	            font-size:14px;
	        }
	    }
	    @media screen and (max-width: 320px){
	        .atclRdSb .tmPst {
	            font-size:12px;
	        }
	    }
	/*== End Vertical ==*/
/*== End Tin Lien Quan ==*/

/*== Footer ==*/
	.mg-footer {
	    padding: 15px 5px;
	}
/*== End Footer ==*/
</style>
<!--end_css_inline_magazine-->									   
<div class="container" style="overflow-x:hidden">
    <!-- Start header html -->
    <div class="mg-header clearfix">
        <div class="back-site">
            <a href="javascript:;" onclick="go_back_site()">
                <span class="icoBack"></span>
            </a>
        </div>
        <div class="mg-menu">
            <a href="javascript:;" onclick="document.getElementById("demo").style.display='block'" id="show_menu_button"></a>
            <div id="mg-menu-destop-container" style="display: none; position: relative;">
                <ul class="mg-menu-destop" id="mg-menu-destop" style="height: 37px;;">
                <li class="sub-li"><a href="<?php echo BASE_URL_FOR_PUBLIC; ?>" title="Trang chủ 24giờ" class="sub-a" id="sub-a-parent-0">Trang chủ</a></li>
                <li class="sub-li"><a href="<?php echo BASE_URL_FOR_PUBLIC; ?>" title="Trang chủ 24giờ" class="sub-a" id="sub-a-parent-0">Tin tức</a></li>
            </ul>
        </div>
        </div>
        <!--begin_h1-->					   
        <div class="heading-site">
            <a href="<?php echo BASE_URL_FOR_PUBLIC; ?>" title="Magazine" class="home-logo-link">
                <img class="fullscreen" src="<?php html_image('/images/logo_3do_mgz_1.png'); ?>" >
            </a>
        </div>
        <!--end_h1-->					   
        <?php
		$cat_id = '';
		$news_id = '';
        if( preg_match( '#-c([0-9]+)a([0-9]+).html#', $_GET['web_url'], $v_result) || preg_match( '#-c([0-9]+)a([0-9]+)q([0-9]+).html#', $_GET['web_url'], $v_result)){
            $cat_id = intval($v_result[1]);
            $news_id = intval($v_result[2]);
        } elseif( preg_match( '#-c([0-9]+)a([0-9]+)i([0-9]+).html#', $_GET['web_url'], $v_result)){
            $cat_id = intval($v_result[1]);
            $news_id = intval($v_result[2]);
        }
        ?>
    </div>
    <!-- End header html -->
