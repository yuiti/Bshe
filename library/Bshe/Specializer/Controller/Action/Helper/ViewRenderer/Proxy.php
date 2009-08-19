<?php
/**
 * Bshe B Smart HTML Extender
 *
 * http://www.bshe.org
 * Yuichiro Abe <bshe@bshe.org>
 * Copyright(c) since 2009,all rights reserved
 *
 * @category   Bshe
 * @package    Bshe_Specializer
 * @copyright  since 2009,all rights reserved Yuichiro Abe (http://www.bshe.org)
 * @license    LGPL
 */

/** Bshe_Log */
require_once 'Bshe/Log.php';
/** Bshe_Specializer_Controller_Action_Helper_ViewRenderer_Abstract */
require_once 'Bshe/Specializer/Controller/Action/Helper/ViewRenderer/Abstract.php';

/**
 * Bshe_Specializerを利用するためのコントローラー用ヘルパー
 * Viewの自動生成やパス、ログの設定を行う。
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.03.11
 * @license LGPL
 */
class Bshe_Specializer_Controller_Action_Helper_ViewRenderer_Proxy extends Bshe_Specializer_Controller_Action_Helper_ViewRenderer_Abstract
{
	    /**
     * Initialize the view object
     *
     * @param  string $path
     * @param  string $prefix
     * @param  array  $options
     * @throws Zend_Controller_Action_Exception
     * @return void
     */
    public function initView($path = null, $prefix = null, array $options = array())
    {
    	try {
    	    if (!isset($options['bshe_view_params']['loader'])) {
    	        $options['bshe_view_params']['loader'] = new Bshe_View_Template_Loader_Proxy($options['bshe_view_params']['target_request']);
    	    }
    	    return parent::initView($path, $prefix, $options);
    	} catch (Exception $e) {
    		throw $e;
    	}
    }
}