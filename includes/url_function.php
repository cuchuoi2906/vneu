<?php 

/**
 * Tra lai chuoi ky tu ko dau, neu co tham so thu 2 se lay lam ext cho chuoi ko dau tra ve
 *
 * @param string $string Chuoi ky tu can chuyen thanh ko dau
 * @param string $ext Phan mo rong can them vao
 * @return string
 */
function _url_text( $string, $ext = '.html', $func='_utf8_to_ascii')
{
	// remove all characters that aren"t a-z, 0-9, dash, underscore or space
	$string = strip_tags( str_replace( '&nbsp;', ' ', $string));
	$string = str_replace( '&quot;', '', $string);

	$string = $func( $string);
	//Begin 26-12-2015 : trungcq sua_doi_slug
	// $NOT_acceptable_characters_regex = '#[^-a-zA-Z0-9_ ]#';
	$NOT_acceptable_characters_regex = '#[^-a-zA-Z0-9_/ ]#';
	//End 26-12-2015 : trungcq sua_doi_slug
	$string = preg_replace( $NOT_acceptable_characters_regex, '', $string);
	// remove all leading and trailing spaces
	$string = trim( $string); 
	// change all dashes, underscores and spaces to dashes
	$string = preg_replace( '#[-_]+#', '-', $string);
	$string = str_replace( ' ', '-', $string);
	//Begin 26-12-2015 : trungcq sua_doi_slug
	// $string = preg_replace( '#[-]+#', '-', $string);
	$string = preg_replace( '#[-/]+#', '-', $string);
	//End 26-12-2015 : trungcq sua_doi_slug
	return strtolower( $string.$ext);
}

/**
 * Tra lai url cua trang video clip
 *
 * @param string $p_name ten album
 * @param string $p_id id cua album
 * @return string
 */
function _get_url_text($p_name, $p_id, $p_match, $p_canonical =0)
{
    if ($p_canonical == 1) {
        $v_base_url = BASE_URL_FOR_PUBLIC;
    } else {
        $v_base_url = BASE_URL;
    }
    return $v_base_url._url_text($p_name,'').$p_match.$p_id.'.html';
}
/**
 * Tra lai domain tu url 
 * @param string $p_url : duong dan day du can boc tach domain
 * @return string
 */
function _get_domain($p_url)
{
  //Begin 14-07-2016 : Thangnb thay_doi_domain_chia_se_fb
  $pieces = parse_url($p_url);
  $domain = isset($pieces['host']) ? $pieces['host'] : '';
  if (preg_match('/(?P<domain>([a-z0-9][a-z0-9\-]{1,63}\.)+[a-z\.]{2,6})$/i', $domain, $regs)) {
    return $regs['domain'];
  }
  return '';
  //End 14-07-2016 : Thangnb thay_doi_domain_chia_se_fb
}
/**
 * Tra lai domain tu url 
 * @param string $p_url : duong dan day du can boc tach domain
 * @return string
 */
function _convert_to_redirect_link($p_url)
{
	$v_link = "";
	if ($p_url!=''){
        // begin 07/09/2016 TuyenNT chuyen_file_redirectout_ra_domain_rieng_biet
		$v_link = link_to_redirectout($p_url);
        // end 07/09/2016 TuyenNT chuyen_file_redirectout_ra_domain_rieng_biet
	}	
	return $v_link;
}