<?php
/**
 * Bshe B Smart HTML Extender
 *
 * http://www.bshe.org
 * Yuichiro Abe <bshe@bshe.org>
 * Copyright(c) since 2009,all rights reserved
 *
 * @category   Bshe
 * @package    Bshe_Application
 * @copyright  since 2009,all rights reserved Yuichiro Abe (http://www.bshe.org)
 * @license    LGPL
 */

/** Bshe_Specializer_Controller_Action_Proxy */
require_once 'Bshe/Specializer/Controller/Action/Proxy.php';

/**
 * Hellow worldテスト
 *
 * @author Yuichiro Abe
 * @created 2009.04.30
 * @license LGPL
 */
class Test_ProxyController extends Bshe_Specializer_Controller_Action_Proxy
{
    
    /**
     * リクエストから、元HTMLを取得するURIを生成返す。
     * 
     * @return unknown_type
     */
    protected function _getTargetRequest()
    {
        try {
            $orgUri = $_SERVER['REQUEST_URI'];
            $url = str_replace(Bshe_Controller_Init::getUrlPath() . '/test/proxy', 'http://www.sai-graph.com', $orgUri);
            return new Zend_Http_Client($url);
        } catch (Exception $e) {
            throw $e;
        }
    }
    
    public function indexAction()
    {
        try {
            $a = 'a';
        } catch (Exception $e) {
            throw $e;
        }
    }

}