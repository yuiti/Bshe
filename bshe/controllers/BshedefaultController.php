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

/**
 * Bshe_Specializer_Controller_Action_Abstractを継承した
 * _bshe以下のフォルダに設置したHTMLに対するデフォルト処理
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.01.29
 * @license LGPL
 */
class Bshe_BshedefaultController extends Bshe_Specializer_Controller_Action_Bshe_Default
{
    /**
     * Bshe_Specializerの基本処理アクション
     * 基本はヘルパーで処理される
     *
     */
    public function xmlAction()
    {
        try {
            return $this->indexAction();

        } catch (Exception $e) {
            throw $e;
        }
    }
}