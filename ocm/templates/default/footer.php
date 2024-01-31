<script>
	var v_pathname = window.location.pathname;
	var v_module = v_pathname.split('/');
	v_module = v_module[2];
	
	$(".menuContent ul li a").each(function(i){
		v_this_href = $(this).attr("href");
		if (v_module == 'comments') {
			if (v_pathname.indexOf('doc_gia')>= 0) {
				if (v_this_href.indexOf('dsp_all_comment_doc_gia')>= 0) {
					$(this).addClass('menuActive');
					return false;
				}
			} else if (v_pathname.indexOf('dsp_single_news_bai_co_san')>= 0) {
				if (v_this_href.indexOf('dsp_single_news_bai_co_san')>= 0) {
					$(this).addClass('menuActive');
					return false;
				}
			} else if (v_pathname.indexOf('dsp_single_news_goc_nhin_khac')>= 0) {
				if (v_this_href.indexOf('dsp_single_news_goc_nhin_khac')>= 0) {
					$(this).addClass('menuActive');
					return false;
				}
			}else if (v_pathname.indexOf('dsp_single_gnk_comment')>= 0) {
				if (v_this_href.indexOf('dsp_single_gnk_comment')>= 0) {
					$(this).addClass('menuActive');
					return false;
				}
			}else if (v_pathname.indexOf('index')>= 0 || v_pathname.indexOf('dsp_all_comment_of_news')>= 0) {
				if (v_this_href.indexOf('comments/index')>= 0) {
					$(this).addClass('menuActive');
					return false;
				}
			} 
		} else if(v_module == 'special_box_news') {
			if (v_pathname.indexOf('published_special')>= 0) {
				if (v_this_href.indexOf('published_special')>= 0) {
					$(this).addClass('menuActive');
					return false;
				}
			} else if (v_pathname.indexOf('approval_special_box')>= 0) { 
				if (v_this_href.indexOf('approval_special_box')>= 0) {
					$(this).addClass('menuActive');
					return false;
				}
			} else if (v_pathname.indexOf('special_box_news/index')>= 0) { 
				if (v_this_href.indexOf('special_box_news/index')>= 0) {
					$(this).addClass('menuActive');
					return false;
				}
			}
		} else if (v_this_href.indexOf(v_pathname+"/") >= 0) {
			$(this).addClass('menuActive');
			return false;
		} else if (v_this_href.indexOf("/"+v_module+"/") >= 0) {
			$(this).addClass('menuActive');
			return false;
		}
	});
</script>
</body>
</html>