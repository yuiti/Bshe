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
/** Bshe_Specializer_Controller_Action_Default */
require_once 'Bshe/Specializer/Controller/Action/Default.php';

/**
 * BsheライブラリのHTMLファイル分割MVCを利用するための
 * コントローラーひな形クラス
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.01.19
 * @license LGPL
 */
class Bshe_Specializer_Controller_Action_Proxy extends Bshe_Specializer_Controller_Action_Default
{
    /**
     * リクエストから、元HTMLを取得するrequestを生成返す。
     * 
     * @return unknown_type
     */
    protected function _getTargetRequest()
    {
        // デフォルト
    }
    
    /**
     * クッキーがある場合は、クッキークラスを返す。
     * 
     * @return unknown_type
     */
    protected function _getCookieJar()
    {
        return null;
    }
    
    
    /**
     * Bshe_Specializer用のviewRendererをセットする
     * html,htm以外の拡張子の場合は、単純にリクエストを取得して
     * それを出力してしまう。
     *
     */
    public function preDispatch()
    {
        // HTMLファイルかどうかを判別
        // HTML file
        $config = Bshe_Registry_Config::getConfig('Bshe_View');
        $mainPath = Bshe_Controller_Init::getMainPath();

        if (Zend_Controller_Action_HelperBroker::hasHelper('viewRenderer')) {
            Zend_Controller_Action_HelperBroker::removeHelper('viewRenderer');
        }

        $helper = New Bshe_Specializer_Controller_Action_Helper_ViewRenderer_Proxy();

        $helper->setActionController($this);
        $viewPath = $mainPath . 'application/' . $this->getRequest()->getModuleName() . '/views';
        $arrayParams = array();
        if (file_exists($viewPath . '/resource')) {
            $arrayParams['bshe_view_params']['assignClassPath'][] = $viewPath . '/resource';
        }
        if (file_exists($viewPath . '/helper')) {
            $arrayParams['bshe_view_params']['helperClassPath'][] = $viewPath . '/helper';
        }
        $arrayParams['bshe_view_params']['target_request'] = $this->_getTargetRequest();

        try {
            $helper->init($arrayParams);
        } catch (Bshe_View_Template_Loader_Html_Proxy_Exception_NoHtml $e) {
            // HTMLではない
            $response = $e->getResponse();
            $controllerResponse = new Zend_Controller_Response_Http();
            $controllerResponse->clearHeaders();
            $arrayHeaders = $response->getHeaders();
            foreach ($arrayHeaders as $key  => $header) {
                $controllerResponse->setHeader($key, $header);
            }
            $controllerResponse->setBody($response->getBody());
            //$this->setResponse($controllerResponse);
            $controllerResponse->sendResponse();
            
            exit(1);
        }
            
        Zend_Controller_Action_HelperBroker::addHelper($helper);


    }

}