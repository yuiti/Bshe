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
 * @copyright  since 2009,all rights reserved Yuichiro Abe (http://www.bshe.org)
 * @license    LGPL
 */
/** Bshe_Log */
require_once 'Bshe/Log.php';
/** Bshe_View_Resource_Html_Abstract */
require_once 'Bshe/View/Resource/Html/Abstract.php';
/** Bshe_Specializer_Auth_Cms */
require_once 'Bshe/Specializer/Auth/Cms.php';
/** Bshe_Specializer_Cms_Cache_Text */
require_once 'Bshe/Specializer/Cms/Cache/Text.php';

/**
 * Bshe_View_Resource_Html_Cms
 *
 * CMS機能を利用する関連の処理
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.02.02
 * @license LGPL
 */
class Bshe_View_Resource_Html_Cms extends Bshe_View_Resource_Html_Abstract
{

    /**
     * CMS編集のnonic-blank関連（ヘッダー）
     *
     * @param $arrayParams
     * @return unknown_type
     */
/*    static public function assignValuesNoincblankbody($arrayParams)
    {
        try {
            Bshe_Log::logWithFileAndParamsWrite('NonicblancHeaderセット開始', Zend_Log::DEBUG);
            $config = Bshe_Registry_Config::getConfig('Bshe_Specializer');

            // nonic-blankへコンテンツを設置
            $templateName = urldecode($_GET['pageId']);
            $cache = New Bshe_Specializer_Cms_Cache_Text($_GET['elementId'], '', $arrayParams['templateClass'], $_GET['pageId']);
            $strHtml = $cache->getContents();

            $insertNodeClasses[] = New Bshe_Dom_Node_Text($strHtml);

            foreach ($insertNodeClasses as $key => $node) {
                $arrayParams['templateClass']->addChild($node, $arrayParams['element']);
            }

            // IDとclassをセット
            $arrayAssign = array();
            $arrayAssign[] =
                array(
                    'method' => 'a',
                    'element' => $arrayParams['element'],
                    'params' =>
                        array(
                            0 => 'id',
                            1 => $_GET['elementId'],
                            2 => $arrayParams['params']['helperName'],
                            3 => $arrayParams['params']['helperParams']
                        )
                );
            $arrayAssign[] =
                array(
                    'method' => 'a',
                    'element' => $arrayParams['element'],
                    'params' =>
                        array(
                            0 => 'class',
                            1 => $_GET['elementClass'],
                            2 => $arrayParams['params']['helperName'],
                            3 => $arrayParams['params']['helperParams']
                        )
                );

            foreach ($arrayAssign as $key => $assign) {
                $arrayParams['templateClass'] = self::assign($arrayParams['templateClass'], $assign);
            }
            Bshe_Log::logWithFileAndParamsWrite('NonicblancHeaderセット完了', Zend_Log::DEBUG);
            return $arrayParams['templateClass'];
        } catch (Exception $e) {
            throw $e;
        }
    }
*/


    /**
     * CMS編集のnonic-blank関連（ヘッダー）
     *
     * @param $arrayParams
     * @return unknown_type
     */
/*    static public function assignValuesNoincblankhead($arrayParams)
    {
        try {
            Bshe_Log::logWithFileAndParamsWrite('NonicblancHeaderセット開始', Zend_Log::DEBUG);
            $config = Bshe_Registry_Config::getConfig('Bshe_Specializer');

            // nonic-blankへJavascriptを設置
            $strScript =
                '<script type="text/javascript" language="javascript">' .
                "window.onload = function()" .
                "{" .
                'document.designMode = "on";' .
                "document.body.contentEditable = true;" .
                "if(document.addEventListener)" .
                "{" .
                'document.addEventListener("keypress", updateWindowHeight, false);' .
                'document.addEventListener("keyup", updateWindowHeight, false);' .
                'document.addEventListener("mouseup", updateWindowHeight, false);' .
                'document.addEventListener("resize", updateWindowHeight, false);' .
                '}' .
                'else' .
                '{' .
                'document.onkeypress = updateWindowHeight;' .
                'document.onkeyup = updateWindowHeight;' .
                'document.onmouseup = updateWindowHeight;' .
                'document.resize = updateWindowHeight;' .
            	'}' .
            	'updateWindowHeight();' .
                '};' .
                'var bodyHeightTotal = 0;' .
                'var elementId = "' . htmlspecialchars($_GET['elementId']) . '";' .
                'function updateWindowHeight()' .
                '{' .
                'if(bodyHeightTotal == 0) bodyHeightTotal = document.body.scrollHeight + document.body.offsetHeight;' .
                "if(bodyHeightTotal <= document.body.scrollHeight + document.body.offsetHeight)" .
                'window.parent.LTSun.updateWindowHeight(document.body.scrollHeight > document.body.offsetHeight ? document.body.scrollHeight : document.body.offsetHeight, elementId);' .
                "else \n" .
                'window.parent.LTSun.updateWindowHeight(document.body.scrollHeight < document.body.offsetHeight ? document.body.scrollHeight : document.body.offsetHeight, elementId);' .
                'bodyHeightTotal = document.body.scrollHeight + document.body.offsetHeight;' .
                'window.parent.LTSun.updateWindowHeight(0, "");' .
                '}' .
                '</script>'
            ;

            $insertNodeClasses[] = New Bshe_Dom_Node_Text($strScript);

            // CSS情報
//            $cache = New Bshe_Specializer_Cms_Cache_Css($arrayParams['templateClass'], urldecode($_GET['pageId']));
//            $insertNodeClasses[] = New Bshe_Dom_Node_Text($cache->getContents());

            foreach ($insertNodeClasses as $key => $node) {
                $arrayParams['templateClass']->addChild($node, $arrayParams['element']);
            }

            // SexylightboxとXajaxのスクリプト出力を抑止
            $arrayPluginFlags = $arrayParams['templateClass']->getParam('pluginFlags');
            $arrayPluginFlags['Bshe_View_Plugin_Sexylightbox']['setLightboxJs'] = false;
            $arrayPluginFlags['Bshe_View_Plugin_Cms'] = false;
            $arrayParams['templateClass']->setParam('pluginFlags', $arrayPluginFlags);

            Bshe_Log::logWithFileAndParamsWrite('NonicblancHeaderセット完了', Zend_Log::DEBUG);
            return $arrayParams['templateClass'];
        } catch (Exception $e) {
            throw $e;
        }
    }
*/


    /**
     * CMS編集のnonic-advEditor関連（body）
     *
     * @param $arrayParams
     * @return unknown_type
     */
    static public function assignValuesNoincadvblankbody($arrayParams)
    {
        try {
            Bshe_Log::logWithFileAndParamsWrite('NoincadvblankHeaderセット開始', Zend_Log::DEBUG);
            $config = Bshe_Registry_Config::getConfig('Bshe_Specializer');

            // nonic-blankへJavascriptを設置
            $insertNodeClasses = array();
            $strScript =
                '<form action="noinc-advEditor.html" onSubmit="return false;" onBeforeSubmit="return false;">' . "\n" .
                '<div id="bsheAdvancedTextEditor" class="' . $_GET['elementClass'] . '" style="width:100%;height:455px;"></div>' . "\n" .
                '</form>' . "\n" .
                '<script type="text/javascript" language="javascript">' .
                'document.getElementById("bsheAdvancedTextEditor").innerHTML = document.getElementById("' . $_GET['elementId'] . '").innerHTML;' . "\n" .
                '</script>' . "\n" .
                '<script language="javascript" type="text/javascript" src="../../tiny_mce/tiny_mce.js"></script>' . "\n" .
                '<script type="text/javascript" language="javascript">' . "\n" .
                'tinyMCE.init({' . "\n" .
                    'mode : "exact",' . "\n" .
                    "language : 'ja'," . "\n" .
//                    'height : "200",' . "\n" .
//                    'width : "800",' . "\n" .
//                    'debug : true,' . "\n" .
                    'elements : "LTSunAdvancedTextEditor",' . "\n" .
                    'theme : "advanced",' . "\n" .
                    'plugins : "style,layer,save,advhr,advimage,advlink,preview,paste,fullscreen,nonbreaking,table,xhtmlxtras",' . "\n" .
                    'theme_advanced_buttons1_add_before : "save,newdocument,separator",' . "\n" .
                    'theme_advanced_buttons2_add : "separator,preview,separator,forecolor,backcolor",' . "\n" .
                    'theme_advanced_buttons3_add : "fontselect,fontsizeselect",' . "\n" .
                    'theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,|,visualchars,nonbreaking,|,tablecontrols",' . "\n" .
                    'theme_advanced_toolbar_location : "top",' . "\n" .
                    'theme_advanced_toolbar_align : "left",' . "\n" .
                    'theme_advanced_path_location : "bottom",' . "\n" .
                    'plugin_insertdate_dateFormat : "%Y-%m-%d",' . "\n" .
                    'plugin_insertdate_timeFormat : "%H:%M:%S",' . "\n" .
                    'extended_valid_elements : "hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",' . "\n" .
                    'theme_advanced_resize_horizontal : false,' . "\n" .
                    'theme_advanced_resizing : false,' . "\n" .
                    'nonbreaking_force_tab : true,' . "\n" .
                    'paste_create_linebreaks : true,' . "\n" .
                    'paste_create_paragraphs : false,' . "\n" .
                    'paste_remove_spans : true,' . "\n" .
                    'paste_remove_styles : true,' . "\n" .
                    'save_callback: "updateTextModule"' . "\n" .
                    ',theme_advanced_blockformats : "div,p,h1,h2,h3,h4,h5,h6,blockquote,dt,dd,samp"' . "\n" .
                    ',forced_root_block : ""' . "\n" .
//                    'init_instance_callback : "onInitComplete"' . "\n" .
                '});' . "\n" .
                'function updateTextModule(element_id, html, body)' . "\n" .
                '{' . "\n" .
                'jQuery("#' . $_GET['elementId'] . '").updateTextModuleHTML(html, false);' . "\n" .
                'return html;' . "\n" .
                '}' . "\n" .
//                'function onInitComplete()' . "\n" .
//                '{' . "\n" .
//                    "document.getElementById('LTSunAdvancedTextEditor').contentWindow.document.body.className = '" . $_GET['elementClass'] . " mceEditorIframe';" . "\n" .
//                '}' . "\n" .
                '</script>'
            ;

            $insertNodeClasses[] = New Bshe_Dom_Node_Text($strScript);

            foreach ($insertNodeClasses as $key => $node) {
                $arrayParams['templateClass']->addChild($node, $arrayParams['element']);
            }

            // SexylightboxとXajaxのスクリプト出力を抑止
            $arrayPluginFlags = $arrayParams['templateClass']->getParam('pluginFlags');
            $arrayPluginFlags['Bshe_View_Plugin_Sexylightbox']['setLightboxJs'] = false;
            $arrayPluginFlags['Bshe_View_Plugin_Cms'] = false;
            $arrayParams['templateClass']->setParam('pluginFlags', $arrayPluginFlags);

            Bshe_Log::logWithFileAndParamsWrite('NonicblancHeaderセット完了', Zend_Log::DEBUG);
            return $arrayParams['templateClass'];
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * CMS編集のnonic-blank関連（ヘッダー）
     *
     * @param $arrayParams
     * @return unknown_type
     */
    static public function assignValuesNoincrevisionshead($arrayParams)
    {
        try {
            Bshe_Log::logWithFileAndParamsWrite('NonicblancHeaderセット開始', Zend_Log::DEBUG);
            $config = Bshe_Registry_Config::getConfig('Bshe_Specializer');

            // nonic-blankへJavascriptを設置
            $strScript =
                '<script type="text/javascript" language="javascript">' .
                'function updateTextModule(id)' .
                '{' .
                'window.parent.LTSun.updateTextModuleHTML("' . $_GET['elementId'] . '", document.getElementById(id).innerHTML, false);' .
                '}' .
                'function highlight(id)' .
                '{' .
                'document.getElementById(id).className = "bsheDisplayFileHighlight";' .
                '}' .
                'function unHighlight(id)' .
                '{' .
                'document.getElementById(id).className = "bsheDisplayFile";' .
                '}' .
                '</script>'
            ;

            $insertNodeClasses[] = New Bshe_Dom_Node_Text($strScript);

            // CSS情報
//            $cache = New Bshe_Specializer_Cms_Cache_Css($arrayParams['templateClass'], urldecode($_GET['pageId']));
//            $insertNodeClasses[] = New Bshe_Dom_Node_Text($cache->getContents());

            foreach ($insertNodeClasses as $key => $node) {
                $arrayParams['templateClass']->addChild($node, $arrayParams['element']);
            }

            // SexylightboxとXajaxのスクリプト出力を抑止
            $arrayPluginFlags = $arrayParams['templateClass']->getParam('pluginFlags');
            $arrayPluginFlags['Bshe_View_Plugin_Sexylightbox']['setLightboxJs'] = false;
            $arrayPluginFlags['Bshe_View_Plugin_Cms'] = false;
            $arrayParams['templateClass']->setParam('pluginFlags', $arrayPluginFlags);

            Bshe_Log::logWithFileAndParamsWrite('NonicblancHeaderセット完了', Zend_Log::DEBUG);
            return $arrayParams['templateClass'];
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * CMS編集可能なテキストを処理
     *
     * @param $arrayParams
     * @return unknown_type
     */
    static public function assignValuesEdittext($arrayParams)
    {
        try {
            Bshe_Log::logWithFileAndParamsWrite('edittextセット開始', Zend_Log::DEBUG);
            $configView = Bshe_Registry_Config::getConfig('Bshe_View');
            $arrayPluginFlags = $arrayParams['templateClass']->getParam('pluginFlags');

            // 該当タグにIDがあることを確認
            $id = $arrayParams['templateClass']->getAttribute($arrayParams['element'], 'id');
            if ($id == null) {
                // IDがない
                $arrayParams['templateClass']->getLogger()->logWithFileAndParams('key属性:' . $arrayParams['params']['originalId'] . 'のタグにはid属性が必要です。', Zend_Log::INFO);
                return $arrayParams['templateClass'];
            } elseif (preg_match('/[^A-Za-z0-9\-\_\:\.]/', $id, $matches) !== 0) {
                // IDに利用できない文字が含まれている
                $arrayParams['templateClass']->getLogger()->logWithFileAndParams('id属性に利用できない文字「' . $matches[0] . '」が含まれています', Zend_Log::INFO);
                return $arrayParams['templateClass'];
            }

            // キャッシュクラスインスタンス化
            $cache = New Bshe_Specializer_Cms_Cache_Text($id, $arrayParams['element'], $arrayParams['templateClass']);
            $contents = $cache->getContents();

            // 出力

            // ログイン中チェック
            if(!Bshe_Specializer_Auth_Cms::isLogined()) {
                // ログインしていない
                Bshe_Log::logWithFileAndParamsWrite('edittextセット、ログインしていない', Zend_Log::DEBUG);
                // 通常のコンテンツ出力
                $arrayAssign[] =
                    array(
                        'method' => 'a',
                        'element' => $arrayParams['element'],
                        'params' =>
                            array(
                                0 => 'innerHTML',
                                1 => $contents,
                                2 => $arrayParams['params']['helperName'],
                                3 => $arrayParams['params']['helperParams']
                            )
                    );
            } else {
                // ログイン中編集モード
                Bshe_Log::logWithFileAndParamsWrite('edittextセット、ログイン中', Zend_Log::DEBUG);

                $arrayPluginFlags['Bshe_View_Plugin_Cms']['selectTextElementEditable'][] = $id;

                $arrayAssign[] =
                    array(
                        'method' => 'a',
                        'element' => $arrayParams['element'],
                        'params' =>
                            array(
                                0 => 'innerHTML',
                                1 => $contents,
                                2 => $arrayParams['params']['helperName'],
                                3 => $arrayParams['params']['helperParams']
                            )
                    );

                    $strClass = 'bshe_cms_text ';
                    $strClass .= $arrayParams['templateClass']->getAttribute($arrayParams['element'], "class");

                $arrayAssign[] =
                    array(
                        'method' => 'a',
                        'element' => $arrayParams['element'],
                        'params' =>
                            array(
                                0 => 'class',
                                1 => $strClass,
                                2 => $arrayParams['params']['helperName'],
                                3 => $arrayParams['params']['helperParams']
                            )
                    );
            }


            foreach ($arrayAssign as $key => $assign) {
                $arrayParams['templateClass'] = self::assign($arrayParams['templateClass'], $assign);
            }



            $arrayParams['templateClass']->setParam('pluginFlags', $arrayPluginFlags);

            Bshe_Log::logWithFileAndParamsWrite('cms:edittextセット完了', Zend_Log::DEBUG);
            return $arrayParams['templateClass'];
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * CMS編集可能な画像を処理
     *
     * @param $arrayParams
     * @return unknown_type
     */
    static public function assignValuesEditimg($arrayParams)
    {
        try {
            Bshe_Log::logWithFileAndParamsWrite('editimgセット開始', Zend_Log::DEBUG);
            $configView = Bshe_Registry_Config::getConfig('Bshe_View');

            // 該当タグにIDがあることを確認
            $id = $arrayParams['templateClass']->getAttribute($arrayParams['element'], 'id');
            if ($id == null) {
                // IDがない
                $arrayParams['templateClass']->getLogger()->logWithFileAndParams('key属性:' . $arrayParams['params']['originalId'] . 'のタグにはid属性が必要です。', Zend_Log::INFO);
                return $arrayParams['templateClass'];
            } elseif (preg_match('/[^A-Za-z0-9\-\_\:\.]/', $id, $matches) !== 0) {
                // IDに利用できない文字が含まれている
                $arrayParams['templateClass']->getLogger()->logWithFileAndParams('id属性に利用できない文字「' . $matches[0] . '」が含まれています', Zend_Log::INFO);
                return $arrayParams['templateClass'];
            }


            // 親ノードをたどって、aタグがないかを確認
            $parentA = $arrayParams['templateClass']->searchParentTag($arrayParams['element'], 'a');

            // キャッシュクラスインスタンス化
            $cache = New Bshe_Specializer_Cms_Cache_Image($id, $arrayParams['element'], $parentA, $arrayParams['templateClass']);
            $contentsArray = $cache->getContents();

            // 出力

            // 通常のコンテンツ出力
            $arrayAssign[] =
                array(
                    'method' => 'a',
                    'element' => $arrayParams['element'],
                    'params' =>
                        array(
                            0 => 'src',
                            1 => $contentsArray['src'],
                            2 => $arrayParams['params']['helperName'],
                            3 => $arrayParams['params']['helperParams']
                        )
                );
            $arrayAssign[] =
                array(
                    'method' => 'a',
                    'element' => $arrayParams['element'],
                    'params' =>
                        array(
                            0 => 'alt',
                            1 => $contentsArray['alt'],
                            2 => $arrayParams['params']['helperName'],
                            3 => $arrayParams['params']['helperParams']
                        )
                );
            // aタグがあれば
            if ($parentA != null) {
                $arrayAssign[] =
                    array(
                        'method' => 'a',
                        'element' => $parentA,
                        'params' =>
                            array(
                                0 => 'href',
                                1 => $contentsArray['href'],
                                2 => $arrayParams['params']['helperName'],
                                3 => $arrayParams['params']['helperParams']
                            )
                    );
            }

            // ログイン中チェック
            if(Bshe_Specializer_Auth_Cms::isLogined()) {
                // ログイン中編集モード
                Bshe_Log::logWithFileAndParamsWrite('edittextセット、ログイン中', Zend_Log::DEBUG);
                // 親aタグがあれば、IDチェック
                if ($parentA !== false) {
                    if ($arrayParams['templateClass']->hasAttribute($parentA, 'id')) {
                        $parentId = $arrayParams['templateClass']->getAttribute($parentA, 'id');
                    } else {
                        // ID生成
                        $parentId = $arrayParams['templateClass']->getParam('id_suffix') . '_' . $id;
                        $arrayAssign[] =
                            array(
                                'method' => 'a',
                                'element' => $parentA,
                                'params' =>
                                    array(
                                        0 => 'id',
                                        1 => $parentId,
                                        2 => $arrayParams['params']['helperName'],
                                        3 => $arrayParams['params']['helperParams']
                                    )
                            );
                    }

                }



                // Javascript出力
 /*               $strScript =
                	"<script type=\"text/javascript\" language=\"javascript\">" .
                    "LTSunSettings['selectImageElement'].push(\"".$id."\");" .
                    "LTSunSettings['$id'] = " .
                    "{" .
                        "parent_id: \"$parentId\"" .
                    "};" .
                    "</script>\n"
                    ;
                $node = New Bshe_Dom_Node_Text($strScript);
                $nodeId = $arrayParams['templateClass']->newNode($node);
                $arrayParams['templateClass']->insertBefore($nodeId, $arrayParams['element']);*/
            }


            foreach ($arrayAssign as $key => $assign) {
                $arrayParams['templateClass'] = self::assign($arrayParams['templateClass'], $assign);
            }


            Bshe_Log::logWithFileAndParamsWrite('cms:editimgセット完了', Zend_Log::DEBUG);
            return $arrayParams['templateClass'];
        } catch (Exception $e) {
            throw $e;
        }
    }



    /**
     * CMS編集の画像アップローダー関連（ヘッダー）
     *
     * @param $arrayParams
     * @return unknown_type
     */
    static public function assignValuesImguploaderheader($arrayParams)
    {
        try {
            Bshe_Log::logWithFileAndParamsWrite('Imguploaderheaderセット開始', Zend_Log::DEBUG);
            $config = Bshe_Registry_Config::getConfig('Bshe_Specializer');

            // nonic-blankへJavascriptを設置
            $strScript =
                '<script type="text/javascript" src="' . Bshe_Controller_Init::getUrlPath() . $config->indexphp_path . '/cms/prototype.js"></script>' .
	            '<script type="text/javascript" src="' . Bshe_Controller_Init::getUrlPath() . $config->indexphp_path . '/cms/effects.js"></script>' .
                '<script type="text/javascript" language="javascript">' .
                'function validateUpload()' .
                '{' .
    	            'if(document.getElementById("imageFile").value != "")' .
                    '{' .
                        'document.getElementById("imageSave").disabled = true;' .
                        'document.getElementById("imageSave").value = "Uploading...";' .
                        'document.getElementById("imageHref").value = document.getElementById("imageHref").value.replace(/[\,\'\"]/g, "");' .
                        'document.getElementById("imageTitle").value = document.getElementById("imageTitle").value.replace(/[\,\'\"]/g, "");' .
                        'window.parent.LTSun.showLoadingWindow({animationSpeed:800});' .
                        'return true;' .
                    '}' .
                    'else' .
                    '{' .
                        'document.getElementById("imageSave").disabled = true;' .
                        'document.getElementById("imageSave").value = "Uploading...";' .
                        'document.getElementById("imageHref").value = document.getElementById("imageHref").value.replace(/[\,\'\"]/g, "");' .
                        'document.getElementById("imageTitle").value = document.getElementById("imageTitle").value.replace(/[\,\'\"]/g, "");' .
                        'window.parent.LTSun.showLoadingWindow({animationSpeed:800});' .
                        'return true;' .
                    '}' .
                '}' .
                'function publishImage()' .
                '{' .
                    'document.getElementById("imagePublish").disabled = true;' .
                    'document.getElementById("imageRevert").disabled = true;' .
                    'document.getElementById("imagePublish").value = "Publishing...";' .
                    'var pageId = "' . $_GET['pageId'] . '";' .
            		'var ymd = window.parent.LTSunSettings["'. $_GET['elementId'] .'"]["ymd"];' .
                    'document.getElementById("ymd").value = ymd;' .
                    'new Ajax.Request' .
                    '(' .
                        '"' . Bshe_Controller_Init::getUrlPath() . $config->indexphp_path . '/cms/image/noinc-publish.html",' .
                        '{' .
                            'parameters:' .
                            '{' .
                                'pageId: pageId,' .
                                'elementId: "' . $_GET['elementId'] . '",' .
                                'ymd: ymd' .
                            '},' .
                            'onSuccess : function(transport)' .
                            '{' .
                                'window.parent.LTSun.publishImage(true);' .
                            '},' .
                            'onFailure : function(transport)' .
                            '{' .
                                'window.parent.LTSun.publishImage(false);' .
                            '}' .
                        '}' .
                    ');' .
                '}' .
                '</script>'
            ;

            $insertNodeClasses[] = New Bshe_Dom_Node_Text($strScript);

            foreach ($insertNodeClasses as $key => $node) {
                $arrayParams['templateClass']->addChild($node, $arrayParams['element']);
            }

            // SexylightboxとXajaxのスクリプト出力を抑止
            $arrayPluginFlags = $arrayParams['templateClass']->getParam('pluginFlags');
            $arrayPluginFlags['Bshe_View_Plugin_Sexylightbox']['setLightboxJs'] = false;
            $arrayPluginFlags['Bshe_View_Plugin_Cms'] = false;
            $arrayParams['templateClass']->setParam('pluginFlags', $arrayPluginFlags);

            Bshe_Log::logWithFileAndParamsWrite('Imguploaderheaderセット完了', Zend_Log::DEBUG);
            return $arrayParams['templateClass'];
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * CMS編集の画像アップローダー関連（elementid）
     *
     * @param $arrayParams
     * @return unknown_type
     */
    static public function assignValuesImguploaderelementid($arrayParams)
    {
        try {
            Bshe_Log::logWithFileAndParamsWrite('Imguploaderelementidセット開始', Zend_Log::DEBUG);
            $config = Bshe_Registry_Config::getConfig('Bshe_Specializer');

            $arrayAssign[] =
                array(
                    'method' => 'a',
                    'element' => $arrayParams['element'],
                    'params' =>
                        array(
                            0 => 'value',
                            1 => $_GET['elementId'],
                            2 => $arrayParams['params']['helperName'],
                            3 => $arrayParams['params']['helperParams']
                        )
                );

            foreach ($arrayAssign as $key => $assign) {
                $arrayParams['templateClass'] = self::assign($arrayParams['templateClass'], $assign);
            }

            Bshe_Log::logWithFileAndParamsWrite('Imguploaderelementidセット終了', Zend_Log::DEBUG);

            return $arrayParams['templateClass'];
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * CMS編集の画像アップローダー関連（href）
     *
     * @param $arrayParams
     * @return unknown_type
     */
    static public function assignValuesImguploaderhref($arrayParams)
    {
        try {
            Bshe_Log::logWithFileAndParamsWrite('Imguploaderelementidセット開始', Zend_Log::DEBUG);
            $config = Bshe_Registry_Config::getConfig('Bshe_Specializer');

            // キャッシュクラスインスタンス化
            $cache = New Bshe_Specializer_Cms_Cache_Image($_GET['elementId'], null, null, $arrayParams['templateClass'], urldecode($_GET['pageId']));
            $contentsArray = $cache->getContents();

            $arrayAssign[] =
                array(
                    'method' => 'a',
                    'element' => $arrayParams['element'],
                    'params' =>
                        array(
                            0 => 'value',
                            1 => $contentsArray['href'],
                            2 => $arrayParams['params']['helperName'],
                            3 => $arrayParams['params']['helperParams']
                        )
                );
                if ($contentsArray['href'] == null) {
                    $arrayAssign[] =
                        array(
                            'method' => 'a',
                            'element' => $arrayParams['element'],
                            'params' =>
                                array(
                                    0 => 'disabled',
                                    1 => 'true',
                                    2 => $arrayParams['params']['helperName'],
                                    3 => $arrayParams['params']['helperParams']
                                )
                        );
                }
            foreach ($arrayAssign as $key => $assign) {
                $arrayParams['templateClass'] = self::assign($arrayParams['templateClass'], $assign);
            }

            Bshe_Log::logWithFileAndParamsWrite('Imguploaderelementidセット終了', Zend_Log::DEBUG);

            return $arrayParams['templateClass'];
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * CMS編集の画像アップローダー関連（hrefエリア）
     *
     * @param $arrayParams
     * @return unknown_type
     */
    static public function assignValuesImguploaderlinktr($arrayParams)
    {
        try {
            Bshe_Log::logWithFileAndParamsWrite('Imguploaderlinktrセット開始', Zend_Log::DEBUG);
            $config = Bshe_Registry_Config::getConfig('Bshe_Specializer');

            // キャッシュクラスインスタンス化
            $cache = New Bshe_Specializer_Cms_Cache_Image($_GET['elementId'], null, null, $arrayParams['templateClass'], urldecode($_GET['pageId']));
            $contentsArray = $cache->getContents();

            if ($contentsArray['href'] != null) {
                $arrayAssign[] =
                    array(
                        'method' => 'a',
                        'element' => $arrayParams['element'],
                        'params' =>
                            array(
                                0 => 'style',
                                1 => 'display:block',
                                2 => $arrayParams['params']['helperName'],
                                3 => $arrayParams['params']['helperParams']
                            )
                    );
            } else {
                $arrayAssign[] =
                    array(
                        'method' => 'a',
                        'element' => $arrayParams['element'],
                        'params' =>
                            array(
                                0 => 'style',
                                1 => 'display:none',
                                2 => $arrayParams['params']['helperName'],
                                3 => $arrayParams['params']['helperParams']
                            )
                    );
            }

            foreach ($arrayAssign as $key => $assign) {
                $arrayParams['templateClass'] = self::assign($arrayParams['templateClass'], $assign);
            }

            Bshe_Log::logWithFileAndParamsWrite('Imguploaderlinktrセット終了', Zend_Log::DEBUG);

            return $arrayParams['templateClass'];
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * CMS編集の画像アップローダー関連（title）
     *
     * @param $arrayParams
     * @return unknown_type
     */
    static public function assignValuesImguploadertitle($arrayParams)
    {
        try {
            Bshe_Log::logWithFileAndParamsWrite('Imguploadertitleセット開始', Zend_Log::DEBUG);
            $config = Bshe_Registry_Config::getConfig('Bshe_Specializer');

            // キャッシュクラスインスタンス化
            $cache = New Bshe_Specializer_Cms_Cache_Image($_GET['elementId'], null, null, $arrayParams['templateClass'], urldecode($_GET['pageId']));
            $contentsArray = $cache->getContents();


            $arrayAssign[] =
                array(
                    'method' => 'a',
                    'element' => $arrayParams['element'],
                    'params' =>
                        array(
                            0 => 'value',
                            1 => $contentsArray['alt'],
                            2 => $arrayParams['params']['helperName'],
                            3 => $arrayParams['params']['helperParams']
                        )
                );

            foreach ($arrayAssign as $key => $assign) {
                $arrayParams['templateClass'] = self::assign($arrayParams['templateClass'], $assign);
            }

            Bshe_Log::logWithFileAndParamsWrite('Imguploadertitleセット終了', Zend_Log::DEBUG);

            return $arrayParams['templateClass'];
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * CMS編集の画像アップローダー関連（form）
     *
     * @param $arrayParams
     * @return unknown_type
     */
    static public function assignValuesImguploaderform($arrayParams)
    {
        try {
            Bshe_Log::logWithFileAndParamsWrite('Imguploaderelementidセット開始', Zend_Log::DEBUG);
            $config = Bshe_Registry_Config::getConfig('Bshe_Specializer');

            $arrayAssign[] =
                array(
                    'method' => 'a',
                    'element' => $arrayParams['element'],
                    'params' =>
                        array(
                            0 => 'action',
                            1 => Bshe_Controller_Init::getUrlPath() . $config->indexphp_path . '/cms/image/noinc-render.html',
                            2 => $arrayParams['params']['helperName'],
                            3 => $arrayParams['params']['helperParams']
                        )
                );

            foreach ($arrayAssign as $key => $assign) {
                $arrayParams['templateClass'] = self::assign($arrayParams['templateClass'], $assign);
            }

            // SexylightboxとXajaxのスクリプト出力を抑止
            $arrayPluginFlags = $arrayParams['templateClass']->getParam('pluginFlags');
            $arrayPluginFlags['Bshe_View_Plugin_Sexylightbox']['setLightboxJs'] = false;
            $arrayPluginFlags['Bshe_View_Plugin_Cms'] = false;
            $arrayParams['templateClass']->setParam('pluginFlags', $arrayPluginFlags);

            Bshe_Log::logWithFileAndParamsWrite('Imguploaderelementidセット終了', Zend_Log::DEBUG);

            return $arrayParams['templateClass'];
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * CMS編集の画像アップローダー関連（page_id）
     *
     * @param $arrayParams
     * @return unknown_type
     */
    static public function assignValuesImguploaderpageid($arrayParams)
    {
        try {
            Bshe_Log::logWithFileAndParamsWrite('Imguploaderpageidセット開始', Zend_Log::DEBUG);
            $config = Bshe_Registry_Config::getConfig('Bshe_Specializer');

            $arrayAssign[] =
                array(
                    'method' => 'a',
                    'element' => $arrayParams['element'],
                    'params' =>
                        array(
                            0 => 'value',
                            1 => $_GET['pageId'],
                            2 => $arrayParams['params']['helperName'],
                            3 => $arrayParams['params']['helperParams']
                        )
                );

            foreach ($arrayAssign as $key => $assign) {
                $arrayParams['templateClass'] = self::assign($arrayParams['templateClass'], $assign);
            }

            Bshe_Log::logWithFileAndParamsWrite('Imguploaderpageidセット終了', Zend_Log::DEBUG);

            return $arrayParams['templateClass'];
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * CMS編集の画像render関連（結果表示）
     * 実際のファイル保存処理もここで行う。
     *
     * @param $arrayParams
     * @return unknown_type
     */
    static public function assignValuesImgrenderrsl($arrayParams)
    {
        try {
            Bshe_Log::logWithFileAndParamsWrite('Imgrenderrslセット開始', Zend_Log::DEBUG);
            $config = Bshe_Registry_Config::getConfig('Bshe_Specializer');

            // キャッシュクラスインスタンス化
            $cache = New Bshe_Specializer_Cms_Cache_Image($_REQUEST['elementId'], null, null, $arrayParams['templateClass'], $_REQUEST['pageId']);

            if ($cache->saveImageFromRequest() === false) {
                // ファイル未選択
                $strTmp = "<script type=\"text/javascript\" language=\"javascript\">" .
                    "alert(\"ファイルが選択されていません。\\n\\nファイルを選択してください。\");" .
                    "</script>" .
                    "<strong>エラー</strong><br />" .
                    "ファイルが選択されていません。\\n\\nファイルを選択してください。";
                $arrayAssign[] =
                    array(
                        'method' => 'a',
                        'element' => $arrayParams['element'],
                        'params' =>
                            array(
                                0 => 'innerHTML',
                                1 => $strTmp,
                                2 => $arrayParams['params']['helperName'],
                                3 => $arrayParams['params']['helperParams']
                            )
                    );
            } else {
                // ファイルアップロード成功
                $arrayContents = $cache->getContents();
                $strTmp =
                    "<table><tr><td style=\"width:360px;height:174px;\">" .
                    "<center><span style=\"font-size:26px;\">アップロード成功</span></center>" .
                    "</td></tr></table>" .
                    "<script type=\"text/javascript\" language=\"javascript\">" .
                        "window.parent.LTSun.updateImage(\"" . $_REQUEST['elementId'] . "\",\"". $arrayContents['src'] ."\", \"".$arrayContents['href']."\", \"".$arrayContents['alt']."\",\"" . $arrayContents['ymd'] . "\");" .
                        "if(navigator.userAgent.indexOf('sfari') != -1) window.location.reload(false);" .
                    "</script>";
                $arrayAssign[] =
                    array(
                        'method' => 'a',
                        'element' => $arrayParams['element'],
                        'params' =>
                            array(
                                0 => 'innerHTML',
                                1 => $strTmp,
                                2 => $arrayParams['params']['helperName'],
                                3 => $arrayParams['params']['helperParams']
                            )
                    );
            }


            foreach ($arrayAssign as $key => $assign) {
                $arrayParams['templateClass'] = self::assign($arrayParams['templateClass'], $assign);
            }

            // SexylightboxとXajaxのスクリプト出力を抑止
            $arrayPluginFlags = $arrayParams['templateClass']->getParam('pluginFlags');
            $arrayPluginFlags['Bshe_View_Plugin_Sexylightbox']['setLightboxJs'] = false;
            $arrayPluginFlags['Bshe_View_Plugin_Cms'] = false;
            $arrayParams['templateClass']->setParam('pluginFlags', $arrayPluginFlags);

            Bshe_Log::logWithFileAndParamsWrite('Imgrenderrslセット終了', Zend_Log::DEBUG);

            return $arrayParams['templateClass'];
        } catch (Exception $e) {
            throw $e;
        }
    }



    /**
     * ログインフォームへXajaxをセットする処理
     *
     * @param array $arrayParams Resourceクラス向けパラメーター
     * @return Bshe_View_Template_Abstract
     */
    static public function assignValuesLoginform($arrayParams)
    {
        try {
            Bshe_Log::logWithFileAndParamsWrite('cmsログインフォームセット開始', Zend_Log::DEBUG);


            // 値の場合は直接セット
            $arrayAssign[] =
                array(
                    'method' => 'a',
                    'element' => $arrayParams['element'],
                    'params' =>
                        array(
                            0 => 'onsubmit',
                            1 => "xajax_authCmsLogin(xajax.getFormValues('bsheloginform'));return false;",
                            2 => $arrayParams['params']['helperName'],
                            3 => $arrayParams['params']['helperParams']
                        )
                );
            $arrayAssign[] =
                array(
                    'method' => 'a',
                    'element' => $arrayParams['element'],
                    'params' =>
                        array(
                            0 => 'id',
                            1 => "bsheloginform",
                            2 => $arrayParams['params']['helperName'],
                            3 => $arrayParams['params']['helperParams']
                        )
                );
            $arrayAssign[] =
                array(
                    'method' => 'a',
                    'element' => $arrayParams['element'],
                    'params' =>
                        array(
                            0 => 'name',
                            1 => "bsheloginform",
                            2 => $arrayParams['params']['helperName'],
                            3 => $arrayParams['params']['helperParams']
                        )
                );
//            $arrayAssign[] =
//                array(
//                    'method' => 'a',
//                    'element' => $arrayParams['element'],
//                    'params' =>
//                        array(
//                            0 => 'method',
//                            1 => "POST",
//                            2 => $arrayParams['params']['helperName'],
//                            3 => $arrayParams['params']['helperParams']
//                        )
//                );
            foreach ($arrayAssign as $key => $assign) {
                $arrayParams['templateClass'] = self::assign($arrayParams['templateClass'], $assign);
            }

            Bshe_Log::logWithFileAndParamsWrite('cmsログインフォームセット終了', Zend_Log::DEBUG);

            return $arrayParams['templateClass'];
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * Cmsメニューをセットする処理
     *
     * @param array $arrayParams Resourceクラス向けパラメーター
     * @return Bshe_View_Template_Abstract
     */
    static public function assignValuesLeftmenu($arrayParams)
    {
        try {
            Bshe_Log::logWithFileAndParamsWrite('cmsメニューセット開始', Zend_Log::DEBUG);
            $config = Bshe_Registry_Config::getConfig('Bshe_Specializer');

            // Cmsログインしていない場合何もせず終了
            if (!Bshe_Specializer_Acl::isAllowedByUserid($arrayParams['templateClass']->getTemplateFileName(), null)) {
                return $arrayParams['templateClass'];
            }

            // leftメニュー配列取得
            $arrayMenu = $config->cms_menu->toArray();

            // メニューアサイン
            $orgCel = $arrayParams['element'];
            foreach ($arrayMenu as $key => $values) {
                // 生成
                $arrayNodes = $arrayParams['templateClass']->cloneNodeBefore($orgCel, $orgCel);
                // メニューセット
                $arrayParams['templateClass']->setAttribute($arrayNodes['node'], 'href', Bshe_Controller_Init::getUrlPath() . $config->indexphp_path .$values['url']);
                $arrayParams['templateClass']->setNodeValue($arrayNodes['children'][0]['node'], $values['name']);
                $arrayParams['templateClass']->removeAttribute($arrayNodes['node'], 'key');
            }
            // 元を削除
            $arrayParams['templateClass']->removeNode($orgCel);


            return $arrayParams['templateClass'];
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * サイトのトップへhref属性をセットする
     *
     * @param array $arrayParams Resourceクラス向けパラメーター
     * @return Bshe_View_Template_Abstract
     */
    static public function assignValuesSitehome($arrayParams)
    {
        try {
            Bshe_Log::logWithFileAndParamsWrite('cmsサイトホームセット開始', Zend_Log::DEBUG);

            if (($strTmp = Bshe_Controller_Init::getUrlPath()) == '') {
                $strTmp = '/';
            }
            // 値の場合は直接セット
            $arrayAssign[] =
                array(
                    'method' => 'a',
                    'element' => $arrayParams['element'],
                    'params' =>
                        array(
                            0 => 'href',
                            1 => $strTmp,
                            2 => $arrayParams['params']['helperName'],
                            3 => $arrayParams['params']['helperParams']
                        )
                );
            foreach ($arrayAssign as $key => $assign) {
                $arrayParams['templateClass'] = self::assign($arrayParams['templateClass'], $assign);
            }

            Bshe_Log::logWithFileAndParamsWrite('cmsサイトホームセット終了', Zend_Log::DEBUG);

            return $arrayParams['templateClass'];
        } catch (Exception $e) {
            throw $e;
        }
    }

}
