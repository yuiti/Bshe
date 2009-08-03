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
 * NoinctextのHTMLを処理するコントローラー
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.01.29
 * @license LGPL
 */
class Bshecms_NoinctextController extends Bshe_Specializer_Controller_Action_Bshe_Default
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


    public function noincadveditorAction()
    {
        try {
            $arrayPluginFlags = $this->view->getTemplatePluginFlags();
            $arrayPluginFlags = Bshe_View_Plugin_Jquery_Contextmenu_Abstract::setJavascript($arrayPluginFlags);

            $this->view->setTemplatePluginFlags($arrayPluginFlags);
            $this->indexAction();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * CMS履歴画面用コントローラー
     *
     * @return unknown_type
     */
    public function noincrevisionsAction()
    {
        try {
            $cache = New Bshe_Specializer_Cms_Cache_Text($_GET['elementId'], null, null, urldecode($_GET['pageId']));
            $arrayRevisions = $cache->getRevisionList();
            $arrayDatas = array();

            foreach ($arrayRevisions as $key => $arrayRevision) {
                $arrayData['time'] = $arrayRevision['time'];
                $arrayData['revision'][] = array('a' => array('innerHTML', $arrayRevision['contents']));
                $arrayData['revision'][] = array('a' => array('id', 'revisionTop_' . $key));
                $arrayData['revision'][] = array('a' => array('onClick', "updateTextModule('revisionTop_" . $key . "');"));
                $arrayData['revision'][] = array('a' => array('onMouseOver', "highlight('revisionTop_" . $key . "');"));
                $arrayData['revision'][] = array('a' => array('onMouseOut', "unHighlight('revisionTop_" . $key . "');"));

                $arrayDatas['_values'][] = $arrayData;
            }
            $this->view->revisions = $arrayDatas;

        } catch (Exception $e) {
            throw $e;
        }
    }

}