<?php
/**
* SVN FILE: $Id: route.php 2055 2011-11-24 01:50:00Z dungpt $
* 
* $Author: dungpt
* $Revision: 2055 $
* $Date: 2011-11-24 08:50:00 +0700 (Thu, 24 Nov 2011) $
* $LastChangedBy: dungpt $
* $LastChangedDate: 2011-11-24 08:50:00 +0700 (Thu, 24 Nov 2011) $
* $URL: http://svn.24h.com.vn/svn_24h/services-tier/route.php $
*
*/

$fwRoute = array();
$fwRoute['home-test/index']['url'] = '/home-test.html'; 
$fwRoute['register/index']['url'] = 'dang-ky'; 
$fwRoute['news/index']['url'] = '([\/a-zA-Z0-9\-]*)(-a[0-9]+).html';
$fwRoute['cate/index']['url'] = '([\/a-zA-Z0-9\-]*)(-c[0-9]+).html';
$fwRoute['sitemap/index']['url'] = '^sitemap([0-9]+)\.html';
$fwRoute['xml/index']['url'] = '([\/a-zA-Z0-9\-]*)\.xml';