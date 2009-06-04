<?php
/**
 * Bshe B Smart HTML Extender
 *
 * http://www.bshe.org
 * Yuichiro Abe <bshe@bshe.org>
 * copyright: since 2008,all rights reserved
 *
 * @category   Bshe
 * @package    Bshe_Specializer
 * @copyright  since 2008,all rights reserved Yuichiro Abe (http://www.bshe.org)
 * @license    LGPL
 */
/** Bshe_Exception */
require_once "Bshe/Exception.php";

/**
 * Bshe_Specializer_Acl_Abstract
 *
 * Bshe用のAclの抽象クラス
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.04.19
 * @license LGPL
 */
class Bshe_Specializer_Acl_Abstract extends Zend_Acl
{
    /**
     * role配列
     *
     * array(
     *     [role] => [parent]
     * )
     *
     */
    protected $_roles = array();

    /**
     * resource配列
     *
     */
    protected $_resources = array();

    /**
     * construct
     *
     *
     * @param unknown_type $role
     * @param unknown_type $resource
     * @return void
     * @throws Ashe_Exception
     */
    public function __construct ()
    {
        try {
            $this->_registRole();
            $this->_registResource();
        } catch( Exception $e) {
            throw new Bshe_Exception (  __METHOD__ .":" . $e->getMessage(), Zend_Log::ERR ) ;
        }
    }
}
