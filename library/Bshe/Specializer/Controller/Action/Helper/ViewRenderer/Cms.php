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
/** Zend_Controller_Action_Helper_ViewRenderer */
require_once 'Bshe/Specializer/Controller/Action/Helper/ViewRenderer/Abstract.php';

/**
 * CMS機能をオンにするヘルパー
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.03.11
 * @license LGPL
 */
class Bshe_Specializer_Controller_Action_Helper_ViewRenderer_Cms extends Bshe_Specializer_Controller_Action_Helper_ViewRenderer_Abstract
{
    /**
     * 追加Viewアサインクラスプラグイン
     */
    protected $_addViewAssign =
        array(
            'afterAssigns' =>
                array(
                    array (
                    'className' => 'Bshe_View_Plugin_Cms',
                    'methodName' => 'setCmsForm'
                    )
                ),
            'beforeAssigns' =>
                array(
                    array (
                    'className' => 'Bshe_View_Plugin_Cms',
                    'methodName' => 'chkCmsForm'
                    ),
                    array (
                    'className' => 'Bshe_View_Plugin_Cms',
                    'methodName' => 'setXajaxFunctions'
                    )
                )
        );

}