<?php
if ($media_data['m_id'] == 0) {
	echo 'Bài hát không tồn tại';
	return ;
}
extract($media_data);
$m_lyric=fw24h_restore_bad_char($m_lyric);
$m_title=fw24h_restore_bad_char($m_title);
$singer_img = html_get_image_url($singer_img);
$v_link_nghe_nhac = _get_url_nghe_nhac($m_title, $singer_name, $m_id, $m_cat, $m_singer, $m_album, $m_static_url);
$v_link_for_public = _get_url_nghe_nhac($m_title, $singer_name, $m_id, $m_cat, $m_singer, $m_album, $m_static_url,1);
$v_url_singer = _get_url_casi($singer_name,$m_singer);
$v_url_cate = _get_url_category($m_cat_name,$m_cat);
$download_tracker="&lt;iframe  style=\'visibility:hidden\'  src=\'/download_tracker.php?server=".SERVER_NUMBER."\' width=\'0\' height=\'0\'&gt;&lt;/iframe&gt;";
// Duong dan toi server thong ke luot down bai hat
$v_array = explode('.',$v_link_nghe_nhac);
$v_url_thongke = $v_array[0].'d1.'.$v_array[1];
$v_url_thongke = SERVER_THONGKE_PATH.$v_url_thongke;
$url_share_facebook = 'http://www.facebook.com/sharer.php?s=100&#38;p[title]='.$m_title.' – '.$singer_name.' | Nhac.vui.vn';
$url_share_facebook .= '&#38;p[summary]=Nghe – Tải bài hát '.$m_title.' do ca sĩ '.$singer_name.' thể hiện ngay tại Nhac.vui.vn. Nghe nhạc hay Xem Clip hot tại Nhac.vui.vn';
$url_share_facebook .= '&#38;p[url]='.$v_link_for_public;
$url_share_facebook .= '&#38;p[images][0]='.$singer_img;
if (isset($_SESSION['account_id']) && $_SESSION['account_id'] > 0) {
	$v_link_for_public = $v_link_for_public.'?utm_source=nhac.vui.vn&#38;utm_medium='.$_SESSION['account_id'].'&#38;utm_campaign='.$m_id;
}
$m_player_for_blog = _get_player_for_blog($m_id, $m_type, $c_type =1, $width=650, $height=60);
$m_player_for_forum = _get_player_for_forum(1, $m_type, $m_id,"$v_url");
?>
<div class="nghenhac">
	<div class="nghenhac-baihat"><h2><?php echo $m_title.' - '._utf8_to_ascii($m_title) ?></h2></div>
	<div class="nghenhac-info">Ca sĩ: <a href="<?php echo $v_url_singer?>" title="<?php echo htmlspecialchars($singer_name)?>"><?php echo $singer_name?></a> | Nhạc sĩ: <span><?php echo $m_musician_name?></span> | Thể loại: <a href="<?php echo $v_url_cate?>" title="<?php echo htmlspecialchars($m_cat_name)?>"><?php echo $m_cat_name?></a> | Tải: <?php echo $m_downloaded?> | Nghe: <?php echo $m_viewed?></div>	
	<?php
		echo _get_player($m_id, $m_type, $c_type =1, $width=650, $height=60) ;
	?>
	<div class="chiaseTab"><a href="javascript:void(0);" onclick="liked_onclick('<?php echo $m_id?>')" class="chiase-like"><span class="text14-cyan"><?php echo $m_liked?></span> thích</a><a href="/download.php?id=<?php echo $m_id;?>" class="chiase-download" onclick="download_stat_update('<?php echo $v_url_thongke;?>','span_download', '<?php echo $download_tracker;?>')" rel="nofollow" target="_blank">Download</a><a href="#" class="chiase-active" >Chia sẻ</a><a href="#" class="chiase-playlist" >Thêm vào Playlist</a><a href="#" class="chiase-tangqua" >Tặng quà</a></div>
	<div class="chiase-box">
		<div class="chiase-txt">Chia sẻ bài hát qua:</div>                   	
		<div class="socialNetwork2-wide">
			<a href = "<?php echo $url_share_facebook;?>" title="Chia sẻ link qua Facebook" target="_blank"><img src="/images/iconFB.gif" alt="Chia sẻ link qua Facebook" width="30" height="30" /></a><a href="javascript:addto_twitter('<?php echo $v_link_for_public;?>')" title="Chia sẻ link qua Twitter"><img src="/images/iconTwitter.gif" alt="Chia sẻ link qua Twitter" width="30" height="30" /></a><a href = "ymsgr:im?msg=<?php echo $v_link_for_public;?>" title="Chia sẻ link qua Yahoo"><img src="/images/iconYahooChat.gif" alt="Chia sẻ link qua Yahoo" width="30" height="30" /></a><a href="javascript:void(0)" onclick="javascript:addto_google('<?php echo $v_link_for_public;?>');" title="Chia sẻ link qua Google Bookmark"><img src="/images/iconGoogle.gif" alt="Chia sẻ link qua Google Bookmark" width="30" height="30" /></a><a href="#" title=""><img src="/images/iconMail.gif" alt="" width="33" height="30" /></a><a href='javascript:void(0)' onclick="javascript:addto_linkhay('<?php echo $v_link_for_public;?>');" title='Chia sẻ link qua LinkHay'><img src="/images/btn_linkhay.gif" alt="Chia sẻ link qua LinkHay" width="31" height="32" /></a>
			<div class="clear"></div>
		</div>
		<div class="socialNetwork1-wide">
			<!-- Facebook like -->
			<script type="text/javascript">
			//<![CDATA[
				document.write('<fb:like layout="button_count" href="<?php echo $v_link_nghe_nhac?>" show_faces="true" data-width="70"></fb:like>');
				//]]>
			</script>
			<!-- Google plus -->
			<script type="text/javascript">
			//<![CDATA[
				document.write('<g:plusone size="medium"></g:plusone>');
				//]]>
			</script>
			<script type="text/javascript">
			  window.___gcfg = {lang: 'vi'};
			  (function() {
				var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
				po.src = 'https://apis.google.com/js/plusone.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
			  })();
			</script>
			<script type="text/javascript" src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
			<script type="text/javascript">
			FB.Event.subscribe('edge.create', function(targetUrl) {
			  _gaq.push(['_trackSocial', 'facebook', 'like', targetUrl]);
			});
			FB.Event.subscribe('edge.remove', function(targetUrl) {
			  _gaq.push(['_trackSocial', 'facebook', 'unlike', targetUrl]);
			});
			FB.Event.subscribe('message.send', function(targetUrl) {
			  _gaq.push(['_trackSocial', 'facebook', 'send', targetUrl]);
			});
			</script>
			<div class="clear"></div>
		</div>
		<div class="btnNhung"><input type="image" id="_btnNhung" src="/images/btnNhung-down.gif" /></div>
		<div class="clear"></div>
	</div>
	<div class="nhung-box" style="display:none;">
		<div class="nhungItem">
			<div class="nhungLabel">Link nhạc:</div>
			<div class="nhungTextbox">	
				<div class="txt-left"></div><div class="txt-c"><input type="text" class="textbox txt-540" value="<?php echo $v_link_for_public;?>" /></div><div class="txt-right"></div>
			</div>
			<div class="clear"></div>
		</div>
		<div class="nhungItem">
			<div class="nhungLabel">Nhúng blog:</div>
			<div class="nhungTextbox">	
				<div class="txt-left"></div><div class="txt-c"><input type="text" class="textbox txt-540" value="<?php echo htmlspecialchars($m_player_for_blog);?>" /></div><div class="txt-right"></div>
			</div>
			<div class="clear"></div>
		</div>
		<div class="nhungItem">
			<div class="nhungLabel">Copy vào diễn đàn:</div>
			<div class="nhungTextbox">	
				<div class="txt-left"></div><div class="txt-c"><input type="text" class="textbox txt-540" value="<?php echo $m_player_for_forum;?>" /></div><div class="txt-right"></div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	
	<div class="baihat-btnLeft"><a href="#" title=""><img src="/images/btn-ve-trang-chu.gif" alt="" width="127" height="30" /></a><?php if (strlen($m_lyric)>10) {?><a href="javascript:void(0)" id="_lyricShow" title="Hiển thị lời bài hát"><img src="/images/btn-loi-bai-hat-up.gif" alt="" width="122" height="30" /></a><?php }?></div>
	<div class="baihat-btnRight"><span class="btn-blue-left"></span><span class="btn-blue-right"><a href="#" title="">Bài hát do ca sỹ khác thể hiện</a></span></div>
	<div class="clear"></div>
	<div class="nghenhac-loibaihat" style="display:none;"><?php echo $m_lyric?>
		<div class="an-bai-hat"><a href="javascript:void(0)" onclick="$('.nghenhac-loibaihat').hide(70);">Ẩn lời bài hát</a></div>
	</div>
</div>
<script type="text/javascript">
	$("#_lyricShow").click(function () {
		$('.nghenhac-loibaihat').show(70);
		//$(this).text='<input type="image" src="images/btnNhung-up.gif" />';
	});
 	$("#_btnNhung").click(function() {
		$('.nhung-box').toggle(70, function() {
		
		//alert($(this).attr('src'));
		});
		if ($('.nhung-box').css('display')=='none') {
			return $(this).attr("src","/images/btnNhung-down.gif");
		} else {
			return $(this).attr("src","/images/btnNhung-up.gif");
		}
	});	
	/* function btnNhung_onclick() {
		$('.nhung-box').text='tuanna';
		if (document.getElementById("_btnNhung").style.display=='none') {
			document.getElementById("_btnNhung").src="/images/btnNhung-down.gif";
		} else {
			document.getElementById("_btnNhung").src="/images/btnNhung-up.gif";
		}
	} */
</script>