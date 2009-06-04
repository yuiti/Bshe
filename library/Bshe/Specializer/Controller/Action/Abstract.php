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
/** Bshe_Controller_Action */
require_once 'Bshe/Controller/Action.php';

/**
 * BsheライブラリのHTMLファイル分割MVCを利用するための
 * コントローラーひな形クラス
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.01.19
 * @license LGPL
 */
class Bshe_Specializer_Controller_Action_Abstract extends Bshe_Controller_Action
{
    /**
     * Bshe_Specializer用のviewRendererをセットする
     *
     */
    public function preDispatch()
    {
        $config = Bshe_Registry_Config::getConfig('Bshe_View');
        $mainPath = Bshe_Controller_Init::getMainPath();

        if (Zend_Controller_Action_HelperBroker::hasHelper('viewRenderer')) {
            Zend_Controller_Action_HelperBroker::removeHelper('viewRenderer');
        }

        if ($config->plugin->after->Bshe_View_Plugin_Cms->setCms == true) {
            $helper = New Bshe_Specializer_Controller_Action_Helper_ViewRenderer_Cms();

        } else {
            $helper = New Bshe_Specializer_Controller_Action_Helper_ViewRenderer_Default();
        }

        $helper->setActionController($this);
        $viewPath = $mainPath . 'application/' . $this->getRequest()->getModuleName() . '/views';
        $arrayParams = array();
        if (file_exists($viewPath . '/resource')) {
            $arrayParams['bshe_view_params']['assignClassPath'][] = $viewPath . '/resource';
        }
        if (file_exists($viewPath . '/helper')) {
            $arrayParams['bshe_view_params']['helperClassPath'][] = $viewPath . '/helper';
        }

        $helper->init($arrayParams);

        Zend_Controller_Action_HelperBroker::addHelper($helper);

        parent::preDispatch();
    }

}