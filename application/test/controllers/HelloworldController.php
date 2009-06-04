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

/** Zend_Controller_Action */
require_once 'Zend/Controller/Action.php';

/**
 * Hellow worldテスト
 *
 * @author Yuichiro Abe
 * @created 2009.04.30
 * @license LGPL
 */
class Test_HelloworldController extends Bshe_Specializer_Controller_Action_Default
{
    public function indexAction()
    {
        $this->view->test = 'hello world';
    }

}