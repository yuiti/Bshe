<?php
/**
 * Bshe B Smart HTML Extender
 *
 * http://www.bshe.org
 * Yuichiro Abe <bshe@bshe.org>
 * copyright: since 2008,all rights reserved
 *
 * @category   Bshe
 * @package    Bshe_View
 * @subpackage View
 * @copyright  since 2008,all rights reserved Yuichiro Abe (http://www.bshe.org)
 * @license    LGPL
 */

/** Bshe_View_Template_Loader_Html_Proxy_Exception */
require_once 'Bshe/View/Template/Loader/Html/Proxy/Exception.php';

/**
 * Bshe_View_Loader_Html_Proxy_Exception
 *
 * HTML proxy用の例外
 * 対象のレスポンスがHTMLではない
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.08.24
 * @license LGPL
 */
class Bshe_View_Template_Loader_Html_Proxy_Exception_NoHtml extends Bshe_View_Template_Loader_Html_Proxy_Exception
{
    protected $_response;
    
    public function __construct($message, $response)
    {
        $this->_response = $response;
        parent::__construct($message);
    }
    
    public function getResponse()
    {
        return $this->_response;
    }
}