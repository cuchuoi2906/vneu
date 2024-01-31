<?php
/**
* SVN FILE: $Id: class.singleton.php 2055 2011-11-24 01:50:00Z dungpt $
* 
* $Author: dungpt
* $Revision: 2055 $
* $Date: 2011-11-24 08:50:00 +0700 (Thu, 24 Nov 2011) $
* $LastChangedBy: dungpt $
* $LastChangedDate: 2011-11-24 08:50:00 +0700 (Thu, 24 Nov 2011) $
* $URL: http://svn.24h.com.vn/svn_24h/services-tier/includes/class/class.singleton.php $
*
*/

/**
* Singleton object. Usage:
* $objInstance = Singleton::getInstance('ClassName');
*/
class Singleton {

    private static $arrInstances = array();

    private function __construct() {
    
    }

    public function getInstance($strClassName)
    {
        $strClassNameKey = strtolower($strClassName);
        if (!array_key_exists($strClassNameKey, self::$arrInstances)) {
            self::$arrInstances[$strClassNameKey] = new $strClassName;
        }
        return self::$arrInstances[$strClassNameKey];
    }
}

