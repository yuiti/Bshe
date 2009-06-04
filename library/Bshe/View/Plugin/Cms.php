<?php
/**
 * Bshe B Smart HTML Extender
 *
 * http://www.bshe.org
 * Yuichiro Abe <bshe@bshe.org>
 * copyright: since 2008,all rights reserved
 *
 * @category   Bshe
 * @package    Bshe_View
 * @copyright  since 2008,all rights reserved Yuichiro Abe (http://www.bshe.org)
 * @license    LGPL
 */
/** Bshe_Log */
require_once 'Bshe/Log.php';
/** Bshe_View_Plugin_Abstract */
require_once 'Bshe/View/Plugin/Abstract.php';
/** Bshe_Controller_Init */
require_once "Bshe/Controller/Init.php";

/**
 * Bshe_View_Plugin_Cms
 *
 * Bshe_View_Plugin_Cmsのタグをセットするプラグイン
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.02.05
 * @license LGPL
 */
class Bshe_View_Plugin_Cms extends Bshe_View_Plugin_Abstract
{
    /**
     * templateクラスへCms用の各種処理を実装する。
     *
     * @param $template
     * @return unknown_type
     */
    static public function setCms($template)
    {
        try{
            $config = Bshe_Registry_Config::getConfig('Bshe_Specializer');
            // 言語ファイル読込
            $configLanguage = Bshe_Registry_Config::getConfig('Bshe_Language');

            // Cmsログインしていない場合何もせず終了
            if (!Bshe_Specializer_Acl::isAllowedByUserid($template->getTemplateFileName(), null)) {
                return $template;
            }

            $arrayPluginFlags = $template->getParam('pluginFlags');
            if ($arrayPluginFlags['Bshe_View_Plugin_Cms'] === false) {
                // フラグがないため何もしない
                return $template;
            }
            // 出力

            // header,htmlエレメント取得
            $elements = $template->getElementNumbers();
            $headerElement = null;
            $bodyElement = null;
            $strStyle = '';
            foreach ($elements as $key => $element) {
                $nodeName = $element->nodeName;
                if ($nodeName == 'head') {
                    // header発見
                    $headerElement = $key;
                    // スタイル検索
//                    if ($element->hasChildNodes()) {
//                        $headChildren = $element->getChildNumbers();
//                        foreach ($headChildren as $childkey => $childId) {
//                            if ($elements[$childkey]->nodeName == 'link') {
//                                $cssUrl = $elements[$childkey]->getAttribute('href');
//                                if ((substr($cssUrl, 0, 1) == '/') or (substr($cssUrl, 0, 4) == 'http')) {
//                                    $strStyle .= $template->saveHTML($childkey);
//                                } else {
//                                    if (Bshe_Controller_Init::getUrlPath() . '/' . dirname($templateFilename) == '/') {
//                                        $cssUrl = '/' . $cssUrl;
//                                    } else {
//                                        $cssUrl = Bshe_Controller_Init::getUrlPath() . '/' . dirname($templateFilename) . '/' . $cssUrl;
//                                    }
//
//                                    $elements[$childkey]->setAttribute('href', $cssUrl);
//                                    $strStyle .= $template->saveHTML($childkey);
//                                }
//                            } elseif ($elements[$childkey]->nodeName == 'style') {
//                                $strStyle .= $template->saveHTML($childkey);
//                            }
//                        }
//                    }
                } elseif ($nodeName == 'body') {
                    // html発見
                    $bodyElement = $key;
                }

            }
            if ($headerElement === null) {
                // headerが見つからない
                $template->getLogger()->logWithFileAndParams($configLanguage->Bshe_View_Plugin_Cms->cant_find_head, Zend_Log::INFO);
                return $template;
            }
            if ($bodyElement === null) {
                // headerが見つからない
                $template->getLogger()->logWithFileAndParams($configLanguage->Bshe_View_Plugin_Cms->cant_find_body, Zend_Log::INFO);
                return $template;
            }

            // コンテンツ情報
            $encodedTemplateName = urlencode($template->getTemplateFileName());

            // headerへ必要なlinkを挿入
            $insertNodeClasses = array();
            $strTmp =
                '<script type="text/javascript" language="javascript">' .
                "var LTSunSettings = new Array();" .
                "LTSunSettings['selectTextElementEditable'] = new Array();";
            // 編集対象定義
            foreach ($arrayPluginFlags['Bshe_View_Plugin_Cms']['selectTextElementEditable'] as $key => $targetId) {
                $strTmp .= "LTSunSettings['selectTextElementEditable'].push(\"".$targetId."\");";
            }
            // 画像定義
            $strTmp .=
            	"LTSunSettings['selectImageElement'] = new Array();";
            // テキストコントロールパネル文字列
            $strTmp .=
                "LTSunSettings['titleTextControls_save'] = '" . $configLanguage->Bshe_View_Plugin_Cms->titleTextControls_save . "';" .
                "LTSunSettings['titleTextControls_publish'] = '" . $configLanguage->Bshe_View_Plugin_Cms->titleTextControls_publish . "';" .
                "LTSunSettings['titleTextControls_edit'] = '" . $configLanguage->Bshe_View_Plugin_Cms->titleTextControls_edit . "';" .
                "LTSunSettings['titleTextControls_revisions'] = '" . $configLanguage->Bshe_View_Plugin_Cms->titleTextControls_revisions . "';" .
                "LTSunSettings['titleTextControls_menu'] = '" . $configLanguage->Bshe_View_Plugin_Cms->titleTextControls_menu . "';" .
            "</script>\n";
            // css
            $strTmp .=
                "<link rel='stylesheet' href='" . Bshe_Controller_Init::getUrlPath() . $config->indexphp_path .
                    "/cms/admin-styles.css' type='text/css' media='screen' />\n"
            ;
            $insertNodeClasses[] = New Bshe_Dom_Node_Text($strTmp);

            foreach ($insertNodeClasses as $key => $node) {
                $template->addChild($node, $headerElement);
            }


            // bodyの後ろに
           $insertNodeClasses = array();
            $strTmp =
            	"<script type='text/javascript' language='javascript'>\n" .
                    "LTSunSettings['bshe_indexphp_path']='" .
                    Bshe_Controller_Init::getUrlPath() . $config->indexphp_path . "/cms';\n" .
                    "LTSunSettings['bshe_templatename']='" . $encodedTemplateName . "'</script>\n" .
            "<script type='text/javascript' language='javascript' src='" . Bshe_Controller_Init::getUrlPath() . $config->indexphp_path .
                    "/prototype/prototype.js'></script>\n" .
                "<script type='text/javascript' language='javascript' src='" . Bshe_Controller_Init::getUrlPath() . $config->indexphp_path .
                    "/cms/effects.js'></script>\n" .
                "<script type='text/javascript' language='javascript' src='" . Bshe_Controller_Init::getUrlPath() . $config->indexphp_path .
                    "/cms/ltsun-engine.js'></script>\n" .
                "<script type='text/javascript' language='javascript' src='" . Bshe_Controller_Init::getUrlPath() . $config->indexphp_path .
                    "/cms/admin.js'></script>\n"
                ;
            $insertNodeClasses[] = New Bshe_Dom_Node_Text($strTmp);

            foreach ($insertNodeClasses as $key => $node) {
                $template->addChild($node, $bodyElement);
            }


            // HTML上のスタイル関連タグ取得

//            $cache = New Bshe_Specializer_Cms_Cache_Css($template);
//            $cache->saveCmsCache($strStyle);

            // sexylightbox二重読み込み抑止
            // テンプレートへpluginFlagをセット
            $arrayPluginFlags = $template->getParam('pluginFlags');
            // sexylightbox
            $arrayPluginFlags['Bshe_View_Plugin_Sexylightbox']['setLightboxJs'] = true;
            $template->setParam('pluginFlags', $arrayPluginFlags);

            return $template;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * ログイン依頼の入ったリクエストかを判別し、
     * templateのパラメーターのpluginFlagへsexylightboxのタグセットを実施するフラグをセットする
     * bshe_specializer_auth=login
     * で判別する
     *
     * @param $template
     * @return unknown_type
     */
    static public function chkCmsForm($template)
    {
        try {
            if ($_REQUEST['bshe_specializer_auth'] == 'login') {
                $config = Bshe_Registry_Config::getConfig('Bshe_Specializer');
                // テンプレートへpluginFlagをセット
                $arrayPluginFlags = $template->getParam('pluginFlags');
                // sexylightbox
                $arrayPluginFlags['Bshe_View_Plugin_Sexylightbox']['setLightboxJs'] = true;
                // xajax
                // セットするAjaxのクラスをインスタンス化
                $config = Bshe_Registry_Config::getConfig('Bshe_Specializer');
                require_once Bshe_Controller_Init::getMainPath() . $config->alias_path . $config->indexphp_path . '/xajax/xajax_core/xajax.inc.php';
                $arrayPluginFlags['Bshe_View_Plugin_Xajax']['setXajaxRegist'][] = array(
                	'Bshe_Specializer_Xajax_Auth_Cms', 'authCmsLogin'
                );
                $template->setParam('pluginFlags', $arrayPluginFlags);

            } elseif($_REQUEST['bshe_specializer_auth'] == 'logout') {
                // ログアウト処理
                Bshe_Specializer_Auth_Cms::logout();
                $url = str_replace('bshe_specializer_auth=logout&', '', $_SERVER['REQUEST_URI']);
                $url = str_replace('bshe_specializer_auth=logout', '', $url);
                if (substr($url, -1, 1) == '?') {
                    $url = substr($url, 0, -1) ;
                }
                header('Location: ' . $url);
                exit;
            }
            return $template;

        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * テンプレートへsexylightboxでのログインフォームを表示する。
     *
     * @param $template
     * @return unknown_type
     */
    static public function setCmsForm($template)
    {
        try{
            // ログインチェック
            if ($_REQUEST['bshe_specializer_auth'] != 'login') {
                // 何もしない
                return $template;
            }
            $config = Bshe_Registry_Config::getConfig('Bshe_Specializer');

            // headerエレメント取得
            if (($headerElement = $template->getElementByName('head')) === false) {
                return $template;
            }

            // ページ表示にログイン画面が表示されるように設定
            $strTmp = '<link rel="stylesheet" href="' . Bshe_Controller_Init::getUrlPath() . $config->indexphp_path . '/sexy-lightbox-2/login.css" type="text/css" media="all" />';
            $node= New Bshe_Dom_Node_SingleElement($strTmp, 'link');
            $template->addChild($node, $headerElement);

            $strTmp = "<script type=\"text/javascript\">" .
                "function bshecmsauth(e){" .
                "e=new SexyLightBox({find:'sexywhite',color:'white', OverlayStyles:{'background-color':'#000'}, imagesdir:'" . Bshe_Controller_Init::getUrlPath() . $config->indexphp_path . "/sexy-lightbox-2/sexyimages'});" .
                "e.show('login', '" . Bshe_Controller_Init::getUrlPath() . $config->indexphp_path . "/sexy-lightbox-2/login.html?height=160&width=350', 'sexylightbox');};window.onload=bshecmsauth</script>";
            $node = New Bshe_Dom_Node_Element($strTmp, 'script');
            $template->addChild($node, $headerElement);

//new SexyLightBox();
            return $template;
        } catch (Exception $e) {
            throw $e;
        }
    }


}