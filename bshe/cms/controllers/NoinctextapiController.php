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

/** Bshe_Specializer_Controller_Action_Api */
require_once 'Bshe/Specializer/Controller/Action/Api.php';

/**
 * NoinctextのAPI処理を実施するコントローラー
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.01.29
 * @license LGPL
 */
class Bshecms_NoinctextapiController extends Bshe_Specializer_Controller_Action_Api
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


    /**
     * Bshe_SpecializerのCms機能で、
     * View機能を利用しないもの用アクション
     *
     */
    public function cmsAction()
    {
        try {
            $config = Bshe_Registry_Config::getConfig('Bshe_Specializer');
            $mainPath = Bshe_Controller_Init::getMainPath();




            // リクエストクラスのパラメーター取得
            $arrayRequestParams = $this->getRequest()->getParams();

            // テンプレート名により分岐
            $pathinfo = str_replace($config->indexphp_path, '', $this->getRequest()->getPathInfo());
            switch ($pathinfo) {
                case '/cms/text/noinc-save.html':
                    // テキスト保存
                    $cache = New Bshe_Specializer_Cms_Cache_Text($_POST['elementId'], null, null, urldecode($_POST['pageId']));
                    $cache->saveContents(str_replace("\\", "", $_POST['stringDataHTML']));
                    break;
                case '/cms/text/noinc-publish.html':
                    $cache = New Bshe_Specializer_Cms_Cache_Text($_POST['elementId'], null, null, urldecode($_POST['pageId']));
                    $cache->publishContents(str_replace("\\", "", $_POST['stringDataHTML']));
                    break;
            }


            // テンプレートがディレクトリで終わる場合対策
            $pathinfo = str_replace($config->indexphp_path, '', $this->getRequest()->getPathInfo());
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function noincsaveAction()
    {
        try {
            $this->cmsAction();
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function noincpublishAction()
    {
        try {
            $this->cmsAction();
        } catch (Exception $e) {
            throw $e;
        }
    }

}