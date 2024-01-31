<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php /* Begin 10-6-2019 TuyenNT bo_sung_1_so_thong_tin_ho_tro_seo */ ?>
<html lang="vi" xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="icon" href="/favicon.gif" type="image/gif" />


<meta charset = "UTF-8">
<meta http-equiv="content-language" itemprop="inLanguage" content="vi"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes" />
<?php /* End 10-6-2019 TuyenNT bo_sung_1_so_thong_tin_ho_tro_seo */ ?>
<!--@title@-->
<!--@description@-->
<!--@keywords@-->
<!--@canonical@-->
<!--@robots_index@-->
<!--@social_network_meta@-->
<!-- HOME_INDEX_24H -->
<!--Snippets Video Google-->
<!--@meta_googlebot@-->
<?php /* Begin 10-6-2019 TuyenNT bo_sung_1_so_thong_tin_ho_tro_seo */ ?>
<!--@meta_alternate@-->
<?php /* End 10-6-2019 TuyenNT bo_sung_1_so_thong_tin_ho_tro_seo */ ?>
<meta name="language" content="vietnamese" />
<meta name="copyright" content="Copyright © 2013 by 24H.COM.VN" />
<meta name="abstract" content="24H.COM.VN Website tin tức số 1 Việt Nam" />
<meta name="distribution" content="Global" />
<?php /*Begin 23-11-2017 trungcq XLCYCMHENG_27919_bo_sung_author_lang_header*/?>
<meta name="author" content="Tin Tức 24h" />
<?php /*End 23-11-2017 trungcq XLCYCMHENG_27919_bo_sung_author_lang_header*/?>
<meta http-equiv="refresh" content="1200" />
<meta name="REVISIT-AFTER" content="1 DAYS" />
<meta name="RATING" content="GENERAL" /> 
<script async type="text/javascript" src="/js/jquery.min.js"></script>
<!--@@end_code_prebid-->
<!--@@start_code_criteo-->
<!--@@js_code_criteo-->
<!--@@end_code_criteo-->
<!--@@script_code_slot_dfp-->								   
<?php /* begin 4/1/2016 TuyenNT bo_sung_code_du_lieu_24h_audience_insight_trang_oto_xe_may chuyen the meta ants xuong duoi */ ?>
<!--meta_ants-->
<?php /* end 4/1/2016 TuyenNT bo_sung_code_du_lieu_24h_audience_insight_trang_oto_xe_may chuyen the meta ants xuong duoi */ ?>
<?php // Begin 28/08/2015 Ducnq - nang_cap_meta_data (biến global được set ở modules file index) ?>
<!--@meta_data_add_28_08_2015@-->
<?php 
//Begin : 29-09-2015 : Thangnb toi_uu_page_speed
?>
<!--@css@-->
<?php // begin 17-8-2018 BangND XLCYCMHENG_26387_bo_sung_chuc_nang_nhap_bai_magazine ?>
<!--@css_magazine@-->
<?php // end 17-8-2018 BangND XLCYCMHENG_26387_bo_sung_chuc_nang_nhap_bai_magazine ?>
<!--@js@-->
<?php // begin 17-8-2018 BangND XLCYCMHENG_26387_bo_sung_chuc_nang_nhap_bai_magazine ?>
<!--@js_magazine@-->
<?php // end 17-8-2018 BangND XLCYCMHENG_26387_bo_sung_chuc_nang_nhap_bai_magazine ?>


	<?php /* End anhpt1 27/4/201 thay_doi_ma_search */
    if (check_array($row_header_script[1])) {
		foreach ($row_header_script[1] as $v_script) {
            echo $v_script['c_noi_dung']."\n";
        }
    }
	echo html_load_header_js('<!--@js@-->', 0, MINIFY_JS_CSS,'','','pc');
?>
<script type='text/javascript'>
	v_cat_id = <?php echo $row_cat['ID']; ?>;
</script>
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
    <!--html_rss_feed-->
    <?php //Begin 12-07-2017 : Thangnb xu_ly_load_jquery_tren_header ?>
    <link href="https://fonts.googleapis.com/css?family=Quicksand:300,400,500,700&amp;subset=vietnamese" rel="stylesheet">
	<!--@css_magazine@-->
	<!--@js_magazine@-->
    <!-- LIVESCORE_CONST -->
</head>
<body>
<!--begin_css_inline_magazine-->										
<style type="text/css">
/*Start đoạn chung cả header và footer*/
p {
	font-family:Arial, Helvetica, sans-serif !important;
	font-size:16px !important;
}
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
    margin-top: 7px;
}
.mg-menu ul{
    list-style: none;
    position:inherit;
}
.mg-menu > a { 
    display: block;
    width: 24px;
    height: 20px;
    position: absolute; 
    left: 0;
    background-image: url(https://image.24h.com.vn/images/2014/magazine-icon/icon-mg-menu.png);
    top: 10px;
}
.mg-menu ul li.sub-li {
    max-width: 270px;
    position: relative;
    width: 100%;
    display: inline-block;
}
.mg-menu ul li a {
    width: 270px;
    margin-right: -181px;
    background: #000;
    float: left;
    color: #fff;
    font-weight: 400;
    font-size: 14px;
    text-align: left;
    line-height: 16px;
    padding: 10px 12px;
    text-decoration: none;
}
.mg-menu ul li.sub-li > a:hover {
    font-weight: 700;
    transition: .3s;
}
.mg-menu ul.fly {
    background-color: #363636;
	float:left;
	transition: all .2s ease-out;
	padding-left: 20px;
}
.mg-menu ul.fly a {
	background-color: #363636;	
}
.mg-menu ul.fly li a:hover {
    text-decoration: underline;
}
.mg-menu ul.fly {
	position : inherit;
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
    padding: 15px 10px 15px 0px;
    float: right;
    position: absolute;
    top: 0;
    right: 0;
}
.mg-user-profile a{
    margin-right: 10px;
    display: block;
    width: 32px;
    height: 32px;
    float: left;
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
    background-color: #363636;
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
    background: url(<?php html_image('/images/sprites.png'); ?>) 2px -545px no-repeat;
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
.mg-footer-top .container-inner {
	max-width : 1170px;	
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
.mg-footer-inner p {
    color: #7d7d7d;
    font-size: 12px;
}
.mg-footer {
    display: inline-block;
    width: 100%;
    padding: 15px 0;
    background: #f2f2f2;
}
.mg-footer .container-inner {
    text-align: center;
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
        height: 500px;
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
        background: url(<?php html_image('/images/sprites.png'); ?>) 2px -538px no-repeat;
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
        width: 276px;
        display: inline-block;
        font-size: 16px;
        float: inherit;
        margin-right: 0;
        line-height: 30px;
        height: 30px;
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
    .mg-menu-mobile ul li a.down {
        background: url(images/down.png) no-repeat;
        position: absolute;
        top: 15px;
        right: 0;
        width: 17px;
    }
    .mg-menu-mobile ul li a.down {
        background: url(images/up.png) no-repeat;
        position: absolute;
        top: 15px;
        right: 0;
        width: 17px;
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
        width: 93%;
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
.header-down {
	height: 50px;
}
.header-down .back-site a img {
    height: 50px;
    width: auto;	
}
.header-down #mg-menu-destop-container {
	top: 58px;
    left: -50px;
}
.header-down .mg-menu {
    left: 50px;
    top: -8px;	
}
.header-down .mg-menu > a {
	width: 20px;
	height:17px;
	background-size:contain;	
}
.header-down .mg-search {
    top: 7px;
    left: 95px;
}
.header-down .input-group-addon img {
	width: 20px;
	height : 20px;	
}
.header-down .mg-menu ul.fly {
	top: 0px !important;
    left: 0px !important;
}
.header-down .home-logo-link img {
    height: 60px;
    margin-top: 0px;
    width: auto;
}
.header-down .mg-user-profile img {
	width: 30px;
	height: 30px;
}
.header-down .mg-user-profile {
	top: -6px;	
}
.header-up .mg-search {
	display:block !important;	
}

.mg-header *  {
	transition: all .2s ease-out;	
}
.footerSeo {
    padding: 10px 0 0;
    width: 1004px;
    margin: 0 auto;
    text-align: center;
	font-size: 11px;
}
.footerSeo a {
    color: #00722e;
    text-transform: uppercase;
    font-weight: 700;
}
.thong_ke img {
	width: 0px !important;
	height: 0px !important;
}
ul.mg-menu-destop {
	margin: 0px;
	padding: 0px;	
}
.mg-menu-destop {
    overflow-y: scroll;
    overflow-x: hidden;
    height: 550px;
}
.mg-menu-destop::-webkit-scrollbar {
    width: 8px;
    background-color: #F5F5F5;
}
.mg-menu-destop::-webkit-scrollbar-thumb {
    border-radius: 10px;
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
    box-shadow: inset 0 0 6px rgba(0,0,0,.3);
    background-color: #555;
}
.mg-menu-destop::-webkit-scrollbar-track {
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
    box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
    border-radius: 10px;
    background-color: #F5F5F5;
}
.mg-menu-destop li a.down, .mg-menu-destop li a.up {
    position: absolute;
    top: 0px;
    width: 17px;
    right: 10px;
	margin-right: -10px;
}
.mg-menu-destop li a.down {
    background: url(<?php html_image('/images/sprites-small.png'); ?>) no-repeat right -754px;
}
.mg-menu-destop li a.up {
    background: url(<?php html_image('/images/sprites-small.png'); ?>) no-repeat right -665px;
}
.clear {
	clear : both;	
}
.mnRight-close {
    background: #000000;
    padding: 10px 15px;
    font-size: 14px;
    color: #fff;
    display: block;
    text-align: center;
	margin-top: -1px;
}
.mnRight-close span.icon {
    width: 20px;
    height: 20px;
    display: inline-block;
    background: url(<?php html_image('/images/sprites-small.png'); ?>) no-repeat 0px -880px;
    vertical-align: middle;
    padding-left: 5px;
}
#mg-menu-destop-container {
    left: -68px;
    top: 68px;
    width: 270px;
}
.mg-duroi {
    margin: 20px auto !important;
    text-align: right;
	padding : 0px 10px;
}
.baiviet-bailienquan {
	max-width: 900px;
    margin: 0 auto;	
	font-family: Arial, Helvetica, sans-serif;
}
.bailienquan-trangtrong:first-child {
    border-top: 1px dotted #78b43d;
}
.bailienquan-trangtrong {
    display: inline-block;
    width: 100%;
    border-bottom: 1px dotted #78b43d;
    padding: 20px 0;
}
.imgFloat {
    float: left;
	margin-right: 20px;
    display: block;
    position: relative;
}
.baiviet-bailienquan .bailienquan-trangtrong a {
    font-size: 20px;
	color: #333;
	font-weight: 700;
	text-decoration: none;
}
.imgFloat a img{
    border: none;
	display: inline-block;
    vertical-align: middle;
	max-width: 100%;
    height: auto;
}
.atclRdSb {
    max-width: 900px;
    margin: 0 auto;
    font-family: Arial, Helvetica, sans-serif;
}
.atclRdSbIn a img {
    width: 160px;
    height: 120px;
}
.nwsTit {
    display: block;
    color: #333;
    padding: 0 0 5px 0;
    text-align: justify;
    margin: 0;
}
.nwsTit header {
    padding-bottom: 10px;
    padding-top: 10px;
}
.nwsSp {
    font-size: 18px;
    line-height: 24px;
    text-align: justify;
    display: block;
    color: #333;
}
.imgFlt {
    float: left;
    margin-right: 10px;
    display: block;
    position: relative;
}
.nwsTthEvt .nwsRd {
    border-left: #e6e6e6 solid 1px;
    border-right: #e6e6e6 solid 1px;
    border-bottom: #e6e6e6 solid 1px;
    padding: 10px 0;
    margin: 0 0 5px 0;
    display: inline-block;
}
.atclRdSbIn {
    display: inline-block;
    width: 100%;
    padding: 10px 0;
}
.atclRdSbIn .nwsTit a {
    font-size: 20px;
    color: #333;
    font-weight: 700;
    text-decoration: none;
}
.mrB10 {
    margin-bottom: 10px;
}
.mrT10{
    margin-top: 10px;
}
.lind {
    border-bottom: 1px dotted #78b43d;
}
.pic-icon {
	position: absolute;	
	bottom: 5px;
	left: 5px;
}
.mg-author {
	max-width: 1000px;
	margin : 0 auto;	
}
/*End đoạn chung cả header và footer*/

/*begin 03/12/2018 dangtq Bài magazine: Box tin liên quan không hiển thị*/
.atclRdSb.nwsRd .img{
	float: left;
	margin-right: 15px;
}
/*end 03/12/2018 dangtq Bài magazine: Box tin liên quan không hiển thị*/
/* Header */
.mg-header{
    top:0px;
	background: #535353;
}
.mg-search, .header-down .mg-search{
	right: 67px;
	left: unset;
}
.mg-search .form-control, .input-group-addon button{
	background-color: #000000;
	border-color: #000000;
	outline: none;
}
.mg-user-profile a{
	margin-right: 0;
}
.back-site{
	left: 0;
}
.logo24h{
	position: absolute;
	top: 10px;
	left: 83px;
	display: inline-block;
	height: 36px;
	top: 50%;
	margin-top: -18px;
}
.mg-menu{
	left: 170px;
	border-left: 1px solid #757575;
	border-right: 1px solid #757575;
	padding-left: 15px;
}
.mg-menu > a{
	left: 50%;
	margin-left: -9px;
	top: 17px;
	background: url(https://image.24h.com.vn/images/2014/magazine-icon/icon-mg-menu.png) no-repeat;
}
.txtDm{
	font-size: 10px;
	color: #fff;
	position: absolute;
	bottom: 13px;
}
.header-down .logo24h{
	left: 65px;
}
.header-down .mg-menu{
	left: 150px;
	top: 0;
	height: 50px;
	cursor: pointer;
}
.header-down .mg-menu > a{
	top: 8px;
	width: 17px;
    height: 15px;
}
.header-down .txtDm{
	bottom: 6px;
}
.header-down #mg-menu-destop-container{
	top: 50px;
}

/* Tin_lien_quan */
.atclRdSb{
	max-width: 984px;
	margin-bottom: 40px;
}
.nwsTthEvtD .tit, .nwsTthEvtD>div>header{
	text-align: center;
	font-size: 24px;
	text-transform: uppercase;
	font-weight: 600;
	color: #7ab245;
	padding-top: 15px;
	border-top: 5px solid #7ab245;
	width: 420px;
	margin-bottom: 17px;
}
.atclRdSb .row{
	display: flex;
}
.atclRdSb .col{
	width: 100%;
}
.atclRdSb .col:nth-child(2n+1){
	margin-right: 33px;
}
.atclRdSbIn .nwsTit a{
	line-height: 1.4;
}
.atclRdSb.nwsRd .img{
	margin-right: 20px;
}
.nwsTit{
	text-align: left;
}
.dateTime{
	color: #7ab245;
	font-size: 14px;
}

/* Onepage */
.onepage_tlq .tit{
	border-bottom: 3px solid #7ab245;
	border-top: 0;
	padding-bottom: 10px;
	padding-top: 0;
}
.btn_tlq{
	display: inline-block;
    width: 80px;
    height: 66px;
    background: url(//image.24h.com.vn/images/2014/magazine-icon/nut-lien-quan-onclick.png) no-repeat;
    position: fixed;
    bottom: 30px;
    right: 30px;
    cursor: pointer;
    z-index: 1;
}
.onepage_tlq .col{
	display: block!important;
}
.contnr{
	width: 100%;
}
/*== Minhdt 23/12/2021 Add-Btn-Share-MXH-Tren-24H ==*/
    /*== Custom Font ==*/
    @font-face {
      font-family: 'Roboto-Regular';
      src: url(https://cdn.24h.com.vn/css/fonts/Roboto-Regular.ttf);
    }
    @font-face {
      font-family: 'Roboto-Bold';
      src: url(https://cdn.24h.com.vn/css/fonts/Roboto-Bold.ttf);
    }
    /*== End Custom Font ==*/

    /*== Reset ==*/
    .btn-share-24h .cv19-sha-social__list h1, 
    .btn-share-24h .cv19-sha-social__list h2, 
    .btn-share-24h .cv19-sha-social__list h3, 
    .btn-share-24h .cv19-sha-social__list h4, 
    .btn-share-24h .cv19-sha-social__list h5, 
    .btn-share-24h .cv19-sha-social__list h6{
      display: block;
      font-weight: inherit;
    }
    .btn-share-24h .d-flex {
      display: -webkit-box!important;
      display: -ms-flexbox!important;
      display: flex!important;
    }
    .btn-share-24h .justify-content-between {
      -webkit-box-pack: justify!important;
      -ms-flex-pack: justify!important;
      justify-content: space-between!important;
    }
    .btn-share-24h .align-items-center {
      -webkit-box-align: center!important;
      -ms-flex-align: center!important;
      align-items: center!important;
    }
    .btn-share-24h .align-items-start {
      -webkit-box-align: start!important;
      -ms-flex-align: start!important;
      align-items: start!important;
    }
    .btn-share-24h .text-uppercase {
      text-transform: uppercase
    }
    .btn-share-24h .text-center {
      text-align: center;
    }
    .btn-share-24h .cv19-sha-social__list a{
      transition: all ease .3s;
      text-decoration: none;
    }
    .btn-share-24h .cv19-sha-social__list ul li{
      list-style: none;
    }
    .btn-share-24h .margin-bottom-20 {
        margin-bottom: 20px!important
    }
    .btn-share-24h .margin-bottom-30 {
        margin-bottom: 30px!important
    }
    .btn-share-24h .cv19-sha-social__list * {
        padding: 0;
        margin: 0;
        box-sizing: border-box;
        font-family: 'Roboto-Regular';
    }
    /*== End Reset ==*/

    /*== Bg Sprite Main ==*/
    .btn-share-24h .icon-sha-social-sprite{
        background: url('https://cdn.24h.com.vn/images/covid/sprite-icon-share-social-covid-19.png') no-repeat;
    }
    /*== End Bg Sprite Main ==*/

    /*== Font Bold Main ==*/
    .btn-share-24h .cv19-sha-social__list-tit header,
    .btn-share-24h .cv19-sha-social__list-tit header *,
    .btn-share-24h .cv19-sha-social__list .copy-link a{
        font-family: 'Roboto-Bold';
    }
    /*== End Font Bold Main ==*/
    .btn-share-24h .cv19-sha-social__list {
        position: absolute;
        top: 80px;
        right: 10px;
        min-width: 520px;
        padding: 15px 20px;
        background: #fff;
        box-shadow: #00000059 1px 1px 5px;
        opacity: 0;
        visibility: hidden;
        transition: all ease .3s;
        z-index: 9999;
    }
    .mg-user-profile .btn-share-24h  a{
        width: auto;
        height: auto;
        float: none;
    }
    .btn-share-24h .cv19-sha-social__list.active_menu_share{
        top: 56px;
        opacity: 1;
        visibility: visible;
    }
    .btn-share-24h .cv19-sha-social__list-tit header,
    .btn-share-24h .cv19-sha-social__list-tit header * {
        font-size: 18px;
        color: #209956;
    }
    .btn-share-24h .cv19-sha-social__list-close{
        display: inline-block;
        width: 15px;
        height: 15px;
        background-position: -5px -125px;
        cursor: pointer;
    }
    .btn-share-24h .cv19-sha-social .cv19-sha-social__list-icon{
        justify-content: space-between !important;
    }
    .btn-share-24h .cv19-sha-social__list-icon li .icon-sha-social-sprite {
        display: block;
        width: 50px;
        height: 50px;
        margin: 0 auto 10px;
    }
    .btn-share-24h .cv19-sha-social__list-icon li a{
        display: block;
        font-size: 14px;
        color: #252525;
    }
    .btn-share-24h .cv19-sha-social__list-icon li a:hover{
        color: rgb(32, 153, 86);
    }
    .btn-share-24h .cv19-sha-social__list-icon li .icon-zalo{
        background-position: -5px -5px;
    }
    .btn-share-24h .cv19-sha-social__list-icon li .icon-fb{
        background-position: -5px -65px;
    }
    .btn-share-24h .cv19-sha-social__list-icon li .icon-mess{
        background-position: -5px -181px;
    }
    .btn-share-24h .cv19-sha-social__list-icon li .icon-skype{
        background-position: -5px -283px;
    }
    .btn-share-24h .cv19-sha-social__list-icon li .icon-tele{
        background-position: -5px -343px;
    }
    .btn-share-24h .cv19-sha-social__list-icon li .icon-twi{
        background-position: -5px -403px;
    }
    .btn-share-24h .cv19-sha-social__list .copy-link a {
        display: block;
        min-width: 100%;
        padding: 8px 5px;
        background: #F2F2F2;
        border: 1px solid rgb(215, 215, 215);
        border-radius: 5px;
        font-size: 14px;
        color: rgb(32, 153, 86);
    }
    .btn-share-24h .cv19-sha-social__list .copy-link a:hover{
        background: rgb(32, 153, 86);
        color: #fff;
        border-color: rgb(32, 153, 86);
    }
/*== End Minhdt 23/12/2021 Add-Btn-Share-MXH-Tren-24H ==*/
</style>
<!--end_css_inline_magazine-->									   
<script type="text/javascript">
    // begin AnhTT 22/5/2020 Thay_doi_duong_dan_icon_magazine
	function go_back_site() {
        history.go(-1);	
	}
    // begin AnhTT 22/5/2020 Thay_doi_duong_dan_icon_magazine
	function display_icon_socail_image(p_el, p_obj) {
		var el = document.getElementById(p_el);
		if (el) {
			if(p_obj.clientWidth <= 500) {
				el.style.left = '25px';
			} else {
				el.style.left = (((p_obj.clientWidth - 500) / 2) + 25) + 'px';
			}
		}
	}
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
	
	function change_class_onclick(p_object_id, p_class_1, p_class_2) {
		if (document.getElementById(p_object_id).getAttribute('class') == p_class_1) {
			document.getElementById(p_object_id).setAttribute('class', p_class_2);
		} else{
	
			document.getElementById(p_object_id).setAttribute('class', p_class_1);	
		}
	}	
	
	// xu ly header khi cuon trang
//	var lastScrollTop = 0;
//	$(window).scroll(function(event){
//	   var st = $(this).scrollTop();
//	   if (st > lastScrollTop){
//		   $('#mg-header-pc').removeClass('header-up');
//		   $('#mg-header-pc').addClass('header-down');
//	   }
//	   lastScrollTop = st;
//	   if (lastScrollTop == 0) {
//		   $('#mg-header-pc').removeClass('header-down');
//		   $('#mg-header-pc').addClass('header-up');		   
//	   }
//	});
	
	function openContact()
	{
		MM_openBrWindow('/ajax/contact/index','newstools','status=yes,scrollbars=yes,resizable=yes,width=530,height=450')
	}
	function MM_openBrWindow( theURL, winName, features) { //v2.0
		window.open(theURL,winName,features);
	}
	function mo_menu_trai_trang_chu()
	{
		var menu_desktop = document.getElementById('mg-menu-destop-container');
		if (menu_desktop != '' && menu_desktop != null && menu_desktop != undefined) {
			var display_status = menu_desktop.style.display;
			if (display_status == 'block') {
				document.body.style.position = '';
				document.body.style.overflow = '';
				reset_menu_trai_trang_chu();
			} else {
				document.body.style.position = 'relative';
				document.body.style.overflow = 'hidden';				
			}
			show_hide_block('mg-menu-destop-container');
			var height = window.innerHeight;
			var menu = document.getElementById('mg-menu-destop');
			if (menu != '' && menu != null && menu != undefined) {
				menu.style.height = (height-100) + 'px';
			}
		}
	}
	function dong_menu_trai_trang_chu()
	{
		show_hide_block('mg-menu-destop-container');
		document.body.style.overflow = '';
		document.body.style.position = '';
		reset_menu_trai_trang_chu();	
	}
	function reset_menu_trai_trang_chu() {
		var a_parent = document.getElementsByClassName('sub-a');
		var a_parent_length = a_parent.length;
		for(i=0;i<a_parent_length;i++) {
			a_parent.item(i).style.fontWeight = '';
		}
		
		var ul_son = document.getElementsByClassName('fly');
		var ul_son_length = ul_son.length;
		for(i=0;i<ul_son_length;i++) {
			ul_son.item(i).style.display = 'none';
		}
		
		var class_up = document.getElementsByClassName('up');
		while (class_up.length) {
			class_up[0].className = "down";
		}	
	}
	
	function add_font_bold(p_id) {
		var object = document.getElementById(p_id);
		if (object != '' && object != null && object != undefined) {	
			var fontweight = object.style.fontWeight;
			if (fontweight != 'bold') {
				object.style.fontWeight = 'bold';
			} else {
				object.style.fontWeight = '';
			}
		}
	}
    function show_menu_share() {
        var v_script_check = document.getElementById('scriptzalo');
        var menu_share = document.getElementById('menu_share');
        if (v_script_check == '' || v_script_check == null || v_script_check == 'undefined') {
            var script = document.createElement('script');
            script.type = 'text/javascript';
            script.src = 'https://sp.zalo.me/plugins/sdk.js';
            script.id = "scriptzalo";
            document.getElementsByTagName('head')[0].appendChild(script);
        }
        if (menu_share !== '' && menu_share !== 'null' && menu_share !== 'undefined') {
            menu_share.classList.add("active_menu_share");
        }
    }
    function show_popup_link(p_link) {
        var v_url = p_link;
        var menu_share = document.getElementById('menu_share');
        if (v_url !== '' && v_url !== null && v_url !== 'undefined') {
            v_width=500;
            v_height=550;
            winDef = "location=1,status=1,scrollbars=1,width="+v_width+",height="+v_height+",";
            winDef = winDef.concat('top=').concat((screen.height - v_height)/2).concat(',');
            winDef = winDef.concat('left=').concat((screen.width - v_width)/2);
            window.open(v_url,"",winDef);
        }
        if (menu_share !== '' && menu_share !== 'null' && menu_share !== 'undefined') {
            menu_share.classList.remove("active_menu_share");
        }
    }
    function close_menu_share() {
        var menu_share = document.getElementById('menu_share');
        if (menu_share !== '' && menu_share !== 'null' && menu_share !== 'undefined') {
            menu_share.classList.remove("active_menu_share");
        }
    }
</script>
<!-- Start header html -->
<div id="mg-header-pc" class="mg-header clearfix header-down">
    <a href="<?php echo BASE_URL_FOR_PUBLIC; ?>" title="Trang chủ 24h" class="logo24h">
        <img style="height: 60px;margin-top: -13px;width: auto;" class="fullscreen" src="<?php html_image('images/logo-nen-trang-3do-1.jpg'); ?>">
    </a>
    <div class="mg-menu">
        <a href="javascript:;" onclick="mo_menu_trai_trang_chu()"></a>
        <span class="txtDm" onclick="mo_menu_trai_trang_chu()">DANH MỤC</span> 
        <div id="mg-menu-destop-container" style="display: none; position: relative;">
            <ul class="mg-menu-destop" id="mg-menu-destop" style="height: 37px;;">
            <li class="sub-li"><a href="<?php echo BASE_URL_FOR_PUBLIC; ?>" title="Trang chủ 24giờ" class="sub-a" id="sub-a-parent-0">Trang chủ</a></li>
            <li class="sub-li"><a href="<?php echo BASE_URL_FOR_PUBLIC; ?>" title="Trang chủ 24giờ" class="sub-a" id="sub-a-parent-0">Tin tức</a></li>
            </ul>
        </div>
    </div>
    <div class="mg-search" id="box-mg-search"></div>
    <div class="heading-site">
        <a href="<?php echo LINK_CHUYEN_MUC_MAGAZINE; ?>" title="Magazine" class="home-logo-link">
			<img class="fullscreen" src="<?php html_image('/images/logo_3do_mgz_1.png'); ?>" />
		</a>
    </div>
</div>
<!-- End header html -->
