<?php
/**
* SVN FILE: $Id: fw24h_pagination_component.php 2055 2011-11-24 01:50:00Z dungpt $
* 
* @desc Description of function or file
* @param user_name: name to compare; password: password to check
* @return success_flag = true/false
*
* $Author: dungpt
* $Revision: 2055 $
* $Date: 2011-11-24 08:50:00 +0700 (Thu, 24 Nov 2011) $
* $LastChangedBy: dungpt $
* $LastChangedDate: 2011-11-24 08:50:00 +0700 (Thu, 24 Nov 2011) $
* $URL: http://svn.24h.com.vn/svn_24h/services-tier/includes/class/fw24h_pagination_component.php $
*
* @author: dungpt @date: 2008/09/12 @desc: create new
*/


/*
//$v_start = paginationValid($_GET['page_start']);

$abc = new Fw24h_paginationComponent();

$v_start = $abc->get_start_item();

$abc->arrMess = array( 'prev'=>'<<', 'next'=>'>>');

//$abc->isAjax = true;

echo $abc->Pagination( '', 100, 10, $v_start, 3);

How to use:

$Pagination = new Fw24h_paginationComponent();

$start = $Pagination->get_start_item();

$per_page = 10;

// lay danh sach gioi han boi $start va $per_page
$sql = "SELECT ID, Name From Users LIMIT $start, $per_page";
$rsUser = fw24h_db_query( $sql);

while( $rowUser = fw24h_db_fetch_array( $rsUser)) {
	// ...
}

// dem so luong ban ghi
$sql = "SELECT COUNT(ID) as Total FROM Users";
$rsUserCount = fw24h_db_query( $sql);
$rowUserCount = fw24h_db_fetch_array( $rsUserCount)

$RecountCount = $rowUserCount['Total'];

// hien thi doan phan trang

$this_url = 'abc.php?c=2';
echo $Pagination->Pagination( $this_url, $RecountCount, $per_page, $start);

Neu dung cho Ajax

$Pagination->isAjax = true;
Xay dung ham getAjaxPage( url) de su dung

function Pagination( $base_url, $num_items, $per_page, $start_item, $p_numpage=7) {
$base_url:		URL chinh
$num_item:		Tong so luong ban ghi
$per_page:		So ban ghi cho 1 trang
$start_item:	STT ban ghi bat dau
$p_numpage:		So luong trang hien thi, mac dinh 7
*/

class Fw24h_paginationComponent {

	var $arrMess = array('next'=>'Trang &#273;&#7847;u', 'prev'=>'Trang cu&#7889;i');

	var $isAjax = false;

	var $ajaxFunction = 'getAjaxPage';
	var $ajaxWhere = 'div_game_search';

	var $page_start = 'page';
	var $start_item = 0;
	var $classPageFirst = 'class = "pageFirst"';
    var $classPageLast = 'class = "pageLast"';
	function startup( &$controller) {
		
	}

	function start_item( $start=0) {
		$this->start_item = 0;
		if( intval( $start)>0) {
			$this->start_item = $start;
		} 
		return $this->start_item;
	}
	
	function Pagination( $base_url, $num_items, $per_page, $p_numpage=7,$p_curent_page=1) {	
		$page_start = $this->page_start;
		$start_item = $this->start_item;

		if( $per_page < 1) {
			return '';
		}
		
		$base_url = preg_replace( '/[&]*'.$page_start.'=[0-9]+/', '', $base_url);
		if( !preg_match( '#\?#', $base_url)) {
			$base_url .= '?';
		}else{
			$base_url .= '&';
		}
		
		if(preg_match( '#'.$page_start.'([0-9]+)#', $base_url,$v_result)) {
			$base_url = str_replace($v_result[0],'',$base_url);
		}
               
		$total_pages = @ceil($num_items/$per_page);
		$total_pages = ($total_pages > PAGE_MAX_NUMBER)?PAGE_MAX_NUMBER:$total_pages; // chi cho hien thi toi da 20 trang
		$p_curent_page = ($p_curent_page > PAGE_MAX_NUMBER)?PAGE_MAX_NUMBER:$p_curent_page; 
		
		
		if ( $total_pages == 1 || $total_pages == 0)
		{
			return '';
		}

		$on_page =$p_curent_page;// floor($start_item / $per_page) + 1;

		$page_string = '';
		if( $total_pages <= $p_numpage) {
			for( $ii=1; $ii<=$p_numpage; ++$ii) {
				if( $ii > $total_pages) {
					break;
				}
				if( $on_page == $ii) {
					$page_string .= '&nbsp; ['.$ii.'] &nbsp;';
				}else{
					$page_string .= '<a href="'.urlencode($base_url.$page_start.'='.($ii)).'">'.$ii.'</a> &nbsp;';
				}				
			}			
		}else{
			if( $on_page >= $p_numpage-1) {
				$start = $on_page - floor($p_numpage / 2);
				$start = ( $start>0?$start:1);
			}else{
				$start = 1;
			}
			for( $ii = $start; $ii<$start+$p_numpage; ++$ii) {
				if( $ii > $total_pages) {
					break;
				}
				if( $on_page == $ii) {
					$page_string .= '&nbsp; ['.$ii.'] &nbsp;';
				}else{
					$page_string .= '<a href="'.urlencode($base_url.$page_start.'='.($ii)).'">'.$ii.'</a> &nbsp;';
				}
			}			
		}

		if( $on_page < 2) {
			$page_string = '<span '.$this->classPageFirst.'>'.$this->arrMess['prev'].'</span> '.$page_string;			
		}else{
			$page_string = '<a href="'.urlencode($base_url.$page_start.'='.(1)).'" '.$this->classPageFirst.'>'.$this->arrMess['prev'].'</a> '.$page_string;
		} 

		if( $on_page < $total_pages) {
			$page_string .= '<a href="'.urlencode($base_url.$page_start.'='.($total_pages)).'" >'.'<span '.$this->classPageLast.'>'.$this->arrMess['next'].'</span></a>';
		}else{
			$page_string .= '<span '.$this->classPageLast.'>'.$this->arrMess['next'].'</span>';
		}
		
		if( $this->isAjax) {
			$page_string = preg_replace( '#href="([^"]+)"#', 'rel ="nofollow" href="javascript:'.$this->ajaxFunction.'(\''.$this->ajaxWhere.'\',\'$1\');"', $page_string);
		}		
        return $page_string;
	}

}

