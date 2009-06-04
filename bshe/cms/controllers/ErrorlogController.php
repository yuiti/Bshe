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

/** Bshe_Specializer_Controller_Action_Bshe_List_View */
require_once 'Bshe/Specializer/Controller/Action/Bshe/List/View.php';
/** Bshe_Cms_Models_Errorlog_Datapack_Filter */
require_once Bshe_Controller_Init::getMainPath() . '/bshe/cms/models/Errorlog/Datapack/Filter.php';
/** Bshe_Specializer_Acl */
require_once 'Bshe/Specializer/Acl.php';
/** Bshe_Controller_Init */
require_once 'Bshe/Controller/Init.php';
/** Bshe_Cms_Models_Errorlog_Datapack_List */
require_once Bshe_Controller_Init::getMainPath() . '/bshe/cms/models/Errorlog/Datapack/List.php';

/**
 * Bshecms_IndexControllerを処理するコントローラー
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.04.07
 * @license LGPL
 */
class Bshecms_ErrorlogController extends Bshe_Specializer_Controller_Action_Bshe_List_View
{
    /**
     * ログインチェック
     *
     * @return unknown_type
     */
    public function preDispatch()
    {
        try {
            parent::preDispatch();
            $config = Bshe_Registry_Config::getConfig('Bshe_Specializer');
            // ログインチェック
            if (!Bshe_Specializer_Acl::isAllowedByUserid($this->view->getEngine()->getTemplate()->getTemplateFileName(), null)) {
                // ログインしていない
                $this->_redirect(Bshe_Controller_Init::getUrlPath() . $config->indexphp_path . '/cms/login.html');
            } else {
                // ログイン中、LTsunをOFFにしてそのまま表示
                $arrayPluginFlags = $this->view->getTemplatePluginFlags();
                $arrayPluginFlags['Bshe_View_Plugin_Cms'] = false;
                $this->view->setTemplatePluginFlags($arrayPluginFlags);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * エラーログページ表示
     *
     * @return unknown_type
     */
    public function indexAction()
    {
            try {
            // 呼び出す、リストとフィルタのクラスのインスタンスをレジストリへ登録
            $datapackFilter = New Bshe_Cms_Models_Errorlog_Datapack_Filter();
            Zend_Registry::set('Bshe_Specializer_Controller_Action_List_View_Filter', $datapackFilter);
            $datapackList = New Bshe_Cms_Models_Errorlog_Datapack_List();

            // TODO：HTMLから変更できるようにする
            $datapackList->setListCnt(30);

            // 期間のデフォルト設定
            $this->view->term_min = date('Y/m/d');
            $this->view->term_max = date('Y/m/d');

            Zend_Registry::set('Bshe_Specializer_Controller_Action_List_View_List', $datapackList);

            // リスト用にViewクラスをレジストリに保存
            Zend_Registry::set('Bshe_Specializer_Controller_Action_List_View_View', $this->view);
            // リストエリアを指すidを指定
            Zend_Registry::set('Bshe_Specializer_Controller_Action_List_View_ViewListId', 'list_view');
            // テーブルを挿すキー
            Zend_Registry::set('Bshe_Specializer_Controller_Action_List_View_ViewKey', 'errorlogs');



            parent::indexAction();
        } catch (Exception $e) {
            throw $e;
        }
    }
}