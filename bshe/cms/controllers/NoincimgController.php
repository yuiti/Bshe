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

/** Bshe_Specializer_Controller_Action_Bshe_Default */
require_once 'Bshe/Specializer/Controller/Action/Bshe/Default.php';
/** Bshe_Specializer_Exception */
require_once 'Bshe/Specializer/Exception.php';

/**
 * NoinctextのHTMLを処理するコントローラー
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.01.29
 * @license LGPL
 */
class Bshecms_NoincimgController extends Bshe_Specializer_Controller_Action_Bshe_Default
{
    /**
     * ログインしていない場合、例外
     *
     */
    public function preDispatch()
    {
        // ログインしていない場合例外出力
        if (!Bshe_Specializer_Auth_Cms::isLogined()) {
            Bshe_Log::logWithFileAndParamsWrite('ログインせずにアクセスされました。', Zend_Log::WARN);
            throw New Bshe_Specializer_Exception('ログインせずにアクセスされました。');
        }
        parent::preDispatch();
    }

    public function uploaderAction()
    {
        try {
            $this->indexAction();
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function renderAction()
    {
        try {
            $this->indexAction();
        } catch (Exception $e) {
            throw $e;
        }
    }
}