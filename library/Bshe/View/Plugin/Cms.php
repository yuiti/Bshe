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
     * ログイン中の場合、CMS関連のXajaxをセットする。
     *
     * @param $template
     * @return unknown_type
     */
    static public function setXajaxFunctions($template)
    {
        try {
        // Cmsログインしていない場合何もせず終了
            if (!Bshe_Specializer_Acl::isAllowedByUserid($template->getTemplateFileName(), null)) {
                return $template;
            }

            $arrayPluginFlags = $template->getParam('pluginFlags');

            // 下書き保存
            $arrayPluginFlags['Bshe_View_Plugin_Xajax']['setXajaxRegist'][] =
            array(
                'Bshe_Specializer_Xajax_Cms_Text', 'saveText'
            );
            $arrayPluginFlags['Bshe_View_Plugin_Xajax']['setXajaxRegist'][] =
            array(
                'Bshe_Specializer_Xajax_Cms_Text', 'publishText'
            );
            $arrayPluginFlags['Bshe_View_Plugin_Xajax']['setXajaxRegist'][] =
            array(
                'Bshe_Specializer_Xajax_Cms_Text', 'undoText'
            );
            $template->setParam('pluginFlags', $arrayPluginFlags);

            return $template;

        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * templateクラスへコンテキストメニューのCms用の各種処理を実装する。
     *
     * @param $template
     * @return unknown_type
     */
    static public function setCms($template)
    {
        try {
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

            $headerElement = $template->getElementByName('head');
            $bodyElement = $template->getElementByName('body');

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

            // トップに編集中を表示するHTMLを生成
            $strTmp = '<div id="bshe_cmsmode_header" class="bshe_cms_pagemenu"><div><img src="' . Bshe_Controller_Init::getUrlPath() . $config->indexphp_path . '/img/logo3.png" /></div>' .
                 '<div><span>Bsheは編集モードです。</span><img src="' . Bshe_Controller_Init::getUrlPath() . $config->indexphp_path . '/cms/media/edit-panel.png" /><span>が表示されている箇所は編集可能です。直接編集するか右クリックしてください。</div></div>';
            $node = New Bshe_Dom_Node_Text($strTmp);
            // bodyのnodeクラスを取得
            $template->insertChild($node, $bodyElement);

            // メニューHTMLを生成
            $strTmp =
                '<ul id="bshe_cms_menu" class="contextMenu">' .
                  '<li class="edit"><b>　このブロック</b></li>' .
                  '<li class="edit"><a href="#editer">エディタ</a></li>' .
                  '<li class="edit"><a href="#save">下書き保存</a></li>' .
                  '<li class="edit"><a href="#publish">保存公開</a></li>' .
                  '<li class="edit"><a href="#undo">元に戻す</a></li>' .
                  '<li class="edit"><a href="#history">履歴を表示</a></li>' .
                  '<li class="edit separator"><a href="#menu">メニュー</a></li>' .
                  '<li class="edit"><a href="#logout">ログアウト</a></li>' .
//                  '<li class="edit separator"><b>　ページ全体</b></li>' .
//                  '<li class="edit"><a href="#edit_property">タイトル編集</a></li>' .
//                  '<li class="edit"><a href="#save_page">下書き保存</a></li>' .
//                  '<li class="edit"><a href="#publish_page">下書を公開</a></li>' .
//                  '<li class="edit"><a href="#undo_page">元に戻す</a></li>' .
            '</ul>';
            $node = New Bshe_Dom_Node_Text($strTmp);
            // bodyのnodeクラスを取得
            $template->addChild($node, $bodyElement);

            // imgメニューHTMLを生成
            $strTmp =
                '<ul id="bshe_cmsimg_menu" class="contextMenu">' .
                  '<li class="edit"><b>　このブロック</b></li>' .
                  '<li class="edit"><a href="#editer">画像変更</a></li>' .
                  '<li class="edit"><a href="#save">下書き保存</a></li>' .
                  '<li class="edit"><a href="#publish">保存公開</a></li>' .
                  '<li class="edit"><a href="#undo">元に戻す</a></li>' .
                  '<li class="edit"><a href="#history">履歴を表示</a></li>' .
                  '<li class="edit separator"><a href="#menu">メニュー</a></li>' .
                  '<li class="edit"><a href="#logout">ログアウト</a></li>' .
//                  '<li class="edit separator"><b>　ページ全体</b></li>' .
//                  '<li class="edit"><a href="#edit_property">タイトル編集</a></li>' .
//                  '<li class="edit"><a href="#save_page">下書き保存</a></li>' .
//                  '<li class="edit"><a href="#publish_page">下書を公開</a></li>' .
//                  '<li class="edit"><a href="#undo_page">元に戻す</a></li>' .
            '</ul>';
            $node = New Bshe_Dom_Node_Text($strTmp);
            // bodyのnodeクラスを取得
            $template->addChild($node, $bodyElement);
            
            $strTmp =
                '<ul id="bshe_cms_pagemenu" class="contextMenu">' .
                  '<li class="edit"><b>　ページ全体</b></li>' .
                  '<li class="edit"><a href="#edit_property">タイトル編集</a></li>' .
                  '<li class="edit"><a href="#save_page">下書き保存</a></li>' .
                  '<li class="edit"><a href="#publish_page">下書を公開</a></li>' .
                  '<li class="edit separator"><a href="#undo_page">元に戻す</a></li>' .
                  '<li class="edit separator"><a href="#menu">メニュー</a></li>' .
                  '<li class="edit"><a href="#logout">ログアウト</a></li>' .
                '</ul>';
            $node = New Bshe_Dom_Node_Text($strTmp);
            // bodyのnodeクラスを取得
            $template->addChild($node, $bodyElement);

            // css
            $strTmp =
                "<link rel='stylesheet' href='" . Bshe_Controller_Init::getUrlPath() . $config->indexphp_path .
                    "/cms/cms.css' type='text/css' media='screen' />\n"
            ;
            $insertNodeClasses[] = New Bshe_Dom_Node_Text($strTmp);

            // 設定関連
            $strTmp = "<script type=\"text/javascript\">var bshe_basedir='" . Bshe_Controller_Init::getUrlPath() . $config->indexphp_path . "';</script>";
            $insertNodeClasses[] = New Bshe_Dom_Node_Element($strTmp, 'script');
            $encodedTemplateName = urlencode($template->getTemplateFileName());
            $strTmp = "<script type=\"text/javascript\">var bshe_templatename='" . $encodedTemplateName . "';</script>";
            $insertNodeClasses[] = New Bshe_Dom_Node_Element($strTmp, 'script');


            foreach ($insertNodeClasses as $key => $node) {
                $template->addChild($node, $headerElement);
            }


            // jQuery読み込み
            $arrayPluginFlags = Bshe_View_Plugin_Jquery_Contextmenu_Abstract::setJavascript($arrayPluginFlags);
            $arrayPluginFlags['Bshe_View_Plugin_Jquery']['setJqueryPlugin'][] = Bshe_Controller_Init::getUrlPath() . $config->indexphp_path . '/jquery/plugins/bshecms/jquery.bshecms.js';
            $arrayPluginFlags['Bshe_View_Plugin_Jquery']['setJqueryPlugin'][] = Bshe_Controller_Init::getUrlPath() . $config->indexphp_path . '/cms/js/cms.js';





            // HTML上のスタイル関連タグ取得

//            $cache = New Bshe_Specializer_Cms_Cache_Css($template);
//            $cache->saveCmsCache($strStyle);

            // sexylightbox二重読み込み抑止
            // テンプレートへpluginFlagをセット

            // sexylightbox
            $arrayPluginFlags['Bshe_View_Plugin_Sexylightbox']['setLightboxJs'] = true;


            $template->setParam('pluginFlags', $arrayPluginFlags);

            return $template;
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * templateクラスへCms用の各種処理を実装する。
     *
     * @param $template
     * @return unknown_type
     */
    static public function setCms2($template)
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
//            $strTmp =
//                '<script type="text/javascript" language="javascript">' .
//                "var LTSunSettings = new Array();" .
//                "LTSunSettings['selectTextElementEditable'] = new Array();";
//            // 編集対象定義
//            foreach ($arrayPluginFlags['Bshe_View_Plugin_Cms']['selectTextElementEditable'] as $key => $targetId) {
//                $strTmp .= "LTSunSettings['selectTextElementEditable'].push(\"".$targetId."\");";
//            }
            // 画像定義
//            $strTmp .=
//            	"LTSunSettings['selectImageElement'] = new Array();";
            // テキストコントロールパネル文字列
//            $strTmp .=
//                "LTSunSettings['titleTextControls_save'] = '" . $configLanguage->Bshe_View_Plugin_Cms->titleTextControls_save . "';" .
//                "LTSunSettings['titleTextControls_publish'] = '" . $configLanguage->Bshe_View_Plugin_Cms->titleTextControls_publish . "';" .
//                "LTSunSettings['titleTextControls_edit'] = '" . $configLanguage->Bshe_View_Plugin_Cms->titleTextControls_edit . "';" .
//                "LTSunSettings['titleTextControls_revisions'] = '" . $configLanguage->Bshe_View_Plugin_Cms->titleTextControls_revisions . "';" .
//                "LTSunSettings['titleTextControls_menu'] = '" . $configLanguage->Bshe_View_Plugin_Cms->titleTextControls_menu . "';" .
//            "</script>\n";
            // css
            $strTmp .=
                "<link rel='stylesheet' href='" . Bshe_Controller_Init::getUrlPath() . $config->indexphp_path .
                    "/cms/admin-styles.css' type='text/css' media='screen' />\n"
            ;
            $insertNodeClasses[] = New Bshe_Dom_Node_Text($strTmp);

/*            $strTmp =
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
*/            $insertNodeClasses[] = New Bshe_Dom_Node_Text($strTmp);

            foreach ($insertNodeClasses as $key => $node) {
                $template->addChild($node, $headerElement);
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
                "function bshecmsauth(){" .
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


    /**
     * キャッシュを利用してタイトルやdescription、keywordsなどを変更する
     *
     * @param $template
     * @return unknown_type
     */
    static public function updateHead($template)
    {
        try {
            $config = Bshe_Registry_Config::getConfig('Bshe_Specializer');

            // titleキャッシュクラスインスタンス化
            $titleCache = New Bshe_Specializer_Cms_Cache_Title($template);
            $arrayTitles = $titleCache->getArrayContents();

            // headerエレメント取得
            if (($headerElement = $template->getElementByName('head')) === false) {
                return $template;
            }

            // title,metaエレメント取得
            $arrayTags = $titleCache->getTagNumbers($template);

            if ($arrayTags['title'] === false) {
                // headへ追加
                $strTmp = "<title>" . $arrayTitles['title'] . '</title>';
                $node = New Bshe_Dom_Node_Element($strTmp, 'title');
                $template->addChild($node, $headerElement);
            } else {
                // 上書き
                $template->setAttribute($arrayTags['title'], 'innerHTML', $arrayTitles['title']);
            }

            if ($arrayTags['description'] === false) {
                // headへ追加
                $strTmp = '<meta name="description" content="' . $arrayTitles['desc'] . '" />';
                $node = New Bshe_Dom_Node_SingleElement($strTmp, 'meta');
                $template->addChild($node, $headerElement);
            } else {
                // 上書き
                $template->setAttribute($arrayTags['description'], 'context', $arrayTitles['description']);
            }

            if ($arrayTags['keywords'] === false) {
                // headへ追加
                $strTmp = '<meta name="keywords" content="' . $arrayTitles['keywords'] . '" />';
                $node = New Bshe_Dom_Node_SingleElement($strTmp, 'meta');
                $template->addChild($node, $headerElement);
            } else {
                // 上書き
                $template->setAttribute($arrayTags['keywords'], 'context', $arrayTitles['keywords']);
            }

            return $template;
        } catch (Exception $e) {
            throw $e;
        }
    }
}