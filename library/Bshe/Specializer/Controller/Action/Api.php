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
/** Bshe_Specializer_Controller_Action_Abstract */
require_once 'Bshe/Specializer/Controller/Action/Abstract.php';

/**
 * API関連用のViewヘルパーのないコントローラー
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.01.19
 * @license LGPL
 */
class Bshe_Specializer_Controller_Action_Api extends Bshe_Specializer_Controller_Action_Abstract
{
        /**
     * Bshe_Specializer用のviewRendererをセットする
     *
     */
    public function preDispatch()
    {
        $this->_helper->viewRenderer->setNoRender();
    }
}