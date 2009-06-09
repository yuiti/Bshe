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
/** Bshe_Cms_Models_Index_Sitemap */
require_once Bshe_Controller_Init::getMainPath() . '/bshe/cms/models/Index/Sitemap.php';
/** Bshe_Cms_Models_Index_Contextmenu */
require_once Bshe_Controller_Init::getMainPath() . '/bshe/cms/models/Index/Contextmenu.php';

/**
 * Bshecms_IndexControllerを処理するコントローラー
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.04.07
 * @license LGPL
 */
class Bshecms_IndexController extends Bshe_Specializer_Controller_Action_Bshe_Default
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
                $this->_redirect(Bshe_Controller_Init::getUrlPath() . $config->indexphp_path . '/cms/login.html?bshe_specializer_auth=login');
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
     * 管理画面のメインページ
     *
     */
    public function indexAction()
    {
        try {

        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * サイトマップのページ
     *
     * @return unknown_type
     */
    public function sitemapAction()
    {
        try {
            $config = Bshe_Registry_Config::getConfig('Bshe_Specializer');
            $targetPath = Bshe_Controller_Init::getMainPath() . $config->alias_path . $config->template_path;
            $arrayPluginFlags = $this->view->getTemplatePluginFlags();

            $arrayPluginFlags['Bshe_View_Plugin_Sexylightbox']['setLightboxJs'] = true;

            $arrayPluginFlags['Bshe_View_Plugin_Jquery']['setJqueryPlugin'][] = Bshe_Controller_Init::getUrlPath() . $config->indexphp_path . '/cms/admin/js/sitemap.js';

            // treeview関連
            $arrayPluginFlags = Bshe_Cms_Models_Index_Sitemap::setJavascript($arrayPluginFlags);
            // sitemapクラスインスタンス化
            $siteMap = New Bshe_Cms_Models_Index_Sitemap(array('path' => $targetPath, 'self' => 'ホーム', 'relative' => ''));
            $this->view->sitemap = $siteMap->getHtml();

            // contextMenu関連生成
            $arrayPluginFlags = Bshe_View_Plugin_Jquery_Contextmenu_Default::setJavascript($arrayPluginFlags);

            $contextmenu = New Bshe_Cms_Models_Index_Contextmenu();

            // menuの各項目用のxajaxのメソッド生成
            $arrayPluginFlags['Bshe_View_Plugin_Xajax']['setXajaxRegist'][] =
            array(
                'Bshe_Cms_Models_Index_Contextmenu', 'goEdit'
            );
            $arrayPluginFlags['Bshe_View_Plugin_Xajax']['setXajaxRegist'][] =
            array(
                'Bshe_Cms_Models_Index_Contextmenu', 'doCopy'
            );
/*            $arrayPluginFlags['Bshe_View_Plugin_Xajax']['setXajaxRegist'][] =
            array(
                'Bshe_Cms_Models_Index_Contextmenu', 'doDelete'
            );
 */
            $arrayPluginFlags['Bshe_View_Plugin_Xajax']['setXajaxRegist'][] =
            array(
                'Bshe_Cms_Models_Index_Contextmenu', 'doEditProperty'
            );
            // domreadyの内容生成

            $this->view->setTemplatePluginFlags($arrayPluginFlags);


        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * コピーアクション用画面
     *
     * @return unknown_type
     */
    public function copyAction()
    {
        try {
            $this->view->targetfile = urldecode($_REQUEST['target']);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * プロパティ編集画面
     *
     * @return unknown_type
     */
    public function editpropertyAction()
    {
        try {
            $config = Bshe_Registry_Config::getConfig('Bshe_Specializer');

            $this->view->targetfile = urldecode($_REQUEST['target']);

            $mainPath = Bshe_Controller_Init::getMainPath();

            // 対象ファイルのテンプレートクラスを生成
            $params['templatePath'] = $mainPath . $config->alias_path . $config->template_path;
            $params['templateFile'] = str_replace(':', '/', substr($_REQUEST['target'], strlen('target:')));
            $view = New Bshe_View($params);
            $template = $view->getEngine()->getTemplate();

            // 対象ファイルのキャッシュクラス生成
            $titleCache = New Bshe_Specializer_Cms_Cache_Title($template);

            // タイトル情報取得
            $arrayTitles = $titleCache->getArrayContents();

            $this->view->desc = $arrayTitles['desc'];
            $this->view->keywords = $arrayTitles['keywords'];
            $this->view->title = $arrayTitles['title'];

        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Bshe_Specializerの基本処理アクション
     * 基本はヘルパーで処理される
     *
     */
    public function loginAction()
    {
        try {
            $config = Bshe_Registry_Config::getConfig('Bshe_Specializer');
            $auth = Bshe_Specializer_Auth_Cms::getInstance();

            // ログインチェック
            if (!$auth->hasIdentity()) {
                // ログインしていないのでそのまま表示
            } else {
                // ログイン中
                $this->_redirect(Bshe_Controller_Init::getUrlPath() . $config->indexphp_path . '/cms/admin/index.html');
            }

        } catch (Exception $e) {
            throw $e;
        }
    }


}