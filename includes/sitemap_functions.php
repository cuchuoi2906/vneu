<?php
//Begin 10-03-2016 : Thangnb toi_uu_sitemap
class my_simple_xml extends SimpleXMLElement
{
    public function prependChild($name, $value)
    {
        $dom = dom_import_simplexml($this);

        $new = $dom->insertBefore(
            $dom->ownerDocument->createElement($name, $value),
            $dom->firstChild
        );

        return simplexml_import_dom($new, get_class($this));
    }
}
//End 10-03-2016 : Thangnb toi_uu_sitemap

// url gốc được đặt trong cặp thẻ như này. Trường hợp cần thay thế cho các phiên bản khác <!-- start_url_goc:"._replace_xml_special_char($v_root).":end_url_goc -->
// begin: tao_sitemap_tag_video_image_theo_thang
/**
 * Removes invalid XML
 *
 * @access public
 * @param string $value
 * @return string
 */
function stripInvalidXml($value)
{
    $ret = "";
    $current;
    if (empty($value)) 
    {
        return $ret;
    }

    $length = strlen($value);
    for ($i=0; $i < $length; $i++)
    {
        $current = ord($value{$i});
        if (($current == 0x9) ||
            ($current == 0xA) ||
            ($current == 0xD) ||
            (($current >= 0x20) && ($current <= 0xD7FF)) ||
            (($current >= 0xE000) && ($current <= 0xFFFD)) ||
            (($current >= 0x10000) && ($current <= 0x10FFFF)))
        {
            $ret .= chr($current);
        }
        else
        {
            $ret .= " ";
        }
    }
    return $ret;
}
//end: tao_sitemap_tag_video_image_theo_thang

// Thay the cac ky tw dac biet trong file xml
// function _replace_xml_special_char($str) {
    // // Begin: 05-01-2016 trungcq bổ sung xu_ly_ky_tu_dac_biet_sitemap
    // $str = fw24h_restore_bad_char($str);
	// //Begin 05-02-2016 : Thangnb xu_ly_ky_tu_dac_biet_sitemap
    // $v_arr_replace = array(
        // '/ç/'         => 'c',
        // '/Ç/'         => 'C',
        // '/ñ/'         => 'n',
        // '/Ñ/'         => 'N',
        // '/–/'         => '-',
        // '/[’‘‹›‚]/u'    => ' ',
        // '/[“”«»„�]/u'  => ' ',
        // '/&/'         => ' &amp; ',
        // '/\'/'        => ' &apos; ',
        // '/\"/'        => ' &quot; ',
        // '/\>/'        => '',
        // '/\</'        => ''
    // );
	// //End 05-02-2016 : Thangnb xu_ly_ky_tu_dac_biet_sitemap
    // $str = preg_replace(array_keys($v_arr_replace), array_values($v_arr_replace), $str);
    // // End: 05-01-2016 trungcq bổ sung xu_ly_ky_tu_dac_biet_sitemap
    
    // // begin: tao_sitemap_tag_video_image_theo_thang
    // $str = stripInvalidXml($str); // xử lý loại bỏ các ký tự không hợp lệ trong xml
    // // end: tao_sitemap_tag_video_image_theo_thang
    
    // //begin: sua_doi_he_thong_sitemap_24h    
    // return mb_convert_encoding($str, 'UTF-8', 'UTF-8'); 
    // //end: sua_doi_he_thong_sitemap_24h
// }

/********************************************************************************
Ten ham		:_write_db
Chuc nang	:Ghi file
Tham so		
********************************************************************************/
function _write_db($p_file_path, $p_content, $v_begin, $v_end, $checkmax){
	global $maxURL;
	$n = 1;
	$data = array();
	$tmp = $v_begin;
	$countPcontent = count($p_content);
	$return = array();
	if($checkmax){
		for($i=0; $i < $countPcontent; $i++){
			$tmp .= $p_content[$i];		
			if($n > $maxURL){
				$n = 1;
				$tmp .= $v_end;
				$data[] = $tmp;
				$tmp = $v_begin;
			}
			else{
				$n++;
			}
			if(($i == ($countPcontent-1)) && $n <= $maxURL){
				$tmp .= $v_end;
				$data[] = $tmp;
			}
		}
		for($i = 0; $i< count($data); $i++){
			$rs = fe_update_1_sitemap(-1, 1, $p_file_path."-".$i, $data[$i], '');
			if (intval($rs['NEW_ID']) > 0){
				$return[] = $p_file_path."-".$i.".xml";
			}	
			else{
				echo "khong luu duoc file ".$p_file_path."-".$i.".xml";
			}
		}
	}
	else{ // TRUONG HOP KHONG CHECK MAX URL THI TEN SITEMAP SE DUOC GIU NGUYEN KHONG THEM SO DANG SAU
		for($i=0; $i < $countPcontent; $i++){
			$tmp .= $p_content[$i];		
		}
		$tmp .= $v_end;
		$rs = fe_update_1_sitemap(-1, 1, $p_file_path, $tmp, '');
		if (intval($rs['NEW_ID']) > 0){
			$return[] = $p_file_path.".xml";
		}	
	}
	return $return;
}		

// Lay priority
// @param Type:  value 1: chuyen muc value 2: bai viet
function get_priority($p_arr_priotity,$p_keyword, $type = 1) {
	global $v_root, $v_root_without_slash, $default_menu_priority, $default_article_priority;
	$p_keyword = str_replace($v_root_without_slash, "", $p_keyword);
	$p_keywordarr = explode("/", $p_keyword);
	$p_keyword = ($type == 2) ? $p_keyword : $p_keywordarr[1];
	$v_count = sizeof($p_arr_priotity);
	$v_ret= ($type == 2) ? $default_article_priority : $default_menu_priority;
	for($i=0; $i<$v_count; ++$i) {
		$v_single_priority = explode(",", $p_arr_priotity[$i]);
		if (strpos($p_keyword,strtolower($v_single_priority[0]))!==false){
			$v_ret = ($v_single_priority[$type]) ? $v_single_priority[$type] : $v_ret;
			return $v_ret;
		}
	}
	return $v_ret;
}

// Lay changeFreq
// @param Type:  value 1: chuyen muc value 2: bai viet
function get_changefreq($p_arr_changefreq,$p_keyword, $type = 1) {
	global $v_root, $v_root_without_slash, $default_menu_changefreq, $default_article_changefreq;
	$p_keyword = str_replace($v_root_without_slash, "", $p_keyword);
	$p_keywordarr = explode("/", $p_keyword);
	$p_keyword = ($type == 2) ? $p_keyword : $p_keywordarr[1];
	$v_count = sizeof($p_arr_changefreq);
	//print_r($p_keyword);
	$v_ret= ($type == 2) ? $default_article_changefreq : $default_menu_changefreq;
	for($i=0; $i<$v_count; ++$i) {
		$v_single_changefreq = explode(",", $p_arr_changefreq[$i]);
		if (strpos($p_keyword,strtolower($v_single_changefreq[0]))!==false){
			$v_ret= ($v_single_changefreq[$type]) ? $v_single_changefreq[$type] : $v_ret;
			return $v_ret;
		}
	}
	return $v_ret;
}

/* Function to write data to sitemap file */
//Begin 10-03-2016 : Thangnb toi_uu_sitemap
function write2db($v_sitemap_file, $v_xml, $v_begin, $v_end, $sitemapindex, $checkmax = false, $p_is_gen_current_month = 0){
	global $v_root, $v_root_without_slash;
	if (count($v_xml)){
		$smfiles = _write_db($v_sitemap_file,$v_xml, $v_begin, $v_end, $checkmax);
		updateIndex($smfiles, $sitemapindex, $p_is_gen_current_month);
		echo "Da tao thanh cong sitemap file: /$v_sitemap_file \n";
	}else{
		echo "Khong co du lieu de tao file sitemap \n";
		return;
	}	
}
//End 10-03-2016 : Thangnb toi_uu_sitemap

// Ham cap nhat file sitemap-index
//Begin 10-03-2016 : Thangnb toi_uu_sitemap
function updateIndex($smfiles, $sitemapindex, $p_is_gen_current_month){
//End 10-03-2016 : Thangnb toi_uu_sitemap
    if ($sitemapindex == '') {
        return false;
    }
	$nsmfile = count($smfiles);
	$filter = array();
	for($i=0;$i<$nsmfile; $i++){
        // Nếu là file https thì chuyển các đường dẫn bên trong là https
        if(strpos($sitemapindex, '-https') !== false){
            // Xóa bỏ https trong tên file sitemap khi update index
            $smfiles[$i] = str_replace('-https', '', $smfiles[$i]);
            $filter[] = BASE_URL_FOR_PUBLIC_HTTPS.$smfiles[$i];
        }else{
            $filter[] = BASE_URL_FOR_PUBLIC.$smfiles[$i];
        }
	}
	$v_html = fe_chi_tiet_1_sitemap($sitemapindex, -1, true); // đọc từ mst db để đảm bảo mới nhất
	
	// gắn thêm header/footer để thành chuỗi xml
	if (strpos($v_html['c_html'], '</sitemap>') == false) {
		$v_html['c_html'] = '<?xml version="1.0" encoding="UTF-8"?><urlset>'.$v_html['c_html'].'</urlset>';
	} else {
		$v_html['c_html'] = '<?xml version="1.0" encoding="UTF-8"?><sitemapindex>'.$v_html['c_html'].'</sitemapindex>';
	}
	
	//Begin 10-03-2016 : Thangnb toi_uu_sitemap
	$xml = simplexml_load_string($v_html['c_html'],'my_simple_xml');
	//End 10-03-2016 : Thangnb toi_uu_sitemap
	$datestr= Date("c");
	$return = $xml->asXML();
	$exists = array();
	foreach($xml->children() as $sitemap){
		if(in_array($sitemap->loc, $filter)){
			$sitemap->lastmod = $datestr;
			$exists[] = $sitemap->loc;
		}
	}
	$notExists = array_diff($filter, $exists);
	$nNotExists = count($notExists);
	if($nNotExists){
		for($i=0; $i<$nNotExists; $i++){
			//Begin 10-03-2016 : Thangnb toi_uu_sitemap
			if ($p_is_gen_current_month > 0) {
				$sitemap = $xml->prependChild('sitemap');
				$sitemap->addChild('loc',$notExists[$i]);
				$sitemap->addChild('lastmod',$datestr);
			} else {
				$sitemap = $xml->addChild('sitemap');
				$sitemap->addChild('loc', $notExists[$i]);
				$sitemap->addChild('lastmod', $datestr);
			}
			//End 10-03-2016 : Thangnb toi_uu_sitemap
		}
	}
	//Begin 10-03-2016 : Thangnb toi_uu_sitemap
	$return = $xml->asXML(); 	
	//End 10-03-2016 : Thangnb toi_uu_sitemap

	// xóa bỏ header/footer trước khi lưu vào
	$return = str_replace(array('<?xml version="1.0" encoding="UTF-8"?>', '<urlset>', '</urlset>', '<sitemapindex>', '</sitemapindex>'), '', $return);
	
	fe_update_1_sitemap(-1, 1, $sitemapindex, $return, '');
}

/**
 * Lay tin moi nhat theo chuyen muc
 *
 * @param integer $categoryID ID chuyen muc
 * @return array
 */
function get_last_news_by_category($categoryID)
{
	$categoryID = intval($categoryID);
	if ($categoryID==0) return;
	
	$data = fe_bai_viet_theo_chuyen_muc($categoryID);
	if ($data) {
		$data = php_multisort($data, array(array('key'=>'PublishedDate2','sort'=>'desc')));
		$row = $data[0];
	}else{
		$row=array();
		$row['PublishedDate2']='';
	}
	return $row;
}


// Tao sitemap chuyen muc
function gen_sitemap_for_menu($p_changefreq="",$p_priority=""){
	global $v_root, $v_root_without_slash, $default_menu_changefreq, $default_article_changefreq, $default_menu_priority, $default_article_priority;
	$urlHelper = new UrlHelper();$urlHelper->getInstance();
	if ($p_priority==""){
		$p_priority=$default_menu_priority;
	}
	if ($p_priority!=$default_menu_priority){
		$arr_all_priority=explode(";", $p_priority);
	}
	if ($p_changefreq==""){
		$p_changefreq=$default_menu_changefreq;
	}
	if ($p_changefreq!=$default_menu_changefreq){
		$arr_all_changefreg=explode(";", $p_changefreq);
	}

	$v_parent_sql_string = "Select * From category Where Activate=1 AND parent=0 Order by position";	
	$arr_parent_all_menu = Gnud_Db_read_query($v_parent_sql_string);
	$v_parent_count = sizeof($arr_parent_all_menu);	
	
	$parent_arr = array(); 
	
	for( $i=0; $i<$v_parent_count; ++$i){
		$parentid = $arr_parent_all_menu[$i]["ID"];
		if($arr_parent_all_menu[$i]["Urlslugs"]){
			$parent_arr[$parentid] = $arr_parent_all_menu[$i]["Urlslugs"];
		}
		else{
			$parent_arr[$parentid] = $arr_parent_all_menu[$i]["Name"];
		}
	}
	
	$v_sql_string = "Select * From category Where Activate=1 Order by position";	
	$arr_all_menu = Gnud_Db_read_query($v_sql_string);
	$v_count = sizeof($arr_all_menu);	
    
    $datestr= Date("Y-m-d").'T'.date('H:i:sP');
	$v_html = array();
	$v_html[] = "<url>\n
					<loc>"._replace_xml_special_char($v_root)."</loc>\n
					<!-- start_url_goc:"._replace_xml_special_char($v_root).":end_url_goc -->
					<lastmod>"._replace_xml_special_char($datestr)."</lastmod>\n
					<changefreq>daily</changefreq>\n
					<priority>1.0</priority>\n
				</url>\n";
				
	if ($v_count > 0){
		for( $i=0; $i<$v_count; ++$i)
		{   
            if (intval($arr_all_menu[$i]["LinkType"])>0) continue;
			$v_alias=$arr_all_menu[$i]["Urlslugs"];
			
			if ($v_alias==""){
				$v_alias=$arr_all_menu[$i]["Name"];
			}
			$parentid = $arr_all_menu[$i]["Parent"];
			// if($parentid) $v_alias = $parent_arr[$parentid].'/'.$v_alias;
			$v_alias=strtolower(str_replace(" ","-",$v_alias));
			$v_url= $urlHelper->url_cate(array('ID'=>$arr_all_menu[$i]["ID"], 'slug'=>$v_alias));
			if (!is_null($v_url) && $v_url != ''){
			// Xac dinh priority
				if ($p_priority!=$default_menu_priority){
					$v_priority=get_priority($arr_all_priority,$v_url,1) ;
				}else{
					$v_priority=$default_menu_priority;
				}
				if ($p_changefreq!=$default_menu_changefreq){
					$v_changefreq=get_changefreq($arr_all_changefreg,$v_url, 1) ;
				}else{
					$v_changefreq=$default_menu_changefreq;
				}	
				$datestr= Date("Y-m-d").'T'.date('H:i:sP');
				
				$v_url = (strpos($v_url,$v_root_without_slash)) ? $v_url : $v_root_without_slash.$v_url;
				$tmp = "<url>\n";
				$tmp = $tmp."<loc>"._replace_xml_special_char($v_url)."</loc>\n";
				$tmp = $tmp."<!-- start_url_goc:"._replace_xml_special_char($v_url).":end_url_goc -->";
				$tmp = $tmp."<lastmod>"._replace_xml_special_char($datestr)."</lastmod>\n";
				$tmp = $tmp."<changefreq>"._replace_xml_special_char($v_changefreq)."</changefreq>\n";
				$tmp = $tmp."<priority>"._replace_xml_special_char($v_priority)."</priority>\n";
				$tmp = $tmp."</url>\n";
				$v_html[] = $tmp;
			}
		}
	}else{
		$v_html= array();
	}
	return $v_html;
}

// Ham tao sitemap cho cac bai viet, game theo 1 chuyen muc
function gen_sitemap_for_article_by_menu($p_menu_id,$p_changefreq="",$p_priority=""){
	global $v_root, $v_root_without_slash, $default_menu_changefreq, $default_article_changefreq, $default_menu_priority, $default_article_priority;
	$urlHelper = new UrlHelper();$urlHelper->getInstance();
	if ($p_priority==""){
		$p_priority=$default_menu_priority;
	}
	
	if ($p_priority!=$default_menu_priority){
		$arr_all_priority=explode(";", $p_priority);
	}
	
	if ($p_changefreq==""){
		$p_changefreq=$default_article_changefreq;
	}
	if ($p_changefreq!=$default_article_changefreq){
		$arr_all_changefreg=explode(";", $p_changefreq);
	}
	
	// Lay thong tin chuyen muc de xac dinh priority
	$v_sql_string = "Select * From category Where ID=$p_menu_id";	
	$arr_single_menu = Gnud_Db_read_query($v_sql_string);
	$v_count = sizeof($arr_single_menu);	
	
	$v_sql_string = "Select distinct url,date_format(DateEdited,'%Y-%m-%d') DateEdited 
						From news, newscategory where news.ID = newscategory.NewsID AND newscategory.CategoryID = ".$p_menu_id."  AND newscategory.Status > 0 Order by DateEdited desc";
						
	$arr_all_item = Gnud_Db_read_query($v_sql_string);
	$v_count = sizeof($arr_all_item);
	$datestr= Date("Y-m-d");
	$v_html= array();
	if ($v_count > 0){
		for( $i=0; $i<$v_count; ++$i)
		{			
			$v_url=trim($arr_all_item[$i]["url"]);
            $v_begin_time= date('Y-m-d', strtotime($arr_all_item[$i]["DateEdited"])).'T'.date('H:i:sP', strtotime($arr_all_item[$i]["PublishedDate2"])); 
			$v_keyword=$arr_all_item[$i]["url"];
				if ($p_priority!=$default_menu_priority){
					$v_priority=get_priority($arr_all_priority,$v_url,2) ;
				}else{
					$v_priority=$default_menu_priority;
				}
				if ($p_changefreq!=$default_article_changefreq){
					$v_changefreq=get_changefreq($arr_all_changefreg,$v_url, 2) ;
				}else{
					$v_changefreq=$default_article_changefreq;
				}	
				
			// Chi lay cac url duoc tinh cho chuyen muc nay
			if (strpos($v_keyword,$v_alias) && $v_url){
				$tmp = "<url>\n";
				$tmp = $tmp."<loc>"._replace_xml_special_char($v_url)."</loc>\n";
				$tmp = $tmp."<!-- start_url_goc:"._replace_xml_special_char($v_url).":end_url_goc -->";
				$tmp = $tmp."<lastmod>"._replace_xml_special_char($v_begin_time)."</lastmod>\n";
				$tmp = $tmp."<changefreq>"._replace_xml_special_char($v_changefreq)."</changefreq>\n";
				$tmp = $tmp."<priority>"._replace_xml_special_char($v_priority)."</priority>\n";
				$tmp = $tmp."</url>\n";
				$v_html[] = $tmp;
			}	
		}
	}
	return $v_html;
}

// Ham tao sitemap cho TAT CA  bai viet, game 
function gen_sitemap_for_all_event($p_changefreq="",$p_priority="", $v_arr_event = array()){
	global $v_root, $v_root_without_slash, $default_menu_changefreq, $default_article_changefreq, $default_menu_priority, $default_article_priority;
	
	$urlHelper = new UrlHelper();$urlHelper->getInstance();
	if ($p_priority==""){
		$p_priority=$default_article_priority;
	}
	if ($p_priority!=$default_article_priority){
		$arr_all_priority=explode(";", $p_priority);
	}
	if ($p_changefreq==""){
		$p_changefreq=$default_article_changefreq;
	}
	if ($p_changefreq!=$default_article_changefreq){
		$arr_all_changefreg=explode(";", $p_changefreq);
	}
	
	if (check_array($v_arr_event)) {
		$arr_all_item = $v_arr_event; # 20150603 hailt fix tạo riêng sitemap cho ds event tạo mới
		$v_get_date_by_news = false;
		$v_set_by_param = true;
	} else {
		//Begin 02-03-2016 : Thangnb fix_sitemap_lay_thieu_su_kien
		$v_sql_string = "Select event.ID, event.url, event.Date, event.slug, event.Name, eventcategory.id_category,a.c_origin_url,a.c_canonical_url From event JOIN eventcategory ON event.ID =  eventcategory.id_event INNER JOIN t_event_url a ON a.pk_event = event.ID GROUP BY event.ID Order by Date desc";
		//end 02-03-2016 : Thangnb fix_sitemap_lay_thieu_su_kien
		$arr_all_item = Gnud_Db_read_query($v_sql_string);
		$v_get_date_by_news = true;
		$v_set_by_param = false;
	}
	
	$v_count = sizeof($arr_all_item);
	
	$datestr= date("Y-m-d");
	$v_html= array();
	
	if ($v_count > 0){
        // lấy mảng dữ liệu redirect
        $v_arr_redirect = array();
        $v_key = 'data_danh_sach_link_redirect_ban_web';
        $v_arr_redirect = fe_read_key_and_decode_from_file($v_key);
        if (!check_array($v_arr_redirect)) {
            $v_arr_redirect = fe_read_key_and_decode($v_key, _CACHE_TABLE);
        }
        
		for( $i=0; $i<$v_count; ++$i)
		{			
            $v_rowcate = fe_chuyen_muc_theo_id($arr_all_item[$i]['id_category']);
            $v_url = get_url_origin_of_event($arr_all_item[$i], $v_rowcate);
            // Nếu bài viết nằm trong danh sách redirect thì không hiển thị sitemap
            if ($v_arr_redirect[$v_url] != '') {
                continue;
            }
			//End 02-03-2016 : Thangnb fix_sitemap_lay_thieu_su_kien
			if (!is_null($v_url) && $v_url != '' && $v_url != str_replace(array('http://','https://'), '', $v_url)){
                if ($v_get_date_by_news){
					$lastNews = get_last_news_by_event($arr_all_item[$i]['ID']);
				}
                if ($lastNews['PublishedDate2']!='') {
                    $v_begin_time= date('Y-m-d', strtotime($lastNews['PublishedDate2'])).'T'.date('H:i:sP', strtotime($lastNews[$i]["PublishedDate2"])); 
                } else {
                    $v_begin_time= date('Y-m-d', strtotime($arr_all_item[$i]['Date'])).'T'.date('H:i:sP', strtotime($arr_all_item[$i]["Date"])); 
                }
				$v_keyword = $v_url;
				// Chi lay cac url duoc tinh cho chuyen muc nay
				// Xac dinh priority
				if ($v_set_by_param){
					$v_changefreq = $p_changefreq;
					$v_priority = $p_priority;
				} else {
					if ($p_priority!=$default_article_priority){
						$v_priority=get_priority($arr_all_priority,$v_url,2) ;
					}else{
						$v_priority=$default_article_priority;
					}
		
					if ($p_changefreq!=$default_article_changefreq){
						$v_changefreq=get_changefreq($arr_all_changefreg,$v_url, 2) ;
					}else{
						$v_changefreq=$default_article_changefreq;
					}
				}
				
				$tmp = "<url>\n";
				$tmp = $tmp."<loc>"._replace_xml_special_char($v_url)."</loc>\n";
				$tmp = $tmp."<!-- start_url_goc:"._replace_xml_special_char($v_url).":end_url_goc -->";
				$tmp = $tmp."<lastmod>"._replace_xml_special_char($v_begin_time)."</lastmod>\n";
				$tmp = $tmp."<changefreq>"._replace_xml_special_char($v_changefreq)."</changefreq>\n";
				$tmp = $tmp."<priority>"._replace_xml_special_char($v_priority)."</priority>\n";
				$tmp = $tmp."</url>\n";
				$v_html[] = $tmp;
			}		
		}
	}
	return $v_html;
}


// Ham tao sitemap cho TAT CA  bai viet, game 
function gen_sitemap_for_all_article($p_changefreq="",$p_priority=""){
	global $v_root, $v_root_without_slash, $default_menu_changefreq, $default_article_changefreq, $default_menu_priority, $default_article_priority, $v_begin, $v_end, $sitemapindex;
	
	$urlHelper = new UrlHelper();$urlHelper->getInstance();
	if ($p_priority==""){
		$p_priority=$default_menu_priority;
	}
	if ($p_priority!=$default_menu_priority){
		$arr_all_priority=explode(";", $p_priority);
	}
	if ($p_changefreq==""){
		$p_changefreq=$default_article_changefreq;
	}
	if ($p_changefreq!=$default_article_changefreq){
		$arr_all_changefreg=explode(";", $p_changefreq);
	}
    
	//begin: sua_doi_he_thong_sitemap_24h
    // phuonghv edit 20/10/2015 lấy các khoảng thời gian để tạo sitemap, mỗi khoảng thời gian là 1 tháng
	$arr_khoang_thoi_gian = fe_lay_khoang_thoi_gian_tao_sitemap_bai_viet($p_so_thang = 15);
    //end: sua_doi_he_thong_sitemap_24h
	if (!check_array($arr_khoang_thoi_gian)) {
		return '';
	}
	// lấy số bản ghi phân trang cần tạo sitemap
	$v_so_ban_ghi_toi_da =  50;     
	$v_so_thang_chay_sitemap_article =  _get_module_config('sitemap','v_so_thang_chay_sitemap_article');     
	$v_co_chay_sitemap_article_thang_cu =  _get_module_config('sitemap','v_co_chay_sitemap_article_thang_cu');     
	$v_ngay_hien_tai =  date('j',strtotime(date('Y-m-d')));
	$v_so_thang_chay_sitemap_article =  $v_ngay_hien_tai == 1 ? $v_so_thang_chay_sitemap_article++ : $v_so_thang_chay_sitemap_article;
	// Voi moi khoang thoi gian
	$v_sott_file=1;
    // Cấu hình có tạo sitemap
    $v_https_for_sitemap = _get_module_config('sitemap', 'v_create_sitemap_protocol_by_type');
    $sitemapindexhttps = "sitemap-index-https"; // ten file khong co .xml ma he thong se tu them vao sau
    
	foreach($arr_khoang_thoi_gian as $row_khoang_thoi_gian) {
		$v_tu_ngay = $row_khoang_thoi_gian["c_from_date"];
		$v_den_ngay = $row_khoang_thoi_gian["c_to_date"];
		$j = 0;
		// lấy số bản ghi được cấu hình
        $v_page = 1;
		$v_number_for_page = $v_so_ban_ghi_toi_da;
		$v_loop = true;
		while ($v_loop){
            $v_start_time = microtime(true);
			// ngày tháng năm hiện tại
			$v_nam_hien_tai =  date('Y',strtotime($v_den_ngay));
			$v_thang_hien_tai =  date('m',strtotime($v_den_ngay));
			Gnud_Db_close('read');
			// lấy bài viết trong 1 khoảng thời gian
			$arr_all_item = fe_bai_viet_theo_khoang_thoi_gian_xuat_ban($v_tu_ngay, $v_den_ngay, $v_page, $v_number_for_page);
			// Gen thành công sitemap article
			$v_time = lay_thoi_gian_khoang_cach($v_start_time);
			$v_content = date("Y-m-d H:i:s")." Doan 1: Chay SP lay bai viet theo khoang thoi gian. Thoi gian chay:".$v_time."s \n";
			fw24h_write_log($v_content, WEB_ROOT.'/logs/logs_gen_site_map_article.log');
			$v_start_time = microtime(true);
			// đếm số lượng bài viết lấy ra
			$n=count($arr_all_item);
			//end: sua_doi_he_thong_sitemap_24h
			if (!check_array($arr_all_item)) {
                $v_loop = false;
				continue;
			}
			
			array_unique_key($arr_all_item, 'ID');
			//begin: sua_doi_he_thong_sitemap_24h
			//phuonghv add 20/10/2015 sap xep bai viet theo thoi gian xuat ban moi nhat len dau.
			$arr_all_item = php_multisort($arr_all_item, array(array('key' => 'DateEdited', 'sort' => 'desc'), array('key' => 'PublishedDate2', 'sort' => 'desc')));
			//end: sua_doi_he_thong_sitemap_24h        
			
			$v_count = sizeof($arr_all_item);
			$datestr= Date("Y-m-d");
			$v_html= array();		
			if ($v_count > 0){			
				//begin: sua_doi_he_thong_sitemap_24h
				foreach ($arr_all_item as $v_news) {
                    $row_cate = fe_chuyen_muc_theo_id($v_news['CategoryID']);
					$v_url=  get_url_origin_of_news($v_news, $row_cate);                
					$time_news = (strtotime($v_news["PublishedDate2"])>=strtotime($v_news["DateEdited"]))?$v_news["PublishedDate2"]:$v_news["DateEdited"];
					$v_begin_time= date('Y-m-d', strtotime($time_news)).'T'.date('H:i:sP', strtotime($time_news)); 

					//Begin 13-05-2016 : Thangnb toi_uu_sitemap_seo
					$v_news_id = $v_news['ID'];
					if ($v_url == '' || is_null($v_url)) {
                        $sql = "call be_get_all_category_by_one_news($v_news_id)";
                        $arr_newscategory = Gnud_Db_read_query($sql);
                        $v_news_title = (trim($v_news['SlugTitle'])!='') ? $v_news['SlugTitle'] : $v_news['Title'];
                        $intChuyenMucId = 0;
                        foreach ($arr_newscategory as $row_cate) {
                            if($row_cate['Parent'] == 0) { // neu la CM cap 1, chon CM cap 1 dau tien
                                $intChuyenMucId = $row_cate['CategoryID'];
                                $v_urlslugs = (trim($row_cate['Urlslugs'])!='') ? $row_cate['Urlslugs'] : $row_cate['Name'];
                                break;
                            }
                        }
                        if($intChuyenMucId == 0) { // neu ko tim thay CM cap 1, lay CM dau tien duoc xb
                            $intChuyenMucId = $arr_newscategory[0]['CategoryID'];
                            $v_urlslugs = (trim($arr_newscategory[0]['Urlslugs'])!='') ? $arr_newscategory[0]['Urlslugs'] : $arr_newscategory[0]['Name'];
                        }
                        $urlHelper->_BASE_URL = '';
                        $v_url = BASE_URL_FOR_PUBLIC.$urlHelper->url_news(array('ID'=>$v_news_id, 'cID'=>$intChuyenMucId, 'slug'=>$v_urlslugs.'/'.$v_news_title, 'VideoCode'=>$v_news['VideoCode']));
					}
					$v_keyword = $v_url;
					//End 13-05-2016 : Thangnb toi_uu_sitemap_seo

					//begin: sua_doi_he_thong_sitemap_24h
					//phuonghv add 23/10/2015 xu ly loi thieu link anh trong sitemap
					$linkIMG = $v_news["SummaryImg"]==''? $v_news["SummaryImg_chu_nhat"]:$v_news["SummaryImg"];                
					if ($linkIMG == str_replace(array('http://', 'https://'), '', $linkIMG)) {
						// gắn thêm link server nếu không phải link tuyệt đối
						$linkIMG = $v_root.$linkIMG;
					}
					//end: sua_doi_he_thong_sitemap_24h
					
					// begin:tach_sitemap_tags_image_video_theo_thang
					// phuonghv add 17/11/2015 : xu ly thay the domain static & domain cdn
					$linkIMG = replace_domain_static_images_and_domain_cdn_images($linkIMG);
					// end: tach_sitemap_tags_image_video_theo_thang
					
					// Xac dinh priority
					if ($p_priority!=$default_menu_priority){
						$v_priority=get_priority($arr_all_priority,$v_keyword,2) ;
					}else{
						$v_priority=$default_menu_priority;
					}
					if ($p_changefreq!=$default_article_changefreq){
						$v_changefreq=get_changefreq($arr_all_changefreg,$v_keyword, 2) ;
					}else{
						$v_changefreq=$default_article_changefreq;
					}	
						
					if (!is_null($v_url) && $v_url != '' && $v_url != str_replace(array('http://','https://'), '', $v_url)){
						$tmp = "<url>\n";
						//begin: sua_doi_he_thong_sitemap_24h
						$tmp = $tmp."<loc>"._replace_xml_special_char($v_url)."</loc>\n";
						//end: sua_doi_he_thong_sitemap_24h
						$tmp = $tmp."<!-- start_url_goc:"._replace_xml_special_char($v_url).":end_url_goc -->";
						/* begin 07/12/2016 Tytv - ko_lay_link_anh_vao_sitemap_cac_domain_khong_thuoc_24h */
						$v_link_anh_thuoc_domain_cho_phep = _kiem_tra_link_anh_thuoc_domain_cho_phep($linkIMG);
						if ($v_link_anh_thuoc_domain_cho_phep) {
						/* End 07/12/2016 Tytv - ko_lay_link_anh_vao_sitemap_cac_domain_khong_thuoc_24h */
							$tmp = $tmp."<image:image>\n";                        
							$tmp = $tmp."<image:loc>"._replace_xml_special_char($linkIMG)."</image:loc>\n";
							//Begin 05-02-2016 : Thangnb fix_loi_caption_sitemap
							$tmp = $tmp."<image:caption>"._replace_xml_special_char(strip_tags($v_news['SummaryImgTip']))."</image:caption>\n";                        //End 05-02-2016 : Thangnb fix_loi_caption_sitemap
							$tmp = $tmp."<image:license>".BASE_URL_FOR_PUBLIC."</image:license>\n";
							$tmp = $tmp."<image:family_friendly>yes</image:family_friendly>\n";
							$tmp = $tmp."</image:image>\n";
						}
						
						//Begin 13-05-2016 : Thangnb toi_uu_sitemap_seo
						preg_match_all('/<img.*>/msU',$v_news['Body'],$imgs);
						if (check_array($imgs[0])) {
							foreach($imgs[0] as $img) {
								// regex lấy url ảnh bài viết
								preg_match('#src\s*=\s*"([^\"]*)"#ism', $img, $match);
								$v_image_url = trim($match[1]);
								if ($v_image_url != '') {
									$v_image_url = replace_domain_static_images_and_domain_cdn_images($v_image_url);
									/* begin 07/12/2016 Tytv - ko_lay_link_anh_vao_sitemap_cac_domain_khong_thuoc_24h */
									$v_link_anh_thuoc_domain_cho_phep = _kiem_tra_link_anh_thuoc_domain_cho_phep($v_image_url);
									if ($v_link_anh_thuoc_domain_cho_phep) {
									/* End 07/12/2016 Tytv - ko_lay_link_anh_vao_sitemap_cac_domain_khong_thuoc_24h */
										$tmp = $tmp."<image:image>\n";                            
										$tmp = $tmp."<image:loc>"._replace_xml_special_char($v_image_url)."</image:loc>\n";
										$tmp = $tmp."<image:caption>"._replace_xml_special_char(strip_tags($v_news["Title"]))."</image:caption>\n";
										$tmp = $tmp."<image:license>".BASE_URL_FOR_PUBLIC."</image:license>\n";
										$tmp = $tmp."<image:family_friendly>yes</image:family_friendly>\n";
										$tmp = $tmp."</image:image>\n";
									}
								}
							}
						}
						//End 13-05-2016 : Thangnb toi_uu_sitemap_seo
										
						$tmp = $tmp."<lastmod>"._replace_xml_special_char($v_begin_time)."</lastmod>\n";
						$tmp = $tmp."<changefreq>"._replace_xml_special_char($v_changefreq)."</changefreq>\n";
						$tmp = $tmp."<priority>"._replace_xml_special_char($v_priority)."</priority>\n";
						$tmp = $tmp."</url>\n";
						$v_html[] = $tmp;
					}
				}
                // Gen thành công sitemap article
                $v_time = lay_thoi_gian_khoang_cach($v_start_time);
                $v_content = date("Y-m-d H:i:s")." Doan 2:  Tao xml sitemap Thoi gian chay:".$v_time."s \n";
			}
			//end: sua_doi_he_thong_sitemap_24h
			// Ghi file
			if (check_array($v_html) && count($v_html)) {
                // Cập nhật sitemap cho link http
                if($v_https_for_sitemap == 1 || $v_https_for_sitemap == 3){
                    $v_sitemap_file = "sitemap-article-".$v_nam_hien_tai.'-'.$v_thang_hien_tai.'-'.$j;
                    write2db($v_sitemap_file, $v_html, $v_begin, $v_end, $sitemapindex, false );
                }
				$j++;
			}
			++$v_page;
			if ($n < $v_number_for_page){
				$v_loop = false;
			}
		}
		$v_sott_file=$v_sott_file+1;
	}	
	return;
}

// Tao sitemap cho cac bai viet cua 1 ngay
function gen_sitemap_for_daily($date, $p_changefreq,$p_priority, $v_begin, $v_end){
	global $v_root, $v_root_without_slash, $default_menu_changefreq, $default_article_changefreq, $default_menu_priority, $default_article_priority;
	$urlHelper = new UrlHelper();$urlHelper->getInstance();
	if ($p_priority==""){
		$p_priority=$default_menu_priority;
	}
	if ($p_priority!=$default_menu_priority){
		$arr_all_priority=explode(";", $p_priority);
	}
	if ($p_changefreq==""){
		$p_changefreq=$default_article_changefreq;
	}
	if ($p_changefreq!=$default_article_changefreq){
		$arr_all_changefreg=explode(";", $p_changefreq);
	}
	
 	if(!$date) die("Can cung cap ngay thang de tao sitemap co dang nhu &date=YYYY-MM-DD (2010-04-27)");
	
	$tmpdate = explode("-", $date);
	$after = date("Y-m-d", mktime(0, 0, 0, $tmpdate[1]  , $tmpdate[2]+1, $tmpdate[0]));
    //begin: sua_doi_he_thong_sitemap_24h    
    //phuonghv edit ngày 20/10/2015
    $arr_all_item = fe_bai_viet_theo_khoang_thoi_gian_xuat_ban($date, $after, 1, 1000);
	pre($arr_all_item);die;
    array_unique_key($arr_all_item, 'ID');
    $arr_all_item = php_multisort($arr_all_item, array(array('key' => 'DateEdited', 'sort' => 'desc'), array('key' => 'PublishedDate2', 'sort' => 'desc')));
	
    //end: sua_doi_he_thong_sitemap_24h
    $v_count = sizeof($arr_all_item);	
	if(!$v_count) die("Khong co du lieu de tao sitemap");
    
	$datestr= Date("Y-m-d");
	$v_html = '';
	if ($v_count > 0){
		//begin: sua_doi_he_thong_sitemap_24h    
        foreach ($arr_all_item as $v_news) {
            // Không hiển thị bài PR trên sitemap
            if(_is_bai_pr($v_news)){
                continue;
            }
            $row_cate = fe_chuyen_muc_theo_id($v_news['CategoryID']);
            $v_url=  get_url_origin_of_news($v_news, $row_cate);   
            $time_news = (strtotime($v_news["PublishedDate2"])>=strtotime($v_news["DateEdited"]))?$v_news["PublishedDate2"]:$v_news["DateEdited"];
			$v_begin_time= date('Y-m-d', strtotime($time_news)).'T'.date('H:i:sP', strtotime($v_news["PublishedDate2"])); 
			//Begin 13-05-2016 : Thangnb toi_uu_sitemap_seo
			$v_news_id = $v_news['ID'];
			if ($v_url == '' || is_null($v_url)) {
				$sql = "call be_get_all_category_by_one_news($v_news_id)";
				$arr_newscategory = Gnud_Db_read_query($sql);
				$v_news_title = (trim($v_news['SlugTitle'])!='') ? $v_news['SlugTitle'] : $v_news['Title'];
				$intChuyenMucId = 0;
				foreach ($arr_newscategory as $row_cate) {
					if($row_cate['Parent'] == 0) { // neu la CM cap 1, chon CM cap 1 dau tien
						$intChuyenMucId = $row_cate['CategoryID'];
						$v_urlslugs = (trim($row_cate['Urlslugs'])!='') ? $row_cate['Urlslugs'] : $row_cate['Name'];
						break;
					}
				}
				if($intChuyenMucId == 0) { // neu ko tim thay CM cap 1, lay CM dau tien duoc xb
					$intChuyenMucId = $arr_newscategory[0]['CategoryID'];
					$v_urlslugs = (trim($arr_newscategory[0]['Urlslugs'])!='') ? $arr_newscategory[0]['Urlslugs'] : $arr_newscategory[0]['Name'];
				}
				$urlHelper->_BASE_URL = '';
				$v_url = BASE_URL_FOR_PUBLIC.$urlHelper->url_news(array('ID'=>$v_news_id, 'cID'=>$intChuyenMucId, 'slug'=>$v_urlslugs.'/'.$v_news_title, 'VideoCode'=>$v_news['VideoCode']));
			}
			$v_keyword = $v_url;
			//End 13-05-2016 : Thangnb toi_uu_sitemap_seo
			// Xac dinh priority
			if ($p_priority!=$default_menu_priority){
				$v_priority=get_priority($arr_all_priority,$v_url,2) ;
			}else{
				$v_priority=$default_menu_priority;
			}
			if ($p_changefreq!=$default_article_changefreq){
				$v_changefreq=get_changefreq($arr_all_changefreg,$v_url, 2) ;
			}else{
				$v_changefreq=$default_article_changefreq;
			}	
			if (!is_null($v_url) && $v_url != '' && $v_url != str_replace(array('http://','https://'), '', $v_url)){
				$v_html = $v_html."<url>\n";
                //begin: sua_doi_he_thong_sitemap_24h
				$v_html = $v_html."<loc>"._replace_xml_special_char($v_url)."</loc>\n";
                //end: sua_doi_he_thong_sitemap_24h
				$v_html = $v_html."<!-- start_url_goc:"._replace_xml_special_char($v_url).":end_url_goc -->";
                //begin: sua_doi_he_thong_sitemap_24h
				$linkIMG = $v_news['SummaryImg']==''?$v_news['SummaryImg_chu_nhat']:$v_news['SummaryImg'];
                //end: sua_doi_he_thong_sitemap_24h

				if ($linkIMG == str_replace(array('http://', 'https://'), '', $linkIMG)) {
					// gắn thêm link server nếu không phải link tuyệt đối
					$linkIMG = $v_root_without_slash.$linkIMG;
				}
                
                // begin:tach_sitemap_tags_image_video_theo_thang
                // phuonghv add 17/11/2015 : xu ly thay the domain static & domain cdn
                $linkIMG = replace_domain_static_images_and_domain_cdn_images($linkIMG);
                // end: tach_sitemap_tags_image_video_theo_thang
                
                /* begin 07/12/2016 Tytv - ko_lay_link_anh_vao_sitemap_cac_domain_khong_thuoc_24h */
                $v_link_anh_thuoc_domain_cho_phep = _kiem_tra_link_anh_thuoc_domain_cho_phep($linkIMG);
                if ($v_link_anh_thuoc_domain_cho_phep) {
                /* End 07/12/2016 Tytv - ko_lay_link_anh_vao_sitemap_cac_domain_khong_thuoc_24h */
                    $v_html = $v_html."<image:image>\n";                    
                    $v_html = $v_html."<image:loc>"._replace_xml_special_char($linkIMG)."</image:loc>\n";
					/*Begin 01-11-2017 trungcq XLCYCMHENG_27169_bo_sung_anh_chia_se_va_caption*/
                    $v_html = $v_html."<image:caption>"._replace_xml_special_char(strip_tags(($v_news['SummaryImgTip']!='')?$v_news['SummaryImgTip']:$v_news['Title']))."</image:caption>\n";                    
					/*End 01-11-2017 trungcq XLCYCMHENG_27169_bo_sung_anh_chia_se_va_caption*/
                    $v_html = $v_html."<image:license>".BASE_URL_FOR_PUBLIC."</image:license>\n";
                    $v_html = $v_html."<image:family_friendly>yes</image:family_friendly>\n";
                    $v_html = $v_html."</image:image>\n";
                }
                /*Begin 01-11-2017 trungcq XLCYCMHENG_27169_bo_sung_anh_chia_se_va_caption*/
                if($v_news['c_anh_chia_se_mxh']!=''){
                    if ($v_news['c_anh_chia_se_mxh'] == str_replace(array('http://', 'https://'), '', $v_news['c_anh_chia_se_mxh'])) {
                        // gắn thêm link server nếu không phải link tuyệt đối
                        $v_news['c_anh_chia_se_mxh'] = $v_root_without_slash.$v_news['c_anh_chia_se_mxh'];
                    }
                    $v_news['c_anh_chia_se_mxh'] = replace_domain_static_images_and_domain_cdn_images($v_news['c_anh_chia_se_mxh']);
                    $v_link_anh_thuoc_domain_cho_phep = _kiem_tra_link_anh_thuoc_domain_cho_phep($v_news['c_anh_chia_se_mxh']);
                    if ($v_link_anh_thuoc_domain_cho_phep) {
                        $v_html = $v_html."<image:image>\n";
                        $v_html = $v_html."<image:loc>"._replace_xml_special_char($v_news['c_anh_chia_se_mxh'])."</image:loc>\n";
                        $v_html = $v_html."<image:caption>"._replace_xml_special_char(strip_tags(($v_news['SummaryImgTip']!='')?$v_news['SummaryImgTip']:$v_news['Title']))."</image:caption>\n";
                        $v_html = $v_html."<image:license>".BASE_URL_FOR_PUBLIC."</image:license>\n";
                        $v_html = $v_html."<image:family_friendly>yes</image:family_friendly>\n";
                        $v_html = $v_html."</image:image>\n";
                    }
                }
                /*End 01-11-2017 trungcq XLCYCMHENG_27169_bo_sung_anh_chia_se_va_caption*/
				/*Begin 20-12-2017 trungcq LCYCMHENG_28823_bo_sung_anh_schema_newsArticle*/
                $v_url_anh_dai_dien_bai_viet = ($v_news['SummaryImg'] == '') ? $v_news['SummaryImg_chu_nhat'] : $v_news['SummaryImg'];
                if($v_url_anh_dai_dien_bai_viet!=''){
                    $arr_anh_schema_article = _get_module_config('cau_hinh_dung_chung','arr_anh_schema_article');
                    $v_prefix_anh_dai_dien_schema_article = $arr_anh_schema_article['prefix'];
                    $v_arr_anh = explode('.',$v_url_anh_dai_dien_bai_viet);
                    $v_anh_schema_article = $v_arr_anh[0].$v_prefix_anh_dai_dien_schema_article.'.'.$v_arr_anh[1];
                    
                    if ($v_anh_schema_article == str_replace(array('http://', 'https://'), '', $v_anh_schema_article)) {
                        // gắn thêm link server nếu không phải link tuyệt đối
                        $v_anh_schema_article = $v_root_without_slash.$v_anh_schema_article;
                    }
                    $v_anh_schema_article = replace_domain_static_images_and_domain_cdn_images($v_anh_schema_article);
                    $v_link_anh_thuoc_domain_cho_phep = _kiem_tra_link_anh_thuoc_domain_cho_phep($v_anh_schema_article);
                    if ($v_link_anh_thuoc_domain_cho_phep) {
                        $v_html = $v_html."<image:image>\n";
                        $v_html = $v_html."<image:loc>"._replace_xml_special_char($v_anh_schema_article)."</image:loc>\n";
                        $v_html = $v_html."<image:caption>"._replace_xml_special_char(strip_tags($v_news['Title']))."</image:caption>\n";
                        $v_html = $v_html."<image:license>".BASE_URL_FOR_PUBLIC."</image:license>\n";
                        $v_html = $v_html."<image:family_friendly>yes</image:family_friendly>\n";
                        $v_html = $v_html."</image:image>\n";
                    }
                }
                /*End 20-12-2017 trungcq LCYCMHENG_28823_bo_sung_anh_schema_newsArticle*/
				if (intval($v_news['Album_trang_anh'])) {
					$albumID = intval($v_news['Album_trang_anh']);
					$sql = "SELECT * FROM image_trang_anh WHERE AlbumID = $albumID ORDER BY ID ASC";
					$rs = Gnud_Db_read_query($sql);
					foreach ($rs as $r) {
						$linkIMG = $r['Bigimg'];
						if ($linkIMG == str_replace(array('http://', 'https://'), '', $linkIMG)) {
							// gắn thêm link server nếu không phải link tuyệt đối
							$linkIMG = $v_root.$linkIMG;
						}
                        
                        // begin:tach_sitemap_tags_image_video_theo_thang
                        // phuonghv add 17/11/2015 : xu ly thay the domain static & domain cdn
                        $linkIMG = replace_domain_static_images_and_domain_cdn_images($linkIMG);
                        // end: tach_sitemap_tags_image_video_theo_thang
                        /* begin 07/12/2016 Tytv - ko_lay_link_anh_vao_sitemap_cac_domain_khong_thuoc_24h */
                        $v_link_anh_thuoc_domain_cho_phep = _kiem_tra_link_anh_thuoc_domain_cho_phep($linkIMG);
                        if ($v_link_anh_thuoc_domain_cho_phep) {
                        /* End 07/12/2016 Tytv - ko_lay_link_anh_vao_sitemap_cac_domain_khong_thuoc_24h */
                            $v_html = $v_html."<image:image>\n";                            
                            $v_html = $v_html."<image:loc>"._replace_xml_special_char($linkIMG)."</image:loc>\n";
                            $v_html = $v_html."<image:caption>"._replace_xml_special_char(strip_tags($v_news["Title"]))."</image:caption>\n";
                            $v_html = $v_html."<image:license>".BASE_URL_FOR_PUBLIC."</image:license>\n";
                            $v_html = $v_html."<image:family_friendly>yes</image:family_friendly>\n";
                            $v_html = $v_html."</image:image>\n";
                        }
					}
				} 
				// xử lý loại bỏ bài liên quan trong nội dung bài viết
				$v_news['Body'] = preg_replace('/<div\s*class="bv-lq".*<div\s*class="see-now".*<\/div>\s*<\/div>\s*<\/div>\s*<\/div>/msU','',$v_news['Body']);
				//Begin 13-05-2016 : Thangnb toi_uu_sitemap_seo
				preg_match_all('/<img.*>/msU',$v_news['Body'],$imgs);
				if (check_array($imgs[0])) {	
					foreach($imgs[0] as $img) {
						// regex lấy url ảnh bài viết
						preg_match('#src\s*=\s*"([^\"]*)"#ism', $img, $match);
						$v_image_url = trim($match[1]);	
						if ($v_image_url != '') {
							$v_image_url = replace_domain_static_images_and_domain_cdn_images($v_image_url);
                            /* begin 07/12/2016 Tytv - ko_lay_link_anh_vao_sitemap_cac_domain_khong_thuoc_24h */
                            $v_link_anh_thuoc_domain_cho_phep = _kiem_tra_link_anh_thuoc_domain_cho_phep($v_image_url);
                            if ($v_link_anh_thuoc_domain_cho_phep) {
                            /* End 07/12/2016 Tytv - ko_lay_link_anh_vao_sitemap_cac_domain_khong_thuoc_24h */
								$v_html = $v_html."<image:image>\n";                            
								$v_html = $v_html."<image:loc>"._replace_xml_special_char($v_image_url)."</image:loc>\n";
								$v_html = $v_html."<image:caption>"._replace_xml_special_char(strip_tags($v_news["Title"]))."</image:caption>\n";
								$v_html = $v_html."<image:license>".BASE_URL_FOR_PUBLIC."</image:license>\n";
								$v_html = $v_html."<image:family_friendly>yes</image:family_friendly>\n";
								$v_html = $v_html."</image:image>\n";
							}
						}
					}
				}
				//End 13-05-2016 : Thangnb toi_uu_sitemap_seo
				
                //end: sua_doi_he_thong_sitemap_24h    
				$v_html = $v_html."<lastmod>$v_begin_time</lastmod>\n";
				$v_html = $v_html."<changefreq>$v_changefreq</changefreq>\n";
				$v_html = $v_html."<priority>$v_priority</priority>\n";
				$v_html = $v_html."</url>\n";
			}
		}
	}
	var_dump($v_html);die;
    $v_https_for_sitemap = _get_module_config('sitemap', 'v_create_sitemap_protocol_by_type');
    // kiểm tra cấu hình tạo sitemap với giao thức http
    if($v_https_for_sitemap == 1 || $v_https_for_sitemap == 3){
        // begin 6/2/2018 Tytv chinh_ten_sitemap_theo_ngay
        $v_sitemap_file = "sitemap-article-daily";
        _write_db($v_sitemap_file, array($v_html), $v_begin, $v_end, false );	
    }
	return 	array("sitemap-article-daily.xml");
    // End 6/2/2018 Tytv chinh_ten_sitemap_theo_ngay
}

// Tao news-sitemap cho cac bai viet cua 1 ngay
function gen_news_sitemap_for_daily($date, $v_changefreg, $v_priority, $sitename = "24h", $sitelang="vi"){
	global $v_root, $v_root_without_slash, $default_menu_changefreq, $default_article_changefreq, $default_menu_priority, $default_article_priority;
	$urlHelper = new UrlHelper();$urlHelper->getInstance();
	if ($p_priority==""){
		$p_priority=$default_article_priority;
	}
	if ($p_priority!=$default_article_priority){
		$arr_all_priority=explode(";", $p_priority);
	}
	if ($p_changefreq==""){
		$p_changefreq=$default_article_changefreq;
	}
	if ($p_changefreq!=$default_article_changefreq){
		$arr_all_changefreg=explode(";", $p_changefreq);
	}
	
	$tmpdate = explode("-", $date);
	$before = date("Y-m-d", mktime(0, 0, 0, $tmpdate[1]  , $tmpdate[2]-1, $tmpdate[0]));
	$after = date("Y-m-d", mktime(0, 0, 0, $tmpdate[1]  , $tmpdate[2]+1, $tmpdate[0]));
	
	if(!$date) die("Can cung cap ngay thang de tao sitemap co dang nhu &date=YYYY-MM-DD (2010-04-27)");
	
	//begin: sua_doi_he_thong_sitemap_24h    
    // gioi han chi cho phep lay duoi 1000 rows de tao sitemap       
    $clause = " AND news.DateEdited >= '" . $before . "' AND news.DateEdited <= '" . $after . "' LIMIT 999";
    $v_sql_string = "
		Select news.ID, url, news.Title, keywords, DateEdited, CategoryID, Parent, PublishedDate2, t_news_url.c_canonical_url,t_news_url.c_origin_url
        ,(SELECT 1 FROM t_news_data nd WHERE nd.c_news_id = news.ID  AND  nd.c_key = 'bai_pr_nhan_hang'  LIMIT 1) c_pr_nhan_hang
        ,(SELECT 1 FROM t_news_data nd WHERE nd.c_news_id = news.ID  AND  nd.c_key = 'bai_pr_lien_quan'  LIMIT 1) c_pr_lien_quan
        ,(SELECT 1 FROM t_news_pr WHERE t_news_pr.fk_news_id = news.ID LIMIT 1) pr_2015
        ,news.pr
        ,news.pr_dau_trang
        ,news.pr_tu_van
        ,news.pr_mobile
        ,news.pr_diemthi
        From news, newscategory, category , t_news_url 
        Where news.Album_trang_anh=0 
        AND news.VideoCode='' AND newscategory.Video_code=0 
        AND news.ID = newscategory.NewsID 
        AND news.ID = t_news_url.pk_news
        AND newscategory.Status > 0 AND newscategory.CategoryID = category.ID AND category.Activate = 1" . $clause;

    $arr_all_item = Gnud_Db_read_query($v_sql_string);
    array_unique_key($arr_all_item, 'ID');
    //sap xep giam dan theo thoi gian xuat ban
    $arr_all_item = php_multisort($arr_all_item, array(array('key' => 'DateEdited', 'sort' => 'desc'), array('key' => 'PublishedDate2', 'sort' => 'desc')));
    //end: sua_doi_he_thong_sitemap_24h 
    
	$v_count = sizeof($arr_all_item);	
	
	if(!$v_count) die("Khong co du lieu de tao sitemap");
	
	$datestr= Date("Y-m-d");
	$v_html = array();
	if ($v_count > 0){
        // lấy mảng dữ liệu redirect
        $v_arr_redirect = array();
        $v_key = 'data_danh_sach_link_redirect_ban_web';
        $v_arr_redirect = fe_read_key_and_decode_from_file($v_key);
        if (!check_array($v_arr_redirect)) {
            $v_arr_redirect = fe_read_key_and_decode($v_key, _CACHE_TABLE);
        }
		//begin: sua_doi_he_thong_sitemap_24h   
        foreach ($arr_all_item as $v_news) {		
            // Không hiển thị bài PR trên sitemap
            if(_is_bai_pr($v_news)){
                continue;
            }
            // Nếu bài viết nằm trong danh sách redirect thì không hiển thị sitemap
            if (_link_redirect_khong_dua_vao_sitemap($v_news,$v_arr_redirect)) {
                continue;
            }
			$row_cate = fe_chuyen_muc_theo_id($v_news['CategoryID']);
            $v_url=  get_url_origin_of_news($v_news, $row_cate);   
            /* Begin: 05-01-2016 trungcq bổ sung xu_ly_ky_tu_dac_biet_sitemap */
            // Ưu tiên lấy theo thời gian sửa đổi tin bài
            $v_news["PublishedDate2"] = (strtotime($v_news["DateEdited"]) > strtotime($v_news["PublishedDate2"])) ? $v_news["DateEdited"] : $v_news["PublishedDate2"];
            /* End: 05-01-2016 trungcq bổ sung xu_ly_ky_tu_dac_biet_sitemap */
                        $v_begin_time= date('Y-m-d', strtotime($v_news["PublishedDate2"])).'T'.date('H:i:sP', strtotime($v_news["PublishedDate2"])); 
			$v_keyword = $v_news["url"];
			$v_title =  $v_news["Title"];
			$news_keywords = $v_news["keywords"];
			
            $parentID = ($v_news["Parent"]==0) ? $v_news["CategoryID"] : $v_news["Parent"];
            //end: sua_doi_he_thong_sitemap_24h  
            switch ($parentID) {
                case '46': // Tin tuc trong ngay
                case '51': // An ninh xua hoi
                    $news_keywords = 'Nation,Viet Nam,Việt Nam,VN';
                    break;
                case '159': // Phi thuong ky quac
                    $news_keywords = 'Weird News';
                    break;
                case '48': // Bong da
                    $news_keywords = 'Sports, Football, Thể thao,bóng đá,bong da,the thao';
                    break;
                case '101': // The thao
                    $news_keywords = 'Sports, the thao, Thể thao';
                    break;
                case '119': // Tennis
                    $news_keywords = 'Sports, the thao, Thể thao,tennis';
                    break;
                case '161': // Tai chinh bat dong san
                case '52': // Thi truong tieu dung
                    $news_keywords = 'Kinh Doanh,Business,Companies,Economy,Industry,Markets';
                    break;
                case '216': // Giao duc du hoc
                    $news_keywords = 'Education, Viet Nam,Việt Nam,VN, Giáo dục, giao duc';
                    break;
                case '78': // Thoi trang
                case '460': // Am thuc
                case '145': // Lam dep
                    $news_keywords = 'Woman, Lifestyle, National';
                    break;
                case '74': // Phim
                    $news_keywords = 'Entertainment, Movies,giải trí, giai tri';
                    break;
                case '73': // Ca nhac MTV
                    $news_keywords = 'Entertainment, giải trí,giai tri,music';
                    break;
                case '55': // Cong nghe thong tin
                case '77': // O to xe may
                case '407': // Thoi trang hitech
                    $news_keywords = 'Technology, Entertainment, Lifestyle';
                    break;
                default:
                    $news_keywords = 'Nation,Viet Nam,Việt Nam,VN';
                    break;
            }			
			if ($p_priority!=$default_article_priority){
				$v_priority=get_priority($arr_all_priority,$v_url,2) ;
			}else{
				$v_priority=$default_article_priority;
			}
			if ($p_changefreq!=$default_article_changefreq){
				$v_changefreq=get_changefreq($arr_all_changefreg,$v_url, 2) ;
			}else{
				$v_changefreq=$default_article_changefreq;
			}	
            
            //begin: sua_doi_he_thong_sitemap_24h    
            //phuonghv edit ngày 21/10/2015  Thêm giá trị của meta news_keywords vào thẻ keywords trong sitemap news
            $news_keywords.= ($news_keywords == '' ? '' : ',') . $v_news["keywords"];
            //end: sua_doi_he_thong_sitemap_24h    
            
			if (!is_null($v_url) && $v_url != '' && $v_url != str_replace(array('http://','https://'), '', $v_url)){
				$tmp = "<url>\n";
				$tmp = $tmp."<loc>"._replace_xml_special_char($v_url)."</loc>\n";
				$tmp = $tmp."<!-- start_url_goc:"._replace_xml_special_char($v_url).":end_url_goc -->";
				$tmp = $tmp."<news:news>\n";
				$tmp = $tmp."<news:publication>\n<news:name>"._replace_xml_special_char($sitename)."</news:name>\n<news:language>".$sitelang."</news:language>\n</news:publication>\n";
				$tmp = $tmp."<news:publication_date>$v_begin_time</news:publication_date>\n";
				$tmp = $tmp."<news:title>"._replace_xml_special_char($v_title)."</news:title>\n";
				$tmp = $tmp."<news:keywords>"._replace_xml_special_char($news_keywords)."</news:keywords>\n";
				$tmp = $tmp."</news:news>\n";
				$tmp = $tmp."</url>\n";
				$v_html[] = $tmp;
			}
		}
	}
	return $v_html;
}

//begin: tao_sitemap_tag_video_image_theo_thang
// phuonghv comment 06/11/2015
// Tao sitemap video cua 1 ngay
/*
function gen_video_sitemap_for_daily($date, $v_changefreg, $v_priority, $sitename = "24h.com.vn", $sitelang="vi"){
	global $v_root, $v_root_without_slash, $default_menu_changefreq, $default_article_changefreq, $default_menu_priority, $default_article_priority;
	$urlHelper = new UrlHelper();$urlHelper->getInstance();
	if ($p_priority==""){
		$p_priority=$default_article_priority;
	}
	if ($p_priority!=$default_article_priority){
		$arr_all_priority=explode(";", $p_priority);
	}
	if ($p_changefreq==""){
		$p_changefreq=$default_article_changefreq;
	}
	if ($p_changefreq!=$default_article_changefreq){
		$arr_all_changefreg=explode(";", $p_changefreq);
	}
	
	$tmpdate = explode("-", $date);
	$before = date("Y-m-d", mktime(0, 0, 0, $tmpdate[1]  , $tmpdate[2]-5, $tmpdate[0]));
	if(!$date) die("Can cung cap ngay thang de tao sitemap co dang nhu &date=YYYY-MM-DD (2010-04-27)");
    
	$clause = " AND news.DateEdited >= '".$before."' AND news.DateEdited <= '".$date."' ";
	$v_sql_string = "Select distinct news.ID, url, news.Title, keywords, date_format(DateEdited,'%Y-%m-%d') DateEdited ,
						Body, VideoCode, Summary, SummaryImg, SummaryImg_chu_nhat, category.Name AS CategoryName, newscategory.PublishedDate2
						From news, newscategory, category where news.ID = newscategory.NewsID AND newscategory.CategoryID = category.ID AND newscategory.Status > 0 AND (news.VideoCode != '' OR newscategory.Video_code=1) ".$clause." GROUP BY news.ID Order by DateEdited desc LIMIT 18000";
	$arr_all_item = Gnud_Db_read_query($v_sql_string);
	$v_count = sizeof($arr_all_item);	
	
	if(!$v_count) die("Khong co du lieu de tao sitemap");
	
	$datestr= Date("Y-m-d");
	$v_html = array();
	if ($v_count > 0){
		for( $i=0; $i<$v_count; ++$i)
		{			
			$v_url=trim($arr_all_item[$i]["url"]);
			$v_begin_time= dateTimeFormat($arr_all_item[$i]["PublishedDate2"]);
			$v_keyword = $arr_all_item[$i]["url"];
			$v_title =  str_replace('&', '&amp;', $arr_all_item[$i]["Title"]);
			if (is_null($arr_all_item[$i]['SummaryImg']) || $arr_all_item[$i]['SummaryImg'] == '') {
				$arr_all_item[$i]['SummaryImg'] = $arr_all_item[$i]['SummaryImg_chu_nhat'];
			}
			$v_thumbnail_loc =  strpos($arr_all_item[$i]["SummaryImg"], 'http://') === false ? str_replace('upload/', 'http://www.24h.com.vn/upload/',$arr_all_item[$i]["SummaryImg"]) : $arr_all_item[$i]["SummaryImg"];
			$v_thumbnail_loc = substr($v_thumbnail_loc,1);
			$v_description =  str_replace('&', '&amp;', $arr_all_item[$i]["Summary"]);
			$v_video_player_loc = get_video_player_loc ($arr_all_item[$i]);
			$news_keywords = $arr_all_item[$i]["keywords"];
			$v_categoryName = $arr_all_item[$i]["CategoryName"];
			
            if ($p_priority!=$default_article_priority){
                $v_priority=get_priority($arr_all_priority,$v_url,2) ;
            }else{
                $v_priority=$default_article_priority;
            }

            if ($p_changefreq!=$default_article_changefreq){
                $v_changefreq=get_changefreq($arr_all_changefreg,$v_url, 2) ;
            }else{
                $v_changefreq=$default_article_changefreq;
            }	
				
			if (!is_null($v_url) && $v_url != '' && $v_url != str_replace('http://', '', $v_url)){
				$v_thumbnail_loc = _replace_xml_special_char($v_thumbnail_loc);
                $v_title = _replace_xml_special_char($v_title);
                $v_description = _replace_xml_special_char($v_description);
                $v_categoryName = _replace_xml_special_char($v_categoryName);
                $news_keywords = str_replace('&', '&amp;', $news_keywords);
                
				$tmp = "<url>\n";
				$tmp = $tmp."<loc>"._replace_xml_special_char($v_url)."</loc>\n";
				$tmp = $tmp."<!-- start_url_goc:"._replace_xml_special_char($v_url).":end_url_goc -->";
                
                if ($v_video_player_loc['videoloc'] != '') { 
					$videoArr = explode(',', $v_video_player_loc['videoloc']);
                    $video_i = 0;
                    foreach ($videoArr as $videoUrl) {
                        $video_i++;
                        $videoUrl = str_replace('&', '', $videoUrl);
                        $videoUrl = (strpos($videoUrl, 'http://')) ? $videoUrl : $v_root_without_slash.$videoUrl;
                        if (strpos($videoUrl, 'advertising.flv')) {
                            continue;
                        }
                        
                        $tmp = $tmp."<video:video>\n";
						$tmp = $tmp."<video:content_loc>".$videoUrl."</video:content_loc>\n";
						$tmp = $tmp."<video:player_loc allow_embed=\"yes\" autoplay=\"ap=1\"><!-- URL_VIDEO_PLAYER -->?file=".$videoUrl."</video:player_loc>\n";
                        $tmp = $tmp."<video:thumbnail_loc>".$v_thumbnail_loc."</video:thumbnail_loc>\n";
                        $tmp = $tmp."<video:title>".$v_title.(($video_i>1) ? ' (video '.$video_i.')' : '')."</video:title>\n";
                        $tmp = $tmp."<video:description>".$v_description.(($video_i>1) ? ' (video '.$video_i.')' : '')."</video:description>\n";
						$tmp = $tmp."<video:category>".$v_categoryName."</video:category>\n";
                        $duration = getDurationByVideo($videoUrl);
                        $tmp = $tmp."<video:duration>{$duration}</video:duration>\n";
                        $tmp = $tmp."<video:publication_date>$v_begin_time</video:publication_date>\n";
                        $tmp = $tmp."<video:tag>".$news_keywords."</video:tag>\n";
                        $tmp = $tmp."<video:uploader info=\"http://www.24h.com.vn/\">Tin tức 24h</video:uploader>\n";
                        $tmp = $tmp."<video:live>no</video:live>\n";
                        $tmp = $tmp."</video:video>\n";
                    }
				}
				
				$tmp = $tmp."</url>\n";
				$v_html[] = $tmp;
			}
		}	
	}
	return $v_html;
}
*/

/*
* phuonghv add 06/11/2015
* Tao sitemap video theo tháng cho tất cả các bài video
* @param 
*/
function gen_video_sitemap_for_all($p_is_gen_current_month, $v_changefreg, $v_priority, $sitename = "24h.com.vn", $sitelang="vi"){
	global $v_root, $v_root_without_slash, $default_menu_changefreq, $default_article_changefreq, $default_menu_priority, $default_article_priority;
    global  $sitemapindex, $v_begin, $v_end;
	$urlHelper = new UrlHelper();$urlHelper->getInstance();
	if ($p_priority==""){
		$p_priority=$default_article_priority;
	}
	if ($p_priority!=$default_article_priority){
		$arr_all_priority=explode(";", $p_priority);
	}
	if ($p_changefreq==""){
		$p_changefreq=$default_article_changefreq;
	}
	if ($p_changefreq!=$default_article_changefreq){
		$arr_all_changefreg=explode(";", $p_changefreq);
	}
    // lấy khoảng thời gian
    $v_nam_ket_thuc = _get_module_config('cau_hinh_dung_chung','v_nam_ket_thuc_tao_thoi_gian_lay_du_lieu_sitemap');
    $v_ngay_hien_tai =  date('j',strtotime(date('Y-m-d')));
    // có chạy sitemap article tháng cũ
	$v_co_chay_sitemap_article_thang_cu =  _get_module_config('sitemap','v_co_chay_sitemap_video_thang_cu');
    // Nếu cấu hình không chạy cho tháng cũ. và ngày hiện tại khong phải mùng 1. thì chỉ chạy dữ liệu cho tháng đó
    $p_is_gen_current_month = (!$v_co_chay_sitemap_article_thang_cu && $v_ngay_hien_tai != 1) ? 1 : 0;
    $v_arr_khoang_thoi_gian = fe_lay_khoang_thoi_gian_theo_thang($p_chi_lay_thang_hien_tai = $p_is_gen_current_month, $p_nam_ket_thuc=$v_nam_ket_thuc);    
    // cấu hình số tháng cần chạy lại sitemap
    $v_so_thang_chay_sitemap_article =  _get_module_config('sitemap','v_so_thang_chay_sitemap_article');
    
	$v_so_thang_chay_sitemap_article =  $v_ngay_hien_tai == 1 ? $v_so_thang_chay_sitemap_article++ : $v_so_thang_chay_sitemap_article;
    // lấy số bản ghi phân trang cần tạo sitemap
	$v_so_ban_ghi_toi_da =  _get_module_config('sitemap','v_so_ban_ghi_tao_sitemap_video'); 
    $v_https_for_sitemap = _get_module_config('sitemap', 'v_create_sitemap_protocol_by_type');
    // lấy mảng dữ liệu redirect
    $v_arr_redirect = array();
    $v_key = 'data_danh_sach_link_redirect_ban_web';
    $v_arr_redirect = fe_read_key_and_decode_from_file($v_key);
    if (!check_array($v_arr_redirect)) {
        $v_arr_redirect = fe_read_key_and_decode($v_key, _CACHE_TABLE);
    }
	for($t=0; $c=count($v_arr_khoang_thoi_gian), $t<$c; $t++) {
        // Nếu không chạy cho các tháng cũ. Thì chỉ chạy số sáng được cấu hình
        if(!$v_co_chay_sitemap_article_thang_cu && ($t+1) > $v_so_thang_chay_sitemap_article){
            break;
        }
        $v_ngay_bat_dau = $v_arr_khoang_thoi_gian[$t]['c_from_date'];
        $v_ngay_ket_thuc = $v_arr_khoang_thoi_gian[$t]['c_to_date'];
		$j = 0;
		// lấy số bản ghi được cấu hình								
        $v_page = 1;
		$v_number_for_page = $v_so_ban_ghi_toi_da;
		$v_loop = true;
		while ($v_loop){
            $v_start_time = microtime(true);
            Gnud_Db_close('read');
            $arr_result = fe_bai_video_theo_khoang_thoi_gian_xuat_ban($v_ngay_bat_dau, $v_ngay_ket_thuc, $v_page, $v_number_for_page); 
            // Gen thành công sitemap article
			$v_time = lay_thoi_gian_khoang_cach($v_start_time);
			$v_content = date("Y-m-d H:i:s")." Doan 1: Chay SP lay bai viet theo khoang thoi gian. Thoi gian chay:".$v_time."s \n";
			fw24h_write_log($v_content, WEB_ROOT.'/logs/logs_gen_site_map_video.log');
			$v_start_time = microtime(true);
            
            $n = sizeof($arr_result);
            if(check_array($arr_result)) {
                // xử lý bài xuất bản trên nhiều chuyên mục
                array_unique_key($arr_result, 'ID');
                $v_html = array();
                foreach($arr_result as $v_video)
                {
                    // Không hiển thị bài PR trên sitemap
                    if(_is_bai_pr($v_video)){
                        continue;
                    }
                    // Nếu bài viết nằm trong danh sách redirect thì không hiển thị sitemap
                    if (_link_redirect_khong_dua_vao_sitemap($v_news,$v_arr_redirect)) {
                        continue;
                    }
                    $row_cate = fe_chuyen_muc_theo_id($v_video['CategoryID']);
                    $v_url=  get_url_origin_of_news($v_video, $row_cate); 
                    $v_begin_time= dateTimeFormat($v_video["PublishedDate2"]);
                    $v_keyword = $v_video["url"];
                    $v_title =  str_replace('&', '&amp;', $v_video["Title"]);
                    if (is_null($v_video['SummaryImg']) || $v_video['SummaryImg'] == '') {
                        $v_video['SummaryImg'] = $v_video['SummaryImg_chu_nhat'];
                    }
                    $v_thumbnail_loc =  (strpos($v_video["SummaryImg"], 'http://') === false && strpos($v_video["SummaryImg"], 'https://') === false) ? $v_root_without_slash.$v_video["SummaryImg"] : $v_video["SummaryImg"];
                    $v_description =  str_replace('&', '&amp;', $v_video["Summary"]);
                    $v_video_player_loc = get_video_player_loc ($v_video);
                    $news_keywords = $v_video["keywords"];
                    $v_categoryName = $v_video["CategoryName"];

                    if ($p_priority!=$default_article_priority){
                        $v_priority=get_priority($arr_all_priority,$v_url,2) ;
                    }else{
                        $v_priority=$default_article_priority;
                    }

                    if ($p_changefreq!=$default_article_changefreq){
                        $v_changefreq=get_changefreq($arr_all_changefreg,$v_url, 2) ;
                    }else{
                        $v_changefreq=$default_article_changefreq;
                    }	

                    if (!is_null($v_url) && $v_url != '' && $v_url != str_replace(array('http://','https://'), '', $v_url)){
                        $v_thumbnail_loc = _replace_xml_special_char($v_thumbnail_loc);
                        // begin:tach_sitemap_tags_image_video_theo_thang
                        // phuonghv add 17/11/2015 : xu ly thay the domain static & domain cdn
                        $v_thumbnail_loc = replace_domain_static_images_and_domain_cdn_images($v_thumbnail_loc);
                        // end: tach_sitemap_tags_image_video_theo_thang

                        $v_title = _replace_xml_special_char($v_title);
                        $v_description = _replace_xml_special_char($v_description);
                        $v_categoryName = _replace_xml_special_char($v_categoryName);
                        $news_keywords = str_replace('&', '&amp;', $news_keywords);

                        $tmp = "<url>\n";
                        $tmp = $tmp."<loc>"._replace_xml_special_char($v_url)."</loc>\n";
                        $tmp = $tmp."<!-- start_url_goc:"._replace_xml_special_char($v_url).":end_url_goc -->";

                        if ($v_video_player_loc['videoloc'] != '') { 
                            $videoArr = explode(',', $v_video_player_loc['videoloc']);
                            $video_i = 0;
                            foreach ($videoArr as $videoUrl) {
                                $video_i++;
                                $videoUrl = str_replace('&', '', $videoUrl);
                                $videoUrl = (strpos($videoUrl, 'http://') !== false || strpos($videoUrl, 'https://') !== false) ? $videoUrl : $v_root_without_slash.$videoUrl;
                                if (strpos($videoUrl, 'advertising.flv')) {
                                    continue;
                                }

                                $tmp = $tmp."<video:video>\n";
                                $tmp = $tmp."<video:content_loc>".$videoUrl."</video:content_loc>\n";
                                $tmp = $tmp."<video:player_loc allow_embed=\"yes\" autoplay=\"ap=1\"><!-- URL_VIDEO_PLAYER -->?file=".$videoUrl."</video:player_loc>\n";
                                $tmp = $tmp."<video:thumbnail_loc>".$v_thumbnail_loc."</video:thumbnail_loc>\n";
                                $tmp = $tmp."<video:title>".$v_title.(($video_i>1) ? ' (video '.$video_i.')' : '')."</video:title>\n";
                                $tmp = $tmp."<video:description>".$v_description.(($video_i>1) ? ' (video '.$video_i.')' : '')."</video:description>\n";
                                $tmp = $tmp."<video:category>".$v_categoryName."</video:category>\n";
                                $duration = getDurationByVideo($videoUrl);
                                $tmp = $tmp."<video:duration>{$duration}</video:duration>\n";
                                $tmp = $tmp."<video:publication_date>$v_begin_time</video:publication_date>\n";
                                $tmp = $tmp."<video:tag>".$news_keywords."</video:tag>\n";
                                $tmp = $tmp."<video:uploader info=\"http://www.24h.com.vn/\">Tin tức 24h</video:uploader>\n";
                                $tmp = $tmp."<video:live>no</video:live>\n";
                                $tmp = $tmp."</video:video>\n";
                            }
                        }

                        $tmp = $tmp."</url>\n";
                        $v_html[] = $tmp;
                    }
                }	        
                // Ghi file
                if (check_array($v_html)) {
                    $v_ngay_bat_dau = date('Y-m-d',strtotime($v_ngay_bat_dau));
					$v_arr_ngay = explode('-', $v_ngay_bat_dau);
					$v_nam_thang = $v_arr_ngay[0].'-'.$v_arr_ngay[1];
				    // Tạo sitemap với link http
                    if($v_https_for_sitemap == 1 || $v_https_for_sitemap == 3){
                        $v_sitemap_file = "sitemap-video-".$v_nam_thang.'-'.$j;
                        // include sitemap theo tháng vào sitemap-video
	  
                        write2db($v_sitemap_file, $v_html, $v_begin, $v_end, 'sitemap-video', false, $p_is_gen_current_month); 
                    }
                    // Tạo sitemap với link https
                    if($v_https_for_sitemap == 2 || $v_https_for_sitemap == 3){
                        $v_html =  _replace_domain_from_http_to_https($v_html);
                        $v_sitemap_filehttps = "sitemap-video-".$v_nam_thang.'-'.$j.'-https';
                        // include sitemap theo tháng vào sitemap-video
                        write2db($v_sitemap_filehttps, $v_html, $v_begin, $v_end, 'sitemap-video-https', false, $p_is_gen_current_month);
                    }
                    $j++;	
				
                }
                                // Gen thành công sitemap article
                $v_time = lay_thoi_gian_khoang_cach($v_start_time);
                $v_content = date("Y-m-d H:i:s")." Doan 2:  Tao xml sitemap Thoi gian chay:".$v_time."s \n";
                fw24h_write_log($v_content, WEB_ROOT.'/logs/logs_gen_site_map_video.log');
            }
            ++$v_page;
			if ($n < $v_number_for_page){
				$v_loop = false;
			}
        }
				  

	}
    // cap nhat thoi gian gen sitemap tag vào sitemap index  
    if($v_https_for_sitemap == 1 || $v_https_for_sitemap == 3){	
		// cap nhat thoi gian gen sitemap tag vào sitemap index      
		$v_arr_file[] = "sitemap-video.xml";    
		//updateIndex($v_arr_file, $sitemapindex);
	}
	// Xử dụng gắn link https
    if($v_https_for_sitemap == 2 || $v_https_for_sitemap == 3){
        $v_arr_filettps[] = "sitemap-video-https.xml";
        $sitemapindexhttps = "sitemap-index-https";
        //updateIndex($v_arr_filettps, $sitemapindexhttps);
    }
}
//end: tao_sitemap_tag_video_image_theo_thang      
   
function get_video_player_loc ($n) {
	$video_code = '';
	$arrRS = array();
	if (strpos($n['VideoCode'], 'flashWrite') !== false) {
		$video_code = $n['VideoCode'];
	} 
	elseif (strpos($n['Body'], 'flashWrite') !== false) {
		$video_code = $n['Body'];
	} 
	if ($video_code == '')  return $arrRS;
	preg_match( '#file=([^\"]*)",(\d*),(\d*)#', $video_code, $VIDEO_FILE);
	if ($VIDEO_FILE[1] != '') $arrRS['videoloc'] = $VIDEO_FILE[1];
	preg_match( '#flashWrite\(\"([^\"]*)\"#', $video_code, $VIDEO_PLAYER);
	if ($VIDEO_PLAYER[1] != '') $arrRS['playerloc'] = $VIDEO_PLAYER[1];
	return $arrRS;
}

function getDurationByVideo ($video_loc) {
  	global $v_dir;
	$videos = explode (',', $video_loc);
	if (!sizeof($videos)) return 0;
	$sec = 0;
	foreach ($videos as $v_file) {
		$v_file = trim(str_replace('&', '', $v_file));
		$f = $v_dir.$v_file;
		// echo $f."\n";
		ob_start();
		passthru("/usr/local/bin/ffmpeg -i \"{$f}\" 2>&1");  //path to your ffmpeg.exe
		$duration = ob_get_contents();
		ob_end_clean();
		$search='/Duration: (.*?)[.]/';
		$duration=preg_match($search, $duration, $matches, PREG_OFFSET_CAPTURE);
		$duration = $matches[1][0];
		list($hours, $mins, $secs) = explode('[:]', $duration);
		$duration = intval($hours)*3600 + intval($mins)*60 + $secs;
		$sec += $duration;
	}
	return $sec;
}

/**
 * Dinh dang hien thi ngay thang
 *
 * @param datetime $v_date Ngay thang dang Y-m-d H:i:s
 * @return string
 */
function dateTimeFormat($v_date)
{
	$datetime = strtotime($v_date);
	$str = date("Y-m-d", $datetime).'T'.date('H:i', $datetime).'+07:00';
	return $str;
}


//begin: tao_sitemap_tag_video_image_theo_thang
// phuonghv comment 06/11/2015 
// Tao sitemap video cua 1 ngay
/* 
function gen_image_sitemap_for_daily($date, $v_changefreg, $v_priority, $sitename = "24h.com.vn", $sitelang="vi"){
	global $v_root, $v_root_without_slash, $default_menu_changefreq, $default_article_changefreq, $default_menu_priority, $default_article_priority;
	$urlHelper = new UrlHelper();$urlHelper->getInstance();
	if ($p_priority==""){
		$p_priority=$default_article_priority;
	}
	if ($p_priority!=$default_article_priority){
		$arr_all_priority=explode(";", $p_priority);
	}
	if ($p_changefreq==""){
		$p_changefreq=$default_article_changefreq;
	}
	if ($p_changefreq!=$default_article_changefreq){
		$arr_all_changefreg=explode(";", $p_changefreq);
	}
	
	$tmpdate = explode("-", $date);
	$before = date("Y-m-d", mktime(0, 0, 0, $tmpdate[1]  , $tmpdate[2]-7, $tmpdate[0]));
	if(!$date) die("Can cung cap ngay thang de tao sitemap co dang nhu &date=YYYY-MM-DD (2010-04-27)");
	$clause = " AND news.DateEdited >= '".$before."' AND news.DateEdited <= '".$date."' ";
	$v_sql_string = "Select distinct news.ID, url, Body, SummaryImg, SummaryImg_chu_nhat
						From news, newscategory, category where news.ID = newscategory.NewsID AND newscategory.CategoryID = category.ID AND newscategory.Status > 0 ".$clause." GROUP BY news.ID Order by DateEdited desc LIMIT 18000";
						
	$arr_all_item = Gnud_Db_read_query($v_sql_string);
	$v_count = sizeof($arr_all_item);	
	
	if(!$v_count) die("Khong co du lieu de tao sitemap");
	
	$datestr= Date("Y-m-d");
	$v_html = array();
	if ($v_count > 0){
		for( $i=0; $i<$v_count; ++$i)
		{			
			$v_url=trim($arr_all_item[$i]["url"]);
			
			$v_arr_image = extractImagesFromString($arr_all_item[$i]['Body']);
			
            if ($p_priority!=$default_article_priority){
                $v_priority=get_priority($arr_all_priority,$v_url,2) ;
            }else{
                $v_priority=$default_article_priority;
            }

            if ($p_changefreq!=$default_article_changefreq){
                $v_changefreq=get_changefreq($arr_all_changefreg,$v_url, 2) ;
            }else{
                $v_changefreq=$default_article_changefreq;
            }	
			
			$s = sizeof($v_arr_image);
			if (!is_null($v_url) && $v_url != '' && $v_url != str_replace('http://', '', $v_url) && $s > 0){
				$tmp = "<url>\n";
				$tmp = $tmp."<loc>"._replace_xml_special_char($v_url)."</loc>\n";
				$tmp = $tmp."<!-- start_url_goc:"._replace_xml_special_char($v_url).":end_url_goc -->";
				
				for ($j = 0; $j < $s; ++$j) {
					$v_img = $v_arr_image[$j];
					if (is_null($v_img) || $v_img == ''){
						continue;
					}
					$v_img = str_replace('http://mst.24h.com.vn', 'http://www.24h.com.vn', $v_img);
					$v_img = str_replace('https://mst.24h.com.vn', 'http://www.24h.com.vn', $v_img);
					$v_img = strpos($v_img, 'http://') === false ? str_replace('upload/', 'http://www.24h.com.vn/upload/', $v_img) : $v_img;
					$v_img = _replace_xml_special_char($v_img);
					$tmp = $tmp."<image:image>\n";
					$tmp = $tmp."<image:loc>".$v_img."</image:loc>\n";
					$tmp = $tmp."</image:image>\n";
				}
				
				$tmp = $tmp."</url>\n";
				$v_html[] = $tmp;
			}
		}	
	}
	return $v_html;
}
*/

/*
* Ham tao du lieu sitemap cho toan bo anh
*/
function gen_image_sitemap_for_all($p_is_gen_current_month, $v_changefreg, $v_priority, $sitename = "24h.com.vn", $sitelang="vi"){
	global $v_root, $v_root_without_slash, $default_menu_changefreq, $default_article_changefreq, $default_menu_priority, $default_article_priority;
    global $v_begin, $v_end, $sitemapindex;
    
	$urlHelper = new UrlHelper();$urlHelper->getInstance();
	if ($p_priority==""){
		$p_priority=$default_article_priority;
	}
	if ($p_priority!=$default_article_priority){
		$arr_all_priority=explode(";", $p_priority);
	}
	if ($p_changefreq==""){
		$p_changefreq=$default_article_changefreq;
	}
	if ($p_changefreq!=$default_article_changefreq){
		$arr_all_changefreg=explode(";", $p_changefreq);
	}
	// lấy khoảng thời gian
    $v_nam_ket_thuc = _get_module_config('cau_hinh_dung_chung','v_nam_ket_thuc_tao_thoi_gian_lay_du_lieu_sitemap');
    $v_ngay_hien_tai =  date('j',strtotime(date('Y-m-d')));
    // có chạy sitemap article tháng cũ
	$v_co_chay_sitemap_article_thang_cu =  _get_module_config('sitemap','v_co_chay_sitemap_images_thang_cu');
    // Nếu cấu hình không chạy cho tháng cũ. và ngày hiện tại khong phải mùng 1. thì chỉ chạy dữ liệu cho tháng đó
    $p_is_gen_current_month = (!$v_co_chay_sitemap_article_thang_cu && $v_ngay_hien_tai != 1) ? 1 : 0;
    $v_arr_khoang_thoi_gian = fe_lay_khoang_thoi_gian_theo_thang($p_chi_lay_thang_hien_tai = $p_is_gen_current_month, $p_nam_ket_thuc=$v_nam_ket_thuc);    
    // cấu hình số tháng cần chạy lại sitemap
    $v_so_thang_chay_sitemap_article =  _get_module_config('sitemap','v_so_thang_chay_sitemap_images');
	$v_so_thang_chay_sitemap_article =  $v_ngay_hien_tai == 1 ? $v_so_thang_chay_sitemap_article++ : $v_so_thang_chay_sitemap_article;
    // lấy số bản ghi phân trang cần tạo sitemap
	$v_so_ban_ghi_toi_da =  _get_module_config('sitemap','v_so_ban_ghi_tao_sitemap_images');           
    // Cấu hình có tạo sitemap riêng https
    $v_https_for_sitemap = _get_module_config('sitemap', 'v_create_sitemap_protocol_by_type');
    // lấy mảng dữ liệu redirect
    $v_arr_redirect = array();
    $v_key = 'data_danh_sach_link_redirect_ban_web';
    $v_arr_redirect = fe_read_key_and_decode_from_file($v_key);
    if (!check_array($v_arr_redirect)) {
        $v_arr_redirect = fe_read_key_and_decode($v_key, _CACHE_TABLE);
    }
    
    for($t=0; $c=count($v_arr_khoang_thoi_gian), $t<$c; $t++) {
        $v_ngay_bat_dau = $v_arr_khoang_thoi_gian[$t]['c_from_date'];
        $v_ngay_ket_thuc = $v_arr_khoang_thoi_gian[$t]['c_to_date'];
        $v_type = $v_arr_khoang_thoi_gian[$t]['c_type'];         
		$i = 0;
		// lấy số bản ghi được cấu hình
        $v_page = 1;
		$v_number_for_page = $v_so_ban_ghi_toi_da;
		$v_loop = true;
		while ($v_loop){
            $v_start_time = microtime(true);
            // Nếu không chạy cho các tháng cũ. Thì chỉ chạy số sáng được cấu hình
            if(!$v_co_chay_sitemap_article_thang_cu && ($t+1) > $v_so_thang_chay_sitemap_article){
                $v_loop = false;
                break;
            }
            Gnud_Db_close('read'); 
            $arr_result = fe_bai_viet_theo_khoang_thoi_gian_xuat_ban($v_ngay_bat_dau, $v_ngay_ket_thuc, $v_page, $v_number_for_page);  
            $n = sizeof($arr_result);
            // Gen thành công sitemap article
			$v_time = lay_thoi_gian_khoang_cach($v_start_time);
			$v_content = date("Y-m-d H:i:s")." Doan 1: Chay SP lay bai viet theo khoang thoi gian. Thoi gian chay:".$v_time."s \n";
			fw24h_write_log($v_content, WEB_ROOT.'/logs/logs_gen_site_map_images.log');
			$v_start_time = microtime(true);
            if(check_array($arr_result)) {
                // xử lý bài xuất bản trên nhiều chuyên mục
                array_unique_key($arr_result, 'ID');
				$v_html = array();
				foreach($arr_result as $v_news)
				{			
                    // Không hiển thị bài PR trên sitemap
                    if(_is_bai_pr($v_news)){
                        continue;
                    }
                    // Nếu bài viết nằm trong danh sách redirect thì không hiển thị sitemap
                    if (_link_redirect_khong_dua_vao_sitemap($v_news,$v_arr_redirect)) {
                        continue;
                    }
					$row_cate = fe_chuyen_muc_theo_id($v_news['CategoryID']);
                    $v_url=  get_url_origin_of_news($v_news, $row_cate);
                    
					$v_arr_image = extractImagesFromString($v_news['Body']);
					
					if ($p_priority!=$default_article_priority){
						$v_priority=get_priority($arr_all_priority,$v_url,2) ;
					}else{
						$v_priority=$default_article_priority;
					}

					if ($p_changefreq!=$default_article_changefreq){
						$v_changefreq=get_changefreq($arr_all_changefreg,$v_url, 2) ;
					}else{
						$v_changefreq=$default_article_changefreq;
					}	
					
					$s = sizeof($v_arr_image);
					if ($v_url == '' || is_null($v_url) || strpos($v_url,'//-') !== false) {
						$v_url = gen_news_canonical_url($v_news);
					}
					if (!is_null($v_url) && $v_url != '' && $v_url != str_replace(array('http://','https://'), '', $v_url) && $s > 0){
						$tmp = "<url>\n";
						$tmp = $tmp."<loc>"._replace_xml_special_char($v_url)."</loc>\n";
						$tmp = $tmp."<!-- start_url_goc:"._replace_xml_special_char($v_url).":end_url_goc -->";
						
						for ($j = 0; $j < $s; ++$j) {
							$v_img = $v_arr_image[$j];
							if (is_null($v_img) || $v_img == '' || strpos($v_img,'data:image/jpeg;base64') !== false){
								continue;
							}
							// begin:tach_sitemap_tags_image_video_theo_thang
							/*
							$v_img = str_replace('http://mst.24h.com.vn', 'http://www.24h.com.vn', $v_img);
							$v_img = str_replace('https://mst.24h.com.vn', 'http://www.24h.com.vn', $v_img);
							$v_img = strpos($v_img, 'http://') === false ? str_replace('upload/', 'http://www.24h.com.vn/upload/', $v_img) : $v_img;
							*/
							// end: tach_sitemap_tags_image_video_theo_thang                    
							$v_img = _replace_xml_special_char($v_img);                      
							// begin:tach_sitemap_tags_image_video_theo_thang
							// phuonghv add 17/11/2015 : xu ly thay the domain static & domain cdn
							$v_img = replace_domain_static_images_and_domain_cdn_images($v_img);
							// end: tach_sitemap_tags_image_video_theo_thang


							/* begin 07/12/2016 Tytv - ko_lay_link_anh_vao_sitemap_cac_domain_khong_thuoc_24h */
							$v_link_anh_thuoc_domain_cho_phep = _kiem_tra_link_anh_thuoc_domain_cho_phep($v_img);
							if ($v_link_anh_thuoc_domain_cho_phep) {
							/* End 07/12/2016 Tytv - ko_lay_link_anh_vao_sitemap_cac_domain_khong_thuoc_24h */
							$tmp = $tmp."<image:image>\n";
							$tmp = $tmp."<image:loc>".$v_img."</image:loc>\n";
							$tmp = $tmp."</image:image>\n";
							}
						}
						$tmp = $tmp."</url>\n";
						$v_html[] = $tmp;
					}
				}
				// Ghi file
				if (check_array($v_html)) {
					//Begin 10-03-2016 : Thangnb toi_uu_sitemap
					$v_ngay_bat_dau = date('Y-m-d',strtotime($v_ngay_bat_dau));
					//End 10-03-2016 : Thangnb toi_uu_sitemap
					$v_arr_ngay = explode('-', $v_ngay_bat_dau);
					$v_nam_thang = $v_arr_ngay[0].'-'.$v_arr_ngay[1];
                    // Tạo sitemap với link http
                    if($v_https_for_sitemap == 1 || $v_https_for_sitemap == 3){
                        $v_sitemap_file = "sitemap-image-".$v_nam_thang.'-'.$i;
                        // include sitemap theo tháng vào sitemap-image
                        write2db($v_sitemap_file, $v_html, $v_begin, $v_end, 'sitemap-image', false, $p_is_gen_current_month);  
                    }
					// Tạo sitemap với link https
                    if($v_https_for_sitemap == 2 || $v_https_for_sitemap == 3){
                        $v_html = _replace_domain_from_http_to_https($v_html);
                        $v_sitemap_file = "sitemap-image-".$v_nam_thang.'-'.$i.'-https';
                        // include sitemap theo tháng vào sitemap-image
                        write2db($v_sitemap_file, $v_html, $v_begin, $v_end, 'sitemap-image-https', false, $p_is_gen_current_month);  
                    }
					$i++;
				}
                // Gen thành công sitemap article
                $v_time = lay_thoi_gian_khoang_cach($v_start_time);
                $v_content = date("Y-m-d H:i:s")." Doan 2:  Tao xml sitemap Thoi gian chay:".$v_time."s \n";
                fw24h_write_log($v_content, WEB_ROOT.'/logs/logs_gen_site_map_images.log');
            }
            ++$v_page;
			if ($n < $v_number_for_page){
				$v_loop = false;
			}
										  

        }
	}	
    // Tạo sitemap với link http
    if($v_https_for_sitemap == 1 || $v_https_for_sitemap == 3){
        // cap nhat thoi gian gen sitemap image vào sitemap index      
        $v_arr_file[] = "sitemap-image.xml";    
        //updateIndex($v_arr_file, $sitemapindex);
    }
    // Tạo sitemap với link https
    if($v_https_for_sitemap == 2 || $v_https_for_sitemap == 3){
        $sitemapindexhttps = "sitemap-index-https"; // ten file khong co .xml ma he thong se tu them vao sau
        $v_arr_filehttps[] = "sitemap-image-https.xml";    
        //updateIndex($v_arr_filehttps, $sitemapindexhttps);    
    }
}
//end: tao_sitemap_tag_video_image_theo_thang
 
function extractImagesFromString($p_body)
{
	$images = array();
	$v_arr_kieu_file_anh = array('jpg', 'png', 'gif'); // các đuôi file ảnh được chập nhận
	// Image
	preg_match_all('#src\s*=\s*"([^\"]*)"#ism', $p_body, $match);
	if ($match) {
		for ($i=0, $n=count($match[1]); $i<$n; $i++) {
			// kiểm tra đuôi file
			$v_arr_tmp = explode('.', $match[1][$i]);
			if (in_array(strtolower($v_arr_tmp[sizeof($v_arr_tmp) - 1]), $v_arr_kieu_file_anh)) {
				$images[] = $match[1][$i];
			}
		}
	}
	return $images;
}

// 20150511 HaiLT add sitemap tag
// Tao sitemap tags cua 1 ngay
function gen_tag_sitemap_for_daily($p_is_gen_current_month, $v_changefreg, $v_priority, $sitename = "24h.com.vn", $sitelang="vi"){
    global $sitemapindex,$v_begin, $v_end;
	$urlHelper = new UrlHelper();$urlHelper->getInstance();    
	//begin: tao_sitemap_tag_video_image_theo_thang
	$v_nam_ket_thuc = _get_module_config('cau_hinh_dung_chung','v_nam_ket_thuc_tao_thoi_gian_lay_du_lieu_sitemap');
    $v_arr_khoang_thoi_gian = fe_lay_khoang_thoi_gian_theo_thang($p_chi_lay_thang_hien_tai =$p_is_gen_current_month, $p_nam_ket_thuc=$v_nam_ket_thuc);    
	
    $v_so_ban_ghi_toi_da = _get_module_config('cau_hinh_dung_chung','v_tong_so_ban_ghi_can_tao_sitemap');
    $v_number_items =  _get_module_config('cau_hinh_dung_chung','v_so_ban_ghi_phan_trang_tao_sitemap');
    $v_so_trang = ceil($v_so_ban_ghi_toi_da/$v_number_items);    
    for($t=0; $c=count($v_arr_khoang_thoi_gian), $t<$c; $t++) {
        $v_ngay_bat_dau = $v_arr_khoang_thoi_gian[$t]['c_from_date'];
        $v_ngay_ket_thuc = $v_arr_khoang_thoi_gian[$t]['c_to_date'];
        $v_type = $v_arr_khoang_thoi_gian[$t]['c_type'];
        Gnud_Db_close('read');       
            
        $rs_tags = array();
        for($p=1; $p<=$v_so_trang; $p++) {
            $arr_result = fe_danh_sach_tag_moi_nhat($p, $v_number_items, $v_ngay_bat_dau, $v_ngay_ket_thuc);       
            if(check_array($arr_result)) {                               
                $rs_tags = array_merge($rs_tags, $arr_result);
            } else {
                break; // thoat khi gap cau lenh khong co du lieu.
            }
        }        
        if (!check_array($rs_tags)) {
            continue; // sang trang tiếp theo nếu ko có dữ liệu
        }        
        $v_html = array();
        foreach ($rs_tags as $row_tag){            
            if (!check_array($row_tag)) {
                continue; // sang tag tiếp theo nếu ko có dữ liệu
            }            
            $v_tag_slug = $row_tag['slug'];
            // check nếu có link về trang sự kiện/chuyên mục thì bỏ qua, sang tag khác
            $v_rowTagByKeyword =  fe_tag_url_theo_keyword(_utf8_to_ascii(str_replace('-', ' ', $v_tag_slug)));
            if (check_array($v_rowTagByKeyword) && !is_null($v_rowTagByKeyword['c_url']) && $v_rowTagByKeyword['c_url'] != '') {
                continue;
            }            
            $url = ($row_tag['url']!='') ? $row_tag['url'] : $urlHelper->url_tag(array('slug'=>$v_tag_slug)); // xác định url tag
            $v_url = trim($url);
            $v_url = (strpos($v_url, 'http://') === false && strpos($v_url, 'https://') === false) ? BASE_URL_FOR_PUBLIC.$v_url : $v_url;
            $v_url = str_replace(BASE_URL_FOR_PUBLIC.'/', BASE_URL_FOR_PUBLIC, $v_url);
            $v_date_edited = $row_tag['editedDate'];
            $v_date_edited = (is_null($v_date_edited) || $v_date_edited == '' || $v_date_edited == '0000-00-00 00:00:00') ? date('Y-m-d H:i:s') : $v_date_edited;
            if (!is_null($v_url) && $v_url != '' && $v_url != str_replace(array('http://','https://'), '', $v_url)){
                $tmp = "<url>\n";
                $tmp .= "<loc>"._replace_xml_special_char($v_url)."</loc>\n";
                $tmp .= "<!-- start_url_goc:"._replace_xml_special_char($v_url).":end_url_goc -->";
                $tmp .= "<lastmod>"._replace_xml_special_char(date('Y-m-d', strtotime($v_date_edited)).'T'.date('H:i:sP', strtotime($v_date_edited)))."</lastmod>\n";
                $tmp .= "<changefreq>".$v_changefreg."</changefreq>\n";
                $tmp .= "<priority>".$v_priority."</priority>\n";
                $tmp .= "</url>\n";
                $v_html[] = $tmp;
            }
        }
        unset($rs_tags);
        // Ghi file
		if (check_array($v_html)) {
			//Begin 10-03-2016 : Thangnb toi_uu_sitemap
			$v_ngay_bat_dau = date('Y-m-d',strtotime($v_ngay_bat_dau));
			//End 10-03-2016 : Thangnb toi_uu_sitemap
            $v_arr_ngay = explode('-', $v_ngay_bat_dau);
            $v_nam_thang = $v_type=='M'? $v_arr_ngay[0].'-'.$v_arr_ngay[1]:$v_arr_ngay[0];
			
			$v_sitemap_file = "sitemap-tags-".$v_nam_thang;
            // include sitemap theo tháng vào sitemap-tags
			//Begin 10-03-2016 : Thangnb toi_uu_sitemap
			/* Begin: Tytv - 12/09/2017 - off_tag_sitemap */
			//write2db($v_sitemap_file, $v_html, $v_begin, $v_end, 'sitemap-tags', false, $p_is_gen_current_month);       
			/* End: Tytv - 12/09/2017 - off_tag_sitemap */
			//End 10-03-2016 : Thangnb toi_uu_sitemap            
		}
	}	
    // cap nhat thoi gian gen sitemap tag vào sitemap index      
	/* Begin: Tytv - 12/09/2017 - off_tag_sitemap */
    //$v_arr_file[] = "sitemap-tags.xml";    
	/* End: Tytv - 12/09/2017 - off_tag_sitemap */
    updateIndex($v_arr_file, $sitemapindex);
    //end: tao_sitemap_tag_video_image_theo_thang
}

function gen_sitemap_for_article_in_events_review($p_changefreq="",$p_priority="", $p_arr_news){
	array_unique_key($p_arr_news, 'ID');

	$v_count = sizeof($p_arr_news);	
	$v_now = Date("Y-m-d H:i:s");
	$v_html= array();
	
	if ($v_count > 0){
		for( $i=0; $i<$v_count; ++$i){	
			$v_id_news = intval($p_arr_news[$i]['ID']);
			if ($v_id_news <= 0){
				continue;
			}
			# lấy url news
			Gnud_Db_read_close();
			$v_tmp_sql = 'SELECT url FROM news WHERE ID = '.$v_id_news.' LIMIT 1';
			$v_tmp_url = Gnud_Db_read_query($v_tmp_sql);
			$v_url = trim($v_tmp_url[0]["url"]);
			// $time_news = (strtotime($p_arr_news[$i]["PublishedDate2"])>=strtotime($p_arr_news[$i]["DateEdited"]))?$p_arr_news[$i]["PublishedDate2"]:$p_arr_news[$i]["DateEdited"];
			$time_news = $v_now;
			$v_begin_time= date('Y-m-d', strtotime($time_news)).'T'.date('H:i:sP', strtotime($time_news)); 
				
			if (!is_null($v_url) && $v_url != '' && $v_url != str_replace(array('http://','https://'), '', $v_url)){
				$tmp = "<url>\n";
				$tmp = $tmp."<loc>"._replace_xml_special_char($v_url)."</loc>\n";
				$tmp = $tmp."<!-- start_url_goc:"._replace_xml_special_char($v_url).":end_url_goc -->";
				$tmp = $tmp."<lastmod>"._replace_xml_special_char($v_begin_time)."</lastmod>\n";
				$tmp = $tmp."<changefreq>"._replace_xml_special_char($p_changefreq)."</changefreq>\n";
				$tmp = $tmp."<priority>"._replace_xml_special_char($p_priority)."</priority>\n";
				$tmp = $tmp."</url>\n";
				$v_html[] = $tmp;
			}
		}
	}
	return $v_html;
}


//BEgin:  20151103 Anhpt1 add sitemap_profile
// Tao sitemap profile cua 1 ngay
function gen_profile_sitemap_for_daily($v_changefreg, $v_priority, $sitename = "24h.com.vn", $sitelang="vi"){
	$urlHelper = new UrlHelper();$urlHelper->getInstance();
	$page = 1;
	$number_items = 1000; // lấy 1000 profile mới nhất
	$v_html = array();
	$v_date_edited = date('Y-m-d H:i:s');
	$rs_tags_app = fe_danh_sach_profile_moi_nhat($page, $number_items);
	for ($i = 0, $s = sizeof($rs_tags_app); $i < $s; ++$i){
		$row_tag_app = $rs_tags_app[$i];
		if (!check_array($row_tag_app)) {
			continue; // sang profile tiếp theo nếu ko có dữ liệu
		}
        $v_tag_app_id 	=  $row_tag_app['pk_tag_app'];
        $v_arr_tag_app =  fe_box_top_profile($v_tag_app_id,0);
        $v_tag_slug 	=  $v_arr_tag_app[0]['c_slug'];
        $v_cat_id 		=  $v_arr_tag_app[0]['fk_category'];
		//Begin 19-05-2016 : Thangnb toi_uu_sitemap_seo
		$row_cat = fe_chuyen_muc_theo_id($v_cat_id);
		if ($row_cat['Activate'] < 1) {
			continue;
		}
		//End 19-05-2016 : Thangnb toi_uu_sitemap_seo
		if($v_tag_slug != ''){
			$url =$urlHelper->url_profile(array('ID'=>$v_tag_app_id, 'cID'=>$v_cat_id, 'slug'=>$v_tag_slug)); // xác định url tag
		}
		$v_url = trim($url);
		$v_url = (strpos($v_url, 'http://') === false && strpos($v_url, 'https://') === false) ? BASE_URL_FOR_PUBLIC.$v_url : $v_url;
		$v_url = str_replace(BASE_URL_FOR_PUBLIC.'/', BASE_URL_FOR_PUBLIC, $v_url);
		$v_date_edited = (is_null($v_date_edited) || $v_date_edited == '' || $v_date_edited == '0000-00-00 00:00:00') ? date('Y-m-d H:i:s') : $v_date_edited;
		if (!is_null($v_url) && $v_url != '' && $v_url != str_replace(array('http://','https://'), '', $v_url)){
			$tmp = "<url>\n";
			$tmp .= "<loc>"._replace_xml_special_char($v_url)."</loc>\n";
			$tmp .= "<!-- start_url_goc:"._replace_xml_special_char($v_url).":end_url_goc -->";
			$tmp .= "<lastmod>"._replace_xml_special_char(date('Y-m-d', strtotime($v_date_edited)).'T'.date('H:i:sP', strtotime($v_date_edited)))."</lastmod>\n";
			$tmp .= "<changefreq>".$v_changefreg."</changefreq>\n";
			$tmp .= "<priority>".$v_priority."</priority>\n";
			$tmp .= "</url>\n";
			$v_html[] = $tmp;
		}
	}
	
	return $v_html;
}
//End:  20151103 Anhpt1 add sitemap_profile

//begin: tach_sitemap_tags_image_video_theo_thang
/*
* hàm xử lý thay thế link ảnh theo domain static và domain cdn
* @param $p_url_image link ảnh
* @return string
*/
function replace_domain_static_images_and_domain_cdn_images($p_url_image) {
    if($p_url_image!='') {
        // xu ly theo domain static
        $p_url_image = replace_domain_static_images($p_url_image);
		//Begin 10-03-2016 : Thangnb toi_uu_sitemap
		if (strpos($p_url_image,'http://') === false && strpos($p_url_image,'https://') === false) {
			if (substr($p_url_image,0,1) == '/') {
				$p_url_image = rtrim(IMAGE_NEWS,'/').$p_url_image;
			} else {
				$p_url_image = IMAGE_NEWS.$p_url_image;
			}
		}
		//End 10-03-2016 : Thangnb toi_uu_sitemap
        // xu ly theo domain cdn                
        if (defined('USE_CDN') && in_array(USE_CDN, array(2,3))){ // USE_CDN== 2 cấu hình sử dụng doamin CDN cho tất cả tin bài, USE_CDN== 3 cấu hình sử dụng doamin CDN cho tất cả tin bài  +quảng cáo
            $p_url_image = str_replace(array(IMAGE_NEWS.'upload', IMAGE_NEWS.'/upload'), CDN_IMAGE_NEWS.'upload', $p_url_image);
		}        
    }
    return $p_url_image;
}
//end: tach_sitemap_tags_image_video_theo_thang

//Begin 13-05-2016 : Thangnb toi_uu_sitemap_seo
// Tao sitemap cho cac su kien cua 1 ngay
function gen_sitemap_event_for_daily($date, $p_changefreq,$p_priority, $v_begin, $v_end){
	global $v_root, $v_root_without_slash, $default_menu_changefreq, $default_article_changefreq, $default_menu_priority, $default_article_priority;
	$urlHelper = new UrlHelper();$urlHelper->getInstance();
	if ($p_priority==""){
		$p_priority=$default_menu_priority;
	}
	if ($p_priority!=$default_menu_priority){
		$arr_all_priority=explode(";", $p_priority);
	}
	if ($p_changefreq==""){
		$p_changefreq=$default_article_changefreq;
	}
	if ($p_changefreq!=$default_article_changefreq){
		$arr_all_changefreg=explode(";", $p_changefreq);
	}
	
 	if(!$date) die("Can cung cap ngay thang de tao sitemap co dang nhu &date=YYYY-MM-DD (2010-04-27)");
	
	$v_arr_danh_sach_id_su_kien = array();
	$v_sql = "CALL fe_danh_sach_su_kien_moi_nhat(1,1000,'$date','$date')";
	$v_arr_su_kien_moi = Gnud_Db_read_query($v_sql);
	
	//Begin 19-05-2016 : Thangnb toi_uu_sitemap_seo
	$v_arr_danh_sach_id_su_kien_check_trung = array();
	foreach ($v_arr_su_kien_moi as $v_su_kien_moi) {
		$v_arr_danh_sach_id_su_kien[] = array($v_su_kien_moi['ID'],$v_su_kien_moi['c_ngay_sua']);
		$v_arr_danh_sach_id_su_kien_check_trung[] = $v_su_kien_moi['ID'];
	}
	//pre($v_arr_danh_sach_id_su_kien);die;
	
	$tmpdate = explode("-", $date);
	$after = date("Y-m-d", mktime(0, 0, 0, $tmpdate[1]  , $tmpdate[2]+1, $tmpdate[0]));
    //begin: sua_doi_he_thong_sitemap_24h    
    //phuonghv edit ngày 20/10/2015
    $arr_all_item = fe_bai_viet_theo_khoang_thoi_gian_xuat_ban($date, $after, 1, 1000);
    array_unique_key($arr_all_item, 'ID');
	//Begin 13-05-2016 : Thangnb toi_uu_sitemap_seo
	//Lay nhung bai viet gan vao su kien
	if (check_array($arr_all_item)) {
    	$arr_all_item = php_multisort($arr_all_item, array(array('key' => 'DateEdited', 'sort' => 'desc'), array('key' => 'PublishedDate2', 'sort' => 'desc')));	
		foreach($arr_all_item as $key => $v_item_news) {
			if ($v_item_news['EventID'] > 0 && !in_array($v_item_news['EventID'],$v_arr_danh_sach_id_su_kien_check_trung)) {
				$v_arr_danh_sach_id_su_kien[] = array($v_item_news['EventID'],$v_item_news['PublishedDate2']);
				$v_arr_danh_sach_id_su_kien_check_trung[] = $v_item_news['EventID'];
			}
		}
	}
	//End 19-05-2016 : Thangnb toi_uu_sitemap_seo
	
    $v_count = sizeof($v_arr_danh_sach_id_su_kien);	
	if(!$v_count) die("Khong co du lieu de tao sitemap");	
	$arr_all_item = $v_arr_danh_sach_id_su_kien;
	//End 13-05-2016 : Thangnb toi_uu_sitemap_seo

	$datestr= date("Y-m-d");
	$v_html= '';
    // lấy mảng dữ liệu redirect
    $v_arr_redirect = array();
    $v_key = 'data_danh_sach_link_redirect_ban_web';
    $v_arr_redirect = fe_read_key_and_decode_from_file($v_key);
    if (!check_array($v_arr_redirect)) {
        $v_arr_redirect = fe_read_key_and_decode($v_key, _CACHE_TABLE);
    }
    
	if ($v_count > 0){
		//begin: sua_doi_he_thong_sitemap_24h    
        foreach ($arr_all_item as $row_event) {	
			$v_event_id = $row_event[0];
			$v_last_mod = $row_event[1];
			$v_row_event = fe_su_kien_theo_id($v_event_id);
            $v_row_cate = fe_chuyen_muc_theo_id($v_row_event['id_category']);
            $v_url = get_url_origin_of_event($v_row_event, $v_row_cate);
            // Nếu bài viết nằm trong danh sách redirect thì không hiển thị sitemap
            if($v_row_event['c_canonical'] != ''){
                preg_match( '#-c([0-9]+)e([0-9]+).html#', $v_row_event['c_canonical'], $v_result_canonical);
                $event_id_canonical = intval($v_result_canonical[2]);
                preg_match( '#-c([0-9]+)e([0-9]+).html#', $v_url, $v_result);
                $event_id = intval($v_result[2]);
                if(!$event_id_canonical || $event_id_canonical != $event_id){
                    continue;
                }
            }
            if ($v_arr_redirect[$v_url] != '') {
                continue;
            }
			if (!is_null($v_url) && $v_url != '' && $v_url != str_replace(array('http://','https://'), '', $v_url)){
				if ($v_last_mod == '') {
					$v_begin_time= date('Y-m-d', strtotime($v_row_event['Date'])).'T'.date('H:i:sP', strtotime($v_row_event["Date"])); 
				} else {
					$v_begin_time = date('Y-m-d', strtotime($v_last_mod)).'T'.date('H:i:sP', strtotime($v_last_mod));
				}
				$v_keyword = $v_url;
				// Chi lay cac url duoc tinh cho chuyen muc nay
				// Xac dinh priority
				if ($p_priority!=$default_article_priority){
					$v_priority=get_priority($arr_all_priority,$v_url,2) ;
				}else{
					$v_priority=$default_article_priority;
				}
	
				if ($p_changefreq!=$default_article_changefreq){
					$v_changefreq=get_changefreq($arr_all_changefreg,$v_url, 2) ;
				}else{
					$v_changefreq=$default_article_changefreq;
				}
				
				$v_html = $v_html."<url>\n";
				$v_html = $v_html."<loc>"._replace_xml_special_char($v_url)."</loc>\n";
				$v_html = $v_html."<!-- start_url_goc:"._replace_xml_special_char($v_url).":end_url_goc -->";
				$v_html = $v_html."<lastmod>"._replace_xml_special_char($v_begin_time)."</lastmod>\n";
				$v_html = $v_html."<changefreq>"._replace_xml_special_char($v_changefreq)."</changefreq>\n";
				$v_html = $v_html."<priority>"._replace_xml_special_char($v_priority)."</priority>\n";
				$v_html = $v_html."</url>\n";			
			}
		}
	}
    // begin 6/2/2018 Tytv chinh_ten_sitemap_theo_ngay
    // Cấu hình sitemap link http
    $v_https_for_sitemap = _get_module_config('sitemap', 'v_create_sitemap_protocol_by_type');
    if($v_https_for_sitemap == 1 || $v_https_for_sitemap == 3){
        $v_sitemap_file = "sitemap-event-daily";
        _write_db($v_sitemap_file, array($v_html), $v_begin, $v_end, false );
    }
    // Cấu hình sitemap link https
    if($v_https_for_sitemap == 2 || $v_https_for_sitemap == 3){
        // Khai báo tên sitemap cần cập nhật
        $v_sitemap_file_https = "sitemap-event-daily-https";
        $sitemapindexhttps = "sitemap-index-https";
        // Thây thế các link thường sang https
        $v_html = _replace_domain_from_http_to_https($v_html);
        _write_db($v_sitemap_file_https, array($v_html), $v_begin, $v_end, false );
        updateIndex(array($v_sitemap_file_https.'.xml'), $sitemapindexhttps);
    }
	echo "Da tao thanh cong sitemap file: /$v_sitemap_file \n";	
	return 	array("sitemap-event-daily.xml");
    // end 6/2/2018 Tytv chinh_ten_sitemap_theo_ngay
}

// Tao sitemap tags cua 1 ngay
function gen_tag_sitemap_hang_ngay($date, $v_changefreg, $v_priority, $sitename = "24h.com.vn", $sitelang="vi"){
    global $sitemapindex,$v_begin, $v_end;
	$urlHelper = new UrlHelper();$urlHelper->getInstance();     

    $v_ngay_bat_dau = $date;
    $v_ngay_ket_thuc = $date;
    Gnud_Db_close('read');       
            
    $rs_tags = array();
	
	$arr_result = fe_danh_sach_tag_moi_nhat(1, 1000, $v_ngay_bat_dau, $v_ngay_ket_thuc);  
	if(check_array($arr_result)) {                               
		$rs_tags = array_merge($rs_tags, $arr_result);
	}

	$v_id_tag_da_lay = array();
	foreach ($rs_tags as $item_tag) {
		$v_id_tag_da_lay[] = $item_tag['ID'];
	}

	
	$tmpdate = explode("-", $date);
	$after = date("Y-m-d", mktime(0, 0, 0, $tmpdate[1]  , $tmpdate[2]+1, $tmpdate[0]));
    //begin: sua_doi_he_thong_sitemap_24h    
    //phuonghv edit ngày 20/10/2015
    $arr_all_item = fe_bai_viet_theo_khoang_thoi_gian_xuat_ban($date, $after, 1, 1000);
    array_unique_key($arr_all_item, 'ID');
	foreach ($arr_all_item as $v_row_item) {
		//pre($v_row_item);die;
		$sql = "CALL fe_get_all_tag_by_news_id($v_row_item[ID])";
		$v_rs_tag = Gnud_Db_read_query($sql);
		foreach ($v_rs_tag as $v_item_tag) {
			if (!in_array($v_item_tag['TagID'],$v_id_tag_da_lay)) {	
				$sql = "CALL fe_tag_theo_ds_id($v_item_tag[TagID])";
				$row_tag = Gnud_Db_read_query($sql);
				if (check_array($row_tag[0])) {
					$rs_tags[] = $row_tag[0];
					$v_id_tag_da_lay[] = $row_tag[0]['ID'];
				}
			}
		}
	}
	if (!check_array($rs_tags)) {
		return; // sang trang tiếp theo nếu ko có dữ liệu
	}      
	$v_html = array();
	foreach ($rs_tags as $row_tag){            
		if (!check_array($row_tag)) {
			continue; // sang tag tiếp theo nếu ko có dữ liệu
		}            
		$v_tag_slug = $row_tag['slug'];
		// check nếu có link về trang sự kiện/chuyên mục thì bỏ qua, sang tag khác
		$v_rowTagByKeyword =  fe_tag_url_theo_keyword(_utf8_to_ascii(str_replace('-', ' ', $v_tag_slug)));
		if (check_array($v_rowTagByKeyword) && !is_null($v_rowTagByKeyword['c_url']) && $v_rowTagByKeyword['c_url'] != '') {
			continue;
		}            
		$url = ($row_tag['url']!='') ? $row_tag['url'] : $urlHelper->url_tag(array('slug'=>$v_tag_slug)); // xác định url tag
		$v_url = trim($url);
		$v_url = (strpos($v_url, 'http://') === false && strpos($v_url, 'https://') === false) ? BASE_URL_FOR_PUBLIC.$v_url : $v_url;
		$v_url = str_replace(BASE_URL_FOR_PUBLIC.'/', BASE_URL_FOR_PUBLIC, $v_url);
		$v_date_edited = $row_tag['editedDate'];
		$v_date_edited = (is_null($v_date_edited) || $v_date_edited == '' || $v_date_edited == '0000-00-00 00:00:00') ? date('Y-m-d H:i:s') : $v_date_edited;
		if (!is_null($v_url) && $v_url != '' && $v_url != str_replace(array('http://','http://'), '', $v_url)){
			$tmp = "<url>\n";
			$tmp .= "<loc>"._replace_xml_special_char($v_url)."</loc>\n";
			$tmp .= "<!-- start_url_goc:"._replace_xml_special_char($v_url).":end_url_goc -->";
			$tmp .= "<lastmod>"._replace_xml_special_char(date('Y-m-d', strtotime($v_date_edited)).'T'.date('H:i:sP', strtotime($v_date_edited)))."</lastmod>\n";
			$tmp .= "<changefreq>".$v_changefreg."</changefreq>\n";
			$tmp .= "<priority>".$v_priority."</priority>\n";
			$tmp .= "</url>\n";
			$v_html[] = $tmp;
		}
	}
	unset($rs_tags);
	// Ghi file
	if (check_array($v_html)) {
		/* Begin: Tytv - 12/09/2017 - off_tag_sitemap */
		$v_sitemap_file = "sitemap-tags-".$date;
		// include sitemap theo tháng vào sitemap-tags
		//write2db($v_sitemap_file, $v_html, $v_begin, $v_end, 'sitemap-tags', false, $p_is_gen_current_month);        
		/* End: Tytv - 12/09/2017 - off_tag_sitemap */
	}
    // cap nhat thoi gian gen sitemap tag vào sitemap index      
	/* Begin: Tytv - 12/09/2017 - off_tag_sitemap */
    //$v_arr_file[] = "sitemap-tags-".$date.".xml";  
	/* End: Tytv - 12/09/2017 - off_tag_sitemap */
    updateIndex($v_arr_file, $sitemapindex);
}

function gen_profile_sitemap_hang_ngay($date, $v_changefreg, $v_priority, $sitename = "24h.com.vn", $sitelang="vi"){
	$urlHelper = new UrlHelper();$urlHelper->getInstance();
	$page = 1;
	$number_items = 1000; // lấy 1000 profile mới nhất
	$v_html = '';
	$v_begin = '';
	$v_end = '';
	$v_ngay_bat_dau = $date;
	$v_ngay_ket_thuc = $date;
	$sql = "CALL fe_danh_sach_profile_theo_ngay($page, $number_items ,'$v_ngay_bat_dau', '$v_ngay_ket_thuc')";
	$rs_tags_app = Gnud_Db_read_query($sql);
	$v_danh_sach_id_da_co = array();
	if (check_array($rs_tags_app)) {
		foreach ($rs_tags_app as $row_tag_app) {
			$v_danh_sach_id_da_co[] = $row_tag_app['pk_tag_app'];
		}
	}
	
	$tmpdate = explode("-", $date);
	$after = date("Y-m-d", mktime(0, 0, 0, $tmpdate[1]  , $tmpdate[2]+1, $tmpdate[0]));
    //begin: sua_doi_he_thong_sitemap_24h    
    //phuonghv edit ngày 20/10/2015
    $arr_all_item = fe_bai_viet_theo_khoang_thoi_gian_xuat_ban($date, $after, 1, 1000);
    array_unique_key($arr_all_item, 'ID');
	foreach ($arr_all_item as $v_row_item) {
		$arr_profile = fe_danh_sach_profile_theo_bai_viet_id($v_row_item['ID']);
		if (check_array($arr_profile)) {
			foreach ($arr_profile as $key => $row_profile) {
				if (!in_array($row_profile['pk_tag_app'],$v_danh_sach_id_da_co)) {
					$v_danh_sach_id_da_co[] = $row_profile['pk_tag_app'];
					$rs_tags_app[] = $arr_profile[$key];
				}
			}
		}
	}
	
	for ($i = 0, $s = sizeof($rs_tags_app); $i < $s; ++$i){
		$row_tag_app = $rs_tags_app[$i];
		if (!check_array($row_tag_app)) {
			continue; // sang profile tiếp theo nếu ko có dữ liệu
		}
        $v_tag_app_id 	=  $row_tag_app['pk_tag_app'];
        $v_arr_tag_app =  fe_box_top_profile($v_tag_app_id,0);
        $v_tag_slug 	=  $v_arr_tag_app[0]['c_slug'];
        $v_cat_id 		=  $v_arr_tag_app[0]['fk_category'];
		if($v_tag_slug != ''){
			$url =$urlHelper->url_profile(array('ID'=>$v_tag_app_id, 'cID'=>$v_cat_id, 'slug'=>$v_tag_slug)); // xác định url tag
		}
		$v_url = trim($url);
		$v_url = (strpos($v_url, 'http://') === false && strpos($v_url, 'https://') === false) ? BASE_URL_FOR_PUBLIC.$v_url : $v_url;
		$v_url = str_replace(BASE_URL_FOR_PUBLIC.'/', BASE_URL_FOR_PUBLIC, $v_url);
		$v_date_edited = (is_null($v_date_edited) || $v_date_edited == '' || $v_date_edited == '0000-00-00 00:00:00') ? date('Y-m-d H:i:s') : $v_date_edited;
		if (!is_null($v_url) && $v_url != '' && $v_url != str_replace(array('http://','https://'), '', $v_url)){
			$v_html .= "<url>\n";
			$v_html .= "<loc>"._replace_xml_special_char($v_url)."</loc>\n";
			$v_html .= "<!-- start_url_goc:"._replace_xml_special_char($v_url).":end_url_goc -->";
			$v_html .= "<lastmod>"._replace_xml_special_char(date('Y-m-d', strtotime($v_date_edited)).'T'.date('H:i:sP', strtotime($v_date_edited)))."</lastmod>\n";
			$v_html .= "<changefreq>".$v_changefreg."</changefreq>\n";
			$v_html .= "<priority>".$v_priority."</priority>\n";
			$v_html .= "</url>\n";
		}
	}
	$v_sitemap_file = "sitemap-profile-".$date;
	_write_db($v_sitemap_file, array($v_html), $v_begin, $v_end, false );	
	echo "Da tao thanh cong sitemap file: /$v_sitemap_file \n";
	return 	array("sitemap-profile-".$date.".xml");
}
//End 13-05-2016 : Thangnb toi_uu_sitemap_seo
/* begin 07/12/2016 Tytv - ko_lay_link_anh_vao_sitemap_cac_domain_khong_thuoc_24h */
/*
* Ham kiem tra link anh co thuoc do main cho phep (domain cua 24h hay không)
* param $p_image_link  Link tuyệt đối đường dẫn ảnh
* return String
*/
function _kiem_tra_link_anh_thuoc_domain_cho_phep($p_image_link) {
    return true;
}
/* End 07/12/2016 Tytv - ko_lay_link_anh_vao_sitemap_cac_domain_khong_thuoc_24h */



/* begin 12/2/2018  bo_sung_sitemap_anh_theo_ngay */
/*
 * Hàm gen sitemap ảnh theo ngày
 * param: 
 *  $date:      ngày gen sitemap
 *  $v_begin    Thời gian bắt đầu
 *  $v_end      Thời gian kết thúc
 * return file .xml
 **/
function gen_sitemap_for_daily_image($date, $p_changefreq,$p_priority, $v_begin, $v_end){
    global $v_root, $v_root_without_slash, $default_menu_changefreq, $default_article_changefreq, $default_menu_priority, $default_article_priority;
    $urlHelper = new UrlHelper();$urlHelper->getInstance();
    if ($p_priority==""){
        $p_priority=$default_menu_priority;
    }
    if ($p_priority!=$default_menu_priority){
        $arr_all_priority=explode(";", $p_priority);
    }
    if ($p_changefreq==""){
        $p_changefreq=$default_article_changefreq;
    }
    if ($p_changefreq!=$default_article_changefreq){
        $arr_all_changefreg=explode(";", $p_changefreq);
    }
    if(!$date) die("Can cung cap ngay thang de tao sitemap co dang nhu &date=YYYY-MM-DD (2010-04-27)");
    
    // lấy bài viết xuất bản trong ngày
    $arr_all_item = fe_bai_viet_theo_khoang_thoi_gian_xuat_ban($date, $date, 1, 1000);
    array_unique_key($arr_all_item, 'ID');
    $v_count = sizeof($arr_all_item);
    if(!$v_count) die("Khong co du lieu de tao sitemap");
    
    $datestr= Date("Y-m-d");
    $v_html = '';
	$arr_anh_schema_article = _get_module_config('cau_hinh_dung_chung','arr_anh_schema_article', NAME_THIET_BI_PC);
	$v_prefix_anh_dai_dien_schema_article = $arr_anh_schema_article['prefix'];
    if ($v_count > 0){
        // lấy mảng dữ liệu redirect
        $v_arr_redirect = array();
        $v_key = 'data_danh_sach_link_redirect_ban_web';
        $v_arr_redirect = fe_read_key_and_decode_from_file($v_key);
        if (!check_array($v_arr_redirect)) {
            $v_arr_redirect = fe_read_key_and_decode($v_key, _CACHE_TABLE);
        }
        for( $i=0; $i<$v_count; ++$i){
            // Không hiển thị bài PR trên sitemap
            if(_is_bai_pr($arr_all_item[$i])){
                continue;
            }
            // Nếu bài viết nằm trong danh sách redirect thì không hiển thị sitemap
            if (_link_redirect_khong_dua_vao_sitemap($arr_all_item[$i], $v_arr_redirect)) {
                continue;
            }
            // Lấy link gốc chuyên mục
            $v_row_cate = fe_chuyen_muc_theo_id($arr_all_item[$i]["CategoryID"]);
            $v_url = get_url_origin_of_news($arr_all_item[$i], $v_row_cate);
            if ($v_url){
                // lấy ảnh schema 
				$v_url_anh_dai_dien_bai_viet = ($arr_all_item[$i]['SummaryImg'] == '') ? $arr_all_item[$i]['SummaryImg_chu_nhat'] : $arr_all_item[$i]['SummaryImg'];
				// url ảnh đại diện
                $v_img = replace_domain_static_images_and_domain_cdn_images($v_url_anh_dai_dien_bai_viet);
                if($v_url_anh_dai_dien_bai_viet!=''){
					$v_url_anh_dai_dien_bai_viet = (strpos($v_url_anh_dai_dien_bai_viet,'http://anh.24h.com.vn') !== false)?(str_replace('http://anh.24h.com.vn','',$v_url_anh_dai_dien_bai_viet)):$v_url_anh_dai_dien_bai_viet;
					$v_arr_anh = explode('.',$v_url_anh_dai_dien_bai_viet);
					$v_anh_schema_article = $v_arr_anh[0].$v_prefix_anh_dai_dien_schema_article.'.'.$v_arr_anh[1];
				}
                $v_img_schema = replace_domain_static_images_and_domain_cdn_images($v_anh_schema_article);
				
                // thực hiện lấy sanh sách ảnh trong nội dung bài
                preg_match_all('/<img[^>]+>/i',$arr_all_item[$i]['Body'], $imgs);
                
                $v_html .= "<url>\n";
                    $v_html .= "<loc>"._replace_xml_special_char_sitemap($v_url)."</loc>\n";
                    if($v_img !=''){
                        // ảnh đại diện
                        $v_html .= "<image:image>
                                        <image:loc>".$v_img."</image:loc>
                                    </image:image>";
                    }
                    if($v_img_schema != ''){
                        // ảnh schema
                        $v_html .= "<image:image>
                                        <image:loc>".$v_img_schema."</image:loc>
                                    </image:image>";
                    }
                    // xử lý hiện thị image trong nội dung bài viết
                    if(check_array($imgs[0])){
                        foreach($imgs[0] as $v_image){
                            // xử lý lấy url theo từng ảnh
                            preg_match('#src\s*=\s*"([^\"]*)"#ism', $v_image, $match);
                            if(check_array($match) && $match[1] != ''){
                                $v_img_body = replace_domain_static_images_and_domain_cdn_images($match[1]);
                                $v_html .= "<image:image>
                                                <image:loc>".$v_img_body."</image:loc>
                                            </image:image>";
                            }
                        }
                    }
                $v_html .= "</url>\n";
            }
        }
    }
    $v_https_for_sitemap = _get_module_config('sitemap', 'v_create_sitemap_protocol_by_type');
    // kiểm tra cấu hình tạo sitemap với giao thức http
    if($v_https_for_sitemap == 1 || $v_https_for_sitemap == 3){
        $v_sitemap_file = "sitemap-image-daily";
        $v_sitemap = _write_db($v_sitemap_file, array($v_html), $v_begin, $v_end, false);
    }
    // kiểm tra cấu hình tạo sitemap với giao thức http
    if($v_https_for_sitemap == 2 || $v_https_for_sitemap == 3){
        // Khai báo tên sitemap cần cập nhật
        $v_sitemap_file_https = "sitemap-image-daily-https";
        $sitemapindexhttps = "sitemap-index-https";
        // Thây thế các link thường sang https
        $v_html = _replace_domain_from_http_to_https($v_html);
        _write_db($v_sitemap_file_https, array($v_html), $v_begin, $v_end, false );
        //updateIndex(array($v_sitemap_file_https.'.xml'), $sitemapindexhttps);
    }
	echo "Da tao thanh cong sitemap file: /$v_sitemap[0] \n";
    return array("sitemap-image-daily.xml");
}
/* end 12/2/2018 bo_sung_sitemap_anh_theo_ngay */

/*
@desc: Thay thế các ký tự đặc biệt
@aurhor: trungcq 28/12/2015
@param:
	$p_string string chuỗi cần thay thế
@return: string
*/
function _replace_xml_special_char_sitemap($p_string) {
	$p_string = fw24h_restore_bad_char($p_string);
	//Begin 05-02-2016 : Thangnb xu_ly_ky_tu_dac_biet_sitemap
	$v_arr_replace = array(
		'/ç/'         => 'c',
		'/Ç/'         => 'C',
		'/ñ/'         => 'n',
		'/Ñ/'         => 'N',
		'/–/'         => '-',
		'/[’‘‹›‚]/u'  => ' ',
		'/[“”«»„�]/u' => ' ',
		'/&/'         => ' &amp; ',
		'/\'/'        => ' &apos; ',
		'/\"/'        => ' &quot; ',
		'/\>/'        => '',
		'/\</'        => ''
	);
	//End 05-02-2016 : Thangnb xu_ly_ky_tu_dac_biet_sitemap
	return preg_replace(array_keys($v_arr_replace), array_values($v_arr_replace), $p_string);
}


/**
 * Hàm lấy thời gian giữa 2 khoảng
 * @param $p_start_time thời gian bắt đầu
 */
function lay_thoi_gian_khoang_cach($p_start_time){
    $v_time_end = microtime(true);
	$v_duration = $v_time_end - $p_start_time;
	$v_duration = number_format($v_duration, 12);
	$v_duration = substr($v_duration, 0, 8);
    return $v_duration;
}
/**
 * Hàm thực hiện kiểm tra bài viết không đưa vào sitemap
 * @param array $p_news Mảng dữ liệu bài viết
 * return true/false true: bài viết không được đưa vào sitemap
 */
function _link_redirect_khong_dua_vao_sitemap($p_news,$p_arr_redirect){
    $v_result = false;
    // Nếu bài viết nằm trong danh sách redirect thì không hiển thị sitemap
    if (check_array($p_arr_redirect) && ($p_arr_redirect[$p_news['c_canonical_url']] != '' || $p_arr_redirect[$p_news['c_origin_url']] != '')) {
        $v_result = true;
    }
    if($p_news['c_canonical_url'] != ''){
        // Lấy ID bài viết canonical
        preg_match( '#-c([0-9]+)a([0-9]+).html#', $p_news['c_canonical_url'], $v_result_canonical);
        $news_id_canonical = intval($v_result_canonical[2]);

        // Lấy ID bài viết link gốc
        preg_match( '#-c([0-9]+)a([0-9]+).html#', $p_news['c_origin_url'], $v_result_origin);
        $news_id_origin = intval($v_result_origin[2]);
        // Nếu link khác 
        if(!$news_id_canonical || $news_id_canonical != $news_id_origin){
            $v_result = true;
        }
    }
    return $v_result;
}