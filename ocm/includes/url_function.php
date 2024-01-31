<?php 
function url_tag( $arr) {
	return $arr['domain'].'su-kien/'._url_text($arr['slug'],'').(($arr['page']!='') ? '/'.$arr['page'] : '/');
}
/**
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
/* Begin anhpt1 12/08/2016 xy_ly_chuc_nang_su_kien_tieu_diem */
function url_event( $arr) {
	return $arr['domain']._url_text($arr['slug'],'').'-c'.$arr['cID'].'e'.$arr['ID'].'.html';
}
/* End anhpt1 12/08/2016 xy_ly_chuc_nang_su_kien_tieu_diem */
// begin 31/08/2016 TuyenNT trang_bai_viet_tong_hop_su_kien_infographic_backend
/**
 * Tra lai domain tu url 
 * @param string $p_url : duong dan day du can boc tach domain
 * @return string
 */
function _get_domain($p_url)
{
  $pieces = parse_url($p_url);
  $domain = isset($pieces['host']) ? $pieces['host'] : '';
  if (preg_match('/(?P<domain>([a-z0-9][a-z0-9\-]{1,63}\.)+[a-z\.]{2,6})$/i', $domain, $regs)) {
    return $regs['domain'];
  }
  return '';
}
// end 31/08/2016 TuyenNT trang_bai_viet_tong_hop_su_kien_infographic_backend