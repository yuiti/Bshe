<?php
/**
 * Bshe B Smart HTML Extender
 *
 * http://www.bshe.org
 * Yuichiro Abe <bshe@bshe.org>
 * copyright: since 2008,all rights reserved
 *
 * @category   Bshe
 * @package    Bshe_Controller
 * @copyright  since 2008,all rights reserved Yuichiro Abe (http://www.bshe.org)
 * @license    LGPL
 */

/** Zend_Controller_Action */
require_once 'Zend/Controller/Action.php';

/**
 * Bshe_View利用を想定したActionクラス
 *
 * ・自動レンダリングの抑止
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2008.09.23
 * @license LGPL
 */
class Bshe_Controller_Action extends Zend_Controller_Action
{
    /**
     * Dispatch前に自動レンダリングを抑止する
     *
     */
    public function preDispatch()
    {
        $this->_helper->viewRenderer->setNoRender();
    }
}
